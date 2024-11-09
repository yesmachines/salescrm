<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use Validator;
use Image;
use App\Http\Resources\PaginateResource;
use App\Models\Meeting;
use App\Models\MeetingProduct;

class MeetingController extends Controller {

    public function store(Request $request) {
        $rules = [
            'meeting_date' => 'required|date_format:Y-m-d',
            'meeting_time' => 'required|date_format:H:i',
            'timezone' => 'required|timezone',
            'title' => 'required',
            'company_name' => 'required',
            'company_representative' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'location' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errorMessage = $validator->messages();
            return errorResponse(trans('api.required_fields'), $errorMessage);
        }

        $meetingTime = Carbon::createFromFormat('Y-m-d H:i', $request->meeting_date . ' ' . $request->meeting_time, $request->timezone);
        $meetingTimeInUTC = $meetingTime->setTimezone('UTC');

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
        $meeting->scheduled_notes = $request->scheduled_notes;
        $meeting->save();

        return successResponse(trans('api.meeting.created'), ['meeting_id' => $meeting->id]);
    }

    public function notesStore(Request $request, $id) {
        try {
            $meeting = Meeting::findOrFail($id);
            if ($meeting->status < 2) {
                $rules = [
                    'meeting_notes' => 'required',
                    'business_card' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                    'products' => 'required|array',
                    'products.*.brand_id' => 'required',
                    'products.*.product_id' => 'required'
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    $errorMessage = $validator->messages();
                    return errorResponse(trans('api.required_fields'), $errorMessage);
                }

                if ($request->hasfile('business_card')) {
                    $file = $request->file('business_card');
                    $filename = rand(1, time()) . '.' . $file->getClientOriginalExtension();
                    $card = 'meetings/' . $filename;
                    $image = Image::make($file);
                    $image->save(storage_path('app/public/' . $card));
                    $meeting->business_card = $card;
                }
                $meeting->meeting_notes = $request->meeting_notes;
                $meeting->status = 1;
                $meeting->save();

                $this->insertProducts($meeting->id, $request->products);

                return successResponse(trans('api.meeting.notes_created'), ['meeting_id' => $meeting->id]);
            }
        } catch (ModelNotFoundException $e) {
            return errorResponse(trans('api.invalid_request'), $e->getMessage());
        }
        return errorResponse(trans('api.invalid_request'));
    }

    public function list(Request $request, $type) {
        $requestedTimezone = $request->header('timezone');
        $currentTimeInUserTimezone = Carbon::now($requestedTimezone);
        $currentTimeInUTC = $currentTimeInUserTimezone->copy()
                ->setTimezone('UTC')
                ->startOfMinute()
                ->toDateTimeString();

        $meetingsQuery = Meeting::select(
                        'meetings.id',
                        'meetings.user_id',
                        'meetings.title',
                        'meetings.scheduled_at'
                )
                ->where('meetings.user_id', $request->user()->id);

        switch ($type) {
            case 'available' :
                $meetingsQuery->whereRaw("cm_meetings.scheduled_at >= '$currentTimeInUTC'")
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
                $meetingsQuery
                        ->whereDate('scheduled_at', '>=', $request->from_dt)
                        ->whereDate('scheduled_at', '<=', $request->to_dt)
                        ->whereRaw("cm_meetings.scheduled_at < '$currentTimeInUTC'")
                        ->orderByRaw("CONVERT_TZ(cm_meetings.scheduled_at, '+00:00', '$requestedTimezone') DESC");
                break;
            default:
                return errorResponse(trans('api.invalid_request'));
        }

        $meetings = new PaginateResource(
                $meetingsQuery->paginate(10)->through(function ($meeting) use ($requestedTimezone) {
                    $meetingTimeInUserTimezone = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($requestedTimezone);
                    return [
                'id' => $meeting->id,
                'shared_id' => null,
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
        $userTimezoe = $request->header('timezone');

        $groupedByDate = [];

        Meeting::select('id', 'title', 'scheduled_at')
                ->where('user_id', $request->user()->id)
                ->whereYear('scheduled_at', $request->year)
                ->whereMonth('scheduled_at', $request->month)
                ->orderBy('scheduled_at', 'asc')
                ->chunk(200, function ($meetings) use (&$groupedByDate, $userTimezoe) {
                    foreach ($meetings as $meeting) {
                        $meetingTime = Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($userTimezoe);

                        $formattedTime = $meetingTime->format('h:i A');
                        $meetingDate = $meetingTime->format('Y-m-d');

                        $groupedByDate[$meetingDate][] = [
                            'id' => $meeting->id,
                            'shared_id' => null,
                            'title' => $meeting->title,
                            'time' => $formattedTime
                        ];
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
                return errorResponse(trans('api.meeting.note_done'));
            }
            return errorResponse(trans('api.meeting.feedback_exist'));
        }
        return errorResponse(trans('api.invalid_request'));
    }
}
