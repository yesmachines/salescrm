<div class="row mb-2">
  <div class="col-4">
    <h6>OS Type* </h6>
  </div>
  <div class="col-8">
    <input class="form-check-input" type="radio" name="purchase_mode" id="stockOption" value="stock">
    <label class="form-check-label" for="stockOption">
      Stock
    </label>&nbsp;&nbsp;

    <input class="form-check-input" type="radio" name="purchase_mode" id="otherPurchaseOption" value="otherPurchase">
    <label class="form-check-label" for="otherPurchaseOption">
      Other Purchase
    </label>&nbsp;&nbsp;

    <input class="form-check-input" type="radio" name="purchase_mode" id="otherOption" value="other">
    <label class="form-check-label" for="otherOption">
      Other
    </label>


    <div id="descriptionBox" class="mt-3" style="display: none;">
      <label for="description">Description:</label>
      <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>
  </div>
  <div class="col-2"></div>
</div>

<div class="row my-4">
  <div class="col-xxl-12">
    <div class="row mb-2">
      <div class="col-4">
        <h6>OS Date *</h6>
      </div>
      <div class="col-6">
        <input type="text" id="os_date" name="os_date" class="form-control todaydatepick" value="">
      </div>
      <div class="col-2"></div>
    </div>
  </div>

</div>
<div class="row my-4">
  <div class="col-xxl-12">
    <div class="row mb-2">
      <div class="col-4">
        <h6>Order For *</h6>
      </div>
      <div class="col-6">
        <select class="form-select" name="order_for" required id="order_for">
          <option value="yesmachinery">Yesmachinery</option>
          <option value="yesclean">Yesclean</option>
        </select>
      </div>
      <div class="col-2"></div>
    </div>
  </div>
</div>
<div class="row my-4">
  <div class="col-xxl-12">
    <div class="row mb-2">
      <div class="col-4">
        <h6>Manager *</h6>
      </div>
      <div class="col-6">
        <select class="form-select" name="assigned_to" required>
          <option value="">--</option>
          @foreach($employees as $emp)
          <option value="{{$emp->id}}">{{ $emp->user->name}}</option>
          @endforeach
        </select>
      </div>
      <div class="col-2"></div>
    </div>
  </div>

</div>
<div class="row mb-2">
  <div class="col-12">
    <div class="separator"></div>
  </div>
</div>
<div class="row mb-2">
  <div class="col-xxl-12">
    <h6> <b>Supplier & Delivery Terms</b>
      <!-- <a href="javascript:void(0);" class="addST btn btn-primary btn-sm" style="float: right;">
        <i data-feather="plus"></i> Add Row</a> -->
    </h6>
    <table class="table form-table" id="supplierFields">
      <thead>
        <tr>
          <th>Supplier *</th>
          <th>Currency *</th>
          <th>Delivery Term *</th>
          <th>Supplier Remarks</th>
        </tr>
      </thead>
      <tbody>

        <tr valign="top">
          <td width="30%">
            <select class="form-control supplier-dropdown select2" name="supplier_id" id="supplier">
              <option value="">--Select--</option>
              @foreach($suppliers as $sup)
              <option value="{{$sup->id}}">{{$sup->brand}}</option>
              @endforeach
            </select>
          </td>
          <td width="20%">
            <select class="form-control" name="price_basis" id="currencydropdown">
              <option value="">-Select-</option>
              @foreach($currencies as $cur)
              <option value="{{$cur->code}}">{{strtoupper($cur->code)}}</option>
              @endforeach
            </select>
          </td>
          <td width="20%">
            <select class="form-control" name="delivery_term">
              <option value="">-Select-</option>
              @foreach($terms as $term)
              <option value="{{$term->short_code}}">{{$term->title}}</option>
              @endforeach
            </select>
          </td>
          <td width="30%">
            <textarea rows="2" name="supplier_remark" placeholder="Supplier Remarks" class="form-control"></textarea>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var otherOption = document.getElementById('otherOption');
    var descriptionBox = document.getElementById('descriptionBox');

    otherOption.addEventListener('change', function() {
      if (this.checked) {
        descriptionBox.style.display = 'block';
      }
    });

    var otherPurchaseOption = document.getElementById('otherPurchaseOption');
    var stockOption = document.getElementById('stockOption');

    otherPurchaseOption.addEventListener('change', function() {
      if (this.checked) {
        descriptionBox.style.display = 'none';
      }
    });

    stockOption.addEventListener('change', function() {
      if (this.checked) {
        descriptionBox.style.display = 'none';
      }
    });

  });
</script>