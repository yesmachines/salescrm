<div class="row m-2">
  <div class="col-9"> <label>Local Product / Model No / Part No *</label>

    <select class="form-control select2" id="product_item_search" disabled>
      <option value="">-- Select Products --</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">&nbsp;</label>
    <div class="form-group text-center">
      <a href="javascript:void(0);" id="add-new-product" class="mt-3 d-none" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="fas fa-plus"></i> Create New Product</a>
    </div>
  </div>
</div>
<div class="row ">
  <div class="col-12">
    <div class="separator"></div>
  </div>
</div>
<div class="row">
  <div class="col-xxl-12">
    <h6><b> Stock Items</b>

      <!-- <a href="javascript:void(0);" class="addIT btn btn-primary btn-sm" style="float: right;"><i data-feather="plus"></i> Add Row</a> -->
    </h6>
    </h6>
    <table class="table form-table mb-2" id="itemcustomFields">
      <thead>
        <tr>
          <th>Item Details *</th>
          <th>Part No</th>
          <th>Unit (AED)*</th>
          <th>Qty *</th>
          <th>YesNo *</th>
          <th>Dis (%)</th>
          <th>Amt (AED) *</th>
          <th>Expected<br />Delivery</th>
          <th>Status</th>
          <th>Item<br />Remarks</th>
          <th></th>
        </tr>
      </thead>
      <tbody>

        <!-- <tr valign="top" id="irow-1">
       
          <td width="15%">
            <textarea class="form-control" name="item_name[]" placeholder="Item"></textarea>
          </td>
          <td>
            <input type="text" class="form-control" name="partno[]" placeholder="Part No" value="" />
          </td>
          <td>
            <input type="number" class="form-control" name="quantity[]" value="" placeholder="Quantity" />
          </td>
          <td>
            <input type="text" class="form-control" name="yes_number[]" placeholder="YesNo." value="" />
          </td>
          <td>
            <input type="number" class="form-control purchase_amount" name="total_amount[]" value="" step="any" placeholder="Total Amount" />
          </td>
          <td width="8%">
            <input type="text" class="form-control datepick" name="expected_delivery[]" placeholder="Expected Delivery" value="" />
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
        </tr> -->
      </tbody>
    </table>
  </div>
  <div class="row mb-2 mt-3">
    <div class="col-4">
      <h6>Buying Price (AED) From Supplier *</h6>
    </div>
    <div class="col-6">
      <input type="text" class="form-control" id="total_buying_price" name="total_buying_price" readonly>
    </div>
    <div class="col-2"></div>
  </div>
  <div class="row mb-2">
    <div class="col-12">
      <div class="separator"></div>
    </div>
  </div>
</div>