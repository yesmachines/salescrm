<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use \App\Traits\OneSignalTrait;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        OneSignalTrait;

    protected $paginateNumber = 21;

    function insertProducts($mid, $products) {
        foreach ($products as $product) {
            $meetingProduct = new \App\Models\MeetingProduct();
            $meetingProduct->meeting_id = $mid;
            $meetingProduct->product_id = $product['product_id'];
            $meetingProduct->supplier_id = $product['brand_id'];
            $meetingProduct->save();
        }
        return 1;
    }

    function insertSharedProducts($mid, $products) {
        foreach ($products as $product) {
            $meetingProduct = new \App\Models\MeetingSharedProduct();
            $meetingProduct->meetings_shared_id = $mid;
            $meetingProduct->product_id = $product['product_id'];
            $meetingProduct->supplier_id = $product['brand_id'];
            $meetingProduct->save();
        }
        return 1;
    }

    function insertSharedDocs($mid, $docs) {
        foreach ($docs as $doc) {
            $meetingDoc = new \App\Models\MeetingSharedDoc();
            $meetingDoc->meeting_shared_id = $mid;
            $meetingDoc->meeting_doc_id = $doc;
            $meetingDoc->save();
        }
        return 1;
    }

    function notifyAreaMnager($meeting) {
        $requestedTimezone = \App\Models\Area::where('id', $meeting->area_id)->first()->timezone;
        $meetingTimeInUserTimezone = \Carbon\Carbon::parse($meeting->scheduled_at, 'UTC')->setTimezone($requestedTimezone);
        $body = [
            'headings' => ['en' => trans('api.notification.title.area', ['name' => $meeting->company_name])],
            'contents' => ['en' => "Location: " . $meeting->location . ", At: " . $meetingTimeInUserTimezone->format('Y-m-d') . " " . $meetingTimeInUserTimezone->format('h:i A')],
            'data' => [
                'module' => 'general',
                'module_id' => $meeting->id
            ]
        ];
        $body ['include_external_user_ids'] = [$meeting->manager_id];
        $body ['channel_for_external_user_ids'] = 'push';
        $this->sendONotification($body);
        return 1;
    }

    function notifyEnquiryShared($enquiry, $notifyTo, $type) {
        $body = [
            'data' => [
                'module' => 'enquiry',
                'module_id' => $enquiry->id
            ]
        ];

        switch ($type) {
            case 'share';
                $body['headings'] = ['en' => trans('api.notification.title.share_enquiry')];
                $body['contents'] = ['en' => trans('api.notification.message.share_enquiry', ['name' => auth('sanctum')->user()->name, 'type' => $enquiry->lead_type_label])];
                break;
            case 'assist';
                $body['headings'] = ['en' => trans('api.notification.title.assist_enquiry')];
                $body['contents'] = ['en' => trans('api.notification.message.assist_enquiry', ['name' => auth('sanctum')->user()->name, 'type' => $enquiry->lead_type_label])];
                break;
            case 'manager';
                $body['headings'] = ['en' => trans('api.notification.title.area_enquiry', ['type' => $enquiry->lead_type_label])];
                $body['contents'] = ['en' => trans('api.notification.message.area_enquiry', ['name' => auth('sanctum')->user()->name, 'type' => $enquiry->lead_type_label])];
                break;
        }

        $body ['include_external_user_ids'] = [$notifyTo];
        $body ['channel_for_external_user_ids'] = 'push';
        $this->sendONotification($body);
        return 1;
    }

    function getCoordinator($managerId = null) {
        $coordinator = \App\Models\User::select('users.id', 'users.name', 'users.email', 'ep.image_url as pimg')
                ->join('employees as ep', 'ep.user_id', 'users.id')
                ->join('employee_managers as em', 'em.employee_id', 'ep.id');
        If (empty($managerId)) {
            $coordinator->where('em.manager_id', auth()->user()->employee->id);
        } else {
            $coordinator->where('em.manager_id', $managerId);
        }
        return $coordinator->first();
    }
}
