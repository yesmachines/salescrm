<div class="row gx-3">
  <div class="col-md-9">
    <label class="form-label">Enter Product / Model No / Part No <span class="text-danger">*</span></label>
    <div class="form-group">
      @if(!empty($brands))
      <select class="form-control select2" name="product_item_search" id="product_item_search">
        <option value="">-Select Products-</option>
        @foreach($brands as $brand => $products)
        <optgroup label="{{$brand}}">
          @foreach ($products as $item)
          <option value="{{ $item->id }}">
            {{ $item->title }} {{$item->modelno? '/'.$item->modelno: ''}} {{$item->part_number? '/'.$item->part_number: ''}}
          </option>
          @endforeach
        </optgroup>
        @endforeach
      </select>
      @else
      <select class="form-control select2" name="product_item_search" id="product_item_search" disabled>
      </select>
      @endif
    </div>
  </div>
  <div class="col-md-3" style="display: none;">
    <label class="form-label">&nbsp;</label>
    <div class="form-group text-center">

      <a href="javascript:void(0);" id="add-new-product" class="mt-3 {{empty($brands)? 'd-none': ''}}" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="fas fa-plus"></i> Create New Product</a>
    </div>
  </div>
</div>
<div class="row gx-3">
  <div class="col-md-12">
    <table class="table" id="product_item_tbl">
      <thead class="thead-light">
        <tr>
          <th width="25%">Product</th>
          <th>
            <p class="currency-label">UnitPrice({{$quotation->preferred_currency}})</p>
          </th>
          <th>Quantity</th>
          <th>
            <p class="currency-label">Line Total({{$quotation->preferred_currency}})</p>
          </th>
          <th>Discount(%)</th>
          <th>Show discount(%) in quote</th>
          <th>
            <p class="currency-label">Total After Discount({{$quotation->preferred_currency}})</p>
          </th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @if(!$quotationItems->isEmpty())
        @foreach($quotationItems as $item)
        <tr id="irow-{{$item->item_id}}">
          <td>

            @if($item->product->image_url)
            <img src="{{asset('storage/'. $item->product->image_url)}}" width="30" />
            @endif
            <textarea class="form-control" name="item_description[]" rows="2">{{$item->description}}</textarea>
          </td>
          <td><input type="text" class="form-control unit-price" name="unit_price[]" value="{{$item->unit_price}}" />
            <p class="text-danger">MOSP: <span class="mosp-label">{{$item->product->margin_percent}}</span>%</p>
          </td>
          <td><input type="number" class="form-control quantity" name="quantity[]" step="any" min="1" value="{{$item->quantity}}" /></td>
          <td><input type="text" class="form-control subtotal" name="subtotal[]" value="{{$item->subtotal}}" readonly /></td>
          <td><input type="number" class="form-control discount" name="discount[]" min="0" step="any" value="{{$item->discount}}" /></td>
          <td>
            <div class="form-check"> <input type="hidden" name="discount_status[]" value="{{ $item->discount_status }}"><input type="checkbox" class="form-check-input" name="discount_option[]" value="1" onchange="updateCheckboxValue(this)" @if($item->discount_status == 1) checked @endif /></div>
          </td>
          <td><input type="text" class="form-control total-price" name="total_after_discount[]" value="{{$item->total_after_discount}}" readonly />
            <p class="text-danger">New Margin: <span class="new-margin-label">{{$item->margin_price}}</span></p>
          </td>
          <td><input type="hidden" name="item_id[]" value="{{$item->item_id}}" />
            <input type="hidden" name="brand_id[]" value="{{$item->brand_id}}" />
            <input type="hidden" name="product_selling_price[]" value="{{$item->product->selling_price}}" />
            <input type="hidden" class="margin-percent" name="margin_percent[]" value="{{$item->product->margin_percent}}" />
            <input type="hidden" class="allowed-discount" name="allowed_discount[]" value="{{$item->product->allowed_discount}}" />
            <input type="hidden" name="product_margin[]" value="{{$item->product->margin_price}}" />
            <input type="hidden" name="product_currency[]" value="{{$item->product->currency}}" />
            <input type="hidden" name="margin_amount_row[]" value="{{$item->margin_price}}" class="margin-amount-row">
            <input type="hidden" name="history_id[]" value="{{$item->productPriceHistory->id}}" class="margin-amount-row">
            <a href="javascript:void(0);" data-id="{{$item->item_id}}" class="del-item"><i class="fas fa-trash"></i></a>
            @if($item->product && $item->product->product_type === 'custom')
            <a href="javascript:void(0);" data-id="{{$item->item_id}}" data-bs-toggle="modal" data-bs-target="#customFieldsModal">
              <i class="fas fa-edit"></i>
            </a>
            @endif

          </td>
        </tr>
        @endforeach
        @endif
      </tbody>
    </table>

    <input type="hidden" name="customprice" id="customprices">
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

  var asset_url = `{{ env('APP_URL') }}`;

  searchProducts();

  jQuery('#currency_factor').on('input', function(e) {
    Swal.fire({
      title: "Are you sure ?",
      text: "You are sure to change the currency rate !",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, Convert it!"
    }).then((result) => {
      if (result.isConfirmed) {
        // apply new convertion rate to items already selected
        // check for currency conversion
        updateCurrencyConversion();
      }
    });
  });

  jQuery('#supplierDropdown').on('change', function() {
    let selSuppliers = $(this).val();
    let currency = document.getElementById("currencyDropdown").value;

    if (selSuppliers != null && selSuppliers != '' && currency != null && currency != '') {
      $("#product_item_search").removeAttr('disabled');
      $("#add-new-product").removeClass("d-none");
      // enable

      searchProducts();

    } else {
      $("#product_item_search").attr('disabled', 'disabled');
      $("#add-new-product").addClass("d-none");
      // disable
    }
  });

  // on change/ selection of product
  jQuery('#product_item_search').on('change', function(e) {
    e.preventDefault();
    let asset_url = `{{ env('APP_URL') }}`;
    let selProductId = $(this).val();
    let currencyRate = $("#currency_factor").val();
    let currency = document.getElementById("currencyDropdown").value;

    let newRow = '',
    title = '',
    brandId = 0,
    unitprice = 0,
    mosp = 0,
    subtotal = 0,
    discount = 0,
    qty = 1,
    total = 0,
    newmargin = 0;

    if (productData.hasOwnProperty(selProductId)) {
      /*********** CUSTOM PRODUCT **********************/
      if (productData[selProductId].product_type == 'custom') {
        refreshProductHistory(selProductId);
        $('#customModal').modal('show');

        return;
      }
      /***************************************************/
      brandId = productData[selProductId].brand_id;

      title = productData[selProductId].title;
      if (productData[selProductId].modelno) title += ' / ' + productData[selProductId].modelno + '';
      if (productData[selProductId].part_number) title += ' / ' + productData[selProductId].part_number + '';


      if (productData[selProductId].currency == 'aed' && currency != 'aed') {
        // aed to other currency = amount / currency rate
        unitprice = productData[selProductId].selling_price / currencyRate;

      } else if (productData[selProductId].currency != 'aed' && currency == 'aed') {
        // other currency to aed = amount * currency rate
        unitprice = productData[selProductId].selling_price * currencyRate;
      } else {
        unitprice = productData[selProductId].selling_price;
      }

      unitprice = parseFloat(unitprice).toFixed(2);

      mosp = productData[selProductId].margin_percent;

      subtotal = unitprice * qty; // unitprice * quantity (default)
      subtotal = parseFloat(subtotal).toFixed(2);

      total = subtotal - (subtotal * discount / 100);
      total = parseFloat(total).toFixed(2);

      newmargin = subtotal * ((mosp - discount) / 100);
      newmargin = parseFloat(newmargin).toFixed(2);


      let rowExists = $("#irow-" + selProductId);
      if (rowExists) {
        rowExists.remove();
      }
      newRow += '<tr id="irow-' + selProductId + '">';
      newRow += '<td><img src="' + asset_url + 'storage/' + productData[selProductId].image_url + '" width="30" /><textarea class="form-control" name="item_description[]" rows="2">' + title + '</textarea></td>';
      newRow += '<td><input type="text" class="form-control unit-price" name="unit_price[]" value="' + unitprice + '" readonly/><p class="text-danger">MOSP: <span class="mosp-label">' + mosp + '</span>%</p></td>';
      newRow += '<td><input type="number" class="form-control quantity" name="quantity[]" step="any" min="1" value="' + qty + '"/></td>';
      newRow += '<td><input type="text" class="form-control subtotal" name="subtotal[]" value="' + subtotal + '" readonly/></td>';
      newRow += '<td><input type="number" class="form-control discount" name="discount[]" min="0" step="any" value="' + discount + '"/></td>';
      newRow += '<td><input type="hidden" name="discount_status[]" value="0"><input type="checkbox" id="exampleCheckbox" name="discount_option[]" value="1" onchange="updateCheckboxValue(this)"></td>';
      newRow += '<td><input type="text" class="form-control total-price" name="total_after_discount[]" value="' + total + '" readonly/><p class="text-danger">New Margin: <span class="new-margin-label">' + newmargin + '</span></p></td>';
      newRow += `<td><input type="hidden" name="item_id[]" value="${selProductId}"/>
      <input type="hidden" name="brand_id[]" value="${brandId}"/>
      <input type="hidden" name="product_selling_price[]" value="${productData[selProductId].selling_price}"/>
      <input type="hidden" class="margin-percent" name="margin_percent[]" value="${mosp}"/>
      <input type="hidden" class="allowed-discount" name="allowed_discount[]" value="${productData[selProductId].allowed_discount}"/>
      <input type="hidden" name="product_margin[]" value="${productData[selProductId].margin_price}"/>
      <input type="hidden" name="product_currency[]" value="${productData[selProductId].currency}"/>
      <input type="hidden" name="margin_amount_row[]" value="${newmargin}" class="margin-amount-row">
      <a href="javascript:void(0);" data-id="drow-${selProductId}" class="del-item"><i class="fas fa-trash"></i></a>
      </td>`;

      newRow += '</tr>';

      $("#product_item_tbl").find('tbody').append(newRow);
      calculateOverallTotal();
    }
  });

$(document).on('input change', '.quantity, .unit-price', function (e) {

    let row = $(this).closest('tr');

    let unitPrice = parseFloat(row.find('.unit-price').val()) || 0.00;
    let quantity = row.find('.quantity').val() || 0;
    let subTotal = 0.00;
    let discount = parseFloat(row.find('.discount').val()) || 0;
    let totalPrice = 0.00;
    let mosp = parseFloat(row.find('.margin-percent').val()) || 0;
    let quoteMargin = 0.00;
    let labelnewmargin = row.find('.new-margin-label');

    // subtotal change
    subtotal = quantity * unitPrice;
    subtotal = parseFloat(subtotal).toFixed(2);
    row.find('.subtotal').val(subtotal);

    // Total price change
    total = subtotal - (subtotal * discount / 100);
    total = parseFloat(total).toFixed(2);
    row.find('.total-price').val(total);

    // quote margin change
    quoteMargin = subtotal * ((mosp - discount) / 100);
    quoteMargin = parseFloat(quoteMargin).toFixed(2);
    row.find('.margin-amount-row').val(quoteMargin);

    labelnewmargin.html(quoteMargin);
      calculateOverallTotal();
  });

  $(document).on('input change', '.discount', function(e) {

    let row = $(this).closest('tr');

    let unitPrice = parseFloat(row.find('.unit-price').val()) || 0.00;
    let quantity = row.find('.quantity').val() || 0;
    let subTotal = 0.00;
    let discount = parseFloat(row.find('.discount').val()) || 0;
    let totalPrice = 0.00;
    let mosp = parseFloat(row.find('.margin-percent').val()) || 0;
    let allowedDiscount = row.find('.allowed-discount').val() || 0;
    let quoteMargin = 0.00;
    let labelnewmargin = row.find('.new-margin-label');

    if (discount >= mosp) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Discount should be less than MOSP."
      });
      row.find('.discount').val(0);
      return false;
    }

    if (allowedDiscount > 0 && discount > allowedDiscount) {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Discount should be less than permissible discount (" + allowedDiscount + " %)."
      });
      row.find('.discount').val(0);
      return false;
    }

    // subtotal change
    subtotal = quantity * unitPrice;
    subtotal = parseFloat(subtotal).toFixed(2);
    row.find('.subtotal').val(subtotal);

    // Total price change
    total = subtotal - (subtotal * discount / 100);
    total = parseFloat(total).toFixed(2);
    row.find('.total-price').val(total);

    // quote margin change
    quoteMargin = subtotal * ((mosp - discount) / 100);
    quoteMargin = parseFloat(quoteMargin).toFixed(2);
    row.find('.margin-amount-row').val(quoteMargin);

    labelnewmargin.html(quoteMargin);
  });

  var iter = $("#product_item_tbl").find("tbody >tr").length;
  $(document).on('click', '.del-item', function(e) {

    Swal.fire({
      title: "Are you sure?",
      text: "You are sure to delete the product!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if (result.isConfirmed) {
        var row = $(this).closest('tr');
        let productId = row.find('input[name="item_id[]"]').val();

        customPriceArray = customPriceArray.filter(function(item) {
          return item.product_id !== parseInt(productId);
        });
        let quotationId = document.getElementById('quotation_id').value;

        removeQuotationRow(row);
        row.remove();
        const customPriceJSON = JSON.stringify(customPriceArray);
        document.getElementById('customprices').value = customPriceJSON;

        deleteCustomPriceQuote(quotationId,productId);
        updateQuotationCharges(customPriceArray);
        calculateOverallTotal();
      }
    });
  });
  function deleteCustomPriceQuote(quoteId, productId) {

    $.ajax({
      url: `/delete-custom-price-quote/${quoteId}?product_id=${productId}`,
      method: 'DELETE',
      data: {
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        console.log("Product deleted from CustomPriceQuote successfully");
      },
      error: function(xhr, status, error) {
        console.error('Error deleting product from CustomPriceQuote:', error);
      }
    });
  }

  function removeQuotationRow(row) {
    // total amount
    var totalAmountInput = $('input[name="total_value"]');
    var totalAmount = parseFloat(totalAmountInput.val()) || 0;
    // total margin
    var marginAmountInput = $('input[name="total_margin_value"]');
    var marginAmount = parseFloat(marginAmountInput.val()) || 0;

    // reduce row total & update total amount
    var rowTotal = parseFloat(row.find('input[name="total_after_discount[]"]').val()) || 0;
    var newTotalAmount = totalAmount - rowTotal;
    totalAmountInput.val(newTotalAmount.toFixed(2));

    // reduce row margin & update total margin
    var rowMargin = parseFloat(row.find('input[name="margin_amount_row[]"]').val()) || 0;
    var newMarginAmount = marginAmount - rowMargin;
    marginAmountInput.val(newMarginAmount.toFixed(2));

    // is vat available
    var vatRate = 0.05;
    var vatIncluded = $('input[name="vat_option"]:checked').val();

    // sum all row total & reduce deleted row total
    var overallTotal = 0;
    $('input[name="total_after_discount[]"]').each(function() {
      var rowTotalAfterDiscount = parseFloat($(this).val()) || 0;
      overallTotal += rowTotalAfterDiscount;
    });
    overallTotal = overallTotal - rowTotal;
    overallTotal = parseFloat(overallTotal);

    // calculate & update vat amount
    var vatAmount = 0;
    if (vatIncluded == 1) {
      vatAmount = overallTotal * vatRate;
      $('#vatAmountLabel').text(vatAmount.toFixed(2));
    }
  }

  jQuery('.btn-select').on('click', function(e) {
    let selectedRadio = $('input[name="priceBasisRadio"]:checked');
    let newRow = '',
    title = '',
    brandId = 0,
    unitprice = 0,
    mosp = 0,
    subtotal = 0,
    discount = 0,
    qty = 1,
    total = 0,
    newmargin = 0;

    if (selectedRadio.length > 0) {
      let selProductId = selectedRadio.data('productid');
      let sellingPrice = selectedRadio.data('selling-price');
      let marginPrice = selectedRadio.data('margin-price');
      let marginPercent = selectedRadio.data('margin-percent');
      let allowedDiscount = selectedRadio.data('allowed-discount');
      let itemCurrency = selectedRadio.data('currency');
      let historyDataId = selectedRadio.data('history-id');

      //let currencyRate = $("#currency_factor").val();

      brandId = selectedRadio.data('brand-id');
      title = selectedRadio.data('product-title');

      unitprice = sellingPrice;
      unitprice = parseFloat(unitprice).toFixed(2);

      mosp = marginPercent;

      subtotal = unitprice * qty; // unitprice * quantity (default)
      subtotal = parseFloat(subtotal).toFixed(2);

      total = subtotal - (subtotal * discount / 100);
      total = parseFloat(total).toFixed(2);

      newmargin = subtotal * ((mosp - discount) / 100);
      newmargin = parseFloat(newmargin).toFixed(2);


      let rowExists = $("#irow-" + selProductId);
      if (rowExists) {
        rowExists.remove();
      }
      newRow += '<tr id="irow-' + selProductId + '">';
      newRow += '<td><textarea class="form-control" name="item_description[]" rows="2">' + title + '</textarea></td>';
      newRow += '<td><input type="text" class="form-control unit-price" name="unit_price[]" value="' + unitprice + '" readonly/><p class="text-danger">MOSP: <span class="mosp-label">' + mosp + '</span>%</p></td>';
      newRow += '<td><input type="number" class="form-control quantity" name="quantity[]" step="any" min="1" value="' + qty + '"/></td>';
      newRow += '<td><input type="text" class="form-control subtotal" name="subtotal[]" value="' + subtotal + '" readonly/></td>';
      newRow += '<td><input type="number" class="form-control discount" name="discount[]" min="0" step="any" value="' + discount + '"/></td>';
      newRow += '<td>  <input type="hidden" name="discount_status[]" value="0"><input type="checkbox" id="exampleCheckbox" name="discount_option[]" value="1" onchange="updateCheckboxValue(this)"></td>';
      newRow += '<td><input type="text" class="form-control total-price" name="total_after_discount[]" value="' + total + '" readonly/><p class="text-danger">New Margin: <span class="new-margin-label">' + newmargin + '</span></p></td>';
      newRow += `<td><input type="hidden" name="item_id[]" value="${selProductId}"/>
      <input type="hidden" name="brand_id[]" value="${brandId}"/>
      <input type="hidden" name="product_selling_price[]" value="${sellingPrice}"/>
      <input type="hidden" class="margin-percent" name="margin_percent[]" value="${mosp}"/>
      <input type="hidden" class="allowed-discount" name="allowed_discount[]" value="${allowedDiscount}"/>
      <input type="hidden" name="product_margin[]" value="${marginPrice}"/>
      <input type="hidden" name="product_currency[]" value="${itemCurrency}"/>
      <input type="hidden" name="history_id[]" value="${historyDataId}"/>
      <input type="hidden" name="margin_amount_row[]" value="${newmargin}" class="margin-amount-row">
      <a href="javascript:void(0);" data-id="drow-${selProductId}" class="del-item"><i class="fas fa-trash"></i></a>
      </td>`;

      newRow += '</tr>';

      $("#product_item_tbl").find('tbody').append(newRow);
      var customPriceRoute = "{{ route('customPrice', ':id') }}";
      let url = customPriceRoute.replace(':id', historyDataId);

      $.ajax({
        url: url,
        method: 'GET',
        success: function(response) {

          if (response && response.customPrices) {


            const exists = customPriceArray.some(item => item.id === response.customPrices.id);

            if (!exists) {
              customPriceArray.push(response.customPrices);
              const customPriceJSON = JSON.stringify(customPriceArray);


              document.getElementById('customprices').value = customPriceJSON;
              updateQuotationCharges(customPriceArray);


            } else {
              console.log('Duplicate ID detected. Not adding to customPriceArray:', response.customPrices.id);
            }
          }

        },


        error: function(xhr, status, error) {
          console.error('Error fetching custom prices:', error);
        }
      });

      calculateOverallTotal();

      $('#customModal').modal('hide');
    } else {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Something went wrong, Please select atleast a price before proceeding !",
      });
      return;
    }
  });

  $('#saveAdditionalFields').on('click', function() {
    var isValid = true;

    // Remove the is-invalid class and hide the invalid feedback for all elements
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').hide();

    // Define the required fields by their IDs
    var requiredFields = [
      '#sellingPriceHistory',
      '#marginPercentageHistory',
      '#quoteCurrencyHistory',
      '#marginPriceHistory',
      'input[name="product_ids"]',
      '#historyPriceBasis'
    ];

    // Validate each field based on its value
    requiredFields.forEach(function(field) {
      var value = $(field).val().trim();

      // Check if the field value is empty
      if (value === '') {
        $(field).addClass('is-invalid').next('.invalid-feedback').show();
        isValid = false;
      }
    });

    // If all fields pass validation, submit the form
    if (isValid) {
      saveAdditionalFieldsHandler(isValid);
    }
  });

  // // FOR ADD NEW CUSTOM PRODUCT
  // document.getElementById('sellingPrice').addEventListener('input', updateMarginPrice);
  // document.getElementById('marginPercentage').addEventListener('input', updateMarginPrice);
  // $('#sellingPrice, #marginPrice').on('input', function() {
  //   updateMarginPercentage();
  // });
  //
  // // FOR ADD NEW PRICE OF CUSTOM PRODUCT
  // document.getElementById('sellingPriceHistory').addEventListener('input', updateMarginPriceHistory);
  // document.getElementById('marginPercentageHistory').addEventListener('input', updateMarginPriceHistory);
  // $('#sellingPriceHistory, #marginPriceHistory').on('input', function() {
  //   updateMarginPercentageHistory();
  // });
  //



  $('#saveProduct').on('click', function(e) {
    e.preventDefault();
    var isValid = true;

    // Remove the is-invalid class and hide the invalid feedback for all elements
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').hide();

    // Define the required fields by their IDs
    var requiredFields = [
      '#titleInput',
      '#divisionInput',
      '#brandInput',
      '#sellingPrice',
      '#marginPercentage',
      '#marginPriceProduct',
      '#productcategoryInput',
      '#purchasedurationSelect',
      '#currencyInput',
      '#managerInput',
      '#productBuyingCurrency',
      '#gross_price',
      '#purchase_discount',
      '#purchase_discount_amount',
      '#buying_price'
    ];

    // Validate each field based on its value
    requiredFields.forEach(function(field) {
      var value = $(field).val().trim();

      // Check if the field value is empty
      if (value === '') {
        $(field).addClass('is-invalid').next('.invalid-feedback').show();
        isValid = false;
      }
    });

    // If all fields pass validation, submit the form
    if (isValid) {

      if ($("#currencyInput").val() != $("#currencyDropdown").val()) {
        // check allowed to add product currency only in preffered quotation currency
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Please add prices and currency in preffered quotation currency (" + $("#currencyDropdown").val() + ")!"
        });
        $("#currencyInput").val('');
        return false;

      } else {
        createNewProduct(isValid);
      }
    }
  });

});

function formatDate(dateString) {
  var date = new Date(dateString);
  var day = ('0' + date.getDate()).slice(-2);
  var month = ('0' + (date.getMonth() + 1)).slice(-2);
  var year = date.getFullYear();

  var formattedDate = day + '/' + month + '/' + year;
  return formattedDate;
}

function refreshProductHistory(productid) {
  $.ajax({
    url: '{{ route("productHistory", ":id") }}'.replace(':id', productid),
    method: 'GET',
    success: function(response) {

      var productHistoryHtml = `<table class="table">
      <thead><tr><th></th>
      <th>Name</th>
      <th>Price</th>
      <th>Margin Price</th>
      <th>MOSP</th>
      <th>Price Basis</th>
      <th>Added By</th>
      <th>Date</th></tr></thead><tbody>`;
      var counter = 0;

      $.each(response, function(index, history) {

        let title = history.product_title;
        if (history.modelno) title += ' / ' + history.modelno + '';
        if (history.part_number) title += ' / ' + history.part_number + '';

        productHistoryHtml += '<tr>' +
        '<td>' +
        '<div class="form-check">' +
        '<input class="form-check-input" id="priceBasisRadio_' + counter + '" type="radio" name="priceBasisRadio" value="' + history.id + '" data-row-index="' + counter + '" data-history-id="' + history.id + '" data-product-title="' + title + '" data-brand-id="' + history.brand_id + '"  data-selling-price="' + history.selling_price + '" data-margin-price="' + history.margin_price + '"data-margin-percent="' + history.margin_percent + '"data-allowed-discount="' + history.product_discount + '"data-currency="' + history.currency + '"data-productid="' + history.product_id + '">' +
        '</div>' +
        '</td>' +
        '<td> <label class="form-check-label" for="priceBasisRadio_' + counter + '">' + title + '</label></td>' +
        '<td>' + history.selling_price + ' ' + history.currency + '</td>' +
        '<td>' + history.margin_price + ' ' + history.currency + '</td>' +
        '<td>' + history.margin_percent + '%</td>' +
        '<td>' + history.price_basis + '</td>' +
        '<td>' + history.user_name + '</td>' +
        '<td>' + formatDate(history.created_at) + '</td>' +
        //'<td>' + history.currency + '</td>' +
        '<td><input type="hidden" class="form-control" name="product_ids" value="' + history.product_id + '"></td>' +
        '</tr>';

        counter++;
      });

      productHistoryHtml += '</tbody></table>';

      $('#productDetails').html(productHistoryHtml);
      attachEventListeners();
    },
    error: function(xhr, status, error) {
      console.error('Error fetching product history:', error);
    }
  });
}

let customFieldsArray = [];


function attachEventListeners() {

  $('input[name="priceBasisRadio"]').on('click', function() {
    let rowIndex = $(this).data('row-index');
    let historyId = $(this).data('history-id');
    let sellingPrice = $(this).data('selling-price');
    let marginPrice = $(this).data('margin-price');
    let marginPercent = $(this).data('margin-percent');
    let allowedDiscount = $(this).data('allowed-discocunt');
    let currency = $(this).data('currency');
    let productId = $(this).data('productid');

    if ($(this).prop('checked')) {
      let selectedCurrency = $('select[name="quote_currency"]').val();

      if (selectedCurrency !== currency) {
        let errorTxt = 'The product currency (' + currency + ') does not match the preferred currency (' + selectedCurrency + ').Please add a new price';
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: errorTxt,
          //footer: '<div id="additionalText" style="cursor: pointer; color: #007D88;" data-bs-toggle="modal" data-bs-target="#additionalFieldsModal">Do you want to add a new price?</div>',
          willClose: () => {
            $('#customModal').modal('hide');
            $('#additionalFieldsModal').modal('show');
          }
        });
        $(this).prop('checked', false);
        return false;

      } else {
        // Swal.fire({
        //   title: "Are you sure ?",
        //   text: "You are sure to continue with this price !",
        //   icon: "warning",
        //   showCancelButton: true,
        //   confirmButtonColor: "#3085d6",
        //   cancelButtonColor: "#d33",
        //   confirmButtonText: "Yes, Confirm it!"
        // }).then((result) => {
        //   if (result.isConfirmed) {
        //   }
        // });
      }
    }

  });
}

function saveAdditionalFieldsHandler(isValid) {
  if (isValid) {

    $(this).prop('disabled', true);
    let sellingPrice = $('#sellingPriceHistory').val();
    let marginPercentage = $('#marginPercentageHistory').val();
    let quoteCurrency = $('#quoteCurrencyHistory').val();
    let marginPrice = $('#marginPriceHistory').val();
    let productId = $('input[name="product_ids"]').val();
    let priceBasis = $('#historyPriceBasis').val();
    let isDefault = $('#defaultSellingPrice').val();
    let buyingGrossPrice = $('#buying_gross_price').val();
    let buyingPurchaseDiscount = $('#buying_purchase_discount').val();
    let buyingPurchaseDiscountAmount = $('#buying_purchase_discount_amount').val();
    let buyingPrices = $('#buying_prices').val();
    let defaultBuyingPrice = $('#defaultBuyingPrice').val();
    let buyingCurrencyHistory = $('#buyingCurrencyHistory').val();

    let customFields = [];
    $('.custom-field').each(function() {
      customFields.push({
        name: $(this).data('name'),
        value: $(this).val()
      });
    });

    $.ajax({
      url: '{{ route("productHistorySave") }}',
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: {
        selling_price: sellingPrice,
        margin_percentage: marginPercentage,
        quote_currency: quoteCurrency,
        margin_price: marginPrice,
        productId: productId,
        price_basis: priceBasis,
        custom_fields: customsArray,
        default_selling_price:isDefault,

        buying_gross_price:buyingGrossPrice,
        buying_purchase_discount:buyingPurchaseDiscount,
        buying_purchase_discount_amount :buyingPurchaseDiscountAmount,
        buying_prices:buyingPrices,
        default_buying_price:defaultBuyingPrice,
        buying_currency:buyingCurrencyHistory,

      },
      success: function(response) {

        if (response.success) {

          $('#additionalFieldsModal').modal('hide');
          $('#customModal').modal('show');
          refreshProductHistory(productId);
          resetModalValues();
        } else {
          console.error('Error on response:', response);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error saving values:', error);
      },
      complete: function() {
        $('#saveAdditionalFields').prop('disabled', false);
      }
    });
  }
}

function resetModalValues() {
  $('#sellingPriceHistory').val('');
  $('#marginPercentageHistory').val('');
  $('#quoteCurrencyHistory').val('');
  $('#marginPriceHistory').val('');
  $('input[name="product_ids"]').val('');
  $('#priceBasis').val('');
}



function updateCurrencyLabel() {

  let currency = document.getElementById("currencyDropdown").value;
  var selectedCurrency = currency.toUpperCase();

  var labels = document.getElementsByClassName("currency-label");
  var labelsTerm = document.getElementsByClassName("currency-label-terms");

  // conversion rate
  $("#currency_factor").val(0);
  $.get("/get-currency-conversion-rate", {
    currencyCode: currency
  }, function(data, status) {
    if (status == 'success') {
      $("#currency_factor").val(data.standard_rate);
      // check for currency conversion
      updateCurrencyConversion();
    }
  });

  // Labels append with selected currency
  for (var i = 0; i < labels.length; i++) {
    var labelText = labels[i].textContent;
    labelText = labelText.replace(/\([A-Z]+\)/g, '');
    labels[i].textContent = labelText + ' (' + selectedCurrency + ')';

    if (labelsTerm[i]) {
      labelsTerm[i].textContent = "Quoted in" + ' ' + selectedCurrency + '. ';
    }
  }

  // enable product search box
  let selSuppliers = $("#supplierDropdown").val();

  if (selSuppliers != null && selSuppliers != '' && currency != null && currency != '') {

    $("#product_item_search").removeAttr('disabled');
    $("#add-new-product").removeClass("d-none");
    // enable
    searchProducts();
  } else {
    $("#product_item_search").attr('disabled', 'disabled');
    $("#add-new-product").addClass("d-none");
    // disable
  }

}

function updateCurrencyConversion() {
  // check for currency conversion
  let tbl_row = $("#product_item_tbl").find("tbody >tr");
  let currencyRate = $("#currency_factor").val();
  let currency = document.getElementById("currencyDropdown").value;

  if (tbl_row.length > 0) {

    // validate currency conversion
    let otherCurrency = false;
    tbl_row.each(function() {
      let productCurrency = $(this).find('input[name="product_currency[]"]').val();
      if (productCurrency != 'aed' && currency != 'aed' && productCurrency != currency) {
        otherCurrency = true;
      }
    });

    if (otherCurrency) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Currency conversions allowed only to & fro AED."
      });
      document.getElementById("currencyDropdown").value = 0;
      return false;
    }

    // Convert/Update Currencies of Product items
    tbl_row.each(function() {
      let productCurrency = $(this).find('input[name="product_currency[]"]').val(),
      sellingPrice = $(this).find('input[name="product_selling_price[]"]').val(),
      mosp = $(this).find('input[name="margin_percent[]"]').val(),
      quantity = $(this).find('input[name="quantity[]"]').val(),
      discount = $(this).find('input[name="discount[]"]').val();

      let unitprice = 0;
      if (productCurrency == 'aed' && currency != 'aed') {
        // console.log(1)
        // aed to other currency = amount / currency rate
        unitprice = sellingPrice / currencyRate;

      } else if (productCurrency != 'aed' && currency == 'aed') {
        // console.log(2)
        // other currency to aed = amount * currency rate
        unitprice = sellingPrice * currencyRate;
      } else {
        // console.log(3)
        unitprice = sellingPrice;
      }
      unitprice = parseFloat(unitprice).toFixed(2);

      let subtotal = unitprice * quantity; // unitprice * quantity (default)
      subtotal = parseFloat(subtotal).toFixed(2);

      let total = subtotal - (subtotal * discount / 100);
      total = parseFloat(total).toFixed(2);

      let newmargin = subtotal * ((mosp - discount) / 100);
      newmargin = parseFloat(newmargin).toFixed(2);



      $(this).find('input[name="unit_price[]"]').val(unitprice);
      $(this).find('input[name="subtotal[]"]').val(subtotal);
      $(this).find('input[name="total_after_discount[]"]').val(total);
      $(this).find('input[name="margin_amount_row[]"]').val(newmargin);
      $(this).find('.new-margin-label').html(newmargin);
    });

  }
}

var productData = [];

function searchProducts() {
  let selSuppliers = $("#supplierDropdown").val();
  let currency = document.getElementById("currencyDropdown").value;

  if (!selSuppliers && !currency) return false;

  let productDropdown = $("#product_item_search");
  productDropdown.html("");

  $.ajax({
    url: '/fetch-product-models',
    method: 'GET',
    data: {
      // category: selectedCategory,
      supplier_id: selSuppliers
    },
    success: function(resp) {
      let data = resp;
      productData = [];

      if (data) {
        // for dropdown listing
        productDropdown.append('<option value="">-Select Products-</option>');
        for (let brand in data) {
          let products = data[brand];
          let optgroup = $('<optgroup label="' + brand + '">');

          for (let pi in products) {
            let item = products[pi];

            let selopt = item.title;
            if (item.modelno) selopt += ' / ' + item.modelno + '';
            if (item.part_number) selopt += ' / ' + item.part_number + '';
            if (item.product_type) selopt += ' ( ' + (item.product_type).toUpperCase() + ' )';

            optgroup.append('<option value="' + item.id + '">' + selopt + '</option>');

            productData[item.id] = item;
          }

          optgroup.append('</optgroup>');
          productDropdown.append(optgroup);
        }
        productDropdown.val('').trigger('change');

      } else {
        productDropdown.append('<option value="">No models available</option>');
      }
    },
    error: function(error) {
      console.error('Error fetching product models:', error);
    }
  });
}
function createNewProduct(isValid) {

  let asset_url = `{{ env('APP_URL') }}`;

  if (isValid) {
    let formData = new FormData($('#productForm')[0]);

    formData.append('title', $('input[name=title]').val());
    formData.append('division_id', $('select[name=division_id]').val());
    formData.append('brand_id', $('select[name=brand_id]').val());
    formData.append('model_no', $('input[name=model_no]').val());
    formData.append('description', $('textarea[name=description]').val());
    formData.append('product_type', $('input[name=product_type]').val());
    formData.append('payment_term', $('select[name=product_payment_term]').val());
    formData.append('currency', $('select[name=currency]').val());
    formData.append('currency_rate', $('input[name=currency_rate]').val());
    formData.append('manager_id', $('select[name=manager_id]').val());
    formData.append('selling_price', $('input[name=selling_price]').val());
    formData.append('margin_percentage', $('input[name=margin_percentage]').val());
    formData.append('margin_price', $('input[name=product_margin_price]').val());
    formData.append('freeze_discount', $('input[name=freeze_discount]').is(':checked') ? 1 : 0);
    formData.append('image', $('input[name=image]')[0].files[0]);
    formData.append('start_date', $('input[name=start_date]').val());
    formData.append('end_date', $('input[name=end_date]').val());
    formData.append('product_category', $('select[name=product_category]').val());
    formData.append('status', $('input[name=status]').val());
    formData.append('allowed_discount', $('input[name=allowed_discount]').val());
    formData.append('part_number', $('input[name=part_number]').val());

    formData.append('buying_currency', $('select[name=buying_currency]').val());
    formData.append('gross_price', $('input[name=gross_price]').val());
    formData.append('discount', $('input[name=discount]').val());
    formData.append('discount_amount', $('input[name=discount_amount]').val());
    formData.append('buying_price', $('input[name=buying_price]').val());
    formData.append('validity_from', $('input[name=validity_from]').val());
    formData.append('validity_from', $('input[name=validity_from]').val());
    formData.append('is_demo', $('input[name=is_demo]').val());


    customPriceArray.forEach((field, index) => {
      formData.append(`${field.field_name}`, field.value);
    });


    $.ajax({
      url: "{{ route('products.ajaxsave') }}",
      method: "POST",
      data: formData,
      contentType: false,
      processData: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.data) {
          let newProductData = response.data;
          var customPriceRoute = "{{ route('customPrice', ':id') }}";
          let url = customPriceRoute.replace(':id', newProductData.id);

          $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
              if (response && response.customPrices) {
                customPriceArray.push(response.customPrices);
                const customPriceJSON = JSON.stringify(customPriceArray);
                document.getElementById('customprices').value = customPriceJSON;

                updateQuotationCharges(customPriceArray);
              } else {
                console.error('Error: customPrices is not present in the response');
              }
            },
            error: function(error) {
              console.error('Error fetching custom prices:', error);
            }
          });
          $('#myModal').modal('hide');
          let newRow = '',
          title = '',
          brandId = 0,
          unitprice = 0,
          mosp = 0,
          subtotal = 0,
          discount = 0,
          qty = 1,
          total = 0,
          newmargin = 0;

          let selProductId = newProductData.id;

          brandId = newProductData.brand_id;

          title = newProductData.title;
          if (newProductData.modelno) title += ' / ' + newProductData.modelno + '';
          if (newProductData.part_number) title += ' / ' + newProductData.part_number + '';
          if (newProductData.product_type) title += ' ( ' + (newProductData.product_type).toUpperCase() + ') ';

          unitprice = newProductData.selling_price;
          unitprice = parseFloat(unitprice).toFixed(2);

          mosp = newProductData.margin_percent;

          subtotal = unitprice * qty; // Calculate = unitprice * quantity (default)
          subtotal = parseFloat(subtotal).toFixed(2);

          total = subtotal - (subtotal * discount / 100);
          total = parseFloat(total).toFixed(2);

          newmargin = subtotal * ((mosp - discount) / 100);
          newmargin = parseFloat(newmargin).toFixed(2);


          let rowExists = $("#irow-" + selProductId);
          if (rowExists) {
            rowExists.remove();
          }
          let img = '';
          if (newProductData.image_url && newProductData.image_url != null) {
            img += '<img src="' + asset_url + 'storage/' + newProductData.image_url + '" width="30" />';
          }
          newRow += '<tr id="irow-' + selProductId + '">';
          newRow += '<td>' + img + '<textarea class="form-control" name="item_description[]" rows="2">' + title + '</textarea></td>';
          newRow += '<td><input type="text" class="form-control unit-price" name="unit_price[]" value="' + unitprice + '" readonly/><p class="text-danger">MOSP: <span class="mosp-label">' + mosp + '</span>%</p></td>';
          newRow += '<td><input type="number" class="form-control quantity" name="quantity[]" min="1" value="' + qty + '"/></td>';
          newRow += '<td><input type="text" class="form-control subtotal" name="subtotal[]" value="' + subtotal + '" readonly/></td>';
          newRow += '<td><input type="number" class="form-control discount" name="discount[]" step="any" min="0" value="' + discount + '"/></td>';
          newRow += '<td><input type="hidden" name="discount_status[]" value="0"><input type="checkbox" id="exampleCheckbox" name="discount_option[]" value="1" onchange="updateCheckboxValue(this)"></td>';
          newRow += '<td><input type="text" class="form-control total-price" name="total_after_discount[]" value="' + total + '" readonly/><p class="text-danger">New Margin: <span class="new-margin-label">' + newmargin + '</span></p></td>';
          newRow += `<td><input type="hidden" name="item_id[]" value="${selProductId}"/>
          <input type="hidden" name="brand_id[]" value="${brandId}"/>
          <input type="hidden" name="product_selling_price[]" value="${newProductData.selling_price}"/>
          <input type="hidden" class="margin-percent" name="margin_percent[]" value="${mosp}"/>
          <input type="hidden" class="allowed-discount" name="allowed_discount[]" value="${newProductData.allowed_discount}"/>
          <input type="hidden" name="product_margin[]" value="${newProductData.margin_price}"/>
          <input type="hidden" name="product_currency[]" value="${newProductData.currency}"/>
          <input type="hidden" name="margin_amount_row[]" value="${newmargin}" class="margin-amount-row">
          <a href="javascript:void(0);" data-id="drow-${selProductId}" class="del-item"><i class="fas fa-trash"></i></a>
          </td>`;

          newRow += '</tr>';

          $("#product_item_tbl").find('tbody').append(newRow);
          calculateOverallTotal();

          // reset modal
          let modal = $("#myModal");
          console.log("modalll",modal);
          modal.find('input[type=text], input[type=number], textarea').val('');
          modal.find('select').val('').trigger('change');

        }

      },
    });
  }
}

function calculateOverallTotal() {
  var overallTotal = 0;
  var vatRate = 0.05; // VAT rate of 5%
  var vatIncluded = $('input[name="vat_option"]:checked').val(); // Check VAT option
  var sumAfterDiscount = 0;
  var totalMargin = 0;
  var vatAmount = 0;
  var quotationCharges = 0;

  $('input[name="total_after_discount[]"]').each(function () {
    var rowTotalAfterDiscount = parseFloat($(this).val()) || 0;
    sumAfterDiscount += rowTotalAfterDiscount;
  });

  $('input[name="margin_amount_row[]"]').each(function () {
    var rowTotalMargin = parseFloat($(this).val()) || 0;
    totalMargin += rowTotalMargin;
  });

  $('input[name="charge_amount[]"]').each(function () {
    if (!$(this).is(':disabled')) {
      var chargeAmount = parseFloat($(this).val()) || 0;
      quotationCharges += chargeAmount;
    }
  });

  sumAfterDiscount += quotationCharges;

  if (vatIncluded == 1) {
    // VAT is included
    $('#vatSection').show(); // Show VAT-related fields
    var customVatAmount = parseFloat($('input[name="vat_amount"]').val()) || 0;

    if (customVatAmount) {
      vatAmount = customVatAmount; // Use custom VAT if entered
      $('#vatAmountLabel').text(vatAmount.toFixed(2));
    } else {
      vatAmount = sumAfterDiscount * vatRate; // Calculate VAT dynamically
      $('#vatAmountLabel').text(vatAmount.toFixed(2));
    }

    sumAfterDiscount += vatAmount; // Add VAT to total
  } else {
    // VAT is not included
    vatAmount = 0;
    $('#vatSection').hide(); // Hide VAT-related fields
  }

  // Update totals in the form
  $('#totalValue').val(sumAfterDiscount.toFixed(2));
  $('#totalMarginValue').val(totalMargin.toFixed(2));
  $('input[name="vat_amount"]').val(vatAmount.toFixed(2));
}

function updateCheckboxValue(checkbox) {
  // Find the corresponding hidden input element
  const hiddenInput = checkbox.previousElementSibling;

  // Set the value of the hidden input based on checkbox state
  hiddenInput.value = checkbox.checked ? 1 : 0;
}

function updateQuotationCharges(customPriceArray) {

  const quotationChargesContainer = document.getElementById('quotationChargesContainer');
  const existingCharges = [];
  const quotationId = document.getElementById('quotation_id').value;

  // Collect existing charges data from the form
  document.querySelectorAll('.row[data-charge-id]').forEach(row => {
    const chargeId = row.getAttribute('data-charge-id');
    const chargeName = row.querySelector('input[name^="charge_name"]').value.trim();
    const chargeAmount = parseFloat(row.querySelector('input[name^="charge_amount"]').value) || 0;
    const isVisible = row.querySelector('input[name^="is_visible"]').checked ? 1 : 0;

    existingCharges.push({ id: chargeId, charge_name: chargeName, charge_amount: chargeAmount });
  });

  quotationChargesContainer.innerHTML = '';

  const groupedCharges = {};

  // Group charges by name and sum their amounts
  customPriceArray.forEach(item => {
    Object.keys(item).forEach(key => {
      if (key !== 'product_id' && key !== 'id' && key !== 'source' && item[key] !== null && item[key] !== undefined) {
        const formattedKey = key.replace(/_/g, ' '); // Keep key as is, except replacing underscores with spaces

        if (groupedCharges[formattedKey]) {
          groupedCharges[formattedKey].amount += item[key];
        } else {
          groupedCharges[formattedKey] = {
            amount: item[key],
            hasCustomData: item.id && item.product_id ? true : false // Mark if it's custom
          };
        }
      }
    });
  });

  // Add existing charges to grouped charges
  existingCharges.forEach(existingCharge => {
    const chargeName = existingCharge.charge_name;
    if (groupedCharges[chargeName]) {
      groupedCharges[chargeName].amount += existingCharge.charge_amount;
    } else {
      groupedCharges[chargeName] = {
        amount: existingCharge.charge_amount,
        hasCustomData: false // Existing charges are not custom
      };
    }
  });

  $.ajax({
    url: '/quotations-charge-checkbox/' + quotationId,
    method: 'GET',
    success: function (response) {
      const charges = response.charges;
      const quoteVisible = {};

      charges.forEach(charge => {
        // Map database charge name with its visibility status
        const chargeNameInDatabase = charge.charge_name.toLowerCase(); // Get the charge name from the database
        quoteVisible[chargeNameInDatabase] = charge.quote_visible; // Use the raw charge name (with underscores)
      });

      let index = 0;
      Object.keys(groupedCharges).forEach((key) => {
        const value = groupedCharges[key].amount;
        const isCustomPrice = groupedCharges[key].hasCustomData;

        console.log(`Key: ${key}, isCustomPrice: ${isCustomPrice}`);

        const rowContainer = document.createElement('div');
        rowContainer.classList.add('row');
        rowContainer.id = 'row-' + index;

        const normalizedKey = key.toLowerCase().replace(/\s+/g, '_'); // Format the key to match the database format

        const isChecked = quoteVisible[normalizedKey] === 1 ? 'checked' : '';
        const hiddenInputValue = isChecked === 'checked' ? '1' : '0';

        const plusButtonHTML = index === 0 ? `
        <div class="col-sm-1">
          <button type="button" class="btn btn-success" onclick="addQuotationCharge()" style="background-color: #007D88;">
            <i class="fas fa-plus"></i>
          </button>
        </div>` : '';

        const deleteButtonHTML = !isCustomPrice && index > 0 ? `
        <div class="col-sm-1">
          <button type="button" class="remove-button" onclick="removeQuotationCharge(${index})">
            <i class="fas fa-trash"></i>
          </button>
        </div>` : '';

        let checkboxHTML = '';
        // Only add checkbox if it's a custom price
        if (isCustomPrice) {
          checkboxHTML = `
          <div class="col-sm-1">
            <div class="form-check" style="display: flex; justify-content: flex-end;">
              <input type="hidden" name="is_visible[]" value="${hiddenInputValue}">
              <input type="hidden" class="form-check-input" name="is_visibles[]" value="1" onchange="updateChargeCheckboxValue(this)" ${isChecked === 'checked' ? 'checked' : ''} />
            </div>
          </div>`;
        }

        // For manual charge case, change col-sm-3 to col-sm-4 (removes checkbox)
        if (!isCustomPrice) {
          rowContainer.innerHTML = `
          <div class="col-sm-4">
          </div>
          ${checkboxHTML}
          <div class="col-sm-4">
            <div class="form-group">
              <input class="form-control charge-name-input title-input" name="charge_name[]" value="${key}" ${isCustomPrice ? 'disabled' : ''} />
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <input class="form-control" name="charge_amount[]" placeholder="Amount" value="${value}" ${isCustomPrice ? 'disabled' : ''} />
            </div>
          </div>
          ${plusButtonHTML}
          ${deleteButtonHTML}`;
        } else {
          // For custom price case, add the checkbox and keep the original layout
          rowContainer.innerHTML = `
          <div class="col-sm-3">
          </div>
          ${checkboxHTML}
          <div class="col-sm-4">
            <div class="form-group">
              <input class="form-control charge-name-input title-input" name="charge_name[]" value="${key}" ${isCustomPrice ? 'disabled' : ''} />
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <input class="form-control" name="charge_amount[]" placeholder="Amount" value="${value}" ${isCustomPrice ? 'disabled' : ''} />
            </div>
          </div>
          ${plusButtonHTML}
          ${deleteButtonHTML}`;
        }

        quotationChargesContainer.appendChild(rowContainer);
        index++;
      });
    },
    error: function (error) {
      console.log('Error fetching quotation charges:', error);
    }
  });

}

</script>
