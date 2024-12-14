<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Http\Resources\PaginateResource;
use App\Models\PushNotification;
use App\Models\MeetingShare;

class PushNotificationController extends Controller {

    public function index(Request $request) {
        $notifications = PushNotification::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->orderBy('is_read', 'asc');

        $notifications = new PaginateResource($notifications->paginate($this->paginateNumber));
        PushNotification::whereIn('id', $notifications->pluck('id'))->update(['is_read' => 1]);

        return successResponse(trans('api.success'), $notifications);
    }

    public function counts(Request $request) {
        $data['unread_notifications'] = PushNotification::where('user_id', $request->user()->id)
                ->where('is_read', 0)
                ->count();
        $data['share_requests'] = MeetingShare::where('shared_to', $request->user()->id)
                ->where('status', 0)
                ->count();
        return successResponse(trans('api.success'), $data);
    }

    public function test(Request $request) {
        $body = [
            'headings' => ['en' => 'This is a test title'],
            'contents' => ['en' => 'This is a test message'],
            'data' => [
                'module' => $request->module,
                'module_id' => $request->module_id,
            ]
        ];
        $body ['include_external_user_ids'] = [$request->user_id];
        $body ['channel_for_external_user_ids'] = 'push';
        dd($this->sendONotification($body));
    }
}
