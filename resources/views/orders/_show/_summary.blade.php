<h5>Order Summary Details</h5>
<p>Please refer the below Order, delivery and payment information :</p>
<br>

<div class="row my-4">
    <div class="col-xxl-12">
        <div class="row mb-2">
            <div class="col-4">
                <h6>OS Date</h6>
            </div>
            <div class="col-6">
                {{$order->os_date}}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <div class="separator"></div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <h5> <b>Customer to YES</b></h5>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-12">

            </div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Customer PO No.</h6>
            </div>
            <div class="col-6">
                {{$order->po_number }}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>PO Date</h6>
            </div>
            <div class="col-6">
                {{$order->po_date }}
            </div>
            <div class="col-2"></div>
        </div>

        <div class="row mb-2">
            <div class="col-4">
                <h6>PO Received Date</h6>
            </div>
            <div class="col-6">
                {{$order->po_received }}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Selling Price (AED) To Client</h6>
            </div>
            <div class="col-6">
                {{$order->selling_price}} AED
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Price Basis/Term</h6>
            </div>
            <div class="col-6">
                {{$deliveryPoints->price_basis}}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Delivery Term</h6>
            </div>
            <div class="col-6">
                {{$deliveryPoints->delivery_term}}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Promised Delivery Time</h6>
            </div>
            <div class="col-6">
                {{$deliveryPoints->promised_delivery}}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Order Status</h6>
            </div>
            <div class="col-6">
                <span class="badge badge-secondary">{{ $order->status}}</span>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Material Status</h6>
            </div>
            <div class="col-6">
                {{$order->material_status }}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Material Details</h6>
            </div>
            <div class="col-6">
                {{$order->material_details}}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Installations & Training</h6>
            </div>
            <div class="col-6">
                {{ $deliveryPoints->installation_training }}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Service Experts</h6>
            </div>
            <div class="col-6">
                {{ $deliveryPoints->service_expert }}
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Estimated Installation</h6>
            </div>
            <div class="col-6">
                {{ $deliveryPoints->estimated_installation }} Days
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-12">
                <div class="separator"></div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-xxl-12">
                <h6> <b>Payment Terms of Client</b></h6>
                <table class="table form-table" id="paymentcustomFields">
                    <thead>
                        <tr>
                            <th>Payment Term</th>
                            <th>Expected Date</th>
                            <th>Status</th>
                            <th>Payment Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentTermsClient as $key => $value)
                        <tr valign="top">
                            <td width="30%">
                                {{$value->payment_term}}
                            </td>
                            <td width="20%">
                                {{$value->expected_date}}
                            </td>
                            <td width="20%">
                                @switch($value->status)
                                @case(0)
                                Not Done
                                @break;
                                @case(1)
                                Done
                                @break;
                                @case(2)
                                Partially Done
                                @break;
                                @endswitch
                            </td>
                            <td width="30%">
                                {{$value->remarks? $value->remarks: '--'}}
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mb-2 mt-4">
            <div class="col-4"></div>
            <div class="col-4">
                <button type="button" class="btn btn-default next-step m-2">Next <i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="col-4"></div>
        </div>
    </div>

</div>