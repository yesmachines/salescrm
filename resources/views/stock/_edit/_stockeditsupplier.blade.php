<div class="row mb-2">
    <div class="col-12">
        <div class="separator"></div>
    </div>
</div>



<!-- Payment Terms of Suppliers -->
<div class="row mb-2">
    <div class="col-xxl-12">
        <h6> <b>Payment Terms of Suppliers</b>
            <a href="javascript:void(0);" class="addST btn btn-primary btn-sm" style="float: right;">
                <i data-feather="plus"></i> Add Row</a>
        </h6>
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
                @foreach($stockPayments as $index => $payment)
                <tr valign="top">
                    <td width="30%">
                        <textarea class="form-control" name="payment_term[]" placeholder="Payment Term">{{ $payment->payment_term }}</textarea>
                    </td>
                    <td width="20%">
                        <input type="text" class="form-control datepick" name="expected_date[]" placeholder="Expected Date" value="{{ $payment->expected_date }}" />
                    </td>
                    <td width="20%">
                        <select class="form-control" name="status[]">
                            <option value="0" {{ $payment->status == 0 ? 'selected' : '' }}>Not Done</option>
                            <option value="1" {{ $payment->status == 1 ? 'selected' : '' }}>Done</option>
                            <option value="2" {{ $payment->status == 2 ? 'selected' : '' }}>Partially Done</option>
                        </select>
                    </td>
                    <td width="30%">
                        <textarea rows="2" name="payment_remark[]" placeholder="Remarks" class="form-control">{{ $payment->remarks }}</textarea>
                    </td>
                    <td><a href="javascript:void(0);" class="remST" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td>
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

<!-- Additional Charges -->
<div class="row mb-2">
    <div class="col-xxl-12">
        <h6> <b>Additional Charges</b>
            <a href="javascript:void(0);" class="addAC btn btn-primary btn-sm" style="float: right;">
                <i data-feather="plus"></i> Add Row</a>
        </h6>
        <table class="table form-table" id="chargespaymentFields">
            <thead>
                <tr>
                    <th>Additional Items *</th>
                    <th>Considered Charges (AED) *</th>
                    <th>Remarks</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($stockCharges as $index => $charge)
                <tr valign="top">
                    <td width="30%">
                        <input type="text" class="form-control" name="charges[]" placeholder="Packing Charge" value="{{ $charge->title }}" />
                    </td>
                    <td width="20%">
                        <input type="text" class="form-control" name="considered[]" placeholder="Considered Cost" value="{{ $charge->considered }}" />
                    </td>
                    <td width="30%">
                        <textarea rows="2" name="charge_remark[]" placeholder="Remarks" class="form-control">{{ $charge->remarks }}</textarea>
                    </td>
                    <td><a href="javascript:void(0);" class="remAC" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row mb-2">
    <div class="col-4"></div>
    <div class="col-4">
        <button type="submit" name="publish" class="btn btn-primary mt-5 me-2">Update Stock</button>
    </div>
    <div class="col-4"></div>
</div>