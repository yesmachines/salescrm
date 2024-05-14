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
<form name="add_delivery_item" id="add_delivery_item">
    <input type="hidden" name="order_id" id="order_id_step2" />

    <div class="row mb-2">
        <div class="col-4">
            <h6>Material Status *</h6>
        </div>
        <div class="col-6">
            <select class="form-control" name="material_status" id="material_status" required>
                <option value="">-Select Status-</option>
                <option value="is_stock" {{($quote_avail && ($quote_avail->stock_status == 'is_stock'))? 'selected': ''}}>Is Stock</option>
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
            <textarea rows="2" id="material_details" name="material_details" class="form-control" required>@if($quote_avail)Material will delivery within {{$quote_avail->working_weeks}} working {{$quote_avail->working_period}} {{$quote_avail->working_weeks > 1? 's': ''}}@endif</textarea>
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
                        <th>Amount (AED) *</th>
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
                    <tr valign="top">
                        <td width="15%">
                            <input type="hidden" name="item[{{$x}}][product_id]" value="{{$item->item_id}}" />
                            <span class="text-warning">{{isset($item->product)? $item->product->modelno : ''}} / {{$item->brand}}</span>
                            <textarea class="form-control" name="item[{{$x}}][item_name]" placeholder="Item">{{$item->brand}} {{isset($item->product)? $item->product->modelno : ''}} {{isset($item->description)? $item->description : ''}}</textarea>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="item[{{$x}}][partno]" placeholder="Part No" value="{{isset($item->product)? $item->product->part_number : ''}}" />
                        </td>
                        <td>
                            <input type="number" class="form-control" name="item[{{$x}}][quantity]" value="{{$item->quantity}}" placeholder="Quantity" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="item[{{$x}}][yes_number]" placeholder="YesNo." />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="item[{{$x}}][total_amount]" value="{{$item_total}}" placeholder="Total Amount" />
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
                    <tr valign="top">
                        <td width="15%">
                            <input type="hidden" name="item[{{$icnt}}][product_id]" />
                            <textarea class="form-control" name="item[{{$icnt}}][item_name]" placeholder="Item"></textarea>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="item[{{$icnt}}][partno]" placeholder="Part No" />
                        </td>
                        <td>
                            <input type="number" class="form-control" name="item[{{$icnt}}][quantity]" placeholder="Quantity" />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="item[{{$icnt}}][yes_number]" placeholder="YesNo." />
                        </td>
                        <td>
                            <input type="text" class="form-control" name="item[{{$icnt}}][total_amount]" placeholder="Total Amount" />
                        </td>
                        <td width="8%">
                            <input type="text" class="form-control datepick" name="item[{{$icnt}}][expected_delivery]" placeholder="Expected Delivery" />
                        </td>
                        <td width="10%">
                            <select class="form-control" name="item[{{$icnt}}][status]" id="status">
                                <option value="0">Not Delivered</option>
                                <option value="1">Delivered</option>
                            </select>
                        </td>
                        <td width="15%">
                            <textarea rows="2" id="remarks" name="item[{{$icnt}}][remarks]" placeholder="Remarks" class="form-control"></textarea>
                        </td>
                        <td></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
    </div>
    <div class="row mb-2">
        <div class="col-4"></div>
        <div class="col-4">

            <!-- <a href="javascript:void(0);" onclick="skipStep2();" class="ms-2">Skip here</a> -->
            <button type="button" class="btn btn-default prev-step m-2"><i class="fa fa-chevron-left"></i> Back</button>
            <button type="submit" id="order_delivery_details_button" class="btn btn-success m-2">Save & Continue</button>
            <button type="button" class="btn btn-default next-step m-2">Next <i class="fa fa-chevron-right"></i></button>
        </div>
        <div class="col-4"></div>
    </div>
</form>