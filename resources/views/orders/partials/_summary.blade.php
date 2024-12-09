<h5>Order Summary Details</h5>
<p>Please enter the below Order basic informations like PO/OS dates, delivery terms, payment terms etc between customer to YES:</p>
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

<input type="hidden" name="customer_id" value="{{$quotation->customer_id}}" />
<input type="hidden" name="company_id" value="{{$quotation->company_id}}" />
<input type="hidden" name="order_for" value="{{$quotation->quote_from}}" />
<input type="hidden" name="quotation_id" value="{{$quotation->id}}" />
<input type="hidden" name="currency" value="aed" />
<input type="hidden" name="created_by" value="{{$quotation->assigned_to}}" />

<div class="row my-4">
    <div class="col-xxl-12">
        <div class="row mb-2">
            <div class="col-4">
                <h6>Currency Conversion Rate</h6>
            </div>
            <div class="col-6">
                Quotation currency is <span class="text-info">{{$quotation->prefered_currency? $quotation->prefered_currency: 'aed'}}</span>, the conversion rate to AED is <span class="text-info">{{isset($currency_rate->standard_rate)?$currency_rate->standard_rate: 1 }}</span>
                <!-- <input type="number" disabled id="currency_conversion" name="currency_conversion" class="form-control" value="{{isset($currency_rate->standard_rate)?$currency_rate->standard_rate: 1 }}" required> -->
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>OS Date *</h6>
            </div>
            <div class="col-6">
                <input type="text" id="os_date" name="os_date" class="form-control todaydatepick" required>
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
                <input type="text" id="po_number" name="po_number" class="form-control">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>PO Date</h6>
            </div>
            <div class="col-6">
                <input type="text" id="po_date" name="po_date" class="form-control datepick" value="">
            </div>
            <div class="col-2"></div>
        </div>

        <div class="row mb-2">
            <div class="col-4">
                <h6>PO Received Date</h6>
            </div>
            <div class="col-6">
                <input type="text" id="po_received" class="form-control datepick" name="po_received" value="">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Selling Price (AED) To Client *</h6>
            </div>
            <div class="col-6">
                <input type="text" data-val="{{$quotation->total_amount}}" class="form-control" id="selling_price" required name="selling_price" value="{{$selling_price}}">
                <p class="small">Quoted Amount = {{$quotation->total_amount}} {{$quotation->prefered_currency? $quotation->prefered_currency: 'aed'}}</p>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Price Basis *</h6>
            </div>
            <div class="col-6">
                <input type="text" readonly name="price_basis" id="price_basis" class="form-control" value="aed" />
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
                    <option value="{{$term->short_code}}" {{$quotation->price_basis == $term->short_code? "selected": ""}}>{{$term->title}}</option>
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
                <textarea rows="2" id="promised_delivery" name="promised_delivery" class="form-control">@if($quote_avail){{$quote_avail->working_weeks}} {{$quote_avail->working_period}}{{$quote_avail->working_weeks > 1? 's': ''}} @endif</textarea>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Order Status *</h6>
            </div>
            <div class="col-6">
                <select class="form-control" name="status" id="status" required>
                    <option value="">Select Status</option>
                    <option value="open" selected>Open</option>
                    <option value="partial">Partial</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row m-2">
            <div class="col-12">

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
                            <th>Payment Term *</th>
                            <th>Expected Date</th>
                            <th>Status</th>
                            <th>Payment Remarks</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($quote_clientpayment as $k => $cpayment)
                        <tr valign="top">
                            <td width="30%">
                                <textarea class="form-control" name="clientpayment[{{$k}}][payment_term]" placeholder="Payment Term">{{$cpayment->payment_amount}} {{$cpayment->title}}</textarea>
                            </td>
                            <td width="20%">
                                <input type="text" class="form-control datepick" name="clientpayment[{{$k}}][expected_date]" placeholder="Expected Date" />
                            </td>
                            <td width="20%">
                                <select class="form-control" name="clientpayment[{{$k}}][status]">
                                    <option value="0">Not Received</option>
                                    <option value="1">Received</option>
                                    <option value="2">Partially Received</option>
                                </select>
                            </td>
                            <td width="30%">
                                <textarea rows="2" name="clientpayment[{{$k}}][remarks]" placeholder="Remarks" class="form-control"></textarea>
                            </td>
                            <td></td>
                        </tr>
                        @empty
                        <tr valign="top">
                            <td width="30%">
                                <textarea class="form-control" name="clientpayment[0][payment_term]" placeholder="Payment Term"></textarea>
                            </td>
                            <td width="20%">
                                <input type="text" class="form-control datepick" name="clientpayment[0][expected_date]" placeholder="Expected Date" />
                            </td>
                            <td width="20%">
                                <select class="form-control" name="clientpayment[0][status]">
                                    <option value="0">Not Received</option>
                                    <option value="1">Received</option>
                                    <option value="2">Partially Received</option>
                                </select>
                            </td>
                            <td width="30%">
                                <textarea rows="2" name="clientpayment[0][remarks]" placeholder="Remarks" class="form-control"></textarea>
                            </td>
                            <td></td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row mb-2 mt-4">
            <div class="col-3"></div>
            <div class="col-6">
                <!-- <button type="submit" id="order_details_draft" class="btn btn-secondary m-2" value="save-step1-draft">Draft & Continue</button> -->
                <button type="submit" id="order_details_button" class="btn btn-success m-2" value="save-step1">Save & Continue</button>
                <!-- <a href="javascript:void(0);" onclick="skipStep1();" class="">Skip here</a> -->
                <button type="button" class="btn btn-default next-step m-2">Next <i class="fa fa-chevron-right"></i></button>
            </div>
            <div class="col-3"></div>
        </div>
    </div>

</div>

{!! Form::close() !!}