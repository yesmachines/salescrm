<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding-top:21px;"><span>Additional Charges</span></div>

<div id="quotationChargesContainer">

  @if($quotationCharge->isNotEmpty())
  @foreach ($quotationCharge as $index => $charges)
  <div class="row" data-charge-id="{{ $charges->id}}">
    <input type="hidden" name="row_charge_ids[]" value="{{ $charges->id }}">
    <div class="col-sm-4">

    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <!-- <label for="charge_name[]" class="control-label">{{ $charges->title ?? '' }}</label> -->
        <input type="text" class="form-control charge-name-input title-input" name="charge_name[]" value="{{ $charges->title ?? '' }}">
      </div>
    </div>:
    <div class="col-sm-2">
      <div class="form-group">
        <!-- <label for="charge_name[]" class="control-label">{{ $charges->amount ?? '' }}</label> -->
        <input type="text" class="form-control" name="charge_amount[]" value="{{ $charges->amount ?? '' }}" />
      </div>
    </div>
    <div class="col-sm-1">
      <button type="button" onclick="removeCharge({{ $charges->id }})" class="remove-button">
        <i class="fas fa-trash"></i>
      </button>
    </div>
    @if ($loop->first)
    <div class="col-sm-1">
      <button type="button" class="btn btn-success" onclick="addQuotationCharge()" style="background-color: #007D88;">
        <i class="fas fa-plus"></i>
      </button>
    </div>
    @endif
  </div>
  @endforeach
  @else

  <div class="row">
    <input type="hidden" name="row_charge_ids[]">
    <div class="col-sm-3">

    </div>
    <div class="col-sm-5">
      <div class="form-group">
        <select class="form-control charge-name-input title-input" name="charge_name[]" placeholder="Charge Type" data-row-id="0"></select>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <input class="form-control" name="charge_amount[]" placeholder="Amount" value="{{ $charges->amount ?? '' }}" />
        <div class="suggestions"></div>
      </div>
    </div>
    <div class="col-sm-1">
      <button type="button" class="btn btn-success" onclick="addQuotationCharge()" style="background-color: #007D88;">
        <i class="fas fa-plus"></i>
      </button>
    </div>

  </div>
  @endif
</div>
<div class="row" style="margin-top: 21px;margin-left:89px;">
  <div class="col-sm-5">
    <!-- Empty column to occupy 6 units of the grid -->
  </div>
  <div class="col-sm-3" style="text-align: left">
    <label>VAT <span style="color:red">(Include 5%)</span> </label>
  </div>:
  <div class="col-sm-3">
    <input class="form-check-input" type="radio" name="vat_option" id="vat_include" value="1" <?php echo ($quotation->is_vat == 1) ? 'checked' : ''; ?>>
    <label class="form-check-label" for="vat_include">
      Include
    </label>
    <input class="form-check-input" type="radio" name="vat_option" id="vat_exclude" value="0" <?php echo ($quotation->is_vat == 0) ? 'checked' : ''; ?>>
    <label class="form-check-label" for="vat_exclude">
      Exclude
    </label>
  </div>

</div>

<div class="row" style="margin-top: 21px;margin-left:89px;">
  <div class="col-sm-5">
  </div>
  <div class="currency-label col-sm-3" style="text-align: left">
    <label class="form-check-label currency-label">Vat Amount ({{$quotation->preferred_currency}})</label>
  </div>:
  <div class="col-sm-3">
    <label class="form-check-label" id="vatAmountLabel">{{ $quotation->vat_amount }}</label>
  </div>
  <input class="form-check-input" type="hidden" name="vat_amount" value="{{ $quotation->vat_amount }}">
</div>
<div class="row" style="margin-top: 21px;margin-left:89px;">
  <div class="col-sm-5">
  </div>
  <div class="col-sm-3" style="text-align: left">
    <label class="form-check-label currency-label">
      Total Amount ({{$quotation->preferred_currency}})
    </label>
  </div>:

  <div class="col-sm-3" style="text-align: left;">

    <input type="text" id="totalValue" name="total_value" value="{{$quotation->total_amount}}" class="form-control" readonly>
    <input type="hidden" id="totalMarginValue" name="total_margin_value" value="{{$quotation->gross_margin}}" class="form-control">

    <!-- <input class="form-control" type="hidden" name="total_amount" value="{{$quotation->total_amount}}" />
    <input class="form-control" type="hidden" name="gross_margin" value="{{$quotation->gross_margin}}" /> -->
  </div>

</div>

<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding-top:21px;"><span>Availability</span></div>
<div class="row" style="margin-top: 21px">
  <div class="col-sm-4">
  </div>
  <div class="col-sm-3" style="text-align: right">
  </div>
  <div class="col-sm-1 text-align-left" style="width: 11%;">
    <label> <input type="radio" id="delivery-terms1" name="delivery_terms" value="is_stock" required {{(isset($availability->stock_status) && $availability->stock_status == 'is_stock') ? 'checked' : ''}}> In Stock</label>
  </div>
  <div class="col-sm-2" style="text-align: left;">
    <label> <input type="radio" id="delivery-terms2" name="delivery_terms" value="out_stock" {{(isset($availability->stock_status) && $availability->stock_status == 'out_stock') ? 'checked' : ''}}> Out of stock</label>
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
        <option value="day" {{$availability->working_period == 'day'? 'selected': ''}}>Days</option>
        <option value="week" {{$availability->working_period == 'week'? 'selected': ''}}>Weeks</option>
        <option value="month" {{$availability->working_period == 'month'? 'selected': ''}}>Months</option>
      </select>
    </div>
    <div class="col-sm-2">
      <div>
        <input class="form-control" type="number" id="delivery-terms-info1" name="delivery_weeks" value="{{ isset($availability->working_weeks) ? $availability->working_weeks : '' }}" name="delivery_weeks">
      </div>
    </div>
  </div>


</div>

<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding:2px;">
  <span>PAYMENT TERMS</span>
</div>
<div id="payment-cycles">

  @if($paymentCycleList->isNotEmpty())
  @foreach($paymentCycleList as $paymentCycle)
  <div class="row" data-payment-cycle-id="{{ $paymentCycle->id }}">
    <div class="col-sm-5"></div>
    <div class="col-sm-2">
      <div style="text-align: left;">
        <input type="text" class="form-control" value="{{ $paymentCycle->payment_amount }}" name="payment_description[]" class="form-control">
      </div>
    </div>
    <div class="col-sm-3" style="padding:2px;">
      <input type="text" class="form-control" value="{{ $paymentCycle->title }}" name="payment_name[]">
    </div>

    <div class="col-sm-1">
      <button type="button" onclick="removeQuotationPayment({{ $paymentCycle->id }})" class="remove-button">
        <i class="fas fa-trash"></i>
      </button>
    </div>

    @if ($loop->first)
    <div class="col-sm-1">
      <button type="button" class="btn btn-success" id="add-item-btn" style="background-color: #007D88;">
        <i class="fas fa-plus"></i>
      </button>
    </div>
    @endif
  </div>
  @endforeach
  @else
  @foreach($paymentCycles as $paymentCycle)
  <div class="row" data-payment-cycle-id="{{ $paymentCycle->id }}">
    <div class="col-sm-6"></div>
    <div class="col-sm-1" style="padding-top:3px;">
      <div class="text-align-left">
        <input type="text" class="form-control" value="{{ $paymentCycle->description }}" name="payment_description[]">
      </div>
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <input type="text" class="form-control" value="{{ $paymentCycle->title  }}" name="payment_name[]">
    </div>
    @if ($loop->first)
    <div class="col-sm-1">
      <button type="button" class="btn btn-success" id="add-item-btn" style="background-color: #007D88;">
        <i class="fas fa-plus"></i>
      </button>
    </div>
    @endif
  </div>
  @endforeach
  @endif

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
        <option value="" selected>-Select-</option>
        <option value="YM engineers" {{ $installation && $installation->installation_by === 'YM engineers' ? 'selected' : '' }}>YM engineers</option>
        <option value="both YM & Manufacturer’s Engineers" {{ $installation && $installation->installation_by === 'both YM & Manufacturer’s Engineers' ? 'selected' : '' }}>both YM & Manufacturer’s Engineers</option>
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
      <input type="number" class="form-control" value="{{ $installation ? $installation->installation_periods : '' }}" name="installation_periods">
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
      <label><input type="radio" name="install_accommodation" value="Supplier" {{ $installation ? ($installation->install_accommodation === 'Supplier' ? 'checked' : '') : '' }}> Supplier</label>
      <label><input type="radio" name="install_accommodation" value="Buyer" {{ $installation ? ($installation->install_accommodation === 'Buyer' ? 'checked' : '') : '' }}> Buyer</label>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-3" style="padding-top:3px;">
      <div class="text-align-left">
        <label class="form-label">flight tickets to and fro</label>
      </div>
    </div>
    <div class="col-sm-1" style="width: 10px;">
      :
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <label><input type="radio" name="install_tickets" value="Supplier" {{ $installation ? ($installation->install_tickets === 'Supplier' ? 'checked' : '') : '' }}> Supplier</label>
      <label><input type="radio" name="install_tickets" value="Buyer" {{ $installation ? ($installation->install_tickets === 'Buyer' ? 'checked' : '') : '' }}> Buyer</label>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-3" style="padding-top:3px;">
      <div class="text-align-left">
        <label class="form-label">local transport from hotel to site</label>
      </div>
    </div>
    <div class="col-sm-1" style="width: 10px;">
      :
    </div>
    <div class="col-sm-4" style="padding-top:3px;">
      <label><input type="radio" name="install_transport" value="Supplier" {{ $installation ? ($installation->install_transport === 'Supplier' ? 'checked' : '') : '' }}> Supplier</label>
      <label><input type="radio" name="install_transport" value="Buyer" {{ $installation ? ($installation->install_transport === 'Buyer' ? 'checked' : '') : '' }}> Buyer</label>
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
      <label><input type="radio" name="buyer_site" id="buyer_sites" value="ready" {{ $installation ? ($installation->install_buyer_site === 'ready' ? 'checked' : '') : '' }}> Buyer is suppose to keep the site ready & necessary materials available for installation</label>
      <label><input type="radio" name="buyer_site" id="buyer_sites" value="not_ready" {{ $installation ? ($installation->install_buyer_site === 'not_ready' ? 'checked' : '') : '' }}>In Case this is not ready within maximum 30 days from the date of delivery the Yesmachinery is authorize to claim open payments linked to installation
      </label>
    </div>
  </div>
</div>

<div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Quotation Terms</span></div>
@if($quotationTermsList->isNotEmpty())
@foreach ($quotationTermsList as $index => $quotationTerm)
<div class="row" data-term-id="{{ $quotationTerm->id}}">
  <input type="hidden" name="row_term_ids[]" value="{{ $quotationTerm->id }}">
  <div class="col-sm-5">
    <div class="form-group">
      <input type="text" class="form-control title" name="term_title[]" placeholder="Title" value="{{ $quotationTerm->title ?? '' }}" />
    </div>
  </div>
  <div class="col-sm-5">
    <div class="form-group">
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
      {{ $quotationTerm->description ?? '' }}</textarea>

      @else
      <textarea class="form-control term-description {{ $index === 0 ? 'currency-label-terms' : '' }}" name="term_description[]" rows="2">{{ $quotationTerm->description ?? '' }}</textarea>
      @endif

    </div>
  </div>
  @if ($loop->first)
  <div class="col-sm-1" style="margin-left: 2px;">
    <button type="button" class="btn btn-success" onclick="addQuotationTerms()" style="background-color: #007D88;">
      <i class="fas fa-plus"></i>
    </button>
  </div>
  @endif
  <div class="col-sm-1">
    @if (!$loop->first)
    <button type="button" onclick="removeRow(this)" style="color: #007D88; border: 1px solid #007D88; border-radius: 4px; background-color: transparent; padding: 4px 8px; cursor: pointer;">
      <i class="fas fa-trash"></i>
    </button>
    @endif
  </div>
</div>
@endforeach
@else
<div>
  @foreach($quotationTerms as $index=> $quotationTermsData)
  <div class="row">
    <div class="col-md-5">
      <div class="form-group">
        <input type="text" class="form-control title" name="term_title[]" value="{{ $quotationTermsData->title }}" />
      </div>
    </div>
    :
    <div class="col-md-5" style="align-items: center;">
      <input type="hidden" name="payment_title[]" id="paymentTitle" />

      <textarea class="form-control {{ $index === 0 ? ' currency-label-terms' : '' }}" name="term_description[]" rows="1">{{ $quotationTermsData->description ?? '' }}</textarea>
    </div>
    <div class="col-md-1">
      <button type="button" onclick="removeQuotationTerms(this)" style="color: #007D88; border: 1px solid #007D88; border-radius: 4px; background-color: transparent; padding: 4px 8px; cursor: pointer;">
        <i class="fas fa-trash"></i>
      </button>
    </div>
  </div>
  @endforeach
</div>
<div style="text-align: justify; margin-top: 20px; color: #007D88; padding-bottom: 10px;">
  Additional Terms
</div>
<div class="row">
  <input type="hidden" name="row_term_ids[]">
  <div class="col-sm-5">
    <div class="form-group">
      <input type="text" class="form-control title" name="term_title[]" placeholder="Title" value="" />
    </div>
  </div>
  <div class="col-sm-5">
    <div class="form-group">
      <textarea class="form-control" name="term_description[]" placeholder="Description" rows="1"></textarea>
    </div>
  </div>
  <div class="col-sm-1" style="margin-left: 2px;">
    <button type="button" class="btn btn-success" onclick="addQuotationTerms()" style="background-color: #007D88;">
      <i class="fas fa-plus"></i>
    </button>
  </div>
</div>
@endif
<div class="row" id="quotationTermsContainer">
</div>



<script>
  $(document).ready(function() {

    $(document).on('change keyup', 'input[name="total_after_discount[]"], input[name="charge_amount[]"], input[name="quantity[]"], input[name="subtotal[]"], input[name="discount[]"], input[name="vat_option"], input[name="margin[]"]', function() {
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

      if (selectedValueId == 'is_stock') {
        var deliveryText = 'On stock subject to prior sale. Delivery available within ' + weeksValue + ' ' + selectedPeriod;
      } else {
        var deliveryText = 'Out of stock.Production time ' + weeksValue + ' ' + selectedPeriod + ' from the date of PO along with advance payment (if any)';
      }

      $('#quotation_terms_delivery').text(deliveryText);
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

    document.addEventListener("DOMContentLoaded", function() {
      var resultElement = document.getElementById('result');
      var inputs = document.querySelectorAll('input[type="text"], input[type="number"], input[type="radio"], select');
      inputs.forEach(function(input) {
        input.addEventListener('input', updateResult);
      });

      function getSelectedValue(name) {
        var radios = document.getElementsByName(name);
        for (var i = 0; i < radios.length; i++) {
          if (radios[i].checked) {
            return radios[i].value;
          }
        }
        return '';
      }

      function updateResult() {

        var installation_by_select = document.querySelector('select[name="installation_by"]');
        var installation_by = installation_by_select.options[installation_by_select.selectedIndex].value;
        var installation_periods = document.querySelector('input[name="installation_periods"]').value;
        var install_accommodation = getSelectedValue('install_accommodation');
        var install_tickets = getSelectedValue('install_tickets');
        var install_transport = getSelectedValue('install_transport');
        var buyerSite = getSelectedValue('buyer_site');


        var result = constructSentence(installation_by, installation_periods, install_accommodation, install_tickets, install_transport, buyerSite);
        resultElement.textContent = result;
      }

      function constructSentence(installation_by, installation_periods, install_accommodation, install_tickets, install_transport, buyerSite) {
        var result = '';
        result += 'Will be done by ' + installation_by;
        result += ' for a period of ' + installation_periods + ' working days additional days required for Installation and commissioning owing to delays from client side shall be invoiced at the standard rates';
        result += ' Hotel accommodation will be ' + install_accommodation + ' scope ';
        result += ' Flight tickets to and fro ' + install_tickets + ' scope ';
        result += ' Local transport from hotel to site and back ' + install_transport + ' scope. ';
        if (buyerSite == 'ready') {
          result += 'Buyer is suppose to keep the site ready & necessary materials available for installation.';
        }
        if (buyerSite == 'not_ready') {
          result += 'If, for any reason, the site/space/power/other utilities/materials that are necessary for installation are not ready at the client site within 30 days from the arrival of the machine at the buyer factory, the seller (YES Machinery) is authorized to invoice the balance payment linked to the installation and commissioning.';
        }

        return result;
      }

      updateResult();
    });

    // $('#delivery-terms-info1').on('input', function() {
    //   var selectedValue = $(this).val();
    //   $('#quotation_terms_delivery').text(selectedValue + ' working weeks from the date of po & advance payment if any');
    // });

    setPriceValidity(1);
    initializeAutocomplete(0);
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
  };


  function updateQuotationTerms() {
    var selectedCurrency = document.getElementById("currencyDropdown").value.toUpperCase();
    var labels = document.getElementsByClassName("currency-label");
    var labelsTerm = document.getElementsByClassName("currency-label-terms");

    for (var i = 0; i < labels.length; i++) {
      if (labelsTerm[i]) {
        var paymentTermDropdown = document.getElementById("paymentTerm");
        var selectedOption = paymentTermDropdown.options[paymentTermDropdown.selectedIndex];
        var paymentTitle = selectedOption.getAttribute("data-title");

        labelsTerm[i].textContent = "Quoted in" + ' ' + selectedCurrency + '.' + paymentTitle;

        var categoryDropdown = $('select[name="product[]"]');
        // initializeModelDropdown(categoryDropdown);
      }
    }
  }

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

  function addItemRow() {
    var newRow = '<div class="row" style="padding-top:15px;">' +
      '<div class="col-sm-5"></div>' +
      '<div class="col-sm-2" style="display: flex; align-items: center;">' +
      '<div style="text-align: left;">' +
      '<input type="text" class="form-control" name="payment_description[]" value="">' +
      '</div>' +
      '</div>' +
      '<div class="col-sm-3" style="align-items: center;">' +
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
  $('#add-item-btn').click(function() {
    addItemRow();
    updateTextarea();
  });

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
  <div class="col-sm-6" style="width:43%">
  <div class="form-group">
  <input type="text" class="form-control title" name="term_title[]" placeholder="Title"/>
  </div>
  </div>
  <div class="col-sm-6" style="width:43%">
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
  <div class="col-sm-4"></div>
  <div class="col-sm-3">
  <div class="form-group">
  <select class="form-control charge-name-input title-input" name="charge_name[]" placeholder="Charge Type" data-row-id="${rowId}"></select>
  </div>
  </div>
  <div class="col-sm-2">
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

  function removeQuotationCharge(rowId) {
    var chargeAmount = parseFloat($('#row-' + rowId).find('input[name="charge_amount[]"]').val()) || 0;
    var totalAmount = parseFloat($('input[name="total_value"]').val()) || 0;
    var newTotalAmount = totalAmount - chargeAmount;
    $('input[name="total_value"]').val(newTotalAmount.toFixed(2));
    $('#row-' + rowId).remove();
  }

  function removeCharge(rowId) {
    // Find the row with the specified data-charge-id attribute
    var rowToRemove = $('[data-charge-id="' + rowId + '"]');

    // Remove the row from the DOM
    rowToRemove.remove();
  }

  function removeQuotationPayment(paymentCycleId) {
    // Find the payment row with the specified data-payment-cycle-id attribute
    var paymentRowToRemove = $('[data-payment-cycle-id="' + paymentCycleId + '"]');

    // Remove the payment row from the DOM
    paymentRowToRemove.remove();
  }

  function removeQuotationRow(button) {
    var row = $(button).closest('.row');
    var totalAmountInput = $('input[name="total_value"]');
    var totalAmount = parseFloat(totalAmountInput.val()) || 0;
    var overallTotal = 0;
    var vatRate = 0.05;
    var sumAfterDiscount = 0;
    var rowTotal = parseFloat(row.find('input[name="total_after_discount[]"]').val()) || 0;
    var newTotalAmount = totalAmount - rowTotal;
    totalAmountInput.val(newTotalAmount.toFixed(2));
    row.remove();
    $('input[name="total_after_discount[]"]').each(function() {
      var rowTotalAfterDiscount = parseFloat($(this).val()) || 0;
      overallTotal += rowTotalAfterDiscount;
      vatAmount = overallTotal * vatRate;
      $('#vatAmountLabel').text(vatAmount.toFixed(2));

    });
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
        console.log('Manually typed charge name:', selectedData.text);
      }
    });
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

  // function updateCurrencyLabel() {
  //   var selectedCurrency = document.getElementById("currencyDropdown").value.toUpperCase();
  //   var labels = document.getElementsByClassName("currency-label");
  //   var labelsTerm = document.getElementsByClassName("currency-label-terms");

  //   for (var i = 0; i < labels.length; i++) {
  //     var labelText = labels[i].textContent;
  //     labelText = labelText.replace(/\([A-Z]+\)/g, '');
  //     labels[i].textContent = labelText + ' (' + selectedCurrency + ')';

  //     if (labelsTerm[i]) {
  //       var paymentTermDropdown = document.getElementById("paymentTerm");
  //       var selectedOption = paymentTermDropdown.options[paymentTermDropdown.selectedIndex];
  //       var paymentTitle = selectedOption.getAttribute("data-title");

  //       labelsTerm[i].textContent = "Quoted in" + ' ' + selectedCurrency + '.' + paymentTitle;

  //       var categoryDropdown = $('select[name="product[]"]');

  //     }
  //   }
  // }
  //$(document).ready(function() {
  // $('#modelDropdown').select2({
  //   placeholder: "Search model",
  //   allowClear: true
  // });

  // var selectedCategories = [];
  // var selectedModelId = [];
  // var row = $(this);
  // row.find('input[name="item_ids[]"]').each(function() {
  //   var itemId = $(this).val();
  //   selectedModelId.push(itemId);
  // });
  // $('select[name="supplier_id[]"]').change(function() {
  //   var selectedSupplierId = $(this).val();
  //   $('select[name="product[]"]').each(function() {
  //     var productValues = $(this).val();
  //     selectedCategories.push(productValues);
  //   });
  //   fetchModels(selectedCategories, selectedSupplierId, selectedModelId)

  // });



  // var responseDatas;

  // function fetchModels(selectedCategory, selectedSupplierId, selectedModelIds) {
  //   var quotationId = $('#quotation_id').val();
  //   var selectedCategories = [];
  //   $('select[name="product[]"]').each(function() {
  //     var productValues = $(this).val();
  //     selectedCategories.push(productValues);
  //   });
  //   var selectedSuppliers = [];
  //   $('select[name="supplier_id[]"]').each(function() {
  //     var supplierValues = $(this).val();
  //     selectedSuppliers.push(supplierValues);
  //   });
  //   var previouslySelectedModelIds = [];
  //   $('.modelno-select').each(function() {
  //     previouslySelectedModelIds.push($(this).val());
  //   });

  //   $.ajax({
  //     url: '/fetch-edit-models/' + quotationId,
  //     method: 'GET',
  //     data: {
  //       selectedCategory: selectedCategories,
  //       selectedSupplier: selectedSuppliers
  //     },
  //     success: function(data) {
  //       var modelnoDropdowns = $('.modelno-select');
  //       responseDatas = data;

  //       modelnoDropdowns.empty();

  //       if (data.length > 0) {

  //         modelnoDropdowns.each(function(index, dropdown) {
  //           var dropdownElement = $(dropdown);
  //           dropdownElement.empty();
  //           var categoryData = data[index];
  //           var category = categoryData.category;
  //           var modelsBySupplier = categoryData.models;

  //           // Iterate over suppliers in the current category
  //           Object.keys(modelsBySupplier).forEach(function(supplierName) {
  //             var supplierModels = modelsBySupplier[supplierName];
  //             var optgroup = $('<optgroup label="' + supplierName + '">');
  //             optgroup.append('<option value="" disabled selected>Select Model</option>');

  //             // Iterate over models for the current supplier
  //             supplierModels.forEach(function(model) {
  //               optgroup.append('<option value="' + model.id + '">' + model.title + '</option>');
  //             });

  //             dropdownElement.append(optgroup);
  //           });
  //           dropdownElement.on('change', function() {
  //             if ($(this).val() === 'product_modal') {
  //               var row = $(this).closest('.row');
  //               var categorySelect = row.find('.category-select');
  //               var categoryValue = categorySelect.val();
  //               $('#myModal').modal('show');
  //               $('#productcategoryInput').val(categoryValue);
  //               $(this).val('');
  //             }
  //           });
  //           var selectedId = selectedModelIds[index];
  //           if (selectedId) {
  //             dropdownElement.val(selectedId);
  //           }
  //         });

  //         modelnoDropdowns.on('change', function() {
  //           if ($(this).val() === 'product_modal') {
  //             var row = $(this).closest('.row');
  //             var categorySelect = row.find('.category-select');
  //             var categoryValue = categorySelect.val();
  //             $('#myModal').modal('show');
  //             $('#productcategoryInput').val(categoryValue);
  //             $(this).val('');
  //           }
  //         });
  //       } else {
  //         var categoryDropdown = $('select[name="product[]"]');
  //         initializeModelDropdown(categoryDropdown);

  //       }
  //     },

  //     error: function(error) {
  //       console.error('Error fetching edit models:', error);
  //     }
  //   });
  // }
  // $('input.margin-price').each(function() {
  //   var marginPrice = $(this).val();
  //   var displayElement = $(this).closest('.row').find('.display-margin-price');
  //   displayElement.text('MARGIN PRICE: ' + marginPrice).css({
  //     'color': 'red',
  //     'font-size': '13px'
  //   });

  // });
  // $('input.margin-percent').each(function() {
  //   var marginPercentSpan = $(this).val();
  //   var displayElement = $(this).closest('.row').find('.display-margin-percent');
  //   displayElement.text('MOSP (%): ' + marginPercentSpan).css({
  //     'color': 'red',
  //     'font-size': '13px',
  //     'display': 'block'
  //   });

  // });

  // $('input.quote-margin-price').each(function() {
  //   var quoteMarginPrice = $(this).val();
  //   var displayElement = $(this).closest('.row').find('.quote-margin-price');
  //   displayElement.text('Quote Margin: ' + quoteMarginPrice).css({
  //     'color': 'green',
  //     'font-size': '13px'
  //   });

  // });



  // var selectedCategory = [];
  // var selectedItemIds = [];
  // var row = $(this);
  // var selectedSupplierId = row.find('select[name="supplier_id[]"]').val();

  // var category = row.find('input[name="category[]"]').val();
  // selectedCategory.push(category);
  // row.find('input[name="item_ids[]"]').each(function() {
  //   var itemId = $(this).val();
  //   selectedItemIds.push(itemId);
  // });

  // fetchModels(selectedCategory, selectedSupplierId, selectedItemIds);


  //initializeAutocomplete(0);
  // $(document).on('change', '.category-select', function() {
  //   var row = $(this).closest('.row');

  //   var quoteMarginInput = row.find('.quote-margin-price');
  //   var marginSpan = row.find('.display-margin-price');
  //   var marginPercentSpan = row.find('.display-margin-percent');

  //   quoteMarginInput.hide();
  //   marginSpan.hide();
  //   marginPercentSpan.hide();

  //   initializeModelDropdown($(this));
  // });

  // $('#cancelProduct').on('click', function() {
  //   fetchModels(selectedCategory, selectedSupplierId, selectedItemIds);
  //   $('#customModal').modal('hide');
  // });
  // var paymentTerm;

  // $(document).on('change', '.modelno-select', function() {
  //   var selectedModelId = $(this).val();
  //   var row = $(this).closest('.row');
  //   var unitPriceInput = row.find('.unit-price');
  //   var quantityInput = row.find('.quantity');
  //   var discountInput = row.find('.discount');
  //   var subtotalInput = row.find('.subtotal');
  //   var totalInput = row.find('.total');
  //   var suppliersValue = row.find('.suppliersValue');
  //   var quoteMarginInput = row.find('.quote-margin-price');
  //   var marginPrice = row.find('.margin');
  //   var marginSpan = row.find('.display-margin-price');
  //   var marginPercentSpan = row.find('.display-margin-percent');
  //   var marginPercentInput = row.find('.margin-percent');
  //   var quantityAlert = row.find('.warning-message');
  //   var itemDescription = row.find('.product-description');
  //   var productCurrency = row.find('.product-currency');
  //   var productCurrencyConvertFlag = row.find('.product-currency-convert');
  //   var quoteCurrency = $('#currencyDropdown').val();
  //   var currencyConversion = $('#currencyConvertion').val();

  //   quantityAlert.text('');
  //   marginSpan.text('');
  //   marginPercentSpan.text('');
  //   quoteMarginInput.text('');
  //   discountInput.val('');



  //   discountInput.off('input').on('input', function() {

  //     var discount = parseFloat(discountInput.val()) || 0;
  //     var subtotal = parseFloat(subtotalInput.val()) || 0.00;
  //     var total = subtotal - (subtotal * discount / 100);
  //     totalInput.val(total.toFixed(2));

  //     if (selectedModelData && discount > selectedModelData.allowed_discount && selectedModelData.product_type != 'custom') {
  //       quantityAlert.text('The entered discount cannot exceed ' + selectedModelData.allowed_discount + ', please contact admin')
  //         .css({
  //           'font-size': '12px',
  //           'color': 'red'
  //         });
  //       discountInput.val("");
  //     }
  //   });

  //   if (!selectedModelId) {
  //     unitPriceInput.val(0.00);
  //     quantityInput.val(0);
  //     discountInput.val(0);
  //     subtotalInput.val(0.00);
  //     totalInput.val(0.00);
  //     quoteMarginInput.val(0.00);

  //     return;
  //   }


  //   if (responseData) {

  //     var selectedModelData;

  //     responseData.forEach(function(supplier) {
  //       // console.log(supplier);
  //       var modelsArray = supplier.models;
  //       var model = modelsArray.find(function(model) {
  //         return model.id == selectedModelId;
  //       });

  //       if (model) {
  //         selectedModelData = model;
  //         return false;
  //       }
  //     });
  //   } else {

  //     var selectedModelData;
  //     responseDatas.forEach(function(supplier) {
  //       // console.log(supplier);
  //       Object.values(supplier.models).forEach(function(modelsArray) {
  //         var model = modelsArray.find(function(model) {
  //           return model.id == selectedModelId;
  //         });

  //         if (model) {
  //           selectedModelData = model;
  //           return false;
  //         }

  //       });
  //     });
  //   }

  //   if (selectedModelData) {


  //     itemDescription.val(selectedModelData.description);
  //     unitPriceInput.val(selectedModelData.selling_price);
  //     suppliersValue.val(selectedModelData.supplier_name);
  //     productCurrency.val(selectedModelData.currency);
  //     marginSpan.text('MARGIN PRICE: ' + selectedModelData.margin_price)
  //       .css({
  //         'font-size': '12px',
  //         'color': 'red'
  //       });
  //     marginPercentSpan.text('MOSP (%): ' + selectedModelData.margin_percent)
  //       .css({
  //         'font-size': '12px',
  //         'color': 'red',
  //         'display': 'block'
  //       });

  //   }

  //   if (quoteCurrency != productCurrency.val()) {
  //     productCurrencyConvertFlag.val(1);
  //     alert('Your selected quotation currency does not match with your product currency. Please apply currency conversion.');
  //     //return false;
  //   }
  //   if (selectedModelData.currency === 'aed') {
  //     row.find('.customCurrencySection').show();
  //     var selectedCurrency = $('select[name="quote_currency"]').val();

  //     $.ajax({
  //       url: '/get-currency-conversion-rate',
  //       method: 'GET',
  //       data: {
  //         currencyCode: selectedCurrency
  //       },
  //       success: function(data) {
  //         console.log(data);
  //         var defaultCurrencyRate = data.standard_rate;
  //         row.find('.currencyConvertion').val(defaultCurrencyRate);
  //       },
  //       error: function(error) {
  //         console.error('Error fetching default currency rate:', error);
  //       }
  //     });
  //   } else {
  //     row.find('.customCurrencySection').hide();
  //   }


  //   var selectedCurrency = $('select[name="quote_currency"]').val();

  //   if (selectedModelData.product_type == 'standard' && selectedCurrency == 'aed') {

  //     row.find('.text-warning').hide();
  //   } else {
  //     row.find('.text-warning').show();
  //   }
  //   $(document).on('click', '#applyCurrency', function() {
  //     var row = $(this).closest('.row');
  //     var unitPriceInput = row.find('.unit-price');
  //     var quantityInput = row.find('.quantity');
  //     var discountInput = row.find('.discount');
  //     var subtotalInput = row.find('.subtotal');
  //     var totalInput = row.find('.total');
  //     var quoteMargin = row.find('.quote-margin-price');
  //     quoteMargin.hide();
  //     quantityInput.val(0);
  //     discountInput.val(0);
  //     subtotalInput.val(0.00);
  //     totalInput.val(0.00);

  //     var row = $(this).closest('.row');
  //     var selectedModelId = row.find('.modelno-select').val();
  //     var unitPriceInput = row.find('.unit-price');
  //     var currencyConversion = row.find('.currencyConvertion').val();
  //     var marginSpan = row.find('.display-margin-price');
  //     var marginPercentSpan = row.find('.display-margin-percent');
  //     var productCurrencyConvertFlag = row.find('.product-currency-convert');

  //     event.preventDefault();
  //     var conversionRate = parseFloat(currencyConversion);
  //     if (!isNaN(conversionRate) && conversionRate !== 0) {
  //       var originalSellingPrice = parseFloat(selectedModelData.selling_price);
  //       var newSellingPrice = originalSellingPrice / conversionRate;
  //       unitPriceInput.val(newSellingPrice.toFixed(2));
  //       marginSpan.text('MARGIN PRICE: ' + (selectedModelData.margin_price / conversionRate).toFixed(2))
  //         .css({
  //           'font-size': '12px',
  //           'color': 'red'
  //         });
  //       productCurrencyConvertFlag.val(0);

  //     } else {
  //       console.error('Invalid conversion rate');
  //     }
  //   });

  //   if (selectedModelData) {

  //     // Update the input fields with data from selectedModelData
  //     unitPriceInput.val(selectedModelData.selling_price);
  //     marginPercentInput.val(selectedModelData.margin_percent);

  //     if (selectedModelData.product_type == 'custom' && selectedModelData.product_category == 'products') {
  //       $('#customModal').modal('show');
  //       $('#additionalText').on('click', function() {
  //         $('input[name="priceBasisRadio"]').prop('checked', false);
  //         var sellingPrice = $('#sellingPriceHistory').val();
  //         var marginPercentage = $('#marginPercentageHistory').val();
  //         var calculatedMarginPrice = sellingPrice * (marginPercentage / 100);
  //         $('#additionalFieldsModal').modal('show');
  //       });
  //       document.getElementById('closeAdditionalFieldsModal').addEventListener('click', function() {
  //         $('#customModal').modal('show');
  //       });

  //       function updateValues() {
  //         var sellingPrice = parseFloat($('#sellingPriceHistory').val());
  //         var marginPercentage = parseFloat($('#marginPercentageHistory').val());

  //         if (!isNaN(sellingPrice) && !isNaN(marginPercentage)) {
  //           var marginPrice = sellingPrice * (marginPercentage / 100);

  //           $('#marginPriceHistory').val(marginPrice.toFixed(2));
  //         } else {
  //           $('#marginPriceHistory').val('');
  //         }
  //       }

  //       $('#sellingPriceHistory, #marginPercentageHistory').on('input', updateValues);

  //       // Function to reset modal values
  //       function resetModalValues() {
  //         $('#sellingPriceHistory').val('');
  //         $('#marginPercentageHistory').val('');
  //         $('#quoteCurrencyHistory').val('');
  //         $('#marginPriceHistory').val('');
  //         $('input[name="product_ids"]').val('');
  //         $('#priceBasis').val('');
  //       }


  //       function saveAdditionalFieldsHandler(saveOperationPerformed) {

  //         if (!saveOperationPerformed) {

  //           saveOperationPerformed = true;

  //           $(this).prop('disabled', true);
  //           var sellingPrice = $('#sellingPriceHistory').val();
  //           var marginPercentage = $('#marginPercentageHistory').val();
  //           var quoteCurrency = $('#quoteCurrencyHistory').val();
  //           var marginPrice = $('#marginPriceHistory').val();
  //           var productId = $('input[name="product_ids"]').val();
  //           var priceBasis = $('#priceBasis').val();

  //           $.ajax({
  //             url: '{{ route("productHistorySave") }}',
  //             method: 'POST',
  //             headers: {
  //               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //             },
  //             data: {
  //               selling_price: sellingPrice,
  //               margin_percentage: marginPercentage,
  //               quote_currency: quoteCurrency,
  //               margin_price: marginPrice,
  //               productId: productId,
  //               price_basis: priceBasis
  //             },
  //             success: function(response) {
  //               if (response.success) {

  //                 $('#additionalFieldsModal').modal('hide');
  //                 $('#customModal').modal('show');
  //                 refreshProductHistory();
  //                 resetModalValues();
  //               } else {}
  //             },
  //             error: function(xhr, status, error) {
  //               console.error('Error saving values:', error);
  //             },
  //             complete: function() {
  //               $('#saveAdditionalFields').prop('disabled', false);
  //               saveOperationPerformed = false;
  //             }
  //           });
  //         }
  //       }

  //       $('#saveAdditionalFields').on('click', function() {
  //         var isValid = true;

  //         // Remove the is-invalid class and hide the invalid feedback for all elements
  //         $('.is-invalid').removeClass('is-invalid');
  //         $('.invalid-feedback').hide();

  //         // Define the required fields by their IDs
  //         var requiredFields = [
  //           '#sellingPriceHistory',
  //           '#marginPercentageHistory',
  //           '#quoteCurrencyHistory',
  //           '#marginPriceHistory',
  //           'input[name="product_ids"]',
  //           '#priceBasis'
  //         ];

  //         // Validate each field based on its value
  //         requiredFields.forEach(function(field) {
  //           var value = $(field).val().trim();

  //           // Check if the field value is empty
  //           if (value === '') {
  //             $(field).addClass('is-invalid').next('.invalid-feedback').show();
  //             isValid = false;
  //           }
  //         });

  //         // If all fields pass validation, submit the form
  //         if (isValid) {
  //           saveAdditionalFieldsHandler(false);
  //         }
  //       });

  //       $('#additionalFieldsModal').on('hidden.bs.modal', function() {
  //         saveOperationPerformed = false;
  //       });

  //       function formatDate(dateString) {
  //         var date = new Date(dateString);
  //         var day = ('0' + date.getDate()).slice(-2);
  //         var month = ('0' + (date.getMonth() + 1)).slice(-2);
  //         var year = date.getFullYear();

  //         var formattedDate = day + '/' + month + '/' + year;
  //         return formattedDate;
  //       }

  //       function refreshProductHistory() {
  //         $.ajax({
  //           url: '{{ route("productHistory", ":id") }}'.replace(':id', selectedModelData.id),
  //           method: 'GET',
  //           success: function(response) {
  //             var productHistoryHtml = '<table class="table"><thead><tr><th></th><th>Name</th><th>Price</th><th>Margin Price</th><th>MOSP</th><th>Price Basis</th><th>Added By</th><th>Date</th></tr></thead><tbody>';
  //             var counter = 0;

  //             $.each(response, function(index, history) {
  //               productHistoryHtml += '<tr>' +
  //                 '<td>' +
  //                 '<div class="form-check">' +
  //                 '<input class="form-check-input" type="radio" name="priceBasisRadio" value="' + history.id + '" data-row-index="' + counter + '" data-history-id="' + history.id + '" data-selling-price="' + history.selling_price + '" data-margin-price="' + history.margin_price + '"data-margin-percent="' + history.margin_percent + '"data-allowed-discount="' + history.product_discount + '"data-currency="' + history.currency + '">' +
  //                 '<label class="form-check-label" for="priceBasisRadio_' + counter + '"></label>' +
  //                 '</div>' +
  //                 '</td>' +
  //                 '<td>' + history.product_title + '</td>' +
  //                 '<td>' + history.selling_price + ' ' + history.currency + '</td>' +
  //                 '<td>' + history.margin_price + ' ' + history.currency + '</td>' +
  //                 '<td>' + history.margin_percent + '%</td>' +
  //                 '<td>' + history.price_basis + '</td>' +
  //                 '<td>' + history.user_name + '</td>' +
  //                 '<td>' + formatDate(history.created_at) + '</td>' +
  //                 //'<td>' + history.currency + '</td>' +
  //                 '<td><input type="hidden" class="form-control" name="product_ids" value="' + history.product_id + '"></td>' +
  //                 '</tr>';

  //               counter++;
  //             });

  //             productHistoryHtml += '</tbody></table>';

  //             $('#productDetails').html(productHistoryHtml);
  //             attachEventListeners();
  //           },
  //           error: function(xhr, status, error) {
  //             console.error('Error fetching product history:', error);
  //           }
  //         });
  //       }

  //       function attachEventListeners() {
  //         $('input[name="priceBasisRadio"]').on('click', function() {
  //           var rowIndex = $(this).data('row-index');
  //           var historyId = $(this).data('history-id');
  //           var sellingPrice = $(this).data('selling-price');
  //           var marginPrice = $(this).data('margin-price');
  //           var marginPercent = $(this).data('margin-percent');
  //           var allowedDiscount = $(this).data('allowed-discocunt');
  //           var currency = $(this).data('currency');

  //           if ($(this).prop('checked')) {
  //             var row = $(this).closest('.row');

  //             var unitPriceInput = row.find('.unit-price');
  //             var marginSpan = row.find('.display-margin-price');
  //             var marginPercentSpan = row.find('.display-margin-percent');

  //             var selectedCurrency = $('select[name="quote_currency"]').val();


  //             if (selectedCurrency !== currency) {

  //               alert('The product currency (' + currency + ') does not match the preferred currency (' + selectedCurrency + ').Please add a new price');
  //               $(this).prop('checked', false);

  //               marginSpan.hide();
  //             } else {

  //               marginSpan.show();
  //             }
  //           }

  //         });

  //         $('.btn-select').off('click').on('click', function() {
  //           var selectedRadio = $('input[name="priceBasisRadio"]:checked');
  //           var unitPriceInput = row.find('.unit-price');
  //           var marginPercentInput = row.find('.margin-percent');
  //           var marginSpan = row.find('.display-margin-price');
  //           var marginPercentSpan = row.find('.display-margin-percent');
  //           var productCurrency = row.find('.product-currency');
  //           var productCurrencyConvertFlag = row.find('.product-currency-convert');


  //           if (selectedRadio.length > 0) {
  //             var sellingPrice = selectedRadio.data('selling-price');
  //             var marginPrice = selectedRadio.data('margin-price');
  //             var marginPercent = selectedRadio.data('margin-percent');
  //             var allowedDiscount = selectedRadio.data('allowed-discount');
  //             var itemCurrency = selectedRadio.data('currency');
  //             unitPriceInput.val(sellingPrice);
  //             marginPercentInput.val(marginPercent);

  //             productCurrency.val(itemCurrency);
  //             var selectedCurrency = $('select[name="quote_currency"]').val();

  //             if (selectedCurrency != productCurrency.val()) {
  //               productCurrencyConvertFlag.val(1);
  //             }
  //             marginSpan.text('MARGIN PRICE: ' + marginPrice)
  //               .css({
  //                 'font-size': '12px',
  //                 'color': 'red'
  //               });
  //             marginPercentSpan.text('MOSP (%): ' + marginPercent)
  //               .css({
  //                 'font-size': '12px',
  //                 'color': 'red',
  //                 'display': 'block'
  //               });
  //             $('#customModal').modal('hide');
  //           } else {

  //           }
  //         });
  //       }
  //       refreshProductHistory();
  //     }

  //     $('input[name="product_currency"]').val(selectedModelData.currency);
  //     marginSpan.text('MARGIN PRICE: ' + selectedModelData.margin_price)
  //       .css({
  //         'font-size': '12px',
  //         'color': 'red'
  //       });
  //     marginPercentSpan.text('MOSP (%): ' + selectedModelData.margin_percent)
  //       .css({
  //         'font-size': '12px',
  //         'color': 'red',
  //         'display': 'block'
  //       });
  //     marginPrice.val(selectedModelData.margin_price);
  //     quantityInput.val(0);
  //     discountInput.val(0);
  //     subtotalInput.val(0.00);
  //     totalInput.val(0.00);
  //   } else {
  //     unitPriceInput.val(0.00);
  //     quantityInput.val(0);
  //     discountInput.val(0);
  //     subtotalInput.val(0.00);
  //     totalInput.val(0.00);
  //   }
  // });
  // var alertShown = false;
  // $(document).on('input change', '.quantity, .discount', function(e) { //,
  //   e.preventDefault();

  //   var row = $(this).closest('.row');
  //   var quantity = parseFloat(row.find('.quantity').val()) || 0;
  //   var unitPrice = parseFloat(row.find('.unit-price').val()) || 0.00;
  //   var discount = parseFloat(row.find('.discount').val()) || 0;
  //   var marginPercent = row.find('.margin-percent').val() || 0;
  //   var quoteMargin = row.find('.quote-margin-price');
  //   var productCurrency = row.find('.product-currency').val();
  //   var productCurrencyConvertFlag = row.find('.product-currency-convert').val() || 0;
  //   var selectedCurrency = $("#currencyDropdown").val();
  //   var subTotal = row.find(".subtotal").val();
  //   alertShown = (productCurrencyConvertFlag == 1) ? true : false;

  //   if (productCurrency != selectedCurrency && alertShown) {
  //     alert('Your selected currency does not match with your product currency. Please apply currency conversion.');
  //     // alertShown = true; // Set the flag to true to indicate the alert has been shown
  //     //row.find('.unit-price').val('');
  //     row.find('.quantity').val('');
  //     // row.find('.subtotal').val('');
  //     return false;
  //   }

  //   // if (productCurrency != 'aed' && !alertShown) {
  //   //   alert('The product currency (' + productCurrency + ') does not match the preferred currency (' + selectedCurrency + ').Please add a new price');
  //   //   alertShown = true;
  //   //   row.find('.unit-price').val('');
  //   //   row.find('.quantity').val('');
  //   //   row.find('.subtotal').val('');
  //   //   return false;
  //   // }

  //   var subtotal = quantity * unitPrice;
  //   row.find('.subtotal').val(subtotal.toFixed(2));

  //   var total = subtotal - (subtotal * discount / 100);
  //   row.find('.total').val(total.toFixed(2));

  //   var quoteMargin = subtotal * ((marginPercent - discount) / 100);

  //   row.find('.margin-amount-row').val(quoteMargin.toFixed(2));

  //   // Recalculate total margin for all rows
  //   var totalMargin = 0;
  //   $('.row').each(function() {
  //     var rowMargin = parseFloat($(this).find('.margin-amount-row').val()) || 0;
  //     totalMargin += rowMargin;
  //   });


  //   // Update the total margin text
  //   // $('.quote-margin-price').text('Total Margin: ' + totalMargin.toFixed(2)).css({'color': 'red', 'font-size': '12px'});

  //   // Display the quote margin in the quote-margin-price element
  //   row.find('.quote-margin-price').text('Quote Margin: ' + quoteMargin.toFixed(2)).css({
  //     'color': 'green',
  //     'font-size': '12px'
  //   });
  //   row.find('.quote-margin-price').show();
  // });


  //
  // $(document).on('input change', '.quantity, .discount', function() {
  //   var row = $(this).closest('.row');
  //   var quantity = parseFloat(row.find('.quantity').val()) || 0;
  //   var unitPrice = parseFloat(row.find('.unit-price').val()) || 0.00;
  //   var discount = parseFloat(row.find('.discount').val()) || 0;
  //   var marginPercent = parseFloat(row.find('.margin-percent').val()) || 0.00;
  //
  //   var subtotal = quantity * unitPrice;
  //   row.find('.subtotal').val(subtotal.toFixed(2));
  //
  //   var total = subtotal - (subtotal * discount / 100);
  //   row.find('.total').val(total.toFixed(2));
  //   var quoteMargin = subtotal * ((marginPercent - discount) / 100);
  //   row.find('.margin-amount-row').val(quoteMargin.toFixed(2));
  //
  //   // Recalculate total margin for all rows
  //   var totalMargin = 0;
  //   $('.row').each(function() {
  //     var rowMargin = parseFloat($(this).find('.margin-amount-row').val()) || 0;
  //     totalMargin += rowMargin;
  //   });
  //   // row.find('.quote-margin-price').text('Quote Margin: ' + quoteMargin.toFixed(2)).css({'color': 'green', 'font-size': '12px'});
  //   row.find('.quote-margin-price').text('Quote Margin: ' + quoteMargin.toFixed(2)).css({
  //     'color': 'green',
  //     'font-size': '12px'
  //   });
  //   row.find('.quote-margin-price').show();
  //
  // });
  //
  // $(document).on('input change', '.quantity, .unit-price, .discount, #discountInput', function() {
  //   var row = $(this).closest('.row');
  //   var quantity = parseFloat(row.find('.quantity').val()) || 0;
  //   var unitPrice = parseFloat(row.find('.unit-price').val()) || 0.00;
  //   var discount = parseFloat(row.find('.discount').val()) || 0;
  //   var margin = parseFloat(row.find('.margin').val()) || 0.00;
  //   var marginPercent = parseFloat(row.find('.margin-percent').val()) || 0.00;
  //
  //   var subtotal = quantity * unitPrice;
  //   row.find('.subtotal').val(subtotal.toFixed(2));
  //
  //   // Calculate total after discount
  //   var total = subtotal - (subtotal * discount / 100);
  //   row.find('.total').val(total.toFixed(2));
  //
  //   // Calculate margin total
  //   var margintotal = quantity * margin;
  //   row.find('.margintotal').val(margintotal.toFixed(2));
  //
  //   var total;
  //   if ($(this).is('#discountInput')) {
  //     total = subtotal - (subtotal * discount / 100);
  //     row.find('.total').val(total.toFixed(2));
  //     return;
  //   }
  //   row.find('.margintotal').val(margintotal.toFixed(2));
  // });
  //
  // function calculateOverallTotal() {
  //   var overallTotal = 0;
  //   var overallMargin = 0;
  //   var vatRate = 0.05; // VAT rate of 5%
  //   var vatIncluded = $('input[name="vat_option"]:checked').val() === '1';
  //   var sumAfterDiscount = 0;
  //   var totalMargin = 0;
  //   var vatAmount = 0;
  //   var quotationCharges = 0;
  //   var totalMarginSum = 0;

  //   $('input[name="total_after_discount[]"]').each(function() {
  //     var rowTotalAfterDiscount = parseFloat($(this).val()) || 0;
  //     overallTotal += rowTotalAfterDiscount;
  //     sumAfterDiscount += rowTotalAfterDiscount;
  //   });
  //   $('input[name="margin_amount_row[]"]').each(function() {
  //     var rowTotalMargin = parseFloat($(this).val()) || 0;
  //     overallMargin += rowTotalMargin;
  //     totalMargin += rowTotalMargin;

  //   });

  //   $('input[name="charge_amount[]"]').each(function() {
  //     var chargeAmount = parseFloat($(this).val()) || 0;
  //     quotationCharges += chargeAmount;
  //   });

  //   sumAfterDiscount += quotationCharges;

  //   if (vatIncluded) {
  //     vatAmount = sumAfterDiscount * vatRate;
  //     sumAfterDiscount += vatAmount;
  //   }

  //   $('#totalValue').val(sumAfterDiscount.toFixed(2));
  //   $('#totalMarginValue').val(totalMargin.toFixed(2));

  //   $('input[name="total_amount"]').val(sumAfterDiscount.toFixed(2));

  //   $('input[name="gross_margin"]').val(totalMarginSum.toFixed(2));
  //   $('#vatAmountLabel').text(vatAmount.toFixed(2));

  //   $('input[name="vat_amount"]').val(vatAmount.toFixed(2));

  //   if (!vatIncluded) {
  //     $('#vatAmountLabel').text('0.00');

  //   }
  // }

  //calculateOverallTotal();

  //});


  //var responseData;

  // function initializeModelDropdown(categoryDropdown) {

  //   var selectedCategory = categoryDropdown.val();
  //   var modelnoDropdown = categoryDropdown.closest('.row').find('.modelno-select');
  //   var unitPriceInput = categoryDropdown.closest('.row').find('.unit-price');
  //   var productDiscount = categoryDropdown.closest('.row').find('.discount');
  //   var productDescription = categoryDropdown.closest('.row').find('.product-description');

  //   var selectedSupplierId = $('select[name="supplier_id[]"]').val();
  //   modelnoDropdown.empty();
  //   if (selectedCategory) {
  //     $.ajax({
  //       url: '/fetch-product-models',
  //       method: 'GET',
  //       data: {
  //         category: selectedCategory,
  //         supplier_id: selectedSupplierId
  //       },
  //       success: function(data) {
  //         modelnoDropdown.empty();

  //         if (data.length > 0) {
  //           data.forEach(function(supplier) {
  //             var optgroup = $('<optgroup label="' + supplier.supplier_name + '">');

  //             // Append "Select Model" as the first option within each supplier's section
  //             optgroup.prepend('<option value="" disabled selected>Select Model</option>');

  //             supplier.models.forEach(function(model) {
  //               optgroup.append('<option value="' + model.id + '">' + model.title + '</option>');
  //             });
  //             modelnoDropdown.append(optgroup);
  //           });


  //           modelnoDropdown.append('<option value="product_modal" class="add-new-option">Add New</option>');

  //           modelnoDropdown.on('change', function() {
  //             if ($(this).val() === 'product_modal') {
  //               var row = $(this).closest('.row');
  //               var categorySelect = row.find('.category-select');
  //               var categoryValue = categorySelect.val();
  //               $('#myModal').modal('show');
  //               $('#productcategoryInput').val(categoryValue);
  //               $(this).val('');
  //             }
  //           });
  //           responseData = data;
  //           modelnoDropdown.trigger('change');
  //         } else {
  //           modelnoDropdown.append('<option value="">No models available</option>');
  //           modelnoDropdown.append('<option value="product_modal" class="add-new-option">Add New</option>');
  //           unitPriceInput.val('');
  //           productDiscount.val('');
  //         }
  //       },
  //       error: function(error) {
  //         console.error('Error fetching product models:', error);
  //       }
  //     });
  //   }
  // }

  // function removeRow(button) {
  //   var row = $(button).closest('.row');
  //   if (row.attr('id') === 'stableRow') {
  //     console.log('Cannot remove the stable row.');
  //   } else {
  //     row.remove();
  //   }
  // }


  // $(document).ready(function() {

  // function resetModals() {
  //   $('#productDetails').empty();
  //   $('#sellingPriceHistory, #marginPercentageHistory, #quoteCurrencyHistory, #marginPriceHistory').val('');
  //   $('#priceBasis').val('');
  // }


  // $('#saveProduct').click(function() {
  //   var isValid = true;

  //   // Remove the is-invalid class and hide the invalid feedback for all elements
  //   $('.is-invalid').removeClass('is-invalid');
  //   $('.invalid-feedback').hide();

  //   // Define the required fields by their IDs
  //   var requiredFields = [
  //     '#titleInput',
  //     '#divisionInput',
  //     '#brandInput',
  //     '#sellingPrice',
  //     '#marginPercentage',
  //     '#productcategoryInput',
  //     '#durationSelect',
  //     '#currencyInput',
  //     '#managerInput'
  //   ];

  //   // Validate each field based on its value
  //   requiredFields.forEach(function(field) {
  //     var value = $(field).val().trim();

  //     // Check if the field value is empty
  //     if (value === '') {
  //       $(field).addClass('is-invalid').next('.invalid-feedback').show();
  //       isValid = false;
  //     }
  //   });

  //   // If all fields pass validation, submit the form
  //   if (isValid) {
  //     $('#productForm').submit();
  //   }
  // });

  // $('#myModal').on('shown.bs.modal', function() {
  //   var modal = $(this);
  //   // $('#saveProduct').off('click');
  //   $('#saveProduct').click(function(e) {
  //     e.preventDefault();
  //     var formData = new FormData($('#productForm')[0]);
  //     formData.append('title', $('input[name=title]').val());
  //     formData.append('division_id', $('select[name=division_id]').val());
  //     formData.append('brand_id', $('select[name=brand_id]').val());
  //     formData.append('model_no', $('input[name=model_no]').val());
  //     formData.append('description', $('textarea[name=description]').val());
  //     formData.append('product_type', $('select[name=product_type]').val());
  //     formData.append('payment_term', $('select[name=product_payment_term]').val());
  //     formData.append('currency', $('select[name=currency]').val());
  //     formData.append('currency_rate', $('input[name=currency_rate]').val());
  //     formData.append('manager_id', $('select[name=manager_id]').val());
  //     formData.append('selling_price', $('input[name=selling_price]').val());
  //     formData.append('margin_percentage', $('input[name=margin_percentage]').val());
  //     formData.append('margin_price', $('input[name=margin_price]').val());
  //     formData.append('freeze_discount', $('input[name=freeze_discount]').is(':checked') ? 1 : 0);
  //     formData.append('image', $('input[name=image]')[0].files[0]);
  //     formData.append('start_date', $('input[name=start_date]').val());
  //     formData.append('end_date', $('input[name=end_date]').val());
  //     formData.append('product_category', $('select[name=product_category]').val());
  //     formData.append('status', $('select[name=status]').val());
  //     formData.append('allowed_discount', $('input[name=allowed_discount]').val());
  //
  //
  //     var row = $(this).closest('.row');
  //
  //     $.ajax({
  //       url: "{{ route('products.ajaxsave') }}",
  //       method: "POST",
  //       data: formData,
  //       contentType: false,
  //       processData: false,
  //       headers: {
  //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //       },
  //       success: function(response) {
  //         console.log(response);
  //         if (response.data) {
  //           alert(response.success); // success message
  //
  //           var model = response.data;
  //           var supplierName = model.supplier.brand;
  //           var row = $('#itemsContainer').find('.row').last();
  //           var marginSpan = row.find('.display-margin-price');
  //           var marginPercentSpan = row.find('.display-margin-percent');
  //           var marginPercent = row.find('.margin-percent');
  //
  //           marginSpan.text('MARGIN PRICE: ' + model.margin_price)
  //           .css({
  //             'font-size': '12px',
  //             'color': 'red'
  //           });
  //           marginPercentSpan.text('MOSP (%): ' + model.margin_percent)
  //           .css({
  //             'font-size': '12px',
  //             'color': 'red',
  //             'display': 'block'
  //           });
  //           marginPercent.val(model.margin_percent);
  //           marginSpan.show();
  //           marginPercentSpan.show();
  //           row.find('.unit-price').val(model.selling_price);
  //
  //           var option = $('<option></option>').attr('value', model.id).text(model.title);
  //           row.find('.modelno-select').append(option);
  //
  //           row.find('.modelno-select').val(model.id);
  //
  //           modal.find('input[type=text], input[type=number], textarea').val('');
  //           modal.find('select').val('').trigger('change');
  //           modal.modal('hide');
  //         }
  //
  //
  //       },
  //     });
  //   });
  // });
  // $('#myModal').on('shown.bs.modal', function() {
  //   var modal = $(this);

  //   // $('#saveProduct').off('click');
  //   $('#saveProduct').click(function(e) {

  //     e.preventDefault();
  //     var formData = new FormData($('#productForm')[0]);
  //     formData.append('title', $('input[name=title]').val());
  //     formData.append('division_id', $('select[name=division_id]').val());
  //     formData.append('brand_id', $('select[name=brand_id]').val());
  //     formData.append('model_no', $('input[name=model_no]').val());
  //     formData.append('description', $('textarea[name=description]').val());
  //     formData.append('product_type', $('select[name=product_type]').val());
  //     formData.append('payment_term', $('select[name=product_payment_term]').val());
  //     formData.append('currency', $('select[name=currency]').val());
  //     formData.append('currency_rate', $('input[name=currency_rate]').val());
  //     formData.append('manager_id', $('select[name=manager_id]').val());
  //     formData.append('selling_price', $('input[name=selling_price]').val());
  //     formData.append('margin_percentage', $('input[name=margin_percentage]').val());
  //     formData.append('margin_price', $('input[name=margin_price]').val());
  //     formData.append('freeze_discount', $('input[name=freeze_discount]').is(':checked') ? 1 : 0);
  //     formData.append('image', $('input[name=image]')[0].files[0]);
  //     formData.append('start_date', $('input[name=start_date]').val());
  //     formData.append('end_date', $('input[name=end_date]').val());
  //     formData.append('product_category', $('select[name=product_category]').val());
  //     formData.append('status', $('select[name=status]').val());
  //     formData.append('allowed_discount', $('input[name=allowed_discount]').val());
  //     formData.append('part_number', $('input[name=part_number]').val());

  //     var row = $(this).closest('.row');
  //     $.ajax({
  //       url: "{{ route('products.ajaxsave') }}",
  //       method: "POST",
  //       data: formData,
  //       contentType: false,
  //       processData: false,
  //       headers: {
  //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //       },
  //       success: function(response) {
  //         //console.log(response.data);
  //         if (response.data) {

  //           var model = response.data;
  //           var supplierName = model.supplier.brand;

  //           // var optgroup = modal.find('.modelno-select optgroup[label="' + supplierName + '"]');
  //           // if (optgroup.length === 0) {
  //           //   // Create a new optgroup if it doesn't exist
  //           //   optgroup = $('<optgroup label="' + supplierName + '">');
  //           //   modal.find('.modelno-select').append(optgroup);
  //           // }

  //           var row = $('#itemsContainer').find('.row').last();
  //           var marginSpan = row.find('.display-margin-price');
  //           var marginPercentSpan = row.find('.display-margin-percent');
  //           var marginPercent = row.find('.margin-percent');
  //           var productCurrency = row.find('.product-currency');
  //           var productCurrencyConvertFlag = row.find('.product-currency-convert');

  //           var quoteCurrency = $('#currencyDropdown').val();
  //           if (model.currency === 'aed') {
  //             row.find('.customCurrencySection').show();
  //           } else {
  //             row.find('.customCurrencySection').hide();
  //           }

  //           marginSpan.text('MARGIN PRICE: ' + model.margin_price)
  //             .css({
  //               'font-size': '12px',
  //               'color': 'red'
  //             });
  //           marginPercentSpan.text('MOSP (%): ' + model.margin_percent)
  //             .css({
  //               'font-size': '12px',
  //               'color': 'red',
  //               'display': 'block'
  //             });
  //           marginPercent.val(model.margin_percent);
  //           marginSpan.show();
  //           marginPercentSpan.show();
  //           row.find('.unit-price').val(model.selling_price);
  //           row.find('.product-selling').val(model.selling_price);
  //           row.find('.product-margin').val(model.margin_price);
  //           row.find('.product-percent').val(model.margin_percent);
  //           productCurrency.val(model.currency);

  //           let selopt = model.title;
  //           if (model.modelno) selopt += ' / (' + model.modelno + ')';
  //           if (model.part_number) selopt += ' (' + model.part_number + ')';

  //           var option = $('<option></option>').attr('value', model.id).text(selopt);
  //           row.find('.modelno-select').append(option);

  //           row.find('.modelno-select').val(model.id);

  //           if (quoteCurrency != productCurrency.val()) {
  //             productCurrencyConvertFlag.val(1);
  //             //alert('Your selected quotation currency does not match with your product currency. Please add item price with quotation currency.');
  //             // return false;
  //           }

  //           modal.find('input[type=text], input[type=number], textarea').val('');
  //           modal.find('select').val('').trigger('change');
  //           modal.modal('hide');

  //         }

  //       },
  //     });
  //   });
  // });;

  // $('#applyCurrency').click(function() {

  //   var row = $(this).closest('.row');
  //   var unitPriceInput = row.find('.unit-price');
  //   var quantityInput = row.find('.quantity');
  //   var discountInput = row.find('.discount');
  //   var subtotalInput = row.find('.subtotal');
  //   var totalInput = row.find('.total');
  //   var quoteMargin = row.find('.quote-margin-price');

  //   var row = $(this).closest('.row');

  //   var unitPriceInputValue = row.find('.product-selling').val();
  //   var unitPriceInput = row.find('.unit-price');
  //   var currencyConversion = row.find('.currencyConvertion').val();
  //   var marginSpan = row.find('.display-margin-price');
  //   var marginPrice = row.find('.product-margin').val();
  //   var marginPercentSpan = row.find('.display-margin-percent');
  //   var marginPercent = row.find('.product-percent').val();
  //   var productCurrencyConvertFlag = row.find('.product-currency-convert');

  //   event.preventDefault();
  //   var conversionRate = parseFloat(currencyConversion);

  //   var newSellingPrice = unitPriceInputValue / conversionRate;
  //   unitPriceInput.val((newSellingPrice).toFixed(2));
  //   if (marginPrice) {
  //     marginSpan.text('MARGIN PRICE: ' + (marginPrice / conversionRate).toFixed(2))
  //       .css({
  //         'font-size': '12px',
  //         'color': 'red'
  //       });
  //   }
  //   if (marginPercent) {
  //     marginPercentSpan.text('MARGIN PERCENT: ' + marginPercent.toFixed(2))
  //       .css({
  //         'font-size': '12px',
  //         'color': 'red'
  //       });
  //   }

  //   row.find('.quote-margin-price').hide();
  //   productCurrencyConvertFlag.val(0);

  // });
  //});
</script>