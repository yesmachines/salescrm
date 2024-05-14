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
  <input type="hidden" name="order_id" id="order_id_step2" value="{{$order->id}}" />

  <div class="row mb-2">
    <div class="col-4">
      <h6>Material Status *</h6>
    </div>
    <div class="col-6">
      <select class="form-control" name="material_status" id="material_status" required>
        <option value="">Select Status</option>
        <option value="is_stock" {{($order->material_status == 'is_stock')? 'selected': ''}}>Is Stock</option>
        <option value="out_stock" {{($order->material_status == 'out_stock')? 'selected': ''}}>Out Stock</option>
      </select>
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Material Details *</h6>
    </div>
    <div class="col-6">
      <textarea rows="2" id="material_details" name="material_details" class="form-control" required>{{$order->material_details}}</textarea>
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Installations & Training</h6>
    </div>
    <div class="col-6">
      <input type="text" id="installation_training" name="installation_training" class="form-control" value="{{ $deliveryPoints->installation_training ?? '' }}" />
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Service Experts</h6>
    </div>
    <div class="col-6">
      <input type="text" id="service_expert" name="service_expert" class="form-control" value="{{ $deliveryPoints->service_expert ?? '' }}" />
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Estimated Installation</h6>
    </div>
    <div class="col-6">
      <input type="text" id="estimated_installation" name="estimated_installation" class="form-control" value="{{ $deliveryPoints->estimated_installation ?? '' }}" />
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Delivery Point Address *</h6>
    </div>
    <div class="col-6">
      <input type="text" id="delivery_address" name="delivery_address" class="form-control" value="{{ $deliveryPoints->	delivery_address ?? '' }}" />
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Delivery Contact Person *</h6>
    </div>
    <div class="col-6">
      <input type="text" id="contact_person" name="contact_person" class="form-control" value="{{ $deliveryPoints->contact_person ?? '' }}" />
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Delivery Contact Email</h6>
    </div>
    <div class="col-6">
      <input type="text" id="contact_email" name="contact_email" class="form-control" value="{{ $deliveryPoints->contact_email ?? '' }}" />
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Delivery Contact Phone *</h6>
    </div>
    <div class="col-6">
      <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="{{ $deliveryPoints->contact_phone ?? '' }}" />
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Delivery Remarks</h6>
    </div>
    <div class="col-6">
      <textarea name="delivery_remarks" class="form-control">{{ $deliveryPoints->remarks ?? '' }}</textarea>
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Order Conversion on basis of demo?</h6>
    </div>
    <div class="col-6">
      <label><input type="radio" name="is_demo" value="1" {{ ($deliveryPoints->is_demo == 1)? 'checked': '' }}> YES</label>&nbsp; &nbsp;
      <label><input type="radio" name="is_demo" value="0" {{ ($deliveryPoints->is_demo == 0)? 'checked': '' }}> NO</label>
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
        <option value="{{$emp->user_id}}" {{$deliveryPoints->demo_by == $emp->user_id? "selected": ""}}>{{$emp->user->name}}</option>
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
      <h6> Order Items
        <!-- <a href="javascript:void(0);" class="addIT btn btn-primary btn-sm" style="float: right;"><i data-feather="plus"></i> Add Row</a> -->
      </h6>
      <table class="table form-table" id="itemcustomFields">
        <thead>
          <tr>
            <th>Item Details *</th>
            <th>Part No</th>
            <th>Qty *</th>
            <th>YesNo.</th>
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
          <tr valign="top">
            <td width="15%">
              <input type="hidden" name="item[{{$x}}][product_id]" value="{{$item->product_id}}" />
              <span class="text-warning">{{isset($item->product)? $item->product->modelno : ''}} / {{isset($item->product->supplier)? $item->product->supplier->brand : ''}}</span>
              <textarea class="form-control" name="item[{{$x}}][item_name]" placeholder="Item">{{$item->item_name}}</textarea>
            </td>
            <td>
              <input type="text" class="form-control" name="item[{{$x}}][partno]" placeholder="Part No" value="{{$item->partno}}" />
            </td>
            <td>
              <input type="number" class="form-control" name="item[{{$x}}][quantity]" value="{{$item->quantity}}" placeholder="Quantity" />
            </td>
            <td>
              <input type="text" class="form-control" name="item[{{$x}}][yes_number]" placeholder="YesNo." value="{{$item->yes_number}}" />
            </td>
            <td>
              <input type="number" class="form-control" name="item[{{$x}}][total_amount]" value="{{$item->total_amount}}" placeholder="Total Amount" />
            </td>
            <td width="8%">
              <input type="text" class="form-control datepick" name="item[{{$x}}][expected_delivery]" placeholder="Expected Delivery" value="{{$item->expected_delivery}}" />
            </td>
            <td width="10%">
              <select class="form-control" name="item[{{$x}}][status]" id="status">
                <option value="0" @if($item->status == 0) selected @endif>Not Delivered</option>
                <option value="1" @if($item->status == 1) selected @endif>Delivered</option>
              </select>
            </td>
            <td width="15%">
              <textarea rows="2" id="remarks" name="item[{{$x}}][remarks]" placeholder="Remarks" class="form-control">{{$item->remarks}}</textarea>
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
              <input type="number" class="form-control" name="item[{{$icnt}}][total_amount]" placeholder="Total Amount" />
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