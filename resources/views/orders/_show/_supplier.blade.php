<h5>Supplier TO YES</h5>
<p>Please refer the below Supplier delivery, payment terms, additional charges etc:</p>
<br>
<div class="row mb-2">
    <div class="col-4">
        <h6>Buying Price From Supplier </h6>
    </div>
    <div class="col-6">
        {{ $order->buying_price ?? '' }} AED
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Projected Margin </h6>
    </div>
    <div class="col-6">
        {{ $order->projected_margin ?? '' }} AED
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
        <h6><b>Supplier & Delivery Terms</b>
        </h6>
        <table class="table form-table" id="supplierFields">
            <thead>
                <tr>
                    <th>Supplier </th>
                    <th>Price Basis </th>
                    <th>Delivery Term </th>
                    <th>Supplier Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($suppliers as $y => $sup)
                <tr valign="top">
                    <td width="30%">
                        {{$sup->supplier->brand}}, {{$sup->supplier->country->name}}
                    </td>
                    <td width="20%">
                        {{$sup->price_basis}}
                    </td>
                    <td width="20%">
                        {{$sup->delivery_term}}
                    </td>
                    <td width="30%">
                        {{$sup->remarks? $sup->remarks: '--'}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="row mb-2">
    <div class="col-xxl-12">
        <h6> <b>Payment Terms of Supplier</b></h6>
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
                @foreach($paymentTermsSupplier as $key => $value)
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
<div class="row mb-2">
    <div class="col-xxl-12">
        <h6> <b>Additional Charges</b>
        </h6>
        <table class="table form-table" id="chargespaymentFields">
            <thead>
                <tr>
                    <th>Additional Items </th>
                    <th>Considered Charges(AED) </th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orderCharges as $key => $value)
                <tr valign="top">
                    <td width="30%">
                        {{ $value->title ?? '' }}
                    </td>
                    <td width="20%">
                        {{ $value->considered ?? '' }}
                    </td>
                    <td width="30%">
                        {{ $value->remarks ?? '' }}
                    </td>

                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

<div class="row mb-2">
    <div class="col-4"></div>
    <div class="col-4">
        <button type="button" class="btn btn-default prev-step m-2"><i class="fa fa-chevron-left"></i> Back</button>
    </div>
    <div class="col-4"></div>
</div>
</form>