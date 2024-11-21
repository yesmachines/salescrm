@extends('layouts.default')

@section('content')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<div class="container-xxl">
  <div class="hk-pg-header pt-7 pb-4">
    <h1 class="pg-title">Update Product</h1>
  </div>
  <div class="hk-pg-body">
    <div class="row edit-profile-wrap">
      <div class="col-lg-12 col-sm-12 col-12">
        <div class="mt-2">
          @include('layouts.partials.messages')
        </div>
        {!! Form::model($product, ['method' => 'PATCH','enctype' => 'multipart/form-data', 'route' => ['products.update', $product->id]]) !!}
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Title <span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="title" value="{{ $product->title }}" required />
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Division<span class="text-danger">*</span></label>
            </div>

            <div class="form-group">
              <select class="form-select select2" name="division_id" required>
                <option value="0">--</option>
                @foreach ($divisions as $division)
                <option value="{{ $division->id }}" {{ $product->division_id == $division->id ? 'selected' : '' }}>
                  {{ $division->name }}
                </option>
                @endforeach
              </select>
            </div>
          </div>

        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Brand<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <select class="form-select select2" name="brand_id" required>
                <option value="0">--</option>
                @foreach ($suppliers as $sup)
                <option value="{{ $sup->id }}" {{ $product->brand_id == $sup->id ? 'selected' : '' }}>
                  {{ $sup->brand }}
                </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Model No.<span class="text-danger">*</span></label>
            </div>

            <div class="form-group">
              <input class="form-control" type="text" name="model_no" value="{{ $product->modelno }}"  required />
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Description <span class="text-danger">*</span></label>
              </div>

              <div class="form-group">
                <textarea class="form-control" type="text" name="description" value="{{ $product->description }}" rows="1" cols="1"  required>{{ $product->description }}</textarea>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Product Type<span class="text-danger">*</span></label>
              </div>

              <div class="form-group">
                <select class="form-control" name="product_type" id="productTypeSelect">
                  <option value="">Select</option>
                  <option value="standard" {{ $product->product_type == 'standard' ? 'selected' : '' }}>
                    Standard Product
                  </option>
                  <option value="custom" {{ $product->product_type == 'custom' ? 'selected' : '' }}>
                    Custom Product
                  </option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
            <div class="form-group">
            <label class="form-label">Price Basis</label>
          </div>
          <div class="form-group">
          <select class="form-control" name="payment_term">
          @foreach($paymentTerms as $paymentTerm)
          <option value="{{ $paymentTerm->short_code }}" @if($paymentTerm->short_code == $product->price_basis) selected @endif>{{ $paymentTerm->title }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="form-label">Currency<span class="text-danger">*</span></label>
      </div>
      <div class="form-group">
        <select class="form-control" name="currency" required>
          <option value="">Select Currency</option>
          @foreach($currencies as $currency)
          <option value="{{ $currency->code }}" {{ $product->currency == $currency->code ? 'selected' : '' }}>
            {{ $currency->name }}
          </option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="form-label">Currency Conversion</label>
      </div>
      <div class="form-group">
        <input class="form-control" type="text" name="currency_rate" value="{{ $product->currency_rate }}" />
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label class="form-label">Manager<span class="text-danger">*</span></label>
      </div>

      <div class="form-group">
        <select class="form-select" name="manager_id">
          <option value="0" selected="">--</option>
          @foreach ($managers as $key => $row)
          <option value="{{ $row->id }}" {{$product->manager_id == $row->id? "selected": ""}}>
            {{ $row->user->name }}
          </option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="form-label">Selling Price<span class="text-danger">*</span></label>
      </div>

      <div class="form-group">
        <input class="form-control" type="text" name="selling_price" id="sellingPrice" value="{{ $product->selling_price }}" required />
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="form-label">MOSP<span class="text-danger">*</span></label>
      </div>

      <div class="form-group">
        <input class="form-control" type="text" name="margin_percentage" id="marginPercentage" value="{{ $product->margin_percent }}" required />
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-group">
        <label class="form-label">Margin Price<span class="text-danger">*</span></label>
      </div>

      <div class="form-group">
        <input class="form-control" type="text" name="margin_price" id="marginPrice" value="{{ $product->margin_price }}" required readonly />
      </div>
    </div>
      <input class="form-control" type="hidden" name="allowed_discount" />
        <input class="form-check-input" type="hidden" name="freeze_discount">

    <div class="col-md-6" id="permissibleDiscountField">
      <div class="form-group">
        <label class="form-label">Permissible Discount</label>
      </div>

      <div class="form-group">
        <input class="form-control" type="text" name="allowed_discount" value="{{ $product->allowed_discount }}"/>
      </div>
    </div>

    <div class="col-md-6"  id="freezeDiscount">
      <div class="form-group">
        <label class="form-label">Freeze Discount<span class="text-danger">*</span></label>
      </div>
      <div class="form-group">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="freeze_discount" value="1" id="freezeDiscountCheckbox" {{ $product->freeze_discount == 1 ? 'checked' : '' }}>
          <label class="form-check-label" for="freezeDiscountCheckbox">
            Freeze Discount
          </label>
        </div>
      </div>
    </div>

    <div class="col-sm-6">
      <div class="form-group">
        <label class="form-label">Image</label>
      </div>

      <div class="form-group">
        <input class="form-control" type="file" name="image" />
      </div>
      <div class="col-sm-10 form-group">
        <img src="{{ asset('storage/' . $product->image_url) }}" style="width:60px"  alt="Product Image">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
      </div>
      @php
      $startDate = $product->price_valid_from ? \Carbon\Carbon::parse($product->price_valid_from) : null;
      $endDate = $product->price_valid_to ? \Carbon\Carbon::parse($product->price_valid_to) : null;
      $getDateDiff = null;
      if ($startDate && $endDate) {
        $getDateDiff = $startDate->diffInMonths($endDate);
      }
      @endphp
      <div class="form-group">
        <select class="form-control" name="date" id="durationSelect" required>
          <option value="">Select</option>
          <option value="1" {{ $getDateDiff== 1 ? 'selected' : '' }}>1 Month</option>
          <option value="3" {{ $getDateDiff== 3 ? 'selected' : '' }}>3 Month</option>
          <option value="6" {{ $getDateDiff== 6 ? 'selected' : '' }}>6 Month</option>
          <option value="9" {{ $getDateDiff== 9 ? 'selected' : '' }}>9 Month</option>
          <option value="12" {{ $getDateDiff == 12 ? 'selected' : '' }}>12 Month</option>
        </select>
      </div>
      <div class="form-group" id="dateRangeGroup" style="display: none">
        <label class="form-label">Dates:</label>
        <span id="dateRange">
        </span>
        <input type="hidden" name="start_date" id="startDateInput" value="{{ $product->price_valid_from }}" />
        <input type="hidden" name="end_date" id="endDateInput" value="{{ $product->price_valid_to }}" />
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="form-label">Product Category<span class="text-danger">*</span></label>
      </div>
      <div class="form-group">
        <select class="form-control" name="product_category" required>
          <option value="products" @if($product->product_category == 'products') selected @endif>Products</option>
          <option value="spares" @if($product->product_category == 'spares') selected @endif>Spares</option>
          <option value="accessories" @if($product->product_category == 'accessories') selected @endif>Accessories</option>
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label class="form-label">Status<span class="text-danger">*</span></label>
      </div>

      <div class="form-group">
        <select class="form-control" name="status" required>
          <option value="" {{ $product->status == '' ? 'selected' : '' }}>Select</option>
          <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>
    </div>
  </div>
  <div class="row gx-3">
    <div class="col-4"></div>
    <div class="col-4">
      <button type="submit" class="btn btn-primary mt-5 me-2">Create Product</button>
      <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='/products'">Back To List</button>
    </div>
    <div class="col-4"></div>
  </div>
</form>
</div>
</div>
</div>
</div>
<script type="text/javascript">

$(document).ready(function() {
    function toggleFields(productType) {
        if (productType === 'standard') {
            $('#permissibleDiscountField').show();
            $('#freezeDiscount').show();
        } else if (productType === 'custom') {
            $('#permissibleDiscountField').hide();
            $('#freezeDiscount').hide();
        }
    }
    $('#productTypeSelect').change(function() {
        const selectedProductType = $(this).val();
        if (selectedProductType === 'standard') {
            $('#durationSelect').val('12').trigger('change');
        } else if (selectedProductType === 'custom') {
            $('#durationSelect').val('1').trigger('change');
        }
        toggleFields(selectedProductType);
    });
    toggleFields($('#productTypeSelect').val());
    $('#durationSelect').change(function() {
        const selectedValue = $(this).val();
        const currentDate = new Date();
        const startDateInput = $('#startDateInput');
        const endDateInput = $('#endDateInput');
        const dateRange = $('#dateRange');
        const dateRangeGroup = $('#dateRangeGroup');

        if (selectedValue !== "") {
            const startDate = new Date(currentDate);
            const endDate = new Date(currentDate);
            endDate.setMonth(currentDate.getMonth() + parseInt(selectedValue));

            const startDateFormatted = startDate.toISOString().split('T')[0];
            const endDateFormatted = endDate.toISOString().split('T')[0];

            startDateInput.val(startDateFormatted);
            endDateInput.val(endDateFormatted);

            const dateRangeText = `${startDateFormatted} to ${endDateFormatted}`;
            dateRange.text(dateRangeText);
            dateRangeGroup.show();
        } else {
            startDateInput.val('');
            endDateInput.val('');
            dateRange.text('');
            dateRangeGroup.hide();
        }
    });
});
</script>
<script>
function updateMarginPrice() {
  var basePrice = parseFloat(document.getElementById('sellingPrice').value);
  var marginPercentage = parseFloat(document.getElementById('marginPercentage').value);
  var marginPriceInput = document.getElementById('marginPrice');

  if (!isNaN(basePrice) && !isNaN(marginPercentage)) {
    var calculatedMarginPrice = basePrice * (marginPercentage / 100);
    marginPriceInput.value = calculatedMarginPrice.toFixed(2);
  } else {
    marginPriceInput.value = '';
  }
}
document.getElementById('sellingPrice').addEventListener('input', updateMarginPrice);
document.getElementById('marginPercentage').addEventListener('input', updateMarginPrice);
</script>

@endsection
