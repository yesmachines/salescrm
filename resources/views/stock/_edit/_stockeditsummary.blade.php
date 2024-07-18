
<div class="row mb-2">
  <div class="col-4">
    <h6>OS Type </h6>
  </div>
  <div class="col-6">
    <input class="form-check-input" type="radio" name="purchase_mode" id="stockOption" value="stock" {{ $stock->purchase_mode == 'stock' ? 'checked' : '' }}>
    <label class="form-check-label" for="stockOption">Stock</label>

    <input class="form-check-input" type="radio" name="purchase_mode" id="otherPurchaseOption" value="otherPurchase" {{ $stock->purchase_mode == 'otherPurchase' ? 'checked' : '' }}>
    <label class="form-check-label" for="otherPurchaseOption">Other Purchase</label>

    <input class="form-check-input" type="radio" name="purchase_mode" id="otherOption" value="other" {{ $stock->purchase_mode == 'other' ? 'checked' : '' }}>
    <label class="form-check-label" for="otherOption">Other</label>


    <div id="descriptionBox" class="mt-3" style="display: {{ $stock->purchase_mode == 'other' ? 'block' : 'none' }};">
      <label for="description">Description:</label>
      <textarea class="form-control" id="description" name="description" rows="3">{{ $stock->description }}</textarea>
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
        <input type="text" id="os_date" name="os_date" class="form-control todaydatepick" value="{{ $stock->os_date }}">
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
                  <option value="{{$emp->id}}" {{($emp->id == $stock->assigned_to)? "selected": "" }}>{{ $emp->user->name}}</option>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
  var otherOption = document.getElementById('otherOption');
  var descriptionBox = document.getElementById('descriptionBox');

  otherOption.addEventListener('change', function () {
    if (this.checked) {
      descriptionBox.style.display = 'block';
    }
  });

  var otherPurchaseOption = document.getElementById('otherPurchaseOption');
  var stockOption = document.getElementById('stockOption');

  otherPurchaseOption.addEventListener('change', function () {
    if (this.checked) {
      descriptionBox.style.display = 'none';
    }
  });

  stockOption.addEventListener('change', function () {
    if (this.checked) {
      descriptionBox.style.display = 'none';
    }
  });

});
</script>
