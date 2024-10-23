<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding-top:21px;"><span>Optional Items</span></div>

<div id="quotationOptionalContainer">
  <div class="row" id="row-0">
    <div class="col-sm-3">
    </div>
    <div class="col-sm-5">
      <div class="form-group">
        <input class="form-control" name="optional_name[]" placeholder="Optional Item" value="" />
      </div>
    </div>
    <div class="col-sm-1">
      <div class="form-group">
        <input type="text" class="form-control optional-quantity" name="optional_quantity[]" placeholder="Quantity" value="" />
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <input class="form-control optional-amount" name="optional_amount[]" placeholder="Amount" value="" />
      </div>
    </div>
    <div class="col-sm-1">
      <button type="button" class="btn btn-success" onclick="addOptionalItem()" style="background-color: #007D88;">
        <i class="fas fa-plus"></i>
      </button>
    </div>
  </div>
</div>

<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding-top:21px;"><span>Additional Charges</span></div>

<div id="quotationChargesContainer">
  <div class="row" id="row-0">
    <input type="hidden" name="row_charge_ids[]">
    <div class="col-sm-3">
    </div>
    <div class="col-sm-5">
      <div class="form-group">
        <select class="form-control charge-name-input title-input" name="charge_name[]" placeholder="Charge Type" data-row-id="0" style="padding-right:10px;"></select>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <input class="form-control" name="charge_amount[]" placeholder="Amount" value="" />
      </div>
    </div>
    <div class="col-sm-1">
      <button type="button" class="btn btn-success" onclick="addQuotationCharge()" style="background-color: #007D88;">
        <i class="fas fa-plus"></i>
      </button>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-sm-6">
  </div>
  <div class="col-sm-3 text-align-left" style="width: 20%;">
    <label>VAT <span style="color:red">(Include 5%)</span></label>
  </div>:
  <div class="col-sm-3">
    <input class="form-check-input" type="radio" name="vat_option" id="vat_include" value="1" checked>
    <label class="form-check-label" for="vat_include">
      Include
    </label>
    <input class="form-check-input" type="radio" name="vat_option" id="vat_exclude" value="0">
    <label class="form-check-label" for="vat_exclude">
      Exclude
    </label>
    <input class="form-check-input" type="hidden" name="vat_amount">

  </div>
</div>
<div class="row" style="margin-top: 21px">
  <div class="col-sm-6">
  </div>
  <div class="col-sm-3 text-align-left" style="width: 20%;">
    <label class="currency-label form-check-label">
      Vat Amount (AED)
    </label>

  </div>:
  @if (Auth::check() && Auth::user()->email != 'service@yesmachinery.ae')
  <div class="col-sm-3 text-align-left">
    <label class="form-check-label" id="vatAmountLabel"></label>
  </div>
  @else
  <div class="col-sm-3 text-align-left">
    <input type="text" class="form-control" name="vat_service" value="" id="vatAmountLabelService">
  </div>
  @endif
  <!-- <div class="col-sm-3 text-align-left">
  <label class="form-check-label" id="vatAmountLabel"></label>
</div> -->
</div>

<div class="row" style="margin-top: 21px">
  <div class="col-sm-6">
  </div>
  <div class="col-sm-3 text-align-left" style="width: 20%;">
    <label class="currency-label" class="form-check-label">
      Total Amount (AED)</label>
  </div>:
  <div class="col-sm-3 text-align-left">
    <input type="text" id="totalValue" name="total_value" class="form-control" readonly>
    <input type="hidden" id="totalMarginValue" name="total_margin_value" class="form-control">
    <!-- <input type="hidden" name="total_amount" class="form-control" />
      <input type="hidden" name="gross_margin" class="form-control" /> -->
  </div>
</div>
<div class="row" style="padding-top:12px;">
  <div class="col-sm-5">
  </div>
  <div class="col-sm-5 text-align-left" style="width: 28.5%;">
    <label>Do you want to display the total amount in quotation?</label>
  </div>:
  <div class="col-sm-3">
    <input type="radio" id="is_total_yes" name="total_status" value="1" checked>
    <label class="form-check-label" for="is_total_yes">
      yes
    </label>
    <input type="radio" id="is_total_no" name="total_status" value="0">
    <label class="form-check-label" for="is_total_no">
      No
    </label>
  </div>
</div>
<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding-top:21px;"><span>Availability</span></div>
<div class="row" style="margin-top: 21px">
  <div class="col-sm-4">
  </div>
  <div class="col-sm-3 text-align-right">
  </div>
  <div class="col-sm-1 text-align-left" style="width: 11%;">
    <label> <input type="radio" id="delivery-terms1" name="delivery_terms" value="is_stock" required> On Stock</label>
  </div>
  <div class="col-sm-2" style="text-align: left;">
    <label> <input type="radio" id="delivery-terms2" name="delivery_terms" value="out_stock"> Out of stock</label>
  </div>
</div>
<div>
  <div class="row" style="margin-top: 21px;">
    <div class="col-sm-4">
    </div>
    <div class="col-sm-3 text-align-left">
      <label for="">Available Within</label>
    </div>:
    <div class="col-sm-2" style="padding-top:3px;">
      <select class="form-select" name="working_period" id="working_periods">
        <option value="day">Day</option>
        <option value="week">Week</option>
        <option value="month">Month</option>
      </select>
    </div>
    <div class="col-sm-2">
      <div>
        <input class="form-control" type="number" id="delivery-terms-info1" name="delivery_weeks" required>
      </div>
    </div>
  </div>
</div>
<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding:2px;"><span>Payment Terms</span></div>
<div id="payment-cycles">
  @foreach($paymentCycles as $index => $paymentCycle)

  <div class="row">
    <div class="col-sm-6"></div>
    <div class="col-sm-1" style="padding-top:3px;">
      <div class="text-align-left">
        <input type="text" class="form-control" value="{{ $paymentCycle->description }}" name="payment_description[]">
      </div>
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <input type="text" class="form-control" value="{{ $paymentCycle->title }}" name="payment_name[]">
    </div>
    <div class="col-sm-1">
      @if($index === 0)
      <button type="button" class="btn btn-success" id="add-item-btn" style="background-color: #007D88;">
        <i class="fas fa-plus"></i>
      </button>
      @endif
      @if($index > 0)
      <button type="button" onclick="removePayments(this)" class="remove-button">
        <i class="fas fa-trash"></i>
      </button>
      @endif
    </div>
  </div>
  @endforeach
</div>


<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding:2px;"><span>Installation and commissioning</span></div>
<div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-3" style="padding-top:3px;">
      <div class="text-align-left">
        <label class="form-label">Will be done by</label>
      </div>
    </div>
    <div class="col-sm-1" style="width: 10px;">
      :
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <select class="form-select" name="installation_by">
        <option value="" selected>Select...</option>
        <option value="YM engineers">YM engineers</option>
        <option value="both YM & Manufacturer’s Engineers">both YM & Manufacturer’s Engineers</option>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-3" style="padding-top:3px;">
      <div class="text-align-left">
        <label class="form-label">Working Periods (days)</label>
      </div>
    </div>
    <div class="col-sm-1" style="width: 10px;">
      :
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <input type="number" class="form-control" value="" name="installation_periods">
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-3" style="padding-top:3px;">
      <div class="text-align-left">
        <label class="form-label">Hotel accommodation will be</label>
      </div>
    </div>
    <div class="col-sm-1" style="width: 10px;">
      :
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <label><input type="radio" name="install_accommodation" value="Supplier"> Supplier</label>
      <label><input type="radio" name="install_accommodation" value="Buyer">Buyer</label>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-3" style="padding-top:3px;">
      <div class="text-align-left">
        <label class="form-label">Flight tickets to and fro</label>
      </div>
    </div>
    <div class="col-sm-1" style="width: 10px;">
      :
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <label><input type="radio" name="install_tickets" value="Supplier"> Supplier</label>
      <label><input type="radio" name="install_tickets" value="Buyer">Buyer</label>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-3" style="padding-top:3px;">
      <div class="text-align-left">
        <label class="form-label">Local transport from hotel to site</label>
      </div>
    </div>
    <div class="col-sm-1" style="width: 10px;">
      :
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <label><input type="radio" name="install_transport" value="Supplier"> Supplier</label>
      <label><input type="radio" name="install_transport" value="Buyer">Buyer</label>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-3" style="padding-top:3px;">
      <div class="text-align-left">
        <label class="form-label">Buyer Site</label>
      </div>
    </div>
    <div class="col-sm-1" style="width: 10px;">
      :
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <label><input type="radio" name="buyer_site" id="buyer_sites" value="ready"> Buyer is suppose to keep the site ready & necessary materials available for installation</label>
      <label><input type="radio" name="buyer_site" id="buyer_sites" value="not_ready">In Case this is not ready within maximum 30 days from the date of delivery our company is authorize to claim open payments linked to installation
      </label>
    </div>
  </div>

</div>

<div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Quation Terms</span></div>
<div style="padding-top: 22px">
  @foreach($quotationTerms as $index => $quotationTerm)
  <div class="row">
    <div class="col-md-5">
      <div class="form-group" style="padding:3px;">
        <input type="text" class="form-control title" name="term_title[]" value="{{ $quotationTerm->title }}">
      </div>
    </div>
    :
    <div class="col-md-5 text-align-center" style="padding:3px;">
      <input type="hidden" name="payment_title[]" id="paymentTitle" />
      @if ($index === 1)
      <textarea class="form-control term-description" id="quotation_payment-term" data-value="{{ $quotationTerm->description ?? '' }}" name="term_description[]" rows="2">
      {{ $quotationTerm->description ?? '' }}
      </textarea>
      @elseif ($index === 2)
      <textarea class="form-control term-description" id="quotation_terms_delivery" data-value="{{ $quotationTerm->description ?? '' }}" name="term_description[]" rows="2">
      {{ $quotationTerm->description ?? '' }}
      </textarea>
      @elseif ($index === 3)
      <textarea class="form-control term-description" id="result" data-value="{{ $quotationTerm->description ?? '' }}" name="term_description[]" rows="2">
      {{ $quotationTerm->description ?? '' }}
      </textarea>
      @else
      <textarea class="form-control term-description {{ $index === 0 ? 'currency-label-terms' : '' }}" name="term_description[]" rows="2">{{ $quotationTerm->description ?? '' }}</textarea>
      @endif
    </div>
    <div class="col-md-1">
      <button type="button" onclick="removeQuotationTerms(this)" class="remove-button">
        <i class="fas fa-trash"></i>
      </button>
    </div>
  </div>
  @endforeach

</div>
<div class="additional-terms">
  Additional Terms
</div>
<div class="row">
  <input type="hidden" name="row_term_ids[]">
  <div class="col-sm-5">
    <div class="form-group">
      <input type="text" class="form-control title" name="term_title[]" placeholder="Title" value="" />
    </div>
  </div>
  <div class="col-sm-6" style="width: 46%;">
    <div class="form-group">
      <textarea class="form-control" name="term_description[]" placeholder="Description" rows="2"></textarea>
    </div>
  </div>
  <div class="col-sm-1">
    <button type="button" class="btn btn-success" onclick="addQuotationTerms()" style="background-color: #007D88;">
      <i class="fas fa-plus"></i>
    </button>
  </div>
</div>
<div class="row" id="quotationTermsContainer">
</div>



<script>
  $(document).ready(function() {

    $('#add-item-btn').click(function() {
      addItemRow();
      updateTextarea();
    });
    $('#vatAmountLabelService').on('input', function() {
      calculateOverallTotal();
    });

    document.getElementById('durationSelect').addEventListener('change', function() {
      var selectedValue = this.value;
      setPriceValidity(selectedValue);
    });

    $('#delivery-terms-info1, #delivery-terms1, #delivery-terms2,#working_periods').on('input', function() {

      var weeksValue = $('#delivery-terms-info1').val();
      var selectedValue = $('#delivery-terms1').is(':checked') ? 'In Stock' : 'Out of Stock';
      var selectedValueId = $('input[name="delivery_terms"]:checked').val();
      var selectedPeriod = $('select[name="working_period"]').val();
      var selectedPeriod = selectedPeriod + (weeksValue != 1 ? 's' : '');
      if (selectedValueId == 'is_stock') {
        var deliveryText = 'On stock subject to prior sale. Delivery available within ' + weeksValue + ' ' + selectedPeriod;
      } else {
        var deliveryText = 'Out of stock.Production time ' + weeksValue + ' ' + selectedPeriod + ' from the date of PO along with advance payment (if any)';
      }

      $('#quotation_terms_delivery').text(deliveryText);
    });

    initializeAutocomplete(0);

    $(document).on('change keyup', 'input[name="total_after_discount[]"], input[name="charge_amount[]"], input[name="quantity[]"], input[name="subtotal[]"], input[name="discount[]"], input[name="vat_option"], input[name="margin[]"]', function() {
      calculateOverallTotal();
    });
  });

  document.addEventListener("DOMContentLoaded", function() {
    var resultElement = document.getElementById('result');
    document.querySelectorAll('select[name="installation_by"], input[name="installation_periods"], input[name="install_accommodation"], input[name="install_tickets"], input[name="install_transport"], input[name="buyer_site"]').forEach(function(element) {
      element.addEventListener('change', updateResult);
    });


    function updateResult() {

      var installation_by_select = document.querySelector('select[name="installation_by"]');
      var installation_by = installation_by_select.options[installation_by_select.selectedIndex].value;
      var installation_periods = document.querySelector('input[name="installation_periods"]').value;
      var install_accommodation = getSelectedRadioValue('install_accommodation');
      var install_tickets = getSelectedRadioValue('install_tickets');
      var install_transport = getSelectedRadioValue('install_transport');
      var buyerSite = getSelectedRadioValue('buyer_site');


      var sentences = [];
      if (installation_by.trim() !== '') {
        sentences.push('Will be done by ' + installation_by);
      }

      if (installation_periods.trim() !== '') {
        sentences.push('For a period of ' + installation_periods + ' working days. Additional days required for installation and commissioning owing to delays from the client side shall be invoiced at the standard rates.');
      }

      if (install_accommodation.trim() !== '') {
        sentences.push('Hotel accommodation will be ' + install_accommodation + ' scope.');
      }

      if (install_tickets.trim() !== '') {
        sentences.push('Flight tickets to and fro ' + install_tickets + ' scope.');
      }

      if (install_transport.trim() !== '') {
        sentences.push('Local transport from hotel to site and back ' + install_transport + ' scope.');
      }

      if (buyerSite === 'ready') {
        sentences.push('Buyer is supposed to keep the site ready & necessary materials available for installation.');
      } else if (buyerSite === 'not_ready') {
        sentences.push('If, for any reason, the site/space/power/other utilities/materials that are necessary for installation are not ready at the client site within 30 days from the arrival of the machine at the buyer factory, the seller (Our Company) is authorized to invoice the balance payment linked to the installation and commissioning.');
      }
      var resultTextarea = document.getElementById('result');
      resultTextarea.value = sentences.join('\n');
    }

    function getSelectedRadioValue(name) {
      var radios = document.getElementsByName(name);
      for (var i = 0; i < radios.length; i++) {
        if (radios[i].checked) {
          return radios[i].value;
        }
      }
      return '';
    }
  });

  window.onload = function() {
    var paymentDescriptions = document.querySelectorAll('input[name="payment_description[]"]');
    var paymentNames = document.querySelectorAll('input[name="payment_name[]"]');

    // Add event listeners to all payment description and name inputs
    for (var i = 0; i < paymentDescriptions.length; i++) {
      paymentDescriptions[i].addEventListener('input', updateTextarea);
      paymentNames[i].addEventListener('input', updateTextarea);
    }

    updateTextarea();
    setPriceValidity(1);
  };

  function setPriceValidity(selectedValue) {
    var currentDate = new Date();
    var startDateInput = document.getElementById('startDateInput');
    var endDateInput = document.getElementById('endDateInput');
    if (selectedValue !== "") {
      var startDate = new Date(currentDate);
      var endDate = new Date(currentDate);
      endDate.setMonth(currentDate.getMonth() + parseInt(selectedValue));
      var startDateFormatted = startDate.toISOString().split('T')[0];
      var endDateFormatted = endDate.toISOString().split('T')[0];
      startDateInput.value = startDateFormatted;
      endDateInput.value = endDateFormatted;

      var dateRange = startDateFormatted + ' to ' + endDateFormatted;
      document.getElementById('dateRange').innerText = dateRange;
      dateRangeGroup.style.display = 'block';
    } else {
      startDateInput.value = '';
      endDateInput.value = '';
      document.getElementById('dateRange').innerText = "";
      dateRangeGroup.style.display = 'none';
    }
  }

  function addItemRow() {
    var newRow = '<div class="row" style="padding-top:15px;">' +
      '<div class="col-sm-6"></div>' +
      '<div class="col-sm-1" style="display: flex; align-items: center;">' +
      '<div style="text-align: left;">' +
      '<input type="text" class="form-control" name="payment_description[]" value="">' +
      '</div>' +
      '</div>' +
      '<div class="col-sm-4" style="align-items: center;">' +
      '<input  type="text" class="form-control" name="payment_name[]" value="">' +
      '</div>' +
      '<div class="col-sm-1">' +
      '<button type="button" onclick="removePayments(this)" class="remove-button">' +
      '<i class="fas fa-trash"></i>' +
      '</button>' +
      '</div>' +
      '</div>';

    $('#payment-cycles').append(newRow);
    var newPaymentDescription = $('#payment-cycles').find('input[name="payment_description[]"]').last();
    var newPaymentName = $('#payment-cycles').find('input[name="payment_name[]"]').last();

    newPaymentDescription.on('input', updateTextarea);
    newPaymentName.on('input', updateTextarea);

    updateTextarea();
  }

  function removePayments(button) {
    $(button).closest('.row').remove();
    var paymentDescriptions = document.querySelectorAll('input[name="payment_description[]"]');
    var paymentNames = document.querySelectorAll('input[name="payment_name[]"]');

    for (var i = 0; i < paymentDescriptions.length; i++) {
      paymentDescriptions[i].addEventListener('input', updateTextarea);
      paymentNames[i].addEventListener('input', updateTextarea);
    }

    updateTextarea();
  }

  function addQuotationTerms() {

    var newQuotationTerms = `
    <div class="row">
    <div class="col-sm-5">
    <div class="form-group" style="margin-right: -11px;">
    <input type="text" class="form-control title" name="term_title[]" placeholder="Title"/>
    </div>
    </div>
    <div class="col-sm-6" style="width: 46%;">
    <div class="form-group">
    <textarea class="form-control" name="term_description[]" placeholder="Description" rows="2"></textarea>
    </div>
    </div>
    <div class="col-sm-1">
    <button type="button" onclick="removeQuotationTerms(this)" class="remove-button">
    <i class="fas fa-trash"></i>
    </button>
    </div>
    </div>`;
    $('#quotationTermsContainer').append(newQuotationTerms);
  }

  function removeQuotationTerms(button) {
    $(button).closest('.row').remove();
  }

  function addQuotationCharge() {
    var rowId = Date.now();
    var newRows = `
    <div class="row" id="row-${rowId}">
    <div class="col-sm-3"></div>
    <div class="col-sm-5">
    <div class="form-group">
    <select class="form-control charge-name-input title-input" name="charge_name[]" placeholder="Charge Type" data-row-id="${rowId}"></select>
    </div>
    </div>
    <div class="col-sm-3">
    <div class="form-group">
    <input class="form-control" name="charge_amount[]" placeholder="Amount" />
    </div>
    </div>
    <div class="col-sm-1">
    <button type="button" onclick="removeQuotationCharge(${rowId})" class="remove-button">
    <i class="fas fa-trash"></i>
    </button>
    </div>
    </div>`;
    $('#quotationChargesContainer').append(newRows);
    initializeAutocomplete(rowId);
  }

  function addOptionalItem() {
    var rowId = Date.now();
    var newRows = `
    <div class="row" id="row-${rowId}">
    <div class="col-sm-3"></div>
    <div class="col-sm-5">
    <div class="form-group">
    <input class="form-control" name="optional_name[]" placeholder="Optional Item" />
    </div>
    </div>
    <div class="col-sm-1">
    <div class="form-group">
    <input type="text" class="form-control optional-quantity" name="optional_quantity[]" placeholder="Quantity" value="" />
    </div>
    </div>
    <div class="col-sm-2">
    <div class="form-group">
    <input class="form-control optional-amount" name="optional_amount[]" placeholder="Amount" />
    </div>
    </div>
    <div class="col-sm-1">
    <button type="button" onclick="removeOptionalItem(${rowId})" class="remove-button">
    <i class="fas fa-trash"></i>
    </button>
    </div>
    </div>`;
    $('#quotationOptionalContainer').append(newRows);
  }

  function removeOptionalItem(rowId) {
    $('#row-' + rowId).remove();
  }

  function removeQuotationCharge(rowId) {
    var chargeAmount = parseFloat($('#row-' + rowId).find('input[name="charge_amount[]"]').val()) || 0;
    var totalAmount = parseFloat($('input[name="total_value"]').val()) || 0;
    var newTotalAmount = totalAmount - chargeAmount;
    $('input[name="total_value"]').val(newTotalAmount.toFixed(2));
    $('#row-' + rowId).remove();
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

  function initializeAutocomplete(rowId) {
    var chargeNameInput = $(`.charge-name-input[data-row-id="${rowId}"]`);
    chargeNameInput.select2({
      placeholder: 'Charge title',
      allowClear: true,
      ajax: {
        url: '/fetch-charge-names',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            term: params.term
          };
        },
        processResults: function(data) {
          return {
            results: data.map(item => ({
              id: item.title,
              text: item.title
            }))
          };
        },
        cache: true
      },
      minimumInputLength: 2,
      tags: true,
      createTag: function(params) {
        return {
          id: params.term,
          text: params.term,
          newOption: true
        };
      }
    });
    chargeNameInput.next('.select2-container').find('.select2-selection').css({
      'padding-right': '28px'
    });

    chargeNameInput.on('select2:select', function(e) {
      var selectedData = e.params.data;
      if (selectedData.newOption) {

      }
    });
  }

  function updateQuotationTerms() {

    var paymentTerm = $('#paymentTerm').val();
    var parentID = $('#paymentTerm option:selected').data('parent');
    if (!paymentTerm) {
      $('#sub-delivery').hide();
      return;
    }
    $.ajax({
      url: '/delivery-sub-dropdowns/' + parentID,
      type: 'GET',
      success: function(data) {
        $('#subDropdownContainer').html('');

        $('#subDropdownContainer').append('<option value="">--Select--</option>');
        $.each(data, function(index, item) {
          $('#subDropdownContainer').append('<option value="' + item.title + '" data-extra-options="' + item.extra_options + '">' + item.title + '</option>');
        });

        $('#sub-delivery').show();
      },
      error: function(xhr, status, error) {
        console.error(error);
      }
    });
    $('#subDeliveryInput').hide();
    var selectedCurrency = document.getElementById("currencyDropdown").value.toUpperCase();
    var labels = document.getElementsByClassName("currency-label");
    var labelsTerm = document.getElementsByClassName("currency-label-terms");

    for (var i = 0; i < labels.length; i++) {
      var labelText = labels[i].textContent;
      labelText = labelText.replace(/\([A-Z]+\)/g, '');
      labels[i].textContent = labelText + ' (' + selectedCurrency + ')';

      if (labelsTerm[i]) {
        var paymentTermDropdown = document.getElementById("paymentTerm");
        var selectedOption = paymentTermDropdown.options[paymentTermDropdown.selectedIndex];
        var paymentTitle = selectedOption.getAttribute("data-title");

        labelsTerm[i].textContent = "Quoted in" + ' ' + selectedCurrency + '. ';
        if (paymentTitle && selectedOption.value !== "") {
          labelsTerm[i].textContent += paymentTitle;
        }

        var categoryDropdown = $('select[name="product[]"]');
        // initializeModelDropdown(categoryDropdown);
      }
    }

  }

  function updateTextarea() {

    var paymentDescriptions = document.querySelectorAll('input[name="payment_description[]"]');
    var paymentNames = document.querySelectorAll('input[name="payment_name[]"]');

    var termsAndDescriptions = [];

    for (var i = 0; i < paymentDescriptions.length; i++) {
      var paymentDescription = paymentDescriptions[i];
      var paymentName = paymentNames[i].value;
      var termAndDescription = paymentDescription.value + ' ' + paymentName;
      termsAndDescriptions.push(termAndDescription);
    }


    var textareaContent = termsAndDescriptions.join('\n');

    var textarea = document.getElementById('quotation_payment-term');
    textarea.value = textareaContent;
  }
</script>