<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Support\Arr;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\OrderClient;
use App\Models\OrderSupplier;
use App\Models\OrderPayment;
use App\Models\OrderCharge;

class OrderService
{

    public function insertOrder(array $userData): Object
    {
        $insert = [
            'company_id'        => $userData['company_id'],
            'customer_id'       => $userData['customer_id'],
            'quotation_id'      => $userData['quotation_id'],
            'os_date'           => $userData['os_date'],
            'po_number'         => (isset($userData['po_number']) && !is_null($userData['po_number'])) ? $userData['po_number'] : '',
            'po_date'           => (isset($userData['po_date']) && !is_null($userData['po_date'])) ? $userData['po_date'] : '',
            'po_received'       => (isset($userData['po_received']) && !is_null($userData['po_received'])) ? $userData['po_received'] : '',
            'currency'          => (isset($userData['currency']) && !is_null($userData['currency'])) ? $userData['currency'] : 'aed',
            'selling_price'     => $userData['selling_price'],
            'created_by'        => $userData['created_by'],
        ];

        $isexist = Order::where("quotation_id", $userData['quotation_id']);

        if ($isexist->count() > 0) {
            // already created
            $order = $isexist->first();
            $order->update($insert);
        } else {
            // not created
            $osno = $this->getReferenceNumber();
            $insert["os_number"] = $osno;
            $order = Order::create($insert);
        }

        return $order;
    }

    public function getReferenceNumber(): ?string
    {
        $StartNo = 475;
        Order::lockForUpdate()->get();

        $lastRow = Order::whereYear('os_date', '=', date('Y'))
            //->where('os_number', 'not like', "%Rev%")
            ->orderBy("id", "desc")
            ->first();

        $last = 0;
        if ($lastRow) {
            $alast = explode("/", $lastRow->os_number);
            $last = (count($alast) > 0) ? (int) $alast[3] : 0;
        }
        $lastId = ($last) ? ($StartNo + $last) : $StartNo;

        $randStr =  "YES/OS/" . date('y') . '/' . $lastId;

        return $randStr;
    }

    public function updateOrder(array $userData, $id): Object
    {
        $order = Order::find($id);

        $update = [];
        if (isset($userData['company_id']) && !is_null($userData['company_id'])) {
            $update['company_id'] = $userData['company_id'];
        }
        if (isset($userData['customer_id']) && !is_null($userData['customer_id'])) {
            $update['customer_id'] = $userData['customer_id'];
        }
        if (isset($userData['quotation_id']) && !is_null($userData['quotation_id'])) {
            $update['quotation_id'] = $userData['quotation_id'];
        }
        if (isset($userData['os_number']) && !is_null($userData['os_number'])) {
            $update['os_number'] = $userData['os_number'];
        }
        if (isset($userData['os_date']) && !is_null($userData['os_date'])) {
            $update['os_date'] = $userData['os_date'];
        }
        if (isset($userData['po_number']) && !is_null($userData['po_number'])) {
            $update['po_number'] = $userData['po_number'];
        }
        if (isset($userData['po_date']) && !is_null($userData['po_date'])) {
            $update['po_date'] = $userData['po_date'];
        }
        if (isset($userData['po_received']) && !is_null($userData['po_received'])) {
            $update['po_received'] = $userData['po_received'];
        }
        if (isset($userData['selling_price']) && !is_null($userData['selling_price'])) {
            $update['selling_price'] = $userData['selling_price'];
        }
        if (isset($userData['buying_price']) && !is_null($userData['buying_price'])) {
            $update['buying_price'] = $userData['buying_price'];
        }
        if (isset($userData['projected_margin']) && !is_null($userData['projected_margin'])) {
            $update['projected_margin'] = $userData['projected_margin'];
        }
        if (isset($userData['actual_margin']) && !is_null($userData['actual_margin'])) {
            $update['actual_margin'] = $userData['actual_margin'];
        }
        if (isset($userData['material_status']) && !is_null($userData['material_status'])) {
            $update['material_status'] = $userData['material_status'];
        }
        if (isset($userData['material_details']) && !is_null($userData['material_details'])) {
            $update['material_details'] = $userData['material_details'];
        }
        if (isset($userData['created_by']) && !is_null($userData['created_by'])) {
            $update['created_by'] = $userData['created_by'];
        }
        if (isset($userData['status']) && !is_null($userData['status'])) {
            $update['status'] = $userData['status'];
        }

        $order->update($update);

        return $order;
    }

    public function saveOrderClient(array $userData): Object
    {
        $update = [];
        if (isset($userData['order_id']) && !is_null($userData['order_id'])) {
            $update['order_id'] = $userData['order_id'];
        }
        if (isset($userData['price_basis']) && !is_null($userData['price_basis'])) {
            $update['price_basis'] = $userData['price_basis'];
        }
        if (isset($userData['delivery_term']) && !is_null($userData['delivery_term'])) {
            $update['delivery_term'] = $userData['delivery_term'];
        }
        if (isset($userData['promised_delivery']) && !is_null($userData['promised_delivery'])) {
            $update['promised_delivery'] = $userData['promised_delivery'];
        }
        if (isset($userData['targeted_delivery']) && !is_null($userData['targeted_delivery'])) {
            $update['targeted_delivery'] = $userData['targeted_delivery'];
        }
        if (isset($userData['installation_training']) && !is_null($userData['installation_training'])) {
            $update['installation_training'] = $userData['installation_training'];
        }
        if (isset($userData['service_expert']) && !is_null($userData['service_expert'])) {
            $update['service_expert'] = $userData['service_expert'];
        }
        if (isset($userData['estimated_installation']) && !is_null($userData['estimated_installation'])) {
            $update['estimated_installation'] = $userData['estimated_installation'];
        }
        if (isset($userData['delivery_address']) && !is_null($userData['delivery_address'])) {
            $update['delivery_address'] = $userData['delivery_address'];
        }
        if (isset($userData['contact_person']) && !is_null($userData['contact_person'])) {
            $update['contact_person'] = $userData['contact_person'];
        }
        if (isset($userData['contact_email']) && !is_null($userData['contact_email'])) {
            $update['contact_email'] = $userData['contact_email'];
        }
        if (isset($userData['contact_phone']) && !is_null($userData['contact_phone'])) {
            $update['contact_phone'] = $userData['contact_phone'];
        }
        if (isset($userData['remarks']) && !is_null($userData['remarks'])) {
            $update['remarks'] = $userData['remarks'];
        }
        if (isset($userData['is_demo'])) {
            $update['is_demo'] = $userData['is_demo'];
        }
        if (isset($userData['demo_by']) && !is_null($userData['demo_by'])) {
            $update['demo_by'] = $userData['demo_by'];
        }

        $isexist = OrderClient::where("order_id", $userData['order_id']);

        if ($isexist->count() > 0) {
            // if already created
            $orderclient = $isexist->first();
            $orderclient->update($update);
        } else {
            // newly created
            $orderclient = OrderClient::create($update);
        }

        return $orderclient;
    }

    public function insertOrderPayment(array $userData): void
    {
        $update = [];

        $isexist = OrderPayment::where("order_id", $userData['order_id'])
            ->where('section_type', $userData['section_type']);

        if (isset($userData['order_id']) && !is_null($userData['order_id'])) {
            $update['order_id'] = $userData['order_id'];
        }
        if (isset($userData['section_type']) && !is_null($userData['section_type'])) {
            $update['section_type'] = $userData['section_type'];
        }

        if ($isexist->count() > 0) {
            // delete existing entries
            $isexist->delete();
        }
        foreach ($userData['payment_term'] as $payment) {
            $isempty = array_values($payment);
            if (is_null($isempty[0]) || empty($isempty[0])) { // payment term field check
                //  array item not exists
                break;
            } else {
                // array item exists
                $insert = array_merge($update, $payment);
                // create new entry to db
                OrderPayment::create($insert);
            }
        }
    }

    public function saveOrderItems(array $userData): void
    {
        $update = [];

        $isexist = OrderItem::where("order_id", $userData['order_id']);

        if (isset($userData['order_id']) && !is_null($userData['order_id'])) {
            $update['order_id'] = $userData['order_id'];
        }

        if ($isexist->count() > 0) {

            // delete existing entry, not product
            // OrderItem::where("order_id", $userData['order_id'])
            //     ->whereNull('product_id')
            //     ->delete();

            foreach ($userData['item'] as $item) {
                // update exsting
                $isempty = array_values($item);
                if (is_null($isempty[0]) || empty($isempty[0])) { // check for product id is null
                    //  array item not exists
                    break;
                } else {
                    // array item exists
                    $insert = array_merge($update, $item);

                    if (!is_null($item['product_id']) && $item['product_id'] != 0) {
                        // if its an existing product, update
                        OrderItem::where("order_id", $userData['order_id'])
                            ->where('product_id', $item['product_id'])
                            ->update($insert);
                    } else {
                        // create new entry to db
                        OrderItem::create($insert);
                    }
                }
            }
        } else {
            //create new
            foreach ($userData['item'] as $item) {
                $isempty = array_values($item);
                if (is_null($isempty[0]) || empty($isempty[0])) { // check for product id is null
                    //  array item not exists
                    break;
                } else {
                    // array item exists
                    $insert = array_merge($update, $item);
                    // create new entry to db
                    OrderItem::create($insert);
                }
            }
        }
    }

    public function saveOrderSupplier(array $userData)
    {
        $update = [];
        if (isset($userData['order_id']) && !is_null($userData['order_id'])) {
            $update['order_id'] = $userData['order_id'];
        }
        $isexist = OrderSupplier::where("order_id", $userData['order_id']);

        if ($isexist->count() > 0) {
            // if already created           
            foreach ($userData['supplier'] as $supplier) {
                $ordersupplier = OrderSupplier::where("order_id", $userData['order_id'])
                    ->where('supplier_id', $supplier['supplier_id'])
                    ->update($supplier);
            }
        } else {
            // newly created
            foreach ($userData['supplier'] as $supplier) {
                $insert = array_merge($update, $supplier);
                $ordersupplier = OrderSupplier::create($insert);
            }
        }

        return $ordersupplier;
    }

    public function insertOrderCharges(array $userData): void
    {
        $update = [];

        $isexist = OrderCharge::where("order_id", $userData['order_id']);

        if (isset($userData['order_id']) && !is_null($userData['order_id'])) {
            $update['order_id'] = $userData['order_id'];
        }

        if ($isexist->count() > 0) {
            // delete existing entry
            $isexist->delete();
        }

        foreach ($userData['charges'] as $charge) {
            $isempty = array_values($charge);
            if (is_null($isempty[0]) && is_null($isempty[1])) {
                //  array item not exists
                break;
            } else {
                // array item exists
                $insert = array_merge($update, $charge);
                // create new entry to db
                OrderCharge::create($insert);
            }
        }
    }

    public function getOrder($id): Object
    {
        return Order::find($id);
    }

    public function updateOrderPayment(array $userData): void
    {
        $update = [];

        $isexist = OrderPayment::where("order_id", $userData['order_id'])
            ->where('section_type', $userData['section_type']);

        if (isset($userData['order_id']) && !is_null($userData['order_id'])) {
            $update['order_id'] = $userData['order_id'];
        }
        if (isset($userData['section_type']) && !is_null($userData['section_type'])) {
            $update['section_type'] = $userData['section_type'];
        }

        // if ($isexist->count() > 0) {
        //     // delete existing entries
        //     $isexist->delete();
        // }
        foreach ($userData['payment_term'] as $payment) {


            $isempty = array_values($payment);

            if (is_null($isempty[1]) || empty($isempty[1])) { // payment term field check              
                //  array item not exists
                break;
            } else {
                if (isset($payment['payment_id']) && !empty($payment['payment_id']) && $payment['payment_id'] != 0) {

                    $cpayment = OrderPayment::find($payment['payment_id']);

                    $cpayment->payment_term     = $payment['payment_term'];
                    $cpayment->expected_date    = $payment['expected_date'];
                    $cpayment->status           = $payment['status'];
                    $cpayment->remarks          = $payment['remarks'];

                    $cpayment->save();
                } else {
                    // array item exists
                    $insert = array_merge($update, $payment);

                    // create new entry to db
                    OrderPayment::create($insert);
                }
            }
        }
    }
    public function getOrderPayment($userData): Object
    {
        return OrderPayment::where("order_id", $userData['order_id'])
            ->where('section_type', $userData['section_type'])->get();
    }

    public function deletePaymentTerm($id): void
    {
        OrderPayment::find($id)->delete();
    }

    public function updateOrderCharges(array $userData): void
    {
        $update = [];

        $isexist = OrderCharge::where("order_id", $userData['order_id']);

        if (isset($userData['order_id']) && !is_null($userData['order_id'])) {
            $update['order_id'] = $userData['order_id'];
        }

        // if ($isexist->count() > 0) {
        //     // delete existing entry
        //     $isexist->delete();
        // }

        foreach ($userData['charges'] as $charge) {
            $isempty = array_values($charge);
            if (is_null($isempty[1]) || empty($isempty[1])) { // additional items field
                //  array item not exists
                break;
            } else {
                if (isset($charge['charge_id']) && !empty($charge['charge_id']) && $charge['charge_id'] != 0) {

                    $extrapayment = OrderCharge::find($charge['charge_id']);

                    $extrapayment->title         = $charge['title'];
                    $extrapayment->considered    = $charge['considered'];
                    $extrapayment->remarks       = $charge['remarks'];

                    $extrapayment->save();
                } else {
                    // array item exists
                    $insert = array_merge($update, $charge);
                    // create new entry to db
                    OrderCharge::create($insert);
                }
            }
        }
    }

    public function getOrderCharges($orderid): Object
    {
        return OrderCharge::where("order_id", $orderid)->get();
    }

    public function deleteOrderCharge($id): void
    {
        OrderCharge::find($id)->delete();
    }
}
