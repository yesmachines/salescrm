<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Arr;
use App\Models\Orders;
use App\Models\OrderDeliveries;
use App\Models\OrderItems;
use App\Models\OrderHistories;
use App\Models\Customer;
use Faker\Core\Number;
use Ramsey\Uuid\Type\Integer;
use App\Models\CustomerComment;

class OrderService
{
    public function getOrder($data): Object
    {
        if (!empty($data)) {
            if (isset($data['status']) && $data['status']) {
                $sql = Orders::whereIn('status', $data['status']);
            }

            return $sql->orderBy('created_at', 'desc')->get();
        } else {
            return Orders::orderBy('created_at', 'desc')->get();
        }
    }
    public function getOrderById($id): Object
    {
        $order =  Orders::find($id);

        $order->orderHistory = OrderHistories::with(['user', 'replies', 'replies.replies', 'replies.replies.user'])
            ->where('parent_id', 0)
            ->where('order_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return $order;
    }

    public function updateOrderDetails(array $userData): void
    {
        $id = $userData['order_id'];
        $orders = Orders::find($id);
        $update = [];
        if (isset($userData['po_number'])) {
            $update['po_number'] = $userData['po_number'];
        }
        if (isset($userData['yespo_no'])) {
            $update['yespo_no'] = $userData['yespo_no'];
        }
        if (isset($userData['po_date'])) {
            $update['po_date'] = $userData['po_date'];
        }
        if (isset($userData['po_received'])) {
            $update['po_received'] = $userData['po_received'];
        }
        if (isset($userData['status'])) {
            $update['status'] = $userData['status'];
        }
        if ($orders->short_link_code == "") {
            $orders->short_link_code  = strtotime("now");
        }
        $orders->update($update);
    }

    public function updateOrderDeliveryDetails(array $userData): void
    {
        $id = $userData['order_id'];
        $ordDelivExists = OrderDeliveries::where('order_id', $id)->count();
        if ($ordDelivExists > 0) {
            $update = [];
            $orderdeliveries = OrderDeliveries::where('order_id', $id)->first();

            if (isset($userData['shipping_term'])) {
                $update['shipping_term'] = $userData['shipping_term'];
            }
            if (isset($userData['payment_term'])) {
                $update['payment_term'] = $userData['payment_term'];
            }
            if (isset($userData['advance_received'])) {
                $update['advance_received'] = $userData['advance_received'];
            }
            if (isset($userData['delivery_time'])) {
                $update['delivery_time'] = $userData['delivery_time'];
            }
            if (isset($userData['delivery_target'])) {
                $update['delivery_target'] = $userData['delivery_target'];
            }
            if (isset($userData['emails'])) {
                $emails = "";
                $count = count($userData['emails']);
                for ($i = 0; $i < $count; $i++) {
                    $emails .= $userData['emails'][$i] . ",";
                }
                $update['otp_emails'] = rtrim($emails, ',');
            }

            $orderdeliveries->update($update);
        } else {
            $orderdeliveries = new OrderDeliveries;
            $orderdeliveries->order_id   = $id;
            $orderdeliveries->shipping_term      =  $userData['shipping_term'];
            $orderdeliveries->payment_term       =  $userData['payment_term'];
            $orderdeliveries->advance_received   =  $userData['advance_received'];
            $orderdeliveries->delivery_time      =  $userData['delivery_time'];
            $orderdeliveries->delivery_target    =  $userData['delivery_target'];
            if (isset($userData['emails'])) {
                $emails = "";
                $count = count($userData['emails']);
                for ($i = 0; $i < $count; $i++) {
                    $emails .= $userData['emails'][$i] . ",";
                }
                $orderdeliveries->otp_emails  = rtrim($emails, ',');
            }

            $orderdeliveries->userid  =   Auth::id();

            $orderdeliveries->save();
        }
    }

    public function updateOrderItems(array $userData): void
    {
        $deliveryid = $userData['order_delivery_id'];

        if (!empty($userData["item"]) && !empty($userData["qty"])) {

            foreach ($userData["item"] as $key => $value) {
                $orderItems = new OrderItems();
                $orderItems->order_delivery_id = $deliveryid;
                $orderItems->item = $value;
                $orderItems->partno = $userData["part_no"][$key];
                $orderItems->quantity = $userData["qty"][$key];
                $orderItems->remarks = $userData["remarks"][$key];
                $orderItems->delivered = $userData["delivered"][$key];
                $orderItems->status = $userData["status"][$key];
                $orderItems->save();
            }
        }
    }

    public function insertOrderHistoryDetails(array $userData): void
    {
        $id = $userData['order_id'];
        $orderhistories = new OrderHistories();
        $orderhistories->order_id   = $id;
        $orderhistories->comment   =  $userData['comment'];
        $orderhistories->userid    =  Auth::id();
        $orderhistories->save();
    }
    public function loadOrderHistories($id): Object
    {
        return OrderHistories::where('order_id', $id)->orderBy('id', 'desc')->get();
    }
    public function loadOrderItems($id): Object
    {
        return OrderItems::where('order_delivery_id', $id)->orderBy('id', 'asc')->get();
    }

    public function loadOrderDeliveryHistoryDetails($id): Object
    {
        return OrderDeliveries::where('order_id', $id)->orderBy('id', 'desc')->get();
    }

    public function deleteOrderHistoryDetails($id): void
    {
        // delete all children
        $children = OrderHistories::where('parent_id', $id);
        foreach ($children->get() as $child) {
            OrderHistories::where('parent_id', $child->id)->delete();
        }
        $children->delete();

        $orderHistories = OrderHistories::find($id);
        $orderHistories->delete();
    }

    public function deleteReplyComments($id): void
    {
        $orderHistories = OrderHistories::find($id);
        $orderHistories->delete();
    }


    public function loadOrderDeliveryHistoryDetailsById($id): Object
    {
        return OrderDeliveries::where('id', $id)->first();
    }

    public function  deleteOrderItem($id): void
    {
        $orderitems = OrderItems::find($id);
        $orderitems->delete();
    }

    public function updateOrderDeliveryDetailsByIdUpdate(array $userData): void
    {
        $id = $userData['order_delivery_id'];
        $orderdeliveries = OrderDeliveries::find($id);
        $orderdeliveries->shipping_term   =  $userData['shipment_term'];
        $orderdeliveries->payment_term    =  $userData['payment_term'];
        $orderdeliveries->delivery_on    =  $userData['delivery_date'];
        $orderdeliveries->save();

        OrderItems::where('order_delivery_id', $id)->delete();

        if (isset($userData["item"])) {

            foreach ($userData["item"] as $key => $value) {
                $orderItems = new OrderItems();
                $orderItems->order_delivery_id = $orderdeliveries->id;
                $orderItems->item = $value;
                $orderItems->partno = $userData["part_no"][$key];
                $orderItems->quantity = $userData["qty"][$key];
                $orderItems->save();
            }
        }
    }
    public function loadOrderHistoryDetails($id): Object
    {
        return OrderHistories::with(['user', 'replies', 'replies.replies', 'replies.replies.user'])
            ->where('parent_id', 0)
            ->where('order_id', $id)
            ->orderBy('id', 'desc')
            ->get();
    }
    // public function getPonumber($id): array
    // {

    //     $get_order_id = Orders::where('po_number', $id)->first();
    //     $orders = Orders::where('id', $get_order_id->id)->first();
    //     $orderDeliveries = OrderDeliveries::where('order_id', $get_order_id->id)->get();
    //     // $orderDeliveries=OrderDeliveries::where('order_id', $get_order_id->id)->get();
    //     foreach ($orderDeliveries as $orderDelivery) {
    //         $orderItems = OrderItems::where('order_delivery_id', $orderDelivery->id)->get();
    //     }
    //     $orderHistories = OrderHistories::where('order_id', $get_order_id->id)->get();

    //     return array(
    //         'orders' => $orders,
    //         'orderDeliveries' => $orderDeliveries,
    //         'orderHistories' => $orderHistories,
    //         '$orderItems' => $orderItems,
    //     );
    // }
    public function isPonumberExist($id): int
    {
        return Orders::where('po_number', $id)->count();
    }


    public function isOtpvalid($ponumber, $otp): int
    {
        $get_order = Orders::where('po_number', $ponumber)->where('otp_code', $otp)->first();
        $expiry_date_time = $get_order->otp_expire_date_time;
        $date = date('Y-m-d H:i:s');
        $dateTimestamp1 = strtotime($date);
        $dateTimestamp2 = strtotime($expiry_date_time);
        if ($dateTimestamp1 > $dateTimestamp2) {
            return 0;
        } else {
            return Orders::where('po_number', $ponumber)->where('otp_code', $otp)->count();
        }
    }

    public function isNumbervalid($number): int
    {

        return Orders::where('short_link_code', $number)->count();
    }



    public function getPonumber($id): array
    {
        $orders = Orders::where('po_number', $id)->first();

        $orders->company = $orders->company;
        $orders->customer = $orders->customer;

        $get_order_id = $orders->id;

        $orderDeliveries = OrderDeliveries::with('orderItems')->where('order_id', $get_order_id)->first();

        $orderHistories = OrderHistories::with(['user', 'replies', 'replies.replies', 'replies.replies.user'])
            ->where('parent_id', 0)
            ->where('order_id', $get_order_id)
            ->orderBy('id', 'desc')
            ->get();


        // OrderHistories::where('order_id', $get_order_id)->with('user')->get();

        return array(
            'orders' => $orders,
            'orderDeliveries' => $orderDeliveries,
            'orderHistories' => $orderHistories
        );
    }


    public function getPonumberValue($id): array
    {
        $orders = Orders::where('short_link_code', $id)->first();
        return array(
            'orders' => $orders,

        );
    }


    public function updateOtp($orderData): array
    {

        $po_number = $orderData['po_number'];
        $orders = Orders::where('po_number', $po_number)->first();
        $orderDeliveries = OrderDeliveries::find($orders->id);
        $update = [];
        $random = substr(str_shuffle("0123456789"), 0, 6);
        $get_count = Orders::where('otp_code', $random)->get();
        if (count($get_count) >= 1) {
            $update['otp_code'] = substr(str_shuffle("0123456789"), 0, 6);
        } else {
            $update['otp_code'] = $random;
        }
        $date = date('Y-m-d H:i:s');
        $newDate = date('Y-m-d H:i:s', strtotime($date . ' +720 seconds'));
        $update['otp_expire_date_time'] = $newDate;
        $orders->update($update);
        $orderDeliveries = OrderDeliveries::with('orderItems')->where('order_id', $orders->id)->first();
        $customer = Customer::where('id', $orders->customer_id)->first();
        $customer_email = $customer->email;
        if ($orderDeliveries->otp_emails != "") {
            $emails = $customer_email . ',' . $orderDeliveries->otp_emails;
        } else {
            $emails = $customer_email;
        }


        return array(
            'otp_emails' => $orderDeliveries->otp_emails,
            'customer_email' => $customer_email,
            'otp' => $update['otp_code'],

        );
    }
    // public function getDeliveryDetails($id): array
    // {

    //     $orderDeliveries = OrderDeliveries::where('id', $id)->first();


    //     return array(

    //         'orderDeliveries' => $orderDeliveries,
    //         'orderItems' => $orderDeliveries->orderItems,
    //         'fail'  => '0',
    //     );
    // }
    public function createCustomerComments(array $comments): Object
    {
        return OrderHistories::create([

            'order_id' => $comments['order_id'],
            'username' => isset($comments['username']) ? $comments['username'] : null,
            'parent_id' => $comments['parent_id'],
            'comment' => $comments['comment'],
        ]);
    }
    public function geteCustomerComments(int $orderId): Object
    {
        return OrderHistories::with(['replies'])->where('order_id', $orderId)
            ->orderBy('id', 'desc')->get();

        // Category::with(['products', 'childs.products'])->where('parent_id', 0)->get()
        //     ->sortBy('products.priority', SORT_REGULAR, false);
    }
    public function insertCommentReply(array $userData): void
    {
        $id = $userData['order_id'];
        $orderhistories = new OrderHistories();
        $orderhistories->order_id   = $id;
        $orderhistories->parent_id   = $userData['parent_id'];
        $orderhistories->comment   =  $userData['reply'];
        $orderhistories->userid    =  Auth::id();
        $orderhistories->save();
    }
}
