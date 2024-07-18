<h5>YES to Supplier</h5>
<p>Please refer the below Supplier delivery, payment terms, additional charges etc:</p>
<br>
<div class="row">
  <div class="col-12">
    <div class="supplier_error_msg"></div>
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
<form name="add_supplier_details" id="add_supplier_details">
  <input type="hidden" name="order_id" id="order_id_step3" value="{{$order->id}}" />
  <div class="row mb-2">
    <div class="col-4">
      <h6>Buying Price (AED) From Supplier *</h6>
    </div>
    <div class="col-6">
      <input type="number" class="form-control" id="buying_price" name="buying_price" step="any" value="{{ $order->buying_price ?? '' }}">
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-4">
      <h6>Projected Margin (AED) *</h6>
    </div>
    <div class="col-6">
      <input type="number" class="form-control" id="projected_margin" name="projected_margin" value="{{ $order->projected_margin ?? '' }}">
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
        <!-- <a href="javascript:void(0);" class="addST btn btn-primary btn-sm" style="float: right;">
        <i data-feather="plus"></i> Add Row</a> -->
      </h6>
      <table class="table form-table" id="supplierFields">
        <thead>
          <tr>
            <th>Supplier *</th>
            <th>Price Basis *</th>
            <th>Delivery Term *</th>
            <th>Supplier Remarks</th>
          </tr>
        </thead>
        <tbody>
          @foreach($suppliers as $y => $sup)
          <tr valign="top">
            <td width="30%">
              <input type="hidden" class="form-control" value="{{$sup->country_id}}" name="supplier[{{$y}}][country_id]" />
              <input type="text" readonly class="form-control" value="{{$sup->supplier->brand}}" name="supplier[{{$y}}][supplier_name]" />
              <input type="hidden" class="form-control" value="{{$sup->supplier_id}}" name="supplier[{{$y}}][supplier_id]" />
            </td>
            <td width="20%">
              <select class="form-control" name="supplier[{{$y}}][price_basis]">
                <option value="">-Select-</option>
                @foreach($currencies as $cur)
                <option value="{{$cur->code}}" @if($sup->price_basis == $cur->code) selected @endif>{{strtoupper($cur->code)}}</option>
                @endforeach
              </select>
            </td>
            <td width="20%">
              <select class="form-control" name="supplier[{{$y}}][delivery_term]">
                <option value="">-Select-</option>
                @foreach($terms as $term)
                <option value="{{$term->short_code}}" @if($sup->delivery_term == $term->short_code) selected @endif>{{$term->title}}</option>
                @endforeach
              </select>
            </td>
            <td width="30%">
              <textarea rows="2" name="supplier[{{$y}}][remarks]" placeholder="Supplier Remarks" class="form-control">{{$sup->remarks}}</textarea>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="row mb-2">
    <div class="col-12">
      <div class="separator"></div>
    </div>
  </div>
  <div class="row mb-2">
    <div class="col-xxl-12">
      <h6> <b>Payment Terms of Suppliers</b>
        <a href="javascript:void(0);" class="addST btn btn-primary btn-sm" style="float: right;">
          <i data-feather="plus"></i> Add Row</a>
      </h6>
      <input type="hidden" name="section_type" value="supplier" />
      <table class="table form-table" id="supplierpaymentFields">
        <thead>
          <tr>
            <th>Payment Term *</th>
            <th>Expected Date</th>
            <th>Status *</th>
            <th>Payment Remarks</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($paymentTermsSupplier as $key => $value)
          <tr valign="top">
            <td width="30%">
              <input type="hidden" name="supplierpayment[{{$key}}][payment_id]" value="{{$value->id}}" />
              <textarea class="form-control" name="supplierpayment[{{$key}}][payment_term]" placeholder="Payment Term">{{$value->payment_term}}</textarea>
            </td>
            <td width="20%">
              <input type="text" class="form-control datepick" name="supplierpayment[{{$key}}][expected_date]" placeholder="Expected Date" value="{{$value->expected_date}}" />
            </td>
            <td width="20%">
              <select class="form-control" name="supplierpayment[{{$key}}][status]">
                <option value="0" @if($value->status == 0) selected @endif>Not Done</option>
                <option value="1" @if($value->status == 1) selected @endif>Done</option>
                <option value="2" @if($value->status == 2) selected @endif>Partially Done</option>
              </select>
            </td>
            <td width="30%">
              <textarea rows="2" name="supplierpayment[{{$key}}][remarks]" placeholder="Remarks" class="form-control">
                  @if($value->remarks)
                  {{$value->remarks}}
                  @endif
                </textarea>
            </td>
            <td><a href="javascript:void(0);" class="remST" title="DELETE ROW" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  <div class="row mb-2">
    <div class="col-12">
      <div class="separator"></div>
    </div>
  </div>
  <div class="row mb-2">
    <div class="col-xxl-12">
      <h6> <b>Additional Charges</b>
        <input type="hidden" name="isadditionalcharges" value="{{$customProductType}}">

        <a href="javascript:void(0);" class="addAC btn btn-primary btn-sm" style="float: right;">
          <i data-feather="plus"></i> Add Row</a>
      
      </h6>
      <table class="table form-table" id="chargespaymentFields">
        <thead>
          <tr>
            <th>Additional Items *</th>
            <th>Considered Charges(AED) *</th>
            <th>Remarks</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($orderCharges as $key => $value)
          <tr valign="top">
            <td width="30%">
              <input type="hidden" name="charges[{{$key}}][charge_id]" value="{{$value->id}}" />
              <input type="text" class="form-control" name="charges[{{$key}}][title]" placeholder="Packing Charge" value="{{ $value->title ?? '' }}" />
            </td>
            <td width="20%">
              <input type="number" class="form-control" name="charges[{{$key}}][considered]" placeholder="Considered Cost"  step="any" value="{{ $value->considered ?? '' }}" />
            </td>
            <td width="30%">
              <textarea rows="2" name="charges[{{$key}}][remarks]" placeholder="Remarks" class="form-control">{{ $value->remarks ?? '' }}</textarea>
            </td>
            <td><a href="javascript:void(0);" class="remAC" title="DELETE ROW" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a></td>
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
      <button type="submit" id="order_delivery_details_button m-2" class="btn btn-success" value="save">Save & Finish</button>
      <!-- <button type="submit" id="order_summary_download m-2" class="btn btn-primary" value="save-os">Save & Download OS</button> -->
    </div>
    <div class="col-4"></div>
  </div>
</form>
