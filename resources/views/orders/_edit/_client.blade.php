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
        <option value="is_stock" {{($order->material_status == 'is_stock')? 'selected': ''}}>In Stock</option>
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
          <tr valign="top" id="row-p{{$item->product_id}}" data-index="{{$x}}">
            <td width="15%">
              <b> {{$item->product->supplier->brand}}</b>
              <input type="hidden" name="item[{{$x}}][product_id]" value="{{$item->product_id}}" />
              <textarea class="form-control" name="item[{{$x}}][item_name]" placeholder="Item">{{$item->item_name}}</textarea>

            </td>
            <td>
              <input type="text" class="form-control" name="item[{{$x}}][partno]" placeholder="Part No" value="{{$item->partno}}" />
            </td>
            <td>
              <input type="number" class="form-control quantity" name="item[{{$x}}][quantity]" value="{{$item->quantity}}" placeholder="Quantity" />
            </td>
            <td>
              <input type="text" class="form-control" name="item[{{$x}}][yes_number]" placeholder="YesNo." value="{{$item->yes_number}}" />
            </td>
            <td width="12%">
              <label class="text-primary small">Selling Price (AED)</label>
              <input type="number" class="form-control" name="item[{{$x}}][total_amount]" value="{{$item->total_amount}}" step="any" placeholder="Total Amount" />
              <div class="purchase mt-3">
                @if(isset($item->buying_currency) && $item->buying_currency != '')
                <label class="text-primary small">Buying Price ({{$item->buying_currency}})</label>
                @else
                <label class="text-primary small">Buying Price <span class="buying_currency"></span></label>
                @endif
                @if(isset($item->buying_price) && $item->buying_price != 0)
                <input type="hidden" class="form-control" name="item[{{$x}}][buying_currency]" value="{{$item->buying_currency}}" />
                <input type="text" class="form-control" name="item[{{$x}}][buying_price]" value="{{$item->buying_price}}" readonly />
                @else
                <a href="javascript:void(0);" class="b-price-add btn btn-primary btn-sm" data-pid="{{$item->product_id}}" data-bs-toggle="modal" data-bs-target="#addpurchase"> <i class="fas fa-plus"></i> ADD</a>
                @endif
              </div>
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
      <button type="submit" id="order_client_draft" class="btn btn-secondary m-2" value="save-step2-draft">Draft & Continue</button>
      <button type="submit" id="order_delivery_details_button" class="btn btn-success m-2">Save & Continue</button>
      <button type="button" class="btn btn-default next-step m-2">Next <i class="fa fa-chevron-right"></i></button>
    </div>
    <div class="col-3"></div>
  </div>
</form>
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