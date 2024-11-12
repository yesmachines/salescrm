<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

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
}
