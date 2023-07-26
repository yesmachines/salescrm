<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Services\OrderService;
use Illuminate\Support\Facades\Mail;
use App\Mail\SenOtpMail;

class OrderController extends BaseController
{

    public function validatePoNumber(Request $request, OrderService $orderService)
    {

        $input = $request->all();
        $isExists =  0;

        if (isset($input['po_number']) && $input['po_number']) {
            $po_number = $input['po_number'];
            $isExists = $orderService->isPonumberExist($po_number);

            $resp = ['status' => 'success', 'data' => ($isExists > 0) ? true : false];
        } else {
            $resp = ['status' => 'error', 'data' => ($isExists > 0) ? true : false];
        }

        return response()->json($resp);
    }


    public function validateOtp(Request $request, OrderService $orderService)
    {

        $input = $request->all();
        $isOtpvalid =  0;

        if (isset($input['otp']) && $input['otp']) {
            $po_number = $input['po_number'];
            $otp = $input['otp'];
            $isOtpvalid = $orderService->isOtpvalid($po_number, $otp);

            $resp = ['status' => 'success', 'data' => ($isOtpvalid > 0) ? true : false];
        } else {
            $resp = ['status' => 'error', 'data' => ($isOtpvalid > 0) ? true : false];
        }

        return response()->json($resp);
    }

    public function validateNumber(Request $request, OrderService $orderService)
    {

        $input = $request->all();
        $isNumbervalid =  0;

        if (isset($input['number']) && $input['number']) {
            $number = $input['number'];
            $isNumbervalid = $orderService->isNumbervalid($number);

            $resp = ['status' => 'success', 'data' => ($isNumbervalid > 0) ? true : false];
        } else {
            $resp = ['status' => 'error', 'data' => ($isNumbervalid > 0) ? true : false];
        }

        return response()->json($resp);
    }

    public function searchByPoNumber(Request $request, OrderService $orderService)
    {

        $po_number = $request->po_number;

        $orders  = [];
        if ($po_number) {
            $orders = $orderService->getPonumber($po_number);
        }
        return response()->json($orders);
    }

    public function shortLink(Request $request, OrderService $orderService)
    {

        $number = $request->number;

        $orders  = [];
        if ($number) {
            $orders = $orderService->getPonumberValue($number);
        }
        return response()->json($orders);
    }

    public function addComment(Request $request, OrderService $orderService)
    {

        $input = $request->all();

        $comment  = [];
        if (!empty($input)) {
            $comment = $orderService->createCustomerComments($input);
        }
        return response()->json($comment);
    }

    public function getComments(Request $request, OrderService $orderService)
    {

        $orderId = $request->order_id;

        $comments  = $orderService->geteCustomerComments($orderId);


        return response()->json($comments);
    }



    public function sendOtpViaEmail(Request $request, OrderService $orderService)
    {
        // $po_number = $request->po_number;
        // $otp = $request->random;
        $input = $request->all();
        $email_array = [];
        $data = $orderService->updateOtp($input);
        $email_array['po_number'] =  $input['po_number'];
        $email_array['random'] =  $data['otp'];
        $otp_emails = $data['otp_emails'];
        $customer_email = $data['customer_email'];
        if ($otp_emails != "") {
            $emails = $customer_email . ',' . $otp_emails;
            $otp_emails = explode(",",  $emails);
            foreach ($otp_emails as $otp_email) {
                Mail::to($otp_email)->send(new SenOtpMail($email_array));
            }
        } else {
            $emails = $customer_email;
            Mail::to($emails)->send(new SenOtpMail($email_array));
        }
    }


    // public function fetchOrderItemsBydeliveryID(Request $request, OrderService $orderService)
    // {
    //     //
    //     $request->validate([
    //         'order_delivery_id' => 'required',
    //     ]);
    //     $order_delivery_id = $request->order_delivery_id;

    //     $orders_Items = $orderService->getDeliveryDetails($order_delivery_id);

    //     return response()->json($orders_Items);
    // }
}
