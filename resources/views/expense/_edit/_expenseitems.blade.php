
<div class="row">
  <div class="col-xxl-12">
    <h6><b>Items</b>
        <a href="javascript:void(0);" class="addIT btn btn-primary btn-sm" style="float: right;"><i data-feather="plus"></i> Add Row</a>
      </h6>
    </h6>
    <table class="table form-table" id="itemcustomFields">
      <thead>
        <tr>
          <th>Item Details *</th>
          <th>Part No</th>
          <th>Unit Price</th>
          <th>Qty *</th>
          <th>Discount</th>
          <th>Amount (AED) *</th>
          <th>Status</th>
          <th>Item<br />Remarks</th>
          <th></th>
        </tr>
      </thead>
      <tbody>

        @foreach($stockItems as $index => $item)
        <tr valign="top">
          <td width="15%">
            <textarea class="form-control" name="item_name[]" placeholder="Item">{{ $item->item_name }}</textarea>
          </td>
          <td>
            <input type="text" class="form-control" name="partno[]" placeholder="Part No" value="{{ $item->partno }}" />
          </td>
          <td>
            <input type="text" class="form-control" name="unit_price[]" placeholder="Unit-Price" value="{{ $item->unit_price }}" />
          </td>
          <td>
            <input type="number" class="form-control" name="quantity[]" value="{{ $item->quantity }}" placeholder="Quantity" />
          </td>
          <td>
            <input type="text" class="form-control" name="discount[]" placeholder="Discount" value="{{ $item->discount }}" />
          </td>
          <td>
            <input type="number" class="form-control" name="total_amount[]" value="{{ $item->total_amount }}" step="any" placeholder="Total Amount" readonly/>
          </td>
          <td width="10%">
            <select class="form-control" name="status[]" id="status">
              <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Not Delivered</option>
              <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Delivered</option>
            </select>
          </td>
          <td width="15%">
            <textarea rows="2" id="remarks" name="item_remark[]" placeholder="Remarks" class="form-control">{{ $item->remarks }}</textarea>
          </td>
        <td><a href="javascript:void(0);" class="remIT" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td>
        </tr>
        @endforeach

      </tbody>
    </table>
  </div>
  <div class="row mb-2">
      <div class="col-4">
          <h6>Total Expense</h6>
      </div>
      <div class="col-6">
          <input type="text" class="form-control" id="buying_price" name="total_buying_price" value="{{ $stock->buying_price }}" readonly>
      </div>
      <div class="col-2"></div>
  </div>


</div>
