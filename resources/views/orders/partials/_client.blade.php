<h5>Order Items & Delivery Details</h5>
<p>Please refer the below Order items like machines, spares & consumables and address to delivery:</p>
<br>
<div class="row">
    <div class="col-12">
        <div class="client_error_msg"></div>
        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>
<!-- <form name="add_delivery_item" id="add_delivery_item"> -->
{!! Form::open(array('id' => 'add_delivery_item', 'name' => 'add_delivery_item')) !!}
<input type="hidden" name="order_id" id="order_id_step2" />

<div class="row mb-2">
    <div class="col-4">
        <h6>Material Status *</h6>
    </div>
    <div class="col-6">
        <select class="form-control" name="material_status" id="material_status" required>
            <option value="">-Select Status-</option>
            <option value="is_stock" {{($quote_avail && ($quote_avail->stock_status == 'is_stock'))? 'selected': ''}}>In Stock</option>
            <option value="out_stock" {{( $quote_avail && ($quote_avail->stock_status == 'out_stock'))? 'selected': ''}}>Out Stock</option>
        </select>
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Material Details *</h6>
    </div>
    <div class="col-6">
        <textarea rows="2" id="material_details" name="material_details" class="form-control" required>@if($quote_avail)Material will delivery within {{$quote_avail->working_weeks}} working {{$quote_avail->working_period}}{{$quote_avail->working_weeks > 1? 's': ''}}@endif</textarea>
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Installations & Training</h6>
    </div>
    <div class="col-6">
        <input type="text" id="installation_training" name="installation_training" class="form-control" value="{{($quote_install)? $quote_install->installation_by: ''}}" />
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Service Experts</h6>
    </div>
    <div class="col-6">
        <input type="text" id="service_expert" name="service_expert" class="form-control" value="" />
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Estimated Installation (DAYS)</h6>
    </div>
    <div class="col-6">
        <input type="text" id="estimated_installation" name="estimated_installation" class="form-control" value="{{($quote_install)? $quote_install->installation_periods: ''}}" />
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Point Address *</h6>
    </div>
    <div class="col-6">
        <input type="text" id="delivery_address" name="delivery_address" class="form-control" required />
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Contact Person *</h6>
    </div>
    <div class="col-6">
        <input type="text" id="contact_person" name="contact_person" class="form-control" required />
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Contact Email</h6>
    </div>
    <div class="col-6">
        <input type="text" id="contact_email" name="contact_email" class="form-control" />
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Contact Phone *</h6>
    </div>
    <div class="col-6">
        <input type="text" id="contact_phone" name="contact_phone" class="form-control" required />
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Delivery Remarks</h6>
    </div>
    <div class="col-6">
        <textarea name="delivery_remarks" class="form-control"></textarea>
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Order Conversion on basis of demo?</h6>
    </div>
    <div class="col-6">
        <label><input type="radio" name="is_demo" value="1"> YES</label>&nbsp; &nbsp;
        <label><input type="radio" name="is_demo" value="0" checked> NO</label>
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-4">
        <h6>Demo Done By</h6>
    </div>
    <div class="col-6">
        <select class="form-control" name="demo_by">
            <option value="">--Select--</option>
            @foreach($service_employee as $emp)
            <option value="{{$emp->user_id}}">{{$emp->user->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-2"></div>
</div>
<div class="row mb-2">
    <div class="col-12">
        <div class="separator"></div>
    </div>
</div>
@include('orders.partials._localsupplier')
<div class="row">
    <div class="col-xxl-12">
        <h6> <b>Order Items</b>
            <!-- <a href="javascript:void(0);" class="addIT btn btn-primary btn-sm" style="float: right;"><i data-feather="plus"></i> Add Row</a> -->
        </h6>
        <table class="table form-table" id="itemcustomFields">
            <thead>
                <tr>
                    <th>Item Details *</th>
                    <th>Part No</th>
                    <th>Qty *</th>
                    <th>YESNO</th>
                    <th>Amount*</th>
                    <th>Expected<br />Delivery</th>
                    <th>Status</th>
                    <th>Item<br />Remarks</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $icnt = count($quote_items); @endphp

                @forelse($quote_items as $x => $item)
                @php
                $item_total = $item->total_after_discount;
                if($currency_rate){
                $item_total = $item_total * $currency_rate->standard_rate;
                }
                @endphp
                <tr valign="top" id="row-p{{$item->item_id}}" data-index="{{$x}}">
                    <td width="15%">
                        <b>{{$item->supplier->brand}}</b><br />
                        <input type="hidden" name="item[{{$x}}][product_id]" value="{{$item->item_id}}" />
                        <textarea class="form-control" name="item[{{$x}}][item_name]" placeholder="Item" readonly>{{isset($item->description)? $item->description : ''}}</textarea>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="item[{{$x}}][partno]" placeholder="Part No" value="{{isset($item->product)? $item->product->part_number : ''}}" />
                    </td>
                    <td>
                        <input type="number" class="form-control quantity" name="item[{{$x}}][quantity]" value="{{$item->quantity}}" placeholder="Quantity" readonly />
                    </td>
                    <td>
                        <input type="text" class="form-control" name="item[{{$x}}][yes_number]" placeholder="YesNo." />
                    </td>
                    <td width="12%">
                        <label class="text-primary small">Selling Price (AED)</label>
                        <input type="text" class="form-control" name="item[{{$x}}][total_amount]" value="{{$item_total}}" placeholder="Total Amount" readonly />
                        <div class="purchase mt-3">
                            @if(isset($item->buying_currency) && $item->buying_currency != '')
                            <label class="text-primary small">Buying Price ({{$item->buying_currency}})</label>
                            @else
                            <label class="text-primary small">Buying Price <span class="buying_currency"></span></label>
                            @endif
                            <a href="javascript:void(0);" class="b-price-add btn btn-primary btn-sm mt-1" data-pid="{{$item->item_id}}" data-bs-toggle="modal" data-bs-target="#addpurchase"> <i class="fas fa-plus"></i> ADD</a>
                            @if(isset($item->buying_price) && $item->buying_price != 0)
                            @php
                            $buying_linetotal = ($item->buying_price * $item->quantity);
                            @endphp
                            <input type="hidden" class="form-control" name="item[{{$x}}][buying_currency]" value="{{$item->buying_currency}}" />
                            <input type="hidden" class="form-control" name="item[{{$x}}][buying_unit_price]" value="{{ $item->buying_price }}" readonly />
                            <input type="text" class="form-control" name="item[{{$x}}][buying_price]" value="{{ $buying_linetotal }}" readonly />
                            @endif

                        </div>
                    </td>
                    <td width="8%">
                        <input type="text" class="form-control datepick" name="item[{{$x}}][expected_delivery]" placeholder="Expected Delivery" />
                    </td>
                    <td width="10%">
                        <select class="form-control" name="item[{{$x}}][status]" id="status">
                            <option value="0">Not Delivered</option>
                            <option value="1">Delivered</option>
                        </select>
                    </td>
                    <td width="15%">
                        <textarea rows="2" id="remarks" name="item[{{$x}}][remarks]" placeholder="Remarks" class="form-control"></textarea>
                    </td>
                    <td></td>
                </tr>
                @empty

                @endforelse
            </tbody>
        </table>


    </div>
</div>
<div class="row mb-2">
    <div class="col-3"></div>
    <div class="col-6">

        <!-- <a href="javascript:void(0);" onclick="skipStep2();" class="ms-2">Skip here</a> -->
        <button type="button" class="btn btn-default prev-step m-2"><i class="fa fa-chevron-left"></i> Back</button>
        <!-- <button type="submit" id="order_client_draft" class="btn btn-secondary m-2" value="save-step2-draft">Draft & Continue</button> -->
        <button type="submit" id="order_delivery_details_button" class="btn btn-success m-2" value="save-step2">Save & Continue</button>

        <button type="button" class="btn btn-default next-step m-2">Next <i class="fa fa-chevron-right"></i></button>
    </div>
    <div class="col-3"></div>
</div>

{!! Form::close() !!}

<!-- Add New Product -->
<div class="modal fade" id="addpurchase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="padding: 16px;">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add Buying Price</h5>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" name="product_id" id="product_id" />

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Buying Currency<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="buying_currency" id="buying_currency" required>
                                    <option value="">-Select Currency-</option>
                                    @foreach($currencies as $currency)
                                    <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Please select a currency.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Gross Price<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="gross_price" id="gross_price" required />

                                <div class="invalid-feedback">Please enter a gross price.</div>
                            </div>
                        </div>
                        <div class="col-md-4" id="purchase_discount_percent">
                            <div class="form-group">
                                <label class="form-label">Purchase Discount(%)</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="number" name="discount" id="purchase_discount">
                                <div class="invalid-feedback">Please enter a purchase discount.</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4" id="purchase_discount_price">
                            <div class="form-group">
                                <label class="form-label">Purchase Discount Amount<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="discount_amount" id="purchase_discount_amount" required />
                                <div class="invalid-feedback">Please enter a purchase discount price.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Buying Price<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="buying_price" id="buying_price" required />
                                <div class="invalid-feedback">Please enter a buying price.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="purchase_price_duration" id="purchasedurationSelect">
                                    <option value="">Select</option>
                                    <option value="1" selected>1 Month</option>
                                    <option value="3">3 Month</option>
                                    <option value="6">6 Month</option>
                                    <option value="9">9 Month</option>
                                    <option value="12">12 Month</option>
                                </select>
                                <div class="invalid-feedback">Please choose a date.</div>
                            </div>

                            <div class="form-group small" id="purchasedateRangeGroup" style="display: none;">
                                <label class="form-label">Dates:</label>
                                <span id="purchasedateRange"></span>
                                <input type="hidden" name="validity_from" id="validity_from" />
                                <input type="hidden" name="validity_to" id="validity_to" />
                            </div>
                        </div>
                    </div>



                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveProduct">Save</button>
                    </div>
            </div>
            </form>

        </div>

    </div>

</div>

@include('orders.partials._newLocalProduct')