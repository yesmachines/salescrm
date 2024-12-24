<h6 class="mt-4 alert alert-primary"><b>For Supplier </b></h6>
<table class="table form-table" id="supplierFields">
    <tbody>
        <tr valign="top">
            <td width="20%">
                {{$purchaseRequest->supplier->brand}}
            </td>
            <td width="20%">{{$purchaseRequest->pr_number}}
            </td>
            <td width="20%">
                <input type="text" value="{{$purchaseRequest->currency}}" id="supplier_currency" name="supplier[currency]" class="form-control" readonly />
            </td>
            <td width="20%"><input type="text" class="form-control" name="supplier[supplier_email]" placeholder="Supplier Email" required value="{{$purchaseRequest->purchaseDelivery->supplier_email}}" /></td>
            <td width="20%"><input type="text" class="form-control" name="supplier[supplier_contact]" placeholder="Supplier Name" required value="{{$purchaseRequest->purchaseDelivery->supplier_contact}}" /></td>
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

        @forelse($purchaseRequest->purchaseItem as $x => $item)
        <tr valign="top" id="irow-{{$x}}">
            <td>
                <input type="text" class="form-control" name="item[{{$x}}][partno]" placeholder="Part No" value="{{isset($item->partno)? $item->partno : ''}}" readonly />
            </td>
            <td width="30%">
                <textarea class="form-control" name="item[{{$x}}][item_description]" placeholder="Item">{{isset($item->item_description)? $item->item_description : ''}}</textarea>
            </td>
            <td>
                <input type="text" class="form-control unit-price" name="item[{{$x}}][unit_price]" value="{{$item->unit_price}}" placeholder="Unit Price" {{$item->unit_price?"readonly": "" }} />
                <p class="mt-2">
                    <input type="hidden" name="item[{{$x}}][currency]" value="{{$item->currency}}" />
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
                <input type="text" class="form-control" name="item[{{$x}}][total_amount]" placeholder="Final Price" value="{{$item->total_amount}}" readonly />
            </td>
            <td>
                <div class="form-check form-check-md mt-2">
                    <input type="checkbox" id="product_id_{{$x}}" class="form-check-input product_select" name="item[{{$x}}][product_id]" checked value="{{$item->product_id}}">
                    <label class="form-check-label" for="product_id_{{$x}}">SELECT</label>
                    <input type="hidden" name="item[{{$x}}][item_id]" value="{{$item->id}}" />
                </div>
            </td>
        </tr>
        @empty
        @endforelse
    </tbody>
</table>
@if(count($purchaseRequest->purchaseCharge) >0)
<h6><b>Additional Charges</b></h6>
<a href="javascript:void(0);" class="addAC btn btn-primary btn-sm" style="float: right;">
    <i data-feather="plus"></i> Add Row</a>
<table class="table form-table" id="chargespaymentFields">
    <thead>
        <tr>
            <th>Additional Items *</th>
            <th>Considered Charges <span class="text-uppercase">({{$purchaseRequest->currency}})</span> *</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchaseRequest->purchaseCharge as $key => $value)

        <tr valign="top">
            <td width="30%">
                <input type="text" class="form-control" name="charges[{{$key}}][title]" placeholder="Packing Charge" value="{{ $value->title ?? '' }}" />
            </td>
            <td width="20%">
                <input type="number" class="form-control" name="charges[{{$key}}][considered]" placeholder="Considered Cost" step="any" value="{{ $value->considered ?? '' }}" />
            </td>
            <td>
                <div class="form-check form-check-md mt-2">

                    <input type="checkbox" id="charge_id_{{$key}}" class="form-check-input charge_select" name="charges[{{$key}}][charge_id]" value="{{$value->id}}" checked>
                    <label class="form-check-label" for="charge_id_{{$key}}">SELECT</label>
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
            <select class="form-control" name="supplier[shipping_mode]">
                <option value="">-- Mode --</option>
                <option value="air" {{$purchaseRequest->purchaseDelivery->shipping_mode == 'air'? "selected": ""}}>By Air</option>
                <option value="sea" {{$purchaseRequest->purchaseDelivery->shipping_mode == 'sea'? "selected": ""}}>By Sea</option>
                <option value="road" {{$purchaseRequest->purchaseDelivery->shipping_mode == 'road'? "selected": ""}}>By Road</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Availability</label>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="supplier[availability]" value="{{$purchaseRequest->purchaseDelivery->availability}}" placeholder="Product Availability">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Warranty</label>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="supplier[warranty]" placeholder="Warranty Period" value="{{$purchaseRequest->purchaseDelivery->warranty}}">
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-12">
        <div class="separator"></div>
    </div>
</div>

@foreach($purchaseRequest->purchasePaymentTerm as $z => $payment)

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Payment Term</label>
        </div>
        <div class="form-group">
            <input type="hidden" name="payment[{{$z}}][payment_id]" value="{{$payment->id}}">
            <textarea class="form-control" row="3" name="payment[{{$z}}][payment_term]">{{$payment->payment_term}}</textarea>

        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Remarks</label>
        </div>
        <div class="form-group">
            <textarea class="form-control" row="3" name="payment[{{$z}}][remarks]">{{$payment->remarks}}</textarea>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="form-label">Payment Status</label>
        </div>
        <div class="form-group">
            <select class=" form-control" name="payment[{{$z}}][status]">
                <option value="0" {{$payment->status == 0? "selected": ""}}>Not Done</option>
                <option value="1" {{$payment->status == 1? "selected": ""}}>Done</option>
                <option value="2" {{$payment->status == 2? "selected": ""}}>Partially Done</option>
            </select>
        </div>
    </div>
</div>

@endforeach