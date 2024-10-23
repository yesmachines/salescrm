<h5>Order Summary Details</h5>
<p>Please refer the below Order, delivery and payment information :</p>
<br>
<div class="row">
    <div class="col-12">
        <div class="summary_error_msg"></div>
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
{!! Form::open(array('id' => 'frmosummary', 'name' => 'frmosummary')) !!}
<input type="hidden" name="order_id" value="{{$order->id}}" />
<input type="hidden" name="customer_id" value="{{$quotation->customer_id}}" />
<input type="hidden" name="company_id" value="{{$quotation->company_id}}" />
<input type="hidden" name="quotation_id" value="{{$quotation->id}}" />
<input type="hidden" name="currency" value="aed" />
<input type="hidden" name="created_by" value="{{$quotation->assigned_to}}" />

<div class="row my-4">
    <div class="col-xxl-12">
        <div class="row mb-2">
            <div class="col-4">
                <h6>OS Date *</h6>
            </div>
            <div class="col-6">
                <input type="text" id="os_date" name="os_date" class="form-control todaydatepick" value="{{$order->os_date}}">
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
                <input type="text" id="po_number" name="po_number" class="form-control" value="{{$order->po_number }}">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>PO Date</h6>
            </div>
            <div class="col-6">
                <input type="text" id="po_date" name="po_date" class="form-control datepick" value="{{$order->po_date }}">
            </div>
            <div class="col-2"></div>
        </div>

        <div class="row mb-2">
            <div class="col-4">
                <h6>PO Received Date</h6>
            </div>
            <div class="col-6">
                <input type="text" id="po_received" class="form-control datepick" name="po_received" value="{{$order->po_received }}">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Selling Price (AED) To Client *</h6>
            </div>
            <div class="col-6">
                <input type="number" class="form-control" id="selling_price" step="any" required name="selling_price" value="{{$order->selling_price}}">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Price Basis/Term *</h6>
            </div>
            <div class="col-6">
                <input type="text" readonly name="price_basis" id="price_basis" class="form-control" value="{{$deliveryPoints->price_basis}}" />
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Delivery Term *</h6>
            </div>
            <div class="col-6">
                <select class="form-control" name="delivery_term" id="delivery_term" required>
                    <option value="">-Select-</option>
                    @foreach($terms as $term)
                    <option value="{{$term->short_code}}" {{$deliveryPoints->delivery_term == $term->short_code? "selected": ""}}>{{$term->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Promised Delivery Time</h6>
            </div>
            <div class="col-6">
                <textarea rows="2" id="promised_delivery" name="promised_delivery" class="form-control">{{$deliveryPoints->promised_delivery}}</textarea>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Order Status *</h6>
            </div>
            <div class="col-6">
                <select class="form-control" name="status" id="status">
                    <option seleceted disabled>Please select a status</option>
                    <option value="open" {{ $order->status=="open" ? 'selected' : '' }}>Open</option>
                    <option value="partial" {{ $order->status=="partial" ? 'selected' : '' }}>Partial</option>
                    <option value="closed" {{ $order->status=="closed" ? 'selected' : '' }}>Closed</option>
                </select>
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
                <h6> <b>Payment Terms of Customer</b>
                    <a href="javascript:void(0);" class="addPT btn btn-primary btn-sm" style="float: right;">
                        <i data-feather="plus"></i> Add Row</a>
                </h6>
                <input type="hidden" name="section_type" id="section_type" value="client" />
                <table class="table form-table" id="paymentcustomFields">
                    <thead>
                        <tr>
                            <th>Payment Term</th>
                            <th>Expected Date</th>
                            <th>Status</th>
                            <th>Payment Remarks</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentTermsClient as $key => $value)
                        <tr valign="top">
                            <td width="30%">
                                <input type="hidden" name="clientpayment[{{$key}}][payment_id]" value="{{$value->id}}" />
                                <textarea class="form-control" name="clientpayment[{{$key}}][payment_term]" placeholder="Payment Term">{{$value->payment_term}}</textarea>
                            </td>
                            <td width="20%">
                                <input type="text" class="form-control datepick" name="clientpayment[{{$key}}][expected_date]" placeholder="Expected Date" value="{{$value->expected_date}}" />
                            </td>
                            <td width="20%">
                                <select class="form-control" name="clientpayment[{{$key}}][status]">
                                    <option value="0" @if($value->status == 0) selected @endif>Not Done</option>
                                    <option value="1" @if($value->status == 1) selected @endif>Done</option>
                                    <option value="2" @if($value->status == 2) selected @endif>Partially Done</option>
                                </select>
                            </td>
                            <td width="30%">
                                <textarea rows="2" name="clientpayment[{{$key}}][remarks]" placeholder="Remarks" class="form-control">  @if($value->remarks)
                                  {{$value->remarks}}
                                  @endif</textarea>
                            </td>
                            <td><a href="javascript:void(0);" class="remPT" title="DELETE ROW" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mb-2 mt-4">
            <div class="col-4"></div>
            <div class="col-4">
                <button type="submit" id="order_details_draft" class="btn btn-secondary m-2" value="save-step1-draft">Draft & Continue</button>
                <button type="submit" id="order_details_button" class="btn btn-success m-2">Save & Continue</button>
                <!-- <a href="javascript:void(0);" onclick="skipStep1();" class="">Skip here</a> -->
                <button type="button" class="btn btn-default next-step m-2">Next <i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="col-4"></div>
        </div>
    </div>

</div>

{!! Form::close() !!}