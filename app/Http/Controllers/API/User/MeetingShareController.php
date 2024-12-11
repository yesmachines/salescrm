<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmailMeetingNotes;
use App\Http\Resources\PaginateResource;
use App\Models\Meeting;
use App\Models\MeetingProduct;
use App\Models\MeetingShare;
use App\Models\MeetingSharedProduct;

class MeetingShareController extends Controller {

    public function employees() {
        $roleNames = ['salesmanager', 'coordinators', 'satellite'];
        $roles = Role::whereIn('name', $roleNames)
                ->with(['users' => function ($query) {
                        $query->orderBy('name');
                    }, 'users.employee'])
                ->get();
        $groupedByRole = $roles->mapWithKeys(function ($role) {
            return [
        $role->name => $role->users->map(function ($user) {
            return [
        'id' => $user->id,
        'name' => $user->name,
        'image_url' => !empty($user->employee->image_url) ? asset('storage/' . $user->employee->image_url) : null
            ];
        })
            ];
        });
        return successResponse(trans('api.meeting.created'), $groupedByRole);
    }

    public function share(Request $request, $id) {
        $rules = [
            'type' => 'required|in:normal,shared',
            'meeting_notes' => 'required',
            'products' => 'nullable|array',
            'title' => 'required',
            'company_name' => 'required',
            'company_representative' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'location' => 'required',
            'meeting_notes' => 'required',
            'share_to' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }
        try {
            $shareTo = \App\Models\User::findOrFail($request->share_to);
            switch ($request->type) {
                case 'normal':
                    $meeting = Meeting::findOrFail($id);
                    if ($meeting->status == 0) {
                        return errorResponse(trans('api.meeting.not_done'));
                    }
                    $meeting_id = $meeting->id;
                    $parent_id = null;
                    $products = MeetingProduct::select('product_id', 'supplier_id as brand_id')
                            ->whereIn('id', $request->products)
                            ->get()
                            ->map(function ($item) {
                        return $item->toArray();
                    });
                    break;
                case 'shared':
                    $meeting = MeetingShare::findOrFail($id);
                    if ($meeting->status == 0) {
                        return errorResponse(trans('api.meeting.not_confirmed'));
                    }
                    $meeting_id = $meeting->meeting_id;
                    $parent_id = $id;
                    $products = MeetingSharedProduct::select('product_id', 'supplier_id as brand_id')
                            ->whereIn('id', $request->products)
                            ->get()
                            ->map(function ($item) {
                        return $item->toArray();
                    });
                    break;
            }
            //Check this meeting is already shared with 
            $alreadyShared = MeetingShare::where('meeting_id', $meeting_id)
                    ->where('shared_to', $request->share_to)
                    ->whereIn('status', [0, 1, 2])
                    ->first();
            if ($alreadyShared) {
                return errorResponse(trans('api.meeting.already_shared_to', ['name' => $shareTo->name]));
            }
            $shareMeeting = new MeetingShare();
            $shareMeeting->meeting_id = $meeting_id;
            $shareMeeting->shared_by = $request->user()->id;
            $shareMeeting->shared_to = $request->share_to;
            $shareMeeting->parent_id = $parent_id;
            $shareMeeting->title = $request->title;
            $shareMeeting->company_name = $request->company_name;
            $shareMeeting->company_representative = $request->company_representative;
            $shareMeeting->phone = $request->phone;
            $shareMeeting->email = $request->email;
            $shareMeeting->location = $request->location;
            $shareMeeting->business_card = $request->business_card;
            $shareMeeting->meeting_notes = $request->meeting_notes;
            $shareMeeting->status = ($shareTo->hasRole('coordinators')) ? 2 : 0;
            $shareMeeting->save();

            $meeting->status = 2;
            $meeting->save();

            //Save shared products
            if (!$products->isEmpty()) {
                $this->insertSharedProducts($shareMeeting->id, $products);
            }

            if ($shareTo->hasRole('coordinators')) {
                //Send Email
                $this->sendAsEmailToCoordinator($shareTo, $meeting, $shareMeeting);
            } else {
                //Send push notifiction to share_to usres
                $body = [
                    'headings' => ['en' => trans('api.notification.title.shared', ['name' => $request->user()->name])],
                    'contents' => ['en' => $shareMeeting->title],
                    'data' => [
                        'module' => 'share-requests',
                        'module_id' => $shareMeeting->id
                    ]
                ];
                $body ['include_external_user_ids'] = [$shareTo->id];
                $body ['channel_for_external_user_ids'] = 'push';
                $this->sendONotification($body);
            }
            return successResponse(trans('api.meeting.shared'), ['meeting_id' => $shareMeeting->id]);
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function sendAsEmailToCoordinator($shareTo, $meeting, $shareMeeting) {
        $meetingDate = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($meeting->timezone);
        $shareMeeting->date = $meetingDate->format('M d, Y g:i A') . ' - ' . $meeting->timezone;
        $shareMeeting->business_card = empty($shareMeeting->business_card) ? null : asset('storage') . '/' . $shareMeeting->business_card;

        $shareMeeting->products = MeetingSharedProduct::select('meeting_shared_products.id', 'suppliers.brand', 'products.title')
                ->join('suppliers', 'suppliers.id', 'meeting_shared_products.supplier_id')
                ->join('products', 'products.id', 'meeting_shared_products.product_id')
                ->where('meeting_shared_products.meetings_shared_id', $shareMeeting->id)
                ->get();
        //$shareTo->email =  'shainu.giraf@gmail.com';
        Notification::send($shareTo, new EmailMeetingNotes($shareMeeting));
    }

    public function requests(Request $request, $status) {
        $requestedTimezone = $request->header('timezone', config('app.timezone'));
        $meetingsQuery = MeetingShare::select('meeting_shares.id', 'meeting_shares.title', 'users.name as shared_by', 'meetings.scheduled_at', 'meeting_shares.created_at')
                ->join('users', 'users.id', 'meeting_shares.shared_by')
                ->join('meetings', 'meetings.id', 'meeting_shares.meeting_id')
                ->where('meeting_shares.shared_to', $request->user()->id)
                ->orderBy('meeting_shares.created_at', 'DESC');

        switch ($status) {
            case 'pending':
                $meetingsQuery->where('meeting_shares.status', 0);
                break;
            case 'accepted':
                $meetingsQuery->whereIn('meeting_shares.status', [1, 2]);
                break;
            case 'rejected':
                $meetingsQuery->where('meeting_shares.status', 3);
                break;
            default:
                $meetingsQuery->where('meeting_shares.status', 0);
        }

        $shares = new PaginateResource(
                $meetingsQuery->paginate($this->paginateNumber)->through(function ($meeting) use ($requestedTimezone) {
                    $meetingTimeInUserTimezone = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($requestedTimezone);
                    $createdAtInUserTimezone = Carbon::parse($meeting->created_at, config('app.timezone'))->setTimezone($requestedTimezone);

                    return [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'shared_by' => $meeting->shared_by,
                'date' => $meetingTimeInUserTimezone->format('Y-m-d'),
                'time' => $meetingTimeInUserTimezone->format('h:i A'),
                'created_date' => $createdAtInUserTimezone->format('Y-m-d'),
                'created_time' => $createdAtInUserTimezone->format('h:i A'),
                    ];
                })
        );
        return successResponse(trans('api.success'), $shares);
    }

    public function confirmRequest(Request $request, $id) {
        $rules = [
            'confirm_status' => 'required|in:accepted,rejected',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }
        try {
            $meeting = MeetingShare::findOrFail($id);
            if ($meeting->status == 0) {
                $status = ['accepted' => 1, 'rejected' => 3];
                $meeting->status = $status[$request->confirm_status];
                $meeting->save();
                //Send status notification to shared_by
                $body = [
                    'headings' => ['en' => trans('api.notification.title.' . $request->confirm_status, ['name' => $request->user()->name])],
                    'contents' => ['en' => $meeting->title],
                    'data' => [
                        'module' => 'shared-list',
                        'module_id' => $meeting->id,
                        'confirm_status' => $request->confirm_status
                    ]
                ];
                $body ['include_external_user_ids'] = [$meeting->shared_by];
                $body ['channel_for_external_user_ids'] = 'push';
                $this->sendONotification($body);
                return successResponse(trans('api.success'));
            }
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function sharedList(Request $request) {
        $rules = [
            'from_dt' => 'date_format:Y-m-d',
            'to_dt' => 'date_format:Y-m-d',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $requestedTimezone = $request->header('timezone', config('app.timezone'));

        $meetingsQuery = MeetingShare::select('meeting_shares.id', 'meeting_shares.title', 'users.name as shared_to', 'meetings.scheduled_at', 'meeting_shares.status')
                ->join('users', 'users.id', 'meeting_shares.shared_to')
                ->join('meetings', 'meetings.id', 'meeting_shares.meeting_id')
                ->where('meeting_shares.shared_by', $request->user()->id)
                ->orderBy('meeting_shares.created_at', 'DESC');
        
        if (!empty($request->from_dt) && !empty($request->to_dt)) {
            $fromDate = Carbon::parse($request->from_dt, $requestedTimezone)->startOfDay()->setTimezone('UTC');
            $toDate = Carbon::parse($request->to_dt, $requestedTimezone)->endOfDay()->setTimezone('UTC');
            $meetingsQuery->whereBetween('meetings.scheduled_at', [$fromDate, $toDate]);
        }

        $status = [0 => 'Pending', 1 => 'Accepted', 2 => ' Accepted', 3 => 'Rejected'];
        $meetings = new PaginateResource(
                $meetingsQuery->paginate($this->paginateNumber)->through(function ($meeting) use ($requestedTimezone, $status) {
                    $meetingTimeInUserTimezone = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($requestedTimezone);
                    return [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'shared_to' => $meeting->shared_to,
                'date' => $meetingTimeInUserTimezone->format('Y-m-d'),
                'time' => $meetingTimeInUserTimezone->format('h:i A'),
                'status' => $status[$meeting->status]
                    ];
                })
        );
        return successResponse(trans('api.success'), $meetings);
    }
}
