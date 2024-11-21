@extends('layouts.default')

@section('content')

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<div class="container-xxl">
  <!-- Page Header -->
  <div class="hk-pg-header pt-7 pb-4">
    <h1 class="pg-title">Create New Product</h1>
  </div>
  <!-- /Page Header -->

  <!-- Page Body -->
  <div class="hk-pg-body">
    <div class="row edit-profile-wrap">
      <div class="col-lg-12 col-sm-12 col-12">
        <div class="mt-2">
          @include('layouts.partials.messages')
        </div>
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Title <span class="text-danger">*</span></label>
              </div>

              <div class="form-group">
                <input class="form-control" type="text" name="title" required />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Division<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-select select2" name="division_id">
                  <option value="0">--</option>
                  @foreach ($divisions as $division)
                  <option value="{{ $division->id }}">
                    {{ $division->name }}
                  </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Brand<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-select select2" name="brand_id">
                  <option value="0">--</option>
                  @foreach ($suppliers as $k => $sup)
                  <option value=" {{ $sup->id }}">
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
                <input class="form-control" type="text" name="model_no"  required />
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Description <span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="description" rows="2" cols="1"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Product Type<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_type" id="productTypeSelect" required>
                  <option value="">Select</option>
                  <option value="standard">Standard Product</option>
                  <option value="custom">Custom Product</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Price Basis<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="payment_term" required>
                  @foreach($paymentTerms as $paymentTerm)
                  <option value="{{ $paymentTerm->short_code }}">{{ $paymentTerm->title }}</option>
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
                  <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Currency Conversion</label>
              </div>
              <div class="form-group">
              <input class="form-control" type="text" name="currency_rate"/>
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
                  <option value="{{ $row->id }}">
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
                <input class="form-control" type="text" name="selling_price" id="sellingPrice" required />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">MOSP<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="margin_percentage" id="marginPercentage" required />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Margin Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="margin_price" id="marginPrice" required readonly />
              </div>
            </div>
            <div class="col-md-6" id="permissibleDiscountField">
              <div class="form-group">
                <label class="form-label">Permissible Discount<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="allowed_discount"/>
              </div>
            </div>
            <div class="col-md-6" id="freezeDiscount">
              <div class="form-group">
                <label class="form-label">Freeze Discount</label>
              </div>
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="freeze_discount" value="1" id="freezeCheckbox" checked>
                  <label class="form-check-label" for="freezeCheckbox">
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
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="date" id="durationSelect" required>
                  <option value="">Select</option>
                  <option value="1">1 Month</option>
                  <option value="3">3 Month</option>
                  <option value="6">6 Month</option>
                  <option value="9">9 Month</option>
                  <option value="12">12 Month</option>
                </select>
              </div>
              <div class="form-group" id="dateRangeGroup" style="display: none;">
                <label class="form-label">Dates:</label>
                <span id="dateRange"></span>
                <input type="hidden" name="start_date" id="startDateInput" />
                <input type="hidden" name="end_date" id="endDateInput" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Product Category<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_category" required>
                  <option>Select</option>
                  <option value="products">Products</option>
                  <option value="spares">Spares</option>
                  <option value="accessories">Accessories</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Status<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="status" required>
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row gx-3">
            <div class="col-4"></div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary mt-5 me-2">Create Product</button>
              <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='{{ route('products.index') }}'">Back To List</button>
            </div>
            <div class="col-4"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- /Page Body -->
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    const durationSelect = document.getElementById('durationSelect');
    const dateRangeGroup = document.getElementById('dateRangeGroup');

    function displayDateRange(selectedValue) {
        const currentDate = new Date();
        const startDateInput = document.getElementById('startDateInput');
        const endDateInput = document.getElementById('endDateInput');

        if (selectedValue !== "") {
            const startDate = new Date(currentDate);
            const endDate = new Date(currentDate);
            endDate.setMonth(currentDate.getMonth() + parseInt(selectedValue));

            const startDateFormatted = startDate.toISOString().split('T')[0];
            const endDateFormatted = endDate.toISOString().split('T')[0];

            startDateInput.value = startDateFormatted;
            endDateInput.value = endDateFormatted;

            const dateRange = `${startDateFormatted} to ${endDateFormatted}`;
            document.getElementById('dateRange').innerText = dateRange;
            dateRangeGroup.style.display = 'block';
        } else {
            startDateInput.value = '';
            endDateInput.value = '';
            document.getElementById('dateRange').innerText = "";
            dateRangeGroup.style.display = 'none';
        }
    }

    durationSelect.addEventListener('change', function () {
        const selectedValue = this.value;
        displayDateRange(selectedValue);
    });

    const selectedDuration = durationSelect.value;
    displayDateRange(selectedDuration);
});

$(document).ready(function() {
    $('#productTypeSelect').change(function() {
        const selectedProductType = $(this).val();

        if (selectedProductType === 'standard') {
            $('#durationSelect').val('12').trigger('change');
             $('#permissibleDiscountField').show();
                   $('#freezeDiscount').show();
        } else if (selectedProductType === 'custom') {
            $('#durationSelect').val('1').trigger('change');
              $('#permissibleDiscountField').hide();
                $('#freezeDiscount').hide();

        }
    });

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
