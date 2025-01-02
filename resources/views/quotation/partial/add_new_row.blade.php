<div class="row gx-3">
  <div class="col-md-9">
    <label class="form-label">Enter Product / Model No / Part No <span class="text-danger">*</span></label>
    <div class="form-group">
      <select class="form-control select2" name="product_item_search" id="product_item_search" disabled>
      </select>
    </div>
  </div>
  <div class="col-md-3">
    <label class="form-label">&nbsp;</label>
    <div class="form-group text-center">
      <a href="javascript:void(0);" id="add-new-product" class="mt-3 d-none" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="fas fa-plus"></i> Create New Product</a>
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
            <p class="currency-label">UnitPrice(AED)</p>
          </th>
          <th>Quantity</th>
          <th>
            <p class="currency-label">Line Total(AED)</p>
          </th>
          <th>Discount(%)</th>
          <th width="12%">Show discount(%) in quote</th>
          <th>
            <p class="currency-label">Total After Discount(AED)</p>
          </th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>

    <input type="hidden" name="customprice" id="customprice">
  </div>
</div>
<script>
$(document).ready(function() {

  var asset_url = `{{ env('APP_URL') }}`;

  $('input[name="sub_delivery_input"]').on('input', function() {
    // You can add any additional logic here if needed
    var subDeliveryInputValue = $(this).val();

    // Trigger the change event for subDropdownContainer
    $('#subDropdownContainer').trigger('change');
  });

  $('#subDropdownContainer').on('change', function() {
    var subDeliveryInputValue = $('input[name="sub_delivery_input"]').val();

    var selectedValue = $(this).val();
    var extraOptions = $(this).find('option:selected').data('extra-options');

    if (extraOptions == '1') {
      $('#subDeliveryInput').show();
    } else {
      $('#subDeliveryInput').hide();
    }
    var selectedCurrency = document.getElementById("currencyDropdown").value.toUpperCase();
    var labels = document.getElementsByClassName("currency-label");
    var labelsTerm = document.getElementsByClassName("currency-label-terms");
    var subDropdown = document.getElementById("subDropdownContainer").value;

    for (var i = 0; i < labels.length; i++) {
      if (labelsTerm[i]) {
        var paymentTermDropdown = document.getElementById("paymentTerm");
        var selectedOption = paymentTermDropdown.options[paymentTermDropdown.selectedIndex];
        var paymentTitle = selectedOption.getAttribute("data-title");

        labelsTerm[i].textContent = "Quoted in" + ' ' + selectedCurrency + '. ' + paymentTitle + '. ' + subDropdown + '. ' + subDeliveryInputValue;

        var categoryDropdown = $('select[name="product[]"]');
      }
    }
  });

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
        unitmargin = productData[selProductId].margin_price/ currencyRate

      } else if (productData[selProductId].currency != 'aed' && currency == 'aed') {
        // other currency to aed = amount * currency rate
        unitprice = productData[selProductId].selling_price * currencyRate;
        unitmargin = productData[selProductId].margin_price * currencyRate;
      } else {
        unitprice = productData[selProductId].selling_price;
        unitmargin = productData[selProductId].margin_price;
      }

      unitprice = parseFloat(unitprice).toFixed(2);
      unitmargin = parseFloat(unitmargin).toFixed(2);


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
      newRow += '<td><input type="number" class="form-control discount" name="discount[]" step="any" min="0" value="' + discount + '"/></td>';
      newRow += '<td>  <input type="hidden" name="discount_status[]" value="0"><input type="checkbox" id="exampleCheckbox" name="discount_option[]" value="1" onchange="updateCheckboxValue(this)"></td>';
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

  $(document).on('input change', '.quantity', function(e) {

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

  // delete item row
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
        let row = $(this).parents('tr');
        let productId = row.find('input[name="item_id[]"]').val();

        customPriceArray = customPriceArray.filter(function(item) {
          return item.product_id !== parseInt(productId);
        });

        removeQuotationRow(row);
        row.remove();


        updateQuotationCharges(customPriceArray);

        calculateOverallTotal();

      }
    });
  });

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
      newRow += '<td><input type="number" class="form-control discount" name="discount[]" step="any" min="0" value="' + discount + '"/></td>';
      newRow += '<td>  <input type="hidden" name="discount_status[]" value="0"><input type="checkbox" id="exampleCheckbox" name="discount_option[]" value="1" onchange="updateCheckboxValue(this)"></td>';
      newRow += '<td><input type="text" class="form-control total-price" name="total_after_discount[]" value="' + total + '" readonly/><p class="text-danger">New Margin: <span class="new-margin-label">' + newmargin + '</span></p></td>';
      newRow += `<td><input type="hidden" name="item_id[]" value="${selProductId}"/>
      <input type="hidden" name="brand_id[]" value="${brandId}"/>
      <input type="hidden" name="product_selling_price[]" value="${sellingPrice}"/>
      <input type="hidden" class="margin-percent" name="margin_percent[]" value="${mosp}"/>
      <input type="hidden" class="allowed-discount" name="allowed_discount[]" value="${allowedDiscount}"/>
      <input type="hidden" name="product_margin[]" value="${marginPrice}"/>
      <input type="hidden" name="product_currency[]" value="${itemCurrency}"/>
      <input type="hidden" name="margin_amount_row[]" value="${newmargin}" class="margin-amount-row">
      <input type="hidden" name="history_id[]" value="${historyDataId}"/>
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
            // Check if the ID already exists in customPriceArray
            const exists = customPriceArray.some(item => item.id === response.customPrices.id);

            if (!exists) {

              customPriceArray.push(response.customPrices);
              const customPriceJSON = JSON.stringify(customPriceArray);

              document.getElementById('customprice').value = customPriceJSON;
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

  // FOR ADD NEW CUSTOM PRODUCT
  document.getElementById('sellingPrice').addEventListener('input', updateMarginPrice);
  document.getElementById('marginPercentage').addEventListener('input', updateMarginPrice);
  $('#sellingPrice, #marginPrice').on('input', function() {
    updateMarginPercentage();
  });

  // FOR ADD NEW PRICE OF CUSTOM PRODUCT
  document.getElementById('sellingPriceHistory').addEventListener('input', updateMarginPriceHistory);
  document.getElementById('marginPercentageHistory').addEventListener('input', updateMarginPriceHistory);
  $('#sellingPriceHistory, #marginPriceHistory').on('input', function() {
    updateMarginPercentageHistory();
  });

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
      '#marginPrice',
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

        productHistoryHtml += '<tr' + (history.is_custom == 1 ? ' style="background-color: #f0f8ff; font-weight: bold;"' : '') + '>' +
        '<td>' +
        '<div class="form-check">' +
        '<input class="form-check-input" id="priceBasisRadio_' + counter + '" type="radio" name="priceBasisRadio" value="' + history.id + '" data-row-index="' + counter + '" data-history-id="' + history.id + '" data-product-title="' + title + '" data-brand-id="' + history.brand_id + '"  data-selling-price="' + history.selling_price + '" data-margin-price="' + history.margin_price + '"data-margin-percent="' + history.margin_percent + '"data-allowed-discount="' + history.product_discount +
         '"data-currency="' + history.currency + '"data-productid="' + history.product_id + '"data-price-basis="' + history.price_basis + '">'
        +'</div>' +
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
let customPriceArray = [];
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
    let priceBasis = $(this).data('price-basis');



    if ($(this).prop('checked')) {
      let selectedCurrency = $('select[name="quote_currency"]').val();

       let selectedDeliveryTerm = $('#paymentTerm').val();

       if (priceBasis !== selectedDeliveryTerm) {
        Swal.fire({
          title: "Mismatch Detected",
          text: "The selected Price Basis does not match the Delivery Term.",
          icon: "warning",
          confirmButtonText: "Okay",
        }).then((result) => {
          if (result.isConfirmed) {
            // Optional: Reset the radio button selection
            $(this).prop('checked', false);
          }
        });
      }

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

function updateMarginPriceHistory() {
  let sellingPriceStr = $('#sellingPriceHistory').val(); // Get the value using jQuery
  let marginPercentageStr = $('#marginPercentageHistory').val(); // Get the value using jQuery

  if (sellingPriceStr.trim() == '' && marginPercentageStr.trim() == '') {
    return;
  }

  let basePrice = parseFloat(sellingPriceStr.replace(/,/g, ''));
  let marginPercentage = parseFloat(marginPercentageStr.replace(/,/g, ''));

  let marginPriceInput = $('#marginPriceHistory');

  if (!isNaN(basePrice) && !isNaN(marginPercentage)) {

    if (marginPercentage > 99) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Margin Percentage should be less than 100 !",
      });
      $('#marginPercentageHistory').val('');
      marginPriceInput.val('');
      return false;

    } else {

      let calculatedMarginPrice = basePrice * (marginPercentage / 100);
      let formattedMarginPrice = numberWithCommas(calculatedMarginPrice.toFixed(2));

      marginPriceInput.val(formattedMarginPrice);

    }
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

function updateMarginPercentageHistory() {
  let sellingPriceStr = $('#sellingPriceHistory').val();
  let marginPriceStr = $('#marginPriceHistory').val();

  if (sellingPriceStr.trim() == '' && marginPriceStr.trim() == '') {
    return;
  }

  let basePrice = parseFloat(sellingPriceStr.replace(/,/g, ''));
  let marginPrice = parseFloat(marginPriceStr.replace(/,/g, ''));

  // console.log('Base Price:', basePrice);
  // console.log('Margin Price:', marginPrice);

  let marginPercentageInput = $('#marginPercentageHistory');

  if (!isNaN(basePrice) && !isNaN(marginPrice) && basePrice !== 0) {
    if (basePrice <= marginPrice) {

      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Selling price should be greater than margin price.!",
      });
      marginPercentageInput.val('');
      return false;

    } else {
      let calculatedMarginPercentage = (marginPrice / basePrice) * 100;
      marginPercentageInput.val(calculatedMarginPercentage.toFixed(2));
    }
  } else {
    marginPercentageInput.val('');
  }
}
console.log(customPriceArray);
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
    formData.append('margin_price', $('input[name=margin_price]').val());
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


    productCustomFieldsArray.forEach((field, index) => {
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

                document.getElementById('customprice').value = customPriceJSON;

                updateQuotationCharges(customPriceArray);
              } else {
                console.error('Error: customPrices is not present in the response');
              }
            },
            error: function(error) {
              console.error('Error fetching custom prices:', error);
            }
          });
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
          modal.find('input[type=text], input[type=number], textarea').val('');
          modal.find('select').val('').trigger('change');

          modal.modal('hide');

        }

      },
    });
  }
}

function calculateOverallTotal() {
  var overallTotal = 0;
  var overallMargin = 0;
  var vatRate = 0.05; // VAT rate of 5%
  var vatIncluded = $('input[name="vat_option"]:checked').val();  // Get VAT option (if checked)
  var sumAfterDiscount = 0;
  var totalMargin = 0;
  var vatAmount = 0;
  var quotationCharges = 0;
  var totalMarginSum = 0;
  var customVatAmount = parseFloat($('#vatAmountLabelService').val());

  // Initialize sum after discount based on the rows
  $('input[name="total_after_discount[]"]').each(function() {
    var rowTotalAfterDiscount = parseFloat($(this).val()) || 0;
    sumAfterDiscount += rowTotalAfterDiscount;
  });

  // Initialize total margin based on the margin amount rows
  $('input[name="margin_amount_row[]"]').each(function() {
    var rowTotalMargin = parseFloat($(this).val()) || 0;
    totalMargin += rowTotalMargin;
  });

  // Initialize quotation charges (existing charges already on the page)
  $('input[name="charge_amount[]"]').each(function() {
    var chargeAmount = parseFloat($(this).val()) || 0;

    // Only include non-disabled charge amount fields
    if (!$(this).prop('disabled')) {
      quotationCharges += chargeAmount;
    }
  });

  // Add manually entered charges to quotation charges (adjust for your use case)
  var manualChargesTotal = 0;
  $('input[name="charge_name[]"]').each(function(index) {
    var chargeName = $(this).val();
    var chargeAmount = parseFloat($('input[name="charge_amount[]"]').eq(index).val()) || 0;

    // Assuming manually entered charges have non-disabled fields
    if (!$(this).prop('disabled')) {
      manualChargesTotal += chargeAmount;  // Add the manual charge to the total
    }
  });

  // Add manual charges to the sum after discount
  sumAfterDiscount += manualChargesTotal;

  // Add the regular charges to the sum after discount
  sumAfterDiscount += quotationCharges;

  // If VAT is included, calculate VAT based on the total
  if (vatIncluded == 1) {
    vatAmount = sumAfterDiscount * vatRate;
    sumAfterDiscount += vatAmount;
  }

  // Set the total value in the input field
  $('#totalValue').val(sumAfterDiscount.toFixed(2));

  // Set the total margin value in the input field
  $('#totalMarginValue').val(totalMargin.toFixed(2));

  // Set VAT amount text and input field
  $('#vatAmountLabel').text(vatAmount.toFixed(2));
  $('input[name="vat_amount"]').val(vatAmount.toFixed(2));

  // If VAT is not included, reset the VAT fields
  if (!vatIncluded) {
    $('#vatAmountLabel').text('0.00');
  }
}

function updateQuotationCharges(customPriceArray, sellingPrice) {
  const quotationChargesContainer = document.getElementById('quotationChargesContainer');
  let manualChargesTotal = 0; // Track the total of manually entered charges

  // Persist custom charge status
  const customChargesSet = new Set(); // Store custom charge names to track them reliably

  // Populate customChargesSet with charges from customPriceArray
  customPriceArray.forEach(item => {
    Object.keys(item).forEach(key => {
      if (key !== 'product_id' && key !== 'id' && item[key] !== null && item[key] !== undefined) {
        customChargesSet.add(key);
      }
    });
  });

  // Fetch existing charges that were rendered on the page
  const existingCharges = [];
  document.querySelectorAll('.row[data-charge-id]').forEach(row => {
    const chargeId = row.getAttribute('data-charge-id');
    const chargeName = row.querySelector('input[name^="charge_name"]').value;
    const chargeAmount = parseFloat(row.querySelector('input[name^="charge_amount"]').value) || 0;
    const isVisible = row.querySelector('input[name^="is_visible"]').checked ? 1 : 0;

    existingCharges.push({ id: chargeId, charge_name: chargeName, charge_amount: chargeAmount, is_visible: isVisible });
  });

  // Clear the container to re-render
  quotationChargesContainer.innerHTML = '';

  // Combine existing charges with the new ones (if any)
  const groupedCharges = {};
  customPriceArray.forEach(item => {
    Object.keys(item).forEach(key => {
      if (key !== 'product_id' && key !== 'id' && item[key] !== null && item[key] !== undefined) {
        if (groupedCharges[key]) {
          groupedCharges[key] += item[key];
        } else {
          groupedCharges[key] = item[key];
        }
      }
    });
  });

  // Add existing charges to grouped charges
  existingCharges.forEach(existingCharge => {
    const key = existingCharge.charge_name;
    groupedCharges[key] = existingCharge.charge_amount;
  });

  // If no charges exist, create a default empty row
  if (Object.keys(groupedCharges).length === 0) {
    groupedCharges[''] = ''; // Adding an empty charge for at least one row
  }

  let index = 0;
  Object.keys(groupedCharges).forEach((key) => {
    const value = groupedCharges[key];
    const rowContainer = document.createElement('div');
    rowContainer.classList.add('row');
    rowContainer.id = 'row-' + index;

    // Determine if the charge is a custom charge
    const isCustomCharge = customChargesSet.has(key);

    // Add the "plus" button only for the first row
    const plusButtonHTML = index === 0 ? `
      <div class="col-sm-1">
        <button type="button" class="btn btn-success" onclick="addQuotationCharge()" style="background-color: #007D88;">
          <i class="fas fa-plus"></i>
        </button>
      </div>
    ` : '';

    // Only show the delete button for manually entered rows (not custom)
    const deleteButtonHTML = !isCustomCharge ? `
      <div class="col-sm-1">
        <button type="button" class="remove-button" onclick="removeQuotationCharge(${index})">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    ` : '';

    // If it's a manual charge (not from customPriceArray), add it to the manualChargesTotal
    if (!isCustomCharge) {
      manualChargesTotal += parseFloat(value) || 0;
    }

    rowContainer.innerHTML = `
      <div class="col-sm-3"></div>
      <div class="col-sm-1">
        <div class="form-check" style="display: flex; justify-content: flex-end;">
          <!-- Hidden input for unchecked state -->
          <input type="hidden" name="is_visible[]" value="0">
          <input type="checkbox" class="form-check-input" name="is_visibles[]" value="1" onchange="updateChargeCheckboxValue(this)"/>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="form-group">
          <input class="form-control charge-name-input title-input" name="charge_name[]" value="${key.replace(/_/g, ' ')}" ${isCustomCharge ? 'disabled' : ''}/>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="form-group">
          <input class="form-control" name="charge_amount[]" placeholder="Amount" value="${value}" ${isCustomCharge ? 'disabled' : ''}/>
        </div>
      </div>
      ${plusButtonHTML}
      ${deleteButtonHTML}
    `;

    quotationChargesContainer.appendChild(rowContainer);
    index++;
  });

  return manualChargesTotal;
}

</script>
