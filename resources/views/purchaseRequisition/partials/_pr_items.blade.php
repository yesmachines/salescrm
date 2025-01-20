@foreach($order->orderSupplier as $y => $sup)

<h6 class="mt-4 alert alert-primary"><b>For Supplier </b></h6>
<table class="table form-table" id="supplierFields">
    <tbody>
        <tr valign="top">
            <td width="20%">{{$sup->supplier_name}}
                <input type="hidden" value="{{$sup->supplier_id}}" name="supplier[{{$y}}][supplier_id]" />
                <input type="hidden" value="{{$sup->country_id}}" name="supplier[{{$y}}][country_id]" />
                <input type="hidden" value="{{$sup->delivery_term}}" name="supplier[{{$y}}][delivery_term]" />
                <input type="hidden" value="{{$sup->remarks}}" name="supplier[{{$y}}][remarks]" />
            </td>
            <td width="20%">{{$sup->reference_num}}
                <input type="hidden" value="{{$sup->reference_num}}" name="supplier[{{$y}}][pr_number]" />
            </td>
            <td width="20%">
                <input type="text" value="{{$sup->price_basis}}" name="supplier[{{$y}}][currency]" class="form-control" readonly />
            </td>
            <td width="20%"><input type="text" class="form-control" name="supplier[{{$y}}][supplier_email]" placeholder="Supplier Email" required /></td>
            <td width="20%"><input type="text" class="form-control" name="supplier[{{$y}}][supplier_contact]" placeholder="Supplier Name" required /></td>
        </tr>
    </tbody>
</table>
<div class="row ">
    <div class="col-12">
        <p>&nbsp;</p>
    </div>
</div>
<h6> <b>Purchase Items </b></h6>
<table class="table form-table" id="itemcustomFields">
    <thead>
        <tr>
            <th>Part No</th>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Qty</th>
            <th>Dis (%)</th>
            <th>YESNO</th>
            <th>Final Price</th>
            <th></th>
        </tr>
    </thead>
    <tbody>

        @forelse($order->orderItem as $x => $item)

        @if($item->product->brand_id == $sup->supplier_id)

        <tr valign="top" id="irow-{{$x}}">
            <td>
                <input type="text" class="form-control" name="item[{{$x}}][partno]" placeholder="Part No" value="{{isset($item->partno)? $item->partno : ''}}" readonly />
            </td>
            <td width="30%">
                <textarea class="form-control" name="item[{{$x}}][item_description]" placeholder="Item">{{isset($item->item_name)? $item->item_name : ''}}</textarea>
            </td>
            <td>
                <input type="text" class="form-control unit-price" name="item[{{$x}}][unit_price]" value="{{$item->buying_price}}" placeholder="Unit Price" {{$item->buying_price?"readonly": "" }} />
                <p class="mt-2">
                    @if($item->buying_currency)
                    <input type="hidden" name="item[{{$x}}][currency]" value="{{$item->buying_currency}}" />
                    @else
                    <input type="hidden" name="item[{{$x}}][currency]" value="{{$sup->price_basis}}" />
                    @endif
                </p>
            </td>
            <td>
                <input type="number" class="form-control quantity" name="item[{{$x}}][quantity]" value="{{$item->quantity}}" placeholder="Quantity" />
            </td>
            <td>
                <input type="text" class="form-control discount" name="item[{{$x}}][discount]" value="{{$item->discount}}" placeholder="Discount" />
            </td>
            <td>
                <input type="text" class="form-control" name="item[{{$x}}][yes_number]" placeholder="YesNo." value="{{$item->yes_number}}" readonly />
            </td>
            <td width="15%">
                <input type="text" class="form-control" name="item[{{$x}}][total_amount]" placeholder="Final Price" value="{{$item->line_total}}" readonly />
            </td>
            <td>
                <div class="form-check form-check-md mt-2">
                    <input type="checkbox" id="product_id_{{$x}}" class="form-check-input product_select" name="item[{{$x}}][product_id]" value="{{$item->product_id}}">
                    <label class="form-check-label" for="product_id_{{$x}}">SELECT</label>

                    <input type="hidden" name="item[{{$x}}][supplierid]" value="{{$item->product->brand_id}}" />
                </div>
            </td>
        </tr>
        @endif
        @empty
        @endforelse
    </tbody>
</table>

@if(count($order->orderCharge) >0)
<h6 class="mt-4"><b>Additional Charges</b></h6>
<a href="javascript:void(0);" class="addAC btn btn-primary btn-sm" style="float: right;">
    <i data-feather="plus"></i> Add Row</a>
<table class="table form-table" id="chargespaymentFields">
    <thead>
        <tr>
            <th>Additional Items *</th>
            <th>Considered Charges <span class="text-uppercase">({{$sup->price_basis}})</span> *</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->orderCharge as $key => $value)
        @php
        $charge_amt = ($value->considered / $sup->currency_rate);
        $charge_amt = number_format((float)$charge_amt, 2, '.', '')
        @endphp
        <tr valign="top">
            <td width="30%">
                <input type="text" class="form-control" name="charges[{{$y}}][{{$key}}][title]" placeholder="Packing Charge" value="{{ $value->title ?? '' }}" />
            </td>
            <td width="20%">
                <input type="number" class="form-control" name="charges[{{$y}}][{{$key}}][considered]" placeholder="Considered Cost" step="any" value="{{ $charge_amt ?? '' }}" />
            </td>
            <td>
                <div class="form-check form-check-md mt-2">
                    <input type="hidden" name="charges[{{$y}}][{{$key}}][currency]" value="{{$sup->price_basis}}" />
                    <input type="checkbox" id="charge_id_{{$y}}_{{$key}}" class="form-check-input charge_select" name="charges[{{$y}}][{{$key}}][charge_id]" value="{{$value->id}}">
                    <label class="form-check-label" for="charge_id_{{$y}}_{{$key}}">SELECT</label>
                </div>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
@endif

<div class="row mt-4">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Shipping Mode</label>
        </div>
        <div class="form-group">
            <select class="form-control" name="supplier[{{$y}}][shipping_mode]">
                <option value="">-- Mode --</option>
                <option value="air">By Air</option>
                <option value="sea">By Sea</option>
                <option value="road">By Road</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Availability</label>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="supplier[{{$y}}][availability]" placeholder="Product Availability">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Warranty</label>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="supplier[{{$y}}][warranty]" placeholder="Warranty Period">
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-12">
        <div class="separator"></div>
    </div>
</div>

@foreach($order->orderPayment as $z => $payment)
@if($payment->section_type == 'supplier')
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Payment Term</label>
        </div>
        <div class="form-group">
            <textarea class="form-control" row="3" name="payment[{{$y}}][{{$z}}][payment_term]">{{$payment->payment_term}}</textarea>

        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Remarks</label>
        </div>
        <div class="form-group">
            <textarea class="form-control" row="3" name="payment[{{$y}}][{{$z}}][remarks]">{{$payment->remarks}}</textarea>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Payment Status</label>
        </div>
        <div class="form-group">
            <select class=" form-control" name="payment[{{$y}}][{{$z}}][status]">
                <option value="0" {{$payment->status == 0? "selected": ""}}>Not Done</option>
                <option value="1" {{$payment->status == 1? "selected": ""}}>Done</option>
                <option value="2" {{$payment->status == 2? "selected": ""}}>Partially Done</option>
            </select>
        </div>
    </div>
</div>
@endif
@endforeach
<div class="row mb-2">
    <div class="col-12">
        <div class="separator"></div>
    </div>
</div>
@endforeach
