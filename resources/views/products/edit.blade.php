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
        <form id="updateProductForm" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
          <div class="row gx-3">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Product Details</span></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Title <span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="title" value="{{ $product->title }}" id="titleInput" />
                <div class="invalid-data" style="display: none;">Please enter a title.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Division<span class="text-danger">*</span></label>
              </div>

              <div class="form-group">
                <select class="form-select select2" name="division_id" id="divisionInput">
                  <option value="">--</option>
                  @foreach ($divisions as $division)
                  <option value="{{ $division->id }}" {{ $product->division_id == $division->id ? 'selected' : '' }}>
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
                <select class="form-select select2" name="brand_id" id="brandInput">
                  <option value="">--</option>
                  @foreach ($suppliers as $sup)
                  <option value="{{ $sup->id }}" {{ $product->brand_id == $sup->id ? 'selected' : '' }}>
                    {{ $sup->brand }}
                  </option>
                  @endforeach
                </select>
                <div class="invalid-data" style="display: none;">Please select a brand.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Model No</label>
              </div>

              <div class="form-group">
                <input class="form-control" type="text" name="model_no" value="{{ $product->modelno }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Part Number</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="part_number" value="{{ $product->part_number }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Description <span class="text-danger">*</span></label>
              </div>

              <div class="form-group">
                <textarea class="form-control" type="text" name="description" value="{{ $product->description }}" rows="1" cols="1" id="descriptionInput">{{ $product->description }}</textarea>
                <div class="invalid-data" style="display: none;">Please enter a description.</div>
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
                    Standard Price Product
                  </option>
                  <option value="custom" {{ $product->product_type == 'custom' ? 'selected' : '' }}>
                    Custom Price Product
                  </option>
                </select>
                <div class="invalid-data" style="display: none;">Please select a product type.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Manager<span class="text-danger">*</span></label>
              </div>

              <div class="form-group">
                <select class="form-select" name="manager_id" id="managerInput">
                  <option value="" selected="">--</option>
                  @foreach ($managers as $key => $row)
                  <option value="{{ $row->id }}" {{$product->manager_id == $row->id? "selected": ""}}>
                    {{ $row->user->name }}
                  </option>
                  @endforeach
                </select>
                <div class="invalid-data" style="display: none;">Please select a manager.</div>
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
                @if(isset($product->image_url))
                <img src="{{ asset('storage/' . $product->image_url) }}" style="width: 60px;" alt="Product Image">

                <input type="checkbox" name="remove_image" id="remove_image"> Remove Image<br>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Product Category<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_category" id="productCategoryInput">
                  <option value="products" @if($product->product_category == 'products') selected @endif>Products</option>
                  <option value="spares" @if($product->product_category == 'spares') selected @endif>Spares</option>
                  <option value="accessories" @if($product->product_category == 'accessories') selected @endif>Accessories</option>
                  <option value="spares" @if($product->product_category == 'consumables') selected @endif>Consumables</option>
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
                  <option value="" {{ $product->status == '' ? 'selected' : '' }}>Select</option>
                  <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
              </div>
            </div>
          </div>

          <div class="row gx-3">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Selling Price Details</span></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Price Basis</label>
              </div>
              <div class="form-group">
                <select class="form-control" name="payment_term" id="priceBaseInput">
                  @foreach($paymentTerms as $paymentTerm)
                  <option value="{{ $paymentTerm->short_code }}" @if($paymentTerm->short_code == $product->price_basis) selected @endif>{{ $paymentTerm->title }}</option>
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
                <select class="form-control" name="currency" id="currencyInput">
                  <option value="">Select Currency</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}" {{ $product->currency == $currency->code ? 'selected' : '' }}>
                    {{ $currency->name }}
                  </option>
                  @endforeach
                </select>
                <div class="invalid-data" style="display: none;">Please select a currency.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Selling Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control " type="text" name="selling_price" id="sellingPrice" value="{{ number_format($product->selling_price, 2) }}">
                <div class="invalid-data" style="display: none;">Please enter a selling price.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">MOSP(%)<span class="text-danger">*</span></label>
              </div>

              <div class="form-group">
                <input class="form-control productMarginPercentage" type="text" name="margin_percentage" id="marginPercentage" value="{{ $product->margin_percent }}">
                <div class="invalid-data" style="display: none;">Please enter a margin percentage.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Margin Price<span class="text-danger">*</span></label>
              </div>

              <div class="form-group">
                <input class="form-control" type="text" name="margin_price" id="marginPrice" value="{{ number_format($product->margin_price, 2) }}" required />
              </div>
            </div>
            <input class="form-control" type="hidden" name="allowed_discount" />
            <input class="form-check-input" type="hidden" name="freeze_discount">
            <div class="col-md-6" id="permissibleDiscountField">
              <div class="form-group">
                <label class="form-label">Permissible Discount</label>
              </div>

              <div class="form-group">
                <input class="form-control productAllowedDiscount" type="number" name="allowed_discount" value="{{ $product->allowed_discount }}" id="allowedDiscount" />
              </div>
            </div>
            <div class="col-md-6" id="freezeDiscount">
              <div class="form-group">
                <label class="form-label">Freeze Discount</label>
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
          </div>

          <div class="row gx-3 mt-4 mb-2">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Buying Price Details</span></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Buying Currency<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control select2" name="buying_currency" required>
                  <option value="">Select Currency</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}" {{(!empty($product->buyingPrice) && isset($product->buyingPrice[0]) && $product->buyingPrice[0]->buying_currency == $currency->code) ? 'selected': ''}}>{{ $currency->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-data" style="display: none;">Please select a buying price currency.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Gross Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="gross_price" id="gross_price" required value="{{!empty($product->buyingPrice) && isset($product->buyingPrice[0])? $product->buyingPrice[0]->gross_price: ''}}" />
                <div class="invalid-data" style="display: none;">Please enter a gross price.</div>
              </div>
            </div>
            <div class="col-md-6" id="purchase_discount_percent">
              <div class="form-group">
                <label class="form-label">Purchase Discount(%)</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="number" name="discount" id="purchase_discount" value="{{!empty($product->buyingPrice) && isset($product->buyingPrice[0])? $product->buyingPrice[0]->discount: ''}}">
                <div class="invalid-data" style="display: none;">Please enter a purchase discount.</div>
              </div>
            </div>
            <div class="col-md-6" id="purchase_discount_price">
              <div class="form-group">
                <label class="form-label">Purchase Discount Amount<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="discount_amount" id="purchase_discount_amount" required value="{{!empty($product->buyingPrice) && isset($product->buyingPrice[0])?$product->buyingPrice[0]->discount_amount: ''}}" />
                <div class="invalid-data" style="display: none;">Please enter a discount price.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Buying Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="buying_price" id="buying_price" required value="{{!empty($product->buyingPrice) && isset($product->buyingPrice[0])? $product->buyingPrice[0]->buying_price: ''}}" />
                <div class="invalid-data" style="display: none;">Please enter a buying price.</div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">

                <select class="form-control " name="purchase_price_duration" id="purchasedurationSelect">
                  <option value="">Select</option>
                  <option value="1" {{(!empty($product->buyingPrice) && isset($product->buyingPrice[0]) && $product->buyingPrice[0]->buyingPriceMonth == 1)? "selected": ""}}>1 Month</option>
                  <option value="3" {{(!empty($product->buyingPrice) && isset($product->buyingPrice[0]) && $product->buyingPrice[0]->buyingPriceMonth == 3)? "selected": ""}}>3 Month</option>
                  <option value="6" {{(!empty($product->buyingPrice) && isset($product->buyingPrice[0]) && $product->buyingPrice[0]->buyingPriceMonth == 6)? "selected": ""}}>6 Month</option>
                  <option value="9" {{(!empty($product->buyingPrice) && isset($product->buyingPrice[0]) && $product->buyingPrice[0]->buyingPriceMonth == 9)? "selected": ""}}>9 Month</option>
                  <option value="12" {{(!empty($product->buyingPrice) && isset($product->buyingPrice[0]) && $product->buyingPrice[0]->buyingPriceMonth == 12)? "selected": ""}}>12 Month</option>
                </select>

                <div class="invalid-data" style="display: none;">Please choose a date.</div>
              </div>

              <div class="form-group" id="purchasedateRangeGroup" style="display: block;">
                <label class="form-label">Dates:</label>
                <span id="purchasedateRange">
                  @if(!empty($product->buyingPrice) && isset($product->buyingPrice[0]))
                  {{$product->buyingPrice[0]->validity_from}} to {{$product->buyingPrice[0]->validity_to}}
                  @endif
                </span>
                <input type="hidden" name="validity_from" id="validity_from" value="{{!empty($product->buyingPrice) && isset($product->buyingPrice[0])? $product->buyingPrice[0]->validity_from: ''}}" />
                <input type="hidden" name="validity_to" id="validity_to" value="{{!empty($product->buyingPrice) && isset($product->buyingPrice[0])? $product->buyingPrice[0]->validity_to: ''}}" />
              </div>
            </div>
              
              <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Is Demo<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                  <input class="form-check-input" type="radio" name="is_demo" id="is_demo" value="1" <?php echo ($product->is_demo == 1) ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="is_demo">
                      Yes
                  </label>
                  <input class="form-check-input" type="radio" name="is_demo" id="is_demo" value="0" <?php echo ($product->is_demo == 0) ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="is_demo">
                      No
                  </label>
              </div>
            </div>
              
          </div>

          <div class="row gx-3">
            <div class="col-4"></div>
            <div class="col-4">
              <button type="submit" class="btn btn-primary mt-5 me-2" id="updateProductBtn">Update Product</button>
              <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='/products'">Back To List</button>
            </div>
            <div class="col-4"></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Product Sales Price History -->
<div class="row">
  <div class="col-xxl-12">
    <div class="card">
      <div class="card-header">
        <h5>Selling Price History</h5>
      </div>
      <div class="card-body">
        <table class="table nowrap w-100 mb-5">
          <thead>
            <tr>
              <th>Sl.No</th>
              <th>Price Basis</th>
              <th>Selling Price</th>
              <th>Mosp</th>
              <th>Created Date</th>
              <th>Price Validity Period</th>
              <th>Created By</th>
            </tr>
          </thead>
          <tbody>
            @php
            $count = 0;
            @endphp
            @foreach ($productPriceHistory as $index => $value)
            <tr>
              <td>{{$index+1}}</td>
              <td>{{$value->price_basis}}</td>
              <td>{{$value->selling_price}} {{$value->currency}} </td>
              <td>{{$value->margin_percent}}%({{$value->margin_price}} {{$value->currency}})</td>
              <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}</td>
              <td> {{ $value->price_valid_from }} to <br>{{$value->price_valid_to }}</td>
              <th>{{$value->user->name}}</th>

            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
<!-- Product Buying Price History -->
<div class="row">
  <div class="col-xxl-12">
    <div class="card">
      <div class="card-header">
        <h5>Purchase Price History</h5>
      </div>
      <div class="card-body">
        <table class="table nowrap w-100 mb-5">
          <thead>
            <tr>
              <th>#</th>
              <th>Gross Price</th>
              <th>Discount</th>
              <th>Buying Price</th>
              <th>Created Date</th>
              <th>Price Validity Period</th>
            </tr>
          </thead>
          <tbody>
            @php
            $count = 0;
            @endphp
            @foreach ($purchasePriceHistory as $index => $value)
            <tr>
              <td>{{$index+1}}</td>
              <td>{{$value->gross_price}} {{$value->buying_currency}}</td>
              <td>{{$value->discount}}%&nbsp;({{$value->discount_amount}} {{$value->buying_currency}})</td>
              <td>{{$value->buying_price}} {{$value->buying_currency}} </td>
              <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y') }}</td>
              <td>{{ $value->validity_from }} to <br>{{$value->validity_to }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    if ($('#validity_from').val() == '' && $('#validity_to').val() == '') {
      setPurchaseDateRange(12);
      $("#purchasedurationSelect").val(12);
    }

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
    $("#purchasedurationSelect").on("change", function(e) {
      let selectedValue = $(this).val();
      setPurchaseDateRange(selectedValue);
    });

    function setPurchaseDateRange(selectedValue) {
      let currentDate = new Date();
      let validity_from = document.getElementById('validity_from');
      let validity_to = document.getElementById('validity_to');
      let dateRangeGroup = document.getElementById('purchasedateRangeGroup');

      if (selectedValue !== "") {
        let startDate = new Date(currentDate);
        let endDate = new Date(currentDate);
        endDate.setMonth(currentDate.getMonth() + parseInt(selectedValue));

        let startDateFormatted = startDate.toISOString().split('T')[0];
        let endDateFormatted = endDate.toISOString().split('T')[0];

        validity_from.value = startDateFormatted;
        validity_to.value = endDateFormatted;

        let dateRange = `${startDateFormatted} to ${endDateFormatted}`;

        document.getElementById('purchasedateRange').innerText = dateRange;
        dateRangeGroup.style.display = 'block';

      } else {
        validity_from.value = '';
        validity_to.value = '';
        document.getElementById('purchasedateRange').innerText = "";
        dateRangeGroup.style.display = 'none';
      }
    }
    $('#purchase_discount').on('input', function() {
      var gross_price = $('#gross_price').val();
      if (gross_price.trim() === '') {
        alert('Please enter the gross price first.');
        $(this).val(''); // Clear the margin price input
      }
    });
    $('#purchase_discount_amount').on('input', function() {
      var gross_price = $('#gross_price').val();
      if (gross_price.trim() === '') {
        alert('Please enter the gross price first.');
        $(this).val(''); // Clear the margin price input
      }
    });

    document.getElementById('gross_price').addEventListener('input', updateBuyingPrice);
    document.getElementById('purchase_discount').addEventListener('input', updateBuyingPrice);
    document.getElementById('purchase_discount_amount').addEventListener('input', updateBuyingPriceWithAmount);

    $('#gross_price, #purchase_discount').on('input', function() {
      updateBuyingPrice();
    });

    function updateBuyingPriceWithAmount() {
      let gross_price = $('#gross_price').val(); // Get the value using jQuery
      let purchase_discount = $('#purchase_discount_amount').val(); // Get the value using jQuery

      let basePrice = parseFloat(gross_price.replace(/,/g, ''));
      let dAmount = parseFloat(purchase_discount.replace(/,/g, ''));

      let buyingPriceInput = $('#buying_price');

      if (!isNaN(basePrice) && !isNaN(dAmount)) {

        let calculatedDPrice = basePrice - dAmount;
        let dpercent = (dAmount / basePrice) * 100;
        dpercent = parseFloat(dpercent).toFixed(2);
        $('#purchase_discount').val(dpercent);

        let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

        buyingPriceInput.val(formattedMarginPrice);
      }
    }


    function updateBuyingPrice() {
      let gross_price = $('#gross_price').val(); // Get the value using jQuery
      let purchase_discount = $('#purchase_discount').val(); // Get the value using jQuery

      let basePrice = parseFloat(gross_price.replace(/,/g, ''));
      let dPercentage = parseFloat(purchase_discount.replace(/,/g, ''));

      let buyingPriceInput = $('#buying_price');

      if (!isNaN(basePrice) && !isNaN(dPercentage)) {

        let calculatedDPrice = basePrice * (dPercentage / 100);
        $('#purchase_discount_amount').val(calculatedDPrice);
        calculatedDPrice = basePrice - calculatedDPrice;
        let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

        buyingPriceInput.val(formattedMarginPrice);
      } else if (!isNaN(basePrice)) {
        let formattedMarginPrice = numberWithCommas(basePrice.toFixed(2));

        buyingPriceInput.val(formattedMarginPrice);
      }
    }

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
      updateMarginPrice();
    });

    $('#allowedDiscount').on('input', function() {
      var value = $(this).val();
      if (/\./.test(value)) {
        alert('Please enter only integer values.');
        $(this).val('');
      }
      updateMarginPrice();
    });

    function validateForm() {
      var isValid = true;

      $('.form-control').removeClass('is-invalid');
      $('.invalid-data').hide();

      var requiredFields = ['#titleInput', '#divisionInput', '#brandInput',
        '#productTypeSelect', '#descriptionInput', '#sellingPrice', '#marginPercentage', '#productcategoryInput',
        '#currencyInput', '#managerInput', '#buying_currency', '#gross_price', '#purchase_discount',
        '#purchase_discount_amount', '#buying_price'
      ];

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

      return isValid;

      // requiredFields.each(function() {
      //   if ($(this).val().trim() === '') {
      //     isValid = false;
      //     $(this).closest('.form-group').find('.invalid-data').text('This field is required.').show();
      //     $(this).addClass('is-invalid');
      //   } else {
      //     $(this).closest('.form-group').find('.invalid-data').hide();
      //     $(this).removeClass('is-invalid');
      //   }
      // });
      // 
    }
    $('#updateProductForm').submit(function(event) {
      var isValid = validateForm();
      if (!isValid) {
        event.preventDefault();
        return;
      }
    });

    $('#titleInput, #divisionInput, #brandInput, #descriptionInput, #productTypeSelect, #priceBaseInput, #currencyInput, #managerInput, #sellingPrice, #marginPercentage, #productCategoryInput, #buying_currency, #gross_price, #purchase_discount, #purchase_discount_amount, #buying_price').on('input change', function() {
      validateField($(this));
    });

    function validateField($field) {
      var isValid = true;
      switch ($field.attr('id')) {
        case 'titleInput':
          isValid = toggleError($field, $field.val().trim() === '', 'Please enter a title.');
          break;
        case 'divisionInput':
        case 'brandInput':
        case 'productTypeSelect':
        case 'currencyInput':
        case 'managerInput':
        case 'productCategoryInput':
          isValid = toggleError($field, $field.val() === '', 'Please select a value.');
          break;
        case 'descriptionInput':
          isValid = toggleError($field, $field.val().trim() === '', 'Please enter a value.');
          break;
        case 'sellingPrice':
          isValid = toggleError($field, !/^\d*\.?\d*$/.test($field.val().replace(/,/g, '')), 'Please enter a valid number.');
          break;
        case 'marginPercentage':
          isValid = toggleError($field, isNaN(parseFloat($field.val())), 'Please enter a valid number.');
          break;
        case 'gross_price':
          isValid = toggleError($field, !/^\d*\.?\d*$/.test($field.val().replace(/,/g, '')), 'Please enter a valid number.');
          break;
        case 'buying_price':
          isValid = toggleError($field, !/^\d*\.?\d*$/.test($field.val().replace(/,/g, '')), 'Please enter a valid number.');
          break;
        case 'purchase_discount':
          isValid = toggleError($field, isNaN(parseFloat($field.val())), 'Please enter a valid number.');
          break;
        case 'purchase_discount_amount':
          isValid = toggleError($field, !/^\d*\.?\d*$/.test($field.val().replace(/,/g, '')), 'Please enter a valid number.');
          break;
        default:
          isValid = toggleError($field, $field.val().trim() === '', 'This field is required.');
          break;
      }
      return isValid;
    }


    function toggleError($field, condition, errorMessage) {
      var $errorElement = $field.closest('.form-group').find('.invalid-data');
      if (condition) {
        $errorElement.text(errorMessage).show();
        $field.addClass('is-invalid');
        return false;
      } else {
        $errorElement.hide();
        $field.removeClass('is-invalid');
        return true;
      }
    }
  });
</script>

@endsection