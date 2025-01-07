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

    function notifyAreaMnager($meeting) {
        $userIds = \App\Models\EmployeeArea::where('area_id', $meeting->area_id)
                ->pluck('user_id')
                ->toArray();
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
        $body ['include_external_user_ids'] = $userIds;
        $body ['channel_for_external_user_ids'] = 'push';
        $this->sendONotification($body);
        return 1;
    }

    function notifyEnquiryAreaMnager($enquiry, $productCount) {
        $userIds = \App\Models\EmployeeArea::where('area_id', $enquiry->area_id)
                ->pluck('user_id')
                ->toArray();

        $body = [
            'headings' => ['en' => trans('api.notification.title.area_enquiry', ['type' => $enquiry->lead_type_label])],
            'contents' => ['en' => trans('api.notification.message.area_enquiry', ['name' => auth()->user()->name, 'type' => $enquiry->lead_type_label, 'count' => $productCount])],
            'data' => [
                'module' => 'general',
                'module_id' => null
            ]
        ];
        $body ['include_external_user_ids'] = $userIds;
        $body ['channel_for_external_user_ids'] = 'push';
        $this->sendONotification($body);
        return 1;
    }

    function getCoordinator() {
        return $coordinator = \App\Models\User::select('users.id', 'users.name', 'users.email', 'ep.image_url as pimg')
                ->join('employees as ep', 'ep.user_id', 'users.id')
                ->join('employee_managers as em', 'em.employee_id', 'ep.id')
                ->where('em.manager_id', auth()->user()->employee->id)
                ->first();
    }
}
