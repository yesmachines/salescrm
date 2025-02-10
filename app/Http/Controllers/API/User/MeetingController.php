<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use Validator;
use App\Http\Resources\PaginateResource;
use App\Models\User;
use App\Models\Meeting;
use App\Models\MeetingProduct;
use App\Models\MeetingShare;
use App\Models\MeetingSharedProduct;
use App\Models\MeetingDoc;
use App\Models\MeetingSharedDoc;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailMeetingToClient;
use \App\Traits\ImageTraits;

class MeetingController extends Controller {

    use ImageTraits;

    public function store(Request $request) {
        $rules = [
            'meeting_date' => 'required|date_format:Y-m-d',
            'meeting_time' => 'required|date_format:H:i',
            'timezone' => 'required|timezone',
            'title' => 'required',
            'company_name' => 'required',
            'company_representative' => 'required',
            // 'phone' => 'required',
            // 'email' => 'required',
            'location' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $meetingTime = Carbon::createFromFormat('Y-m-d H:i', $request->meeting_date . ' ' . $request->meeting_time, $request->timezone);
        $meetingTimeInUTC = $meetingTime->setTimezone('UTC');
        $sendPushToAreaManager = false;

        $meeting = new Meeting();
        $meeting->user_id = $request->user()->id;
        $meeting->title = $request->title;
        $meeting->company_name = $request->company_name;
        $meeting->company_representative = $request->company_representative;
        $meeting->phone = $request->phone;
        $meeting->email = $request->email;
        $meeting->location = $request->location;
        $meeting->scheduled_at = $meetingTimeInUTC;
        $meeting->timezone = $request->timezone;
        if (!empty($request->area_id)) {
            $meeting->area_id = $request->area_id;
            if (!empty($request->manager_id)) {
                $meeting->manager_id = $request->manager_id;
                $sendPushToAreaManager = true;
            }
        }
        $meeting->scheduled_notes = $request->scheduled_notes;
        $meeting->save();

        if ($sendPushToAreaManager) {
            $this->notifyAreaMnager($meeting);
        }

        if (!empty($request->invited_id)) {
            $meeting->invites()->create([
                'user_id' => $request->invited_id,
            ]);

            $body = [
                'headings' => ['en' => 'Meeting Invites'],
                'contents' => [$request->user()->name . ' has invited you to attend a meeting.'],
                'data' => [
                    'module' => 'meeting',
                    'module_id' => $meeting->id
                ]
            ];
            $body ['include_external_user_ids'] = [$request->invited_id];
            $body ['channel_for_external_user_ids'] = 'push';
            $this->sendONotification($body);
        }

        return successResponse(trans('api.meeting.created'), ['meeting_id' => $meeting->id]);
    }

    public function notesStore(Request $request, $id) {
        try {
            $meeting = Meeting::findOrFail($id);
            if ($meeting->status < 2) {
                $rules = [
                    'meeting_notes' => 'required',
                    'products' => 'nullable|array',
                    'products.*.brand_id' => 'required_with:products.*',
                    'products.*.product_id' => 'required_with:products.*',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $errorMessage = $validator->messages();
                    return errorResponse(trans('api.required_fields'), $errorMessage);
                }

                $meeting->meeting_notes = $request->meeting_notes;
                $meeting->status = 1;
                $meeting->save();

                if (!empty($request->products)) {
                    $this->insertProducts($meeting->id, $request->products);
                }
                //Send Email To client - get email template and enable
                if (!empty($meeting->email)) {
                    //$this->sendEmailToClient($meeting);
                }

                return successResponse(trans('api.meeting.notes_created'), ['meeting_id' => $meeting->id]);
            }
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function sendEmailToClient($meeting) {
        $meeting->scheduled_at = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($meeting->timezone)->format('M d, Y h:i A');
        //$meeting->email = 'shainu.giraf@gmail.com';
        Notification::send($meeting, new EmailMeetingToClient($meeting));
    }

    public function businessCard(Request $request) {
        try {
            $meeting = Meeting::findOrFail($request->id);
            $rules = [
                'id' => 'required',
                'business_card' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errorMessage = $validator->messages();
                return errorResponse(trans('api.required_fields'), $errorMessage);
            }

            if ($request->hasfile('business_card')) {
                if (!empty($meeting->business_card)) {
                    deleteFile($meeting->business_card);
                }
                $path = 'meetings';
                $meeting->business_card = $path . '/' . $this->singleImage($request->file('business_card'), $path);
                $meeting->save();
            }

            return successResponse(trans('api.meeting.business_card'), ['meeting_id' => $meeting->id]);
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function uploadDoc(Request $request) {
        try {
            $rules = [
                'meeting_id' => 'required',
                'doc_file' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errorMessage = $validator->messages();
                return errorResponse(trans('api.required_fields'), $errorMessage);
            }

            if ($request->hasfile('doc_file')) {
                $doc = new MeetingDoc();
                $path = 'meetings';
                $doc->meeting_id = $request->meeting_id;
                $doc->file_name = $path . '/' . $this->singleImage($request->file('doc_file'), $path);
                $doc->save();
                return successResponse(trans('api.success'), ['doc' => $doc]);
            }
        } catch (\Exception $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function deleteDoc($id) {
        try {
            $doc = MeetingDoc::findOrFail($id);
            if (deleteFile($doc->getRawOriginal('file_name'))) {
                $doc->delete();
                return successResponse(trans('api.meeting.doc_deleted'));
            }
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function list(Request $request, $type) {
        $requestedTimezone = $request->header('timezone', config('app.timezone'));

        $meetingsQuery = Meeting::select(
                        'meetings.id',
                        'meetings.user_id',
                        'meetings.title',
                        'meetings.scheduled_at'
                )
                ->leftJoin('meeting_invites', 'meetings.id', '=', 'meeting_invites.meeting_id')
                ->where(function ($query) use ($request) {
            $query->where('meetings.user_id', $request->user()->id)
            ->orWhere('meeting_invites.user_id', $request->user()->id);
        });

        switch ($type) {
            case 'available' :
                $currentTimeInUserTimezone = Carbon::now($requestedTimezone);
                $currentTimeInUTC = $currentTimeInUserTimezone->copy()
                        ->setTimezone('UTC')
                        ->startOfMinute()
                        ->toDateTimeString();
                $meetingsQuery->whereRaw("cm_meetings.scheduled_at >= '$currentTimeInUTC'")
                        ->where('meetings.status', 0)
                        ->orderByRaw("CONVERT_TZ(cm_meetings.scheduled_at, '+00:00', '$requestedTimezone') ASC");
                break;
            case 'previous' :
                $rules = [
                    'from_dt' => 'required|date_format:Y-m-d',
                    'to_dt' => 'required|date_format:Y-m-d',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $errorMessage = $validator->messages();
                    return errorResponse(trans('api.required_fields'), $errorMessage);
                }

                $fromDate = Carbon::parse($request->from_dt, $requestedTimezone)->startOfDay()->setTimezone('UTC');
                $toDate = Carbon::parse($request->to_dt, $requestedTimezone)->endOfDay()->setTimezone('UTC');
                $currentTimeInUTC = Carbon::now($requestedTimezone)->setTimezone('UTC')->startOfMinute();

                if ($currentTimeInUTC->toDateString() == $toDate->toDateString()) {
                    $meetingsQuery->where('scheduled_at', '>=', $fromDate)
                            ->where(function ($qry) use ($toDate, $currentTimeInUTC) {
                                $qry->where('scheduled_at', '<=', $currentTimeInUTC)
                                ->orWhere(function ($qry) use ($toDate, $currentTimeInUTC) {
                                    $qry->where('scheduled_at', '<=', $toDate)
                                    ->where('status', '<>', 0);
                                });
                            });
                } else {
                    $meetingsQuery
                            ->whereBetween('scheduled_at', [$fromDate, $toDate]);
                }

                $meetingsQuery->orderByRaw("CONVERT_TZ(cm_meetings.scheduled_at, '+00:00', '$requestedTimezone') DESC");
                break;
            default:
                return errorResponse(trans('api.invalid_request'));
        }

        $meetings = new PaginateResource(
                $meetingsQuery->paginate($this->paginateNumber)->through(function ($meeting) use ($requestedTimezone) {
                    $meetingTimeInUserTimezone = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($requestedTimezone);
                    return [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'date' => $meetingTimeInUserTimezone->format('Y-m-d'),
                'time' => $meetingTimeInUserTimezone->format('h:i A'),
                    ];
                })
        );
        return successResponse(trans('api.success'), $meetings);
    }

    public function calendarList(Request $request) {
        $rules = [
            'year' => 'required|date_format:Y',
            'month' => 'required|date_format:m'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }
        $userTimezoe = $request->header('timezone', config('app.timezone'));

        $groupedByDate = [];
        $conflictedMeetings = [];

        Meeting::select('meetings.id', 'meetings.title', 'meetings.scheduled_at')
                ->leftJoin('meeting_invites', 'meetings.id', '=', 'meeting_invites.meeting_id')
                ->where(function ($query) use ($request) {
                    $query->where('meetings.user_id', $request->user()->id)
                    ->orWhere('meeting_invites.user_id', $request->user()->id);
                })
                ->whereYear('meetings.scheduled_at', $request->year)
                ->whereMonth('meetings.scheduled_at', $request->month)
                ->orderBy('meetings.scheduled_at', 'asc')
                ->chunk(200, function ($meetings) use (&$groupedByDate, &$userTimezoe, &$conflictedMeetings) {

                    $meetingTimes = [];

                    foreach ($meetings as $meeting) {
                        $meetingTime = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($userTimezoe);
                        $formattedTime = $meetingTime->format('h:i A');
                        $meetingDate = $meetingTime->format('Y-m-d');

                        if (isset($meetingTimes[$meetingDate][$formattedTime])) {
                            $conflictedMeetings[$meeting->id] = true;
                            $conflictedMeetings[$meetingTimes[$meetingDate][$formattedTime]] = true;
                        }

                        $meetingTimes[$meetingDate][$formattedTime] = $meeting->id;

                        $groupedByDate[$meetingDate][] = [
                            'id' => $meeting->id,
                            'title' => $meeting->title,
                            'time' => $formattedTime,
                            'conflicted' => false,
                        ];
                    }

                    foreach ($groupedByDate as $date => &$items) {
                        foreach ($items as &$item) {
                            $item['conflicted'] = isset($conflictedMeetings[$item['id']]);
                        }
                    }
                });

        return successResponse(trans('api.meeting.notes_created'), collect($groupedByDate)
                        ->map(function ($items, $date) {
                            return [
                        'date' => $date,
                        'items' => $items
                            ];
                        })
                        ->sortBy('date')
                        ->values()
                        ->toArray());
    }

    public function feedback(Request $request, $id) {
        $rules = [
            'mqs' => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $meeting = Meeting::where('id', $id)
                ->where('user_id', $request->user()->id)
                ->first();
        if ($meeting) {
            if (empty($meeting->mqs)) {
                if ($meeting->status >= 1) {
                    $meeting->mqs = $request->mqs;
                    $meeting->save();
                    return successResponse(trans('api.meeting.feedback_success'));
                }
                return errorResponse(trans('api.meeting.not_done'));
            }
            return errorResponse(trans('api.meeting.feedback_exist'));
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function show(Request $request, $id) {
        $rules = [
            'type' => 'required|in:normal,shared'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }
        try {
            $requestedTimezone = $request->header('timezone', config('app.timezone'));
            switch ($request->type) {
                case 'normal':
                    $meeting = Meeting::findOrFail($id);
                    $meetingConflicted = Meeting::where('id', '<>', $meeting->id)
                            ->where('scheduled_at', $meeting->scheduled_at)
                            ->where('user_id', $request->user()->id)
                            ->where('status', 0)
                            ->first();
                    $meeting->conflicted = $meetingConflicted ? true : false;
                    $meetingTime = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($requestedTimezone);
                    $meeting->products = MeetingProduct::select('meeting_products.id', 'suppliers.brand', 'products.title')
                            ->join('suppliers', 'suppliers.id', 'meeting_products.supplier_id')
                            ->join('products', 'products.id', 'meeting_products.product_id')
                            ->where('meeting_products.meeting_id', $id)
                            ->get();
                    $meeting->area;
                    $meeting->manager = User::select('users.id', 'users.name', 'employees.image_url as pimg')
                                    ->join('employees', 'employees.user_id', 'users.id')
                                    ->where('users.id', $meeting->manager_id)->first();
                    $meeting->docs;
                    $meeting->invites= User::select('users.id', 'users.name', 'employees.image_url as pimg')
                                    ->join('employees', 'employees.user_id', 'users.id')
                                    ->join('meeting_invites', 'meeting_invites.user_id', 'users.id')
                                    ->where('meeting_invites.meeting_id', $meeting->id)->get();
                    
                    if ($meeting->user_id != $request->user()->id) {
                        $meeting->editable = false;
                        $meeting->dt_editable = false;
                        $meeting->can_share = false;
                        $meeting->can_feedback = false;
                        $meeting->can_cancel = false;
                    } else {
                        $meeting->editable = ($meeting->status < 2) ? true : false;
                        $meeting->dt_editable = ($meeting->status == 0) ? true : false;
                        $meeting->can_share = true;
                        $meeting->can_feedback = (($meeting->mqs == null) && ($meeting->status >= 1)) ? true : false;
                        $meeting->can_cancel = ($meeting->status == 0) ? true : false;
                    }
                    break;
                case 'shared':
                    $meeting = MeetingShare::findOrFail($id);
                    $parentMeeting = Meeting::select('area_id', 'scheduled_at', 'manager_id')->where('id', $meeting->meeting_id)->first();
                    $meetingTime = Carbon::parse($parentMeeting->scheduled_at, 'UTC')->setTimezone($requestedTimezone);
                    $meeting->products = MeetingSharedProduct::select('meeting_shared_products.id', 'suppliers.brand', 'products.title')
                            ->join('suppliers', 'suppliers.id', 'meeting_shared_products.supplier_id')
                            ->join('products', 'products.id', 'meeting_shared_products.product_id')
                            ->where('meeting_shared_products.meetings_shared_id', $id)
                            ->get();
                    $meeting->docs = MeetingSharedDoc::select('meeting_docs.id', 'meeting_docs.file_name', 'meeting_docs.file_type')
                            ->join('meeting_docs', 'meeting_docs.id', 'meeting_shared_docs.meeting_doc_id')
                            ->where('meeting_shared_docs.meeting_shared_id', $id)
                            ->get();
                    $meeting->editable = false;
                    $meeting->dt_editable = false;
                    $meeting->can_cancel = false;
                    $meeting->can_feedback = false;
                    if ($meeting->shared_to != $request->user()->id) {
                        $meeting->can_share = false;
                    } else {
                        $meeting->can_share = true;
                    }
                    $meeting->area = $parentMeeting->area;
                    $meeting->manager = User::select('users.id', 'users.name', 'employees.image_url as pimg')
                                    ->join('employees', 'employees.user_id', 'users.id')
                                    ->where('users.id', $parentMeeting->manager_id)->first();
                    break;
            }
            $meeting->type = $request->type;
            $meeting->date = $meetingTime->format('Y-m-d');
            $meeting->time = $meetingTime->format('h:i A');
            $meeting->business_card = cdn($meeting->business_card);

            $currentTimeInUserTimezone = Carbon::now($requestedTimezone)->addMinutes(30);

            if ($currentTimeInUserTimezone >= $meetingTime && $request->type == 'normal') {
                if ($meeting->user_id != $request->user()->id) {
                    $meeting->can_start = false;
                } else {
                    $meeting->can_start = true;
                }
            } else {
                $meeting->can_start = false;
            }

            return successResponse(trans('api.success'), $meeting);
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function deleteProduct($id) {
        MeetingProduct::where('id', $id)->delete();
        return successResponse(trans('api.success'));
    }

    public function update(Request $request, $id) {
        try {
            $meeting = Meeting::findOrFail($id);

            if ($meeting->status > 1) {
                return errorResponse(trans('api.invalid_request'));
            }
            if ($meeting->status == 0) {
                $rules = [
                    'meeting_date' => 'required_with:meeting_time,timezone|date_format:Y-m-d',
                    'meeting_time' => 'required_with:meeting_date,timezone|date_format:H:i',
                    'timezone' => 'required_with:meeting_date,meeting_time|timezone',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $errorMessage = $validator->messages();
                    return errorResponse(trans('api.required_fields'), $errorMessage);
                }

                if (!empty($request->meeting_date)) {
                    $meetingTime = Carbon::createFromFormat('Y-m-d H:i', $request->meeting_date . ' ' . $request->meeting_time, $request->timezone);
                    $meetingTimeInUTC = $meetingTime->setTimezone('UTC');
                    $meeting->scheduled_at = $meetingTimeInUTC;
                    $meeting->timezone = $request->timezone;
                }
            }

            if (!empty($request->title)) {
                $meeting->title = $request->title;
            }
            if (!empty($request->company_name)) {
                $meeting->company_name = $request->company_name;
            }
            if (!empty($request->company_representative)) {
                $meeting->company_representative = $request->company_representative;
            }
            if (!empty($request->phone)) {
                $meeting->phone = $request->phone;
            }
            if (!empty($request->email)) {
                $meeting->email = $request->email;
            }
            if (!empty($request->location)) {
                $meeting->location = $request->location;
            }
            if (!empty($request->scheduled_notes)) {
                $meeting->scheduled_notes = $request->scheduled_notes;
            }
            if (!empty($request->meeting_notes)) {
                $meeting->meeting_notes = $request->meeting_notes;
            }

            if ($request->hasfile('business_card')) {
                if (!empty($meeting->business_card)) {
                    deleteFile($meeting->business_card);
                }
                $path = 'meetings';
                $meeting->business_card = $path . '/' . $this->singleImage($request->file('business_card'), $path);
            }

            if (!empty($request->products)) {
                $this->insertProducts($meeting->id, $request->products);
            }

            $meeting->save();
            return successResponse(trans('api.success'));
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function meetingNotes(Request $request) {
        $rules = [
            'from_dt' => 'required|date_format:Y-m-d',
            'to_dt' => 'required|date_format:Y-m-d',
            'type' => 'required|in:notes,feedback'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }
        $requestedTimezone = $request->header('timezone', config('app.timezone'));

        $fromDate = Carbon::parse($request->from_dt, $requestedTimezone)->startOfDay()->setTimezone('UTC');
        $toDate = Carbon::parse($request->to_dt, $requestedTimezone)->endOfDay()->setTimezone('UTC');

        $meetingsQuery = Meeting::whereBetween('scheduled_at', [$fromDate, $toDate])
                ->where('user_id', $request->user()->id)
                ->where('status', '>', 0);

        if ($request->type == 'feedback') {
            $meetingsQuery->whereNotNull('mqs');
        }

        $meetings = new PaginateResource(
                $meetingsQuery->paginate($this->paginateNumber)->through(function ($meeting) use ($requestedTimezone) {
                    $meetingTimeInUserTimezone = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($requestedTimezone);
                    return [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'date' => $meetingTimeInUserTimezone->format('Y-m-d'),
                'time' => $meetingTimeInUserTimezone->format('h:i A'),
                    ];
                })
        );
        return successResponse(trans('api.success'), $meetings);
    }
}
