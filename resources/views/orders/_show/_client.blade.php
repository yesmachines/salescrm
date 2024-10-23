<h5>Order Items & Delivery Details</h5>
<p>Please refer the below Order items like machines, spares & consumables and address to delivery:</p>
<br>

<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Point Address</h6>
    </div>
    <div class="col-6">
        {{ $deliveryPoints->delivery_address}}
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Contact Person *</h6>
    </div>
    <div class="col-6">
        {{ $deliveryPoints->contact_person}}
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Contact Email</h6>
    </div>
    <div class="col-6">
        {{ $deliveryPoints->contact_email}}
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Contact Phone </h6>
    </div>
    <div class="col-6">
        {{ $deliveryPoints->contact_phone}}
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Remarks</h6>
    </div>
    <div class="col-6">
        {{ $deliveryPoints->remarks ?? '' }}
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Order Conversion on basis of demo?</h6>
    </div>
    <div class="col-6">
        {{ $deliveryPoints->is_demo? 'YES': 'NO'}}
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Demo Done By</h6>
    </div>
    <div class="col-6">
        {{ ($deliveryPoints->demo_by >0)? $deliveryPoints->demoby->name: '--'}}
    </div>
    <div class="col-2"></div>
</div>

<div class="row mb-2">
    <div class="col-12">
        <div class="separator"></div>
    </div>
</div>
<div class="row">
    <div class="col-xxl-12">
        <h6> Order Items
        </h6>
        <table class="table form-table" id="itemcustomFields">
            <thead>
                <tr>
                    <th>Product/<br />Model</th>
                    <th>Details</th>
                    <th>Part No</th>
                    <th>Qty</th>
                    <th>YesNo.</th>
                    <th>Selling</th>
                    <th>Buying</th>
                    <th>Expected<br />Delivery</th>
                    <th>Status</th>
                    <th>Item<br />Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quote_items as $x => $item)
                <tr valign="top">
                    <td>
                        {{isset($item->product)? $item->product->modelno : ''}} / {{isset($item->product->supplier)? $item->product->supplier->brand : ''}}
                    </td>
                    <td width="15%">
                        {{$item->item_name}}
                    </td>
                    <td>
                        {{$item->partno}}
                    </td>
                    <td>
                        {{$item->quantity}}
                    </td>
                    <td>
                        {{$item->yes_number}}
                    </td>
                    <td>
                        {{$item->total_amount}} <span class="text-primary">AED</span>
                    </td>
                    <td>
                        {{$item->buying_price}} <span class="text-primary">{{$item->buying_currency}}</span>
                    </td>
                    <td width="8%">
                        {{$item->expected_delivery}}
                    </td>
                    <td width="10%">
                        @switch($item->status)
                        @case(0)
                        Not Delivered
                        @break;
                        @case(1)
                        Delivered
                        @break;
                        @endswitch

                    </td>
                    <td width="15%">
                        {{$item->remarks}}
                    </td>
                </tr>
                @empty
                <tr valign="top">
                    <td colspan="9">-- No data --</td>
                </tr>
                @endforelse
            </tbody>
        </table>


    </div>
</div>
<div class="row mb-2">
    <div class="col-4"></div>
    <div class="col-4">
        <button type="button" class="btn btn-default prev-step m-2"><i class="fa fa-chevron-left"></i> Back</button>
        <button type="button" class="btn btn-default next-step m-2">Next <i class="fa fa-chevron-right"></i></button>
    </div>
    <div class="col-4"></div>
</div>