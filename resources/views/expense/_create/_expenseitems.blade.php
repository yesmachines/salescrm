
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

          <tr valign="top">
            <td width="15%">

              <textarea class="form-control" name="item_name[]" placeholder="Item"></textarea>

            </td>
            <td>
              <input type="text" class="form-control" name="partno[]" placeholder="Part No" value="" />
            </td>
            <td>
              <input type="text" class="form-control" name="unit_price[]" placeholder="Unit Price" value="" />
            </td>
            <td>
              <input type="number" class="form-control" name="quantity[]" min="1" value="" placeholder="Quantity" />
            </td>
            <td>
              <input type="text" class="form-control" name="discount[]" min="1" placeholder="Discount" value="" />
            </td>
            <td>
              <input type="number" class="form-control" name="total_amount[]" value=""  step="any" placeholder="Total Amount" readonly/>
            </td>
            <td width="10%">
              <select class="form-control" name="status[]" id="status">
                <option value="0" {{ 0 ? 'selected' : '' }}>Not Delivered</option>
                <option value="1" {{ 1 ? 'selected' : '' }}>Delivered</option>
              </select>
            </td>
            <td width="15%">
              <textarea rows="2" id="remarks" name="item_remark[]" placeholder="Remarks" class="form-control"></textarea>
            </td>
            <td></td>
          </tr>

        </tbody>
      </table>
    </div>
    <div class="row mb-2">
      <div class="col-4">
        <h6>Total Expense</h6>
      </div>
      <div class="col-6">
        <input type="text" class="form-control" id="buying_price" name="total_buying_price" readonly>
      </div>
      <div class="col-2"></div>
    </div>
  </div>
