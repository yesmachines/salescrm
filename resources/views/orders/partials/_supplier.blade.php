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
    <input type="hidden" name="order_id" id="order_id_step4" />
    <div class="row mb-2">
        <div class="col-4">
            <h6>Buying Price (AED) From Supplier *</h6>
        </div>
        <div class="col-6">
            <input type="text" class="form-control" id="buying_price_total" value="{{$buying_price}}" name="buying_price_total" step="any" required>
        </div>
        <div class="col-2"></div>
    </div>
    <div class="row mb-2">
        <div class="col-4">
            <h6>Projected Margin (AED) *</h6>
        </div>
        <div class="col-6">
            <input type="text" class="form-control" id="projected_margin" name="projected_margin" value="{{$margin_price}}" required>
            <!-- <input type="hidden" class="form-control" id="actual_margin" value="{{$margin_price}}"> -->
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
            <h6> <b>Supplier & Delivery Terms</b>

            </h6>
            <table class="table form-table" id="supplierFields">
                <thead>
                    <tr>
                        <th>Supplier *</th>
                        <th>Currency *</th>
                        <th>Delivery Term *</th>
                        <th>Supplier Remarks</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $y => $sup)
                    <tr valign="top" id="row-sp{{$sup->id}}" data-index="{{$y}}">
                        <td width="30%">
                            <input type="hidden" class="form-control" value="{{$sup->country_id}}" name="supplier[{{$y}}][country_id]" />
                            <input type="text" readonly class="form-control" value="{{$sup->brand}}" name="supplier[{{$y}}][supplier_name]" />
                            <input type="hidden" class="form-control" value="{{$sup->id}}" name="supplier[{{$y}}][supplier_id]" />
                        </td>
                        <td width="20%">
                            <select class="form-control" name="supplier[{{$y}}][price_basis]">
                                <option value="">-Select-</option>
                                @foreach($currencies as $cur)
                                <option value="{{$cur->code}}">{{strtoupper($cur->code)}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td width="20%">
                            <select class="form-control" name="supplier[{{$y}}][delivery_term]">
                                <option value="">-Select-</option>
                                @foreach($terms as $term)
                                <option value="{{$term->short_code}}">{{$term->title}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td width="30%">
                            <textarea rows="2" name="supplier[{{$y}}][remarks]" placeholder="Supplier Remarks" class="form-control"></textarea>
                        </td>
                        <td></td>
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
                    <tr valign="top">
                        <td width="30%">
                            <textarea class="form-control" name="supplierpayment[0][payment_term]" placeholder="Payment Term"></textarea>
                        </td>
                        <td width="20%">
                            <input type="text" class="form-control datepick" name="supplierpayment[0][expected_date]" placeholder="Expected Date" />
                        </td>
                        <td width="20%">
                            <select class=" form-control" name="supplierpayment[0][status]">
                                <option value="0">Not Done</option>
                                <option value="1">Done</option>
                                <option value="2">Partially Done</option>
                            </select>
                        </td>
                        <td width="30%">
                            <textarea rows="2" name="supplierpayment[0][remarks]" placeholder="Remarks" class="form-control"></textarea>
                        </td>
                        <td></td>
                    </tr>
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
                @if($customProductType)
                <a href="javascript:void(0);" class="addAC btn btn-primary btn-sm" style="float: right;">
                    <i data-feather="plus"></i> Add Row</a>
                @endif
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
                    @php $ix = 0; @endphp
                    @if($quotation->is_vat == 1)
                    @php
                    $vat_amount = $quotation->vat_amount;
                    if($currency_rate){
                    $vat_amount = $vat_amount * $currency_rate->standard_rate;
                    }
                    @endphp
                    <tr valign="top">
                        <td width="30%">
                            <input type="text" class="form-control" name="charges[{{$ix}}][title]" placeholder="Packing Charge" value="VAT" />
                        </td>
                        <td width="20%">
                            <input type="text" class="form-control" name="charges[{{$ix}}][considered]" placeholder="Considered Cost" step="any" value="{{$vat_amount}}" />
                        </td>
                        <td width="30%">
                            <textarea rows="2" name="charges[{{$ix}}][remarks]" placeholder="Remarks" class="form-control"></textarea>
                        </td>
                        <td></td>
                    </tr>
                    @php ++$ix; @endphp
                    @endif

                    @forelse($quote_charges as $k => $charge)

                    @php
                    $ix = $ix + $k;
                    $addon_amount = $charge->amount;
                    if($currency_rate){
                    $addon_amount = $addon_amount * $currency_rate->standard_rate;
                    }
                    @endphp
                    <tr valign="top">
                        <td width="30%">
                            <input type="text" class="form-control" name="charges[{{$ix}}][title]" placeholder="Packing Charge" value="{{$charge->title}}" />
                        </td>
                        <td width="20%">
                            <input type="text" class="form-control" name="charges[{{$ix}}][considered]" placeholder="Considered Cost" value="{{$addon_amount}}" />
                        </td>
                        <td width="30%">
                            <textarea rows="2" name="charges[{{$ix}}][remarks]" placeholder="Remarks" class="form-control"></textarea>
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
            <button type="button" class="btn btn-default prev-step m-2"><i class="fa fa-chevron-left"></i> Back</button>
            <button type="submit" id="order_delivery_draft m-2" class="btn btn-secondary" value="save-step4-draft">Save as Draft</button>
            <button type="submit" id="order_delivery_details_button m-2" class="btn btn-success" value="save-os">Save & Finish</button>
            <button type="submit" id="save_os_create_pr m-2" class="btn btn-primary" value="create-pr">Save & Create PR</button>
        </div>
        <div class="col-3"></div>
    </div>
</form>