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
                <input class="form-control" type="text" name="title" id="titleInput" required>
                <div class="invalid-data" style="display: none;">Please enter a title.</div>

              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Division<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control select2" name="division_id" id="divisionInput" required>
                  <option value="0">--</option>
                  @foreach ($divisions as $division)
                  <option value="{{ $division->id }}">
                    {{ $division->name }}
                  </option>
                  @endforeach
                </select>
                <div class="invalid-data" style="display: none;">Please select a division.</div>
              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Brand<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control select2" name="brand_id" id="brandInput" required>
                  <option value="">--</option>
                  @foreach ($suppliers as $k => $sup)
                  <option value=" {{ $sup->id }}">
                    {{ $sup->brand }}
                  </option>
                  @endforeach
                </select>
                <div class="invalid-data" style="display: none;">Please select a brand.</div>
              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Model No.</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="model_no">
              </div>

            </div>

          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Part Number</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="part_number">

              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Description <span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="description" rows="2" cols="1" id="descriptionInput" required></textarea>
                <div class="invalid-data" style="display: none;">Please enter a description.</div>
              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Product Type<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control select2" name="product_type" id="productTypeSelect" required>
                  <option value="">Select</option>
                  <option value="standard">Standard Price Product</option>
                  <option value="custom">Custom Price Product</option>
                </select>
                <div class="invalid-data" style="display: none;">Please select a product type.</div>
              </div>

            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Price Basis<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control select2" name="payment_term" required>
                  @foreach($paymentTerms as $paymentTerm)
                  <option value="{{ $paymentTerm->short_code }}">{{ $paymentTerm->title }}</option>
                  @endforeach
                </select>
                <div class="invalid-data" style="display: none;">Please select a price basis.</div>
              </div>


            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Currency<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control select2" name="currency" id="currencyInput" required>
                  <option value="">Select Currency</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-data" style="display: none;">Please select a currency.</div>
              </div>

            </div>

            <!-- <div class="col-md-6">
            <div class="form-group">
            <label class="form-label">Currency Conversion</label>
          </div>
          <div class="form-group">
          <input class="form-control" type="text" name="currency_rate"/>
        </div>
      </div> -->

      <div class="col-md-6">
        <div class="form-group">
          <label class="form-label">Manager<span class="text-danger">*</span></label>
        </div>
        <div class="form-group">
          <select class="form-select select2" name="manager_id" id="managerInput" required>
            <option value="" selected="">--</option>
            @php
            $sortedManagers = $managers->sortBy('user.name');
            @endphp
            @foreach ($sortedManagers as $key => $row)
            <option value="{{ $row->id }}">
              {{ $row->user->name }}
            </option>
            @endforeach
          </select>
          <div class="invalid-data" style="display: none;">Please select a manager.</div>
        </div>

      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="form-label">Selling Price<span class="text-danger">*</span></label>
        </div>
        <div class="form-group">
          <input class="form-control" type="text" name="selling_price" id="sellingPrice" required />
          <div class="invalid-data" style="display: none;">Please enter a selling price.</div>
        </div>

      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="form-label">MOSP(%)<span class="text-danger">*</span></label>
        </div>
        <div class="form-group">
          <input class="form-control" type="text" name="margin_percentage" id="marginPercentage" required />
          <div class="invalid-data" style="display: none;">Please enter a margin percentage.</div>
        </div>

      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label class="form-label">Margin Price<span class="text-danger">*</span></label>
        </div>
        <div class="form-group">
          <input class="form-control" type="text" name="margin_price" id="marginPrice" required/>
        </div>
      </div>
      <div class="col-md-6" id="permissibleDiscountField">
        <div class="form-group">
          <label class="form-label">Permissible Discount(%)</label>
        </div>
        <div class="form-group">
          <input class="form-control" type="number" name="allowed_discount" id="allowedDiscount">
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
          <select class="form-control select2" name="date" id="durationSelect">
            <option value="">Select</option>
            <option value="1">1 Month</option>
            <option value="3">3 Month</option>
            <option value="6">6 Month</option>
            <option value="9">9 Month</option>
            <option value="12">12 Month</option>
          </select>
          <div class="invalid-data" style="display: none;">Please choose a date.</div>
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
          <select class="form-control select2" name="product_category" id="productcategoryInput" required>
            <option value="">Select</option>
            <option value="products">Products</option>
            <option value="spares">Spares</option>
            <option value="accessories">Accessories</option>
            <option value="consumables">Consumables</option>
          </select>
          <div class="invalid-data" style="display: none;">Please select a product category.</div>
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
        <button type="submit" class="btn btn-primary mt-5 me-2" id="createProductBtn">Create Product</button>
        <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='{{ route('products.index') }}'">Back To List</button>
      </div>
      <div class="col-4"></div>
    </div>
  </form>
</div>
</div>
</div>

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

  durationSelect.addEventListener('change', function() {
    const selectedValue = this.value;
    displayDateRange(selectedValue);
  });

  const selectedDuration = durationSelect.value;
  displayDateRange(selectedDuration);
});

$(document).ready(function() {
  $('#marginPrice').on('input', function() {
    var sellingPriceStr = $('#sellingPrice').val();
    if (sellingPriceStr.trim() === '') {
      alert('Please enter the selling price first.');
      $(this).val(''); // Clear the margin price input
    }
  });
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

  function updateMarginPrice() {
    var sellingPriceStr = $('#sellingPrice').val(); // Get the value using jQuery
    var marginPercentageStr = $('#marginPercentage').val(); // Get the value using jQuery

    var basePrice = parseFloat(sellingPriceStr.replace(/,/g, ''));
    var marginPercentage = parseFloat(marginPercentageStr.replace(/,/g, ''));

    var marginPriceInput = $('#marginPrice');

    if (!isNaN(basePrice) && !isNaN(marginPercentage)) {

        var calculatedMarginPrice = basePrice * (marginPercentage / 100);
        var formattedMarginPrice = numberWithCommas(calculatedMarginPrice.toFixed(2));

        marginPriceInput.val(formattedMarginPrice);
    }
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

  function updateMarginPercentage() {
    var sellingPriceStr = $('#sellingPrice').val();
    var marginPriceStr = $('#marginPrice').val();

    if (sellingPriceStr.trim() === '') {
      return;
    }

    var basePrice = parseFloat(sellingPriceStr.replace(/,/g, ''));
    var marginPrice = parseFloat(marginPriceStr.replace(/,/g, ''));

    console.log('Base Price:', basePrice);
    console.log('Margin Price:', marginPrice);

    var marginPercentageInput = $('#marginPercentage');

    if (!isNaN(basePrice) && !isNaN(marginPrice) && basePrice !== 0) {
      if (basePrice <= marginPrice) {
        alert('Selling price should be greater than margin price.');
        marginPercentageInput.val('');
      } else {
        var calculatedMarginPercentage = (marginPrice / basePrice) * 100;
        var formattedMarginPercentage = calculatedMarginPercentage.toFixed(2);
        marginPercentageInput.val(formattedMarginPercentage);
      }
    } else {
      marginPercentageInput.val('');
    }
  }


  document.getElementById('sellingPrice').addEventListener('input', updateMarginPrice);
  document.getElementById('marginPercentage').addEventListener('input', updateMarginPrice);
  $('#sellingPrice, #marginPrice').on('input', function() {
    updateMarginPercentage();
  });

  $('.invalid-data').hide();

  $('#createProductBtn').click(function(e) {
    var isValid = true;

    $('.form-control').removeClass('is-invalid');
    $('.invalid-data').hide();

    var requiredFields = ['#titleInput', '#divisionInput', '#brandInput', '#productTypeSelect', '#descriptionInput', '#sellingPrice', '#marginPercentage', '#productcategoryInput', '#currencyInput', '#managerInput'];

    requiredFields.forEach(function(field) {
      var fieldValue = $(field).val().trim();
      if (fieldValue === '') {
        $(field).addClass('is-invalid');
        $(field).closest('.form-group').find('.invalid-data').show();
        isValid = false;
      } else {
        if ((field === '#divisionInput' || field === '#brandInput' || field === '#productTypeSelect') && fieldValue === '0') {
          $(field).addClass('is-invalid');
          $(field).closest('.form-group').find('.invalid-data').show();
          isValid = false;
        }
      }
    });

    if (!isValid) {
      e.preventDefault();
    }
  });
  $('.form-control').on('input', function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.form-group').find('.invalid-data').hide();
  });

  // Remove validation when a manager is selected
  $('#managerInput').change(function() {
    $(this).removeClass('is-invalid');
    $(this).closest('.form-group').find('.invalid-data').hide();
  });

  $('#sellingPrice').on('input', function() {
    var sellingPrice = $(this).val();
    var unformattedValue = sellingPrice.replace(/,/g, '');
    if (!/^\d*\.?\d*$/.test(unformattedValue)) {
      alert('Please enter only float values for selling price.');
      $(this).val('');
    } else {
      var parts = unformattedValue.split('.');
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      var formattedValue = parts.join('.');
      $(this).val(formattedValue);
    }
  });
  $('#marginPrice').on('input', function() {
    var marginPrice = $(this).val();
    var unformattedValue = marginPrice.replace(/,/g, '');

      var parts = unformattedValue.split('.');
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
      var formattedValue = parts.join('.');
      $(this).val(formattedValue);

  });


  $('#allowedDiscount').on('input', function() {
    var value = $(this).val();
    if (/\./.test(value)) {
      alert('Please enter only integer values.');
      $(this).val('');
    }
  });


});
</script>

@endsection
