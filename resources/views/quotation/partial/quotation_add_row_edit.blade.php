<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding-top:21px;"><span>Optional Items</span></div>

<div id="quotationOptionalContainer">

  @if($optionalItems->isNotEmpty())
  <div class="row">
    <div class="col-sm-11">
    </div>
    <div class="col-sm-1">
      <button type="button" class="btn btn-success" onclick="addOptionalItem()" style="background-color: #007D88;">
        <i class="fas fa-plus"></i>
      </button>
    </div>
  </div>
  @foreach ($optionalItems as $index => $value)
  <div class="row" data-charge-id="{{ $value->id}}">
    <input type="hidden" name="row_item_ids[]" value="{{ $value->id }}">
    <div class="col-sm-4">

    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <!-- <label for="charge_name[]" class="control-label">{{ $charges->title ?? '' }}</label> -->
        <input type="text" class="form-control item-name-input" name="optional_name[]" value="{{ $value->item_name ?? '' }}">
      </div>
    </div>
    <div class="col-sm-1">
      <div class="form-group">
        <input type="text" class="form-control optional-quantity" name="optional_quantity[]" placeholder="Quantity" value="{{ $value->quantity ?? '' }}" />
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <!-- <label for="charge_name[]" class="control-label">{{ $charges->amount ?? '' }}</label> -->
        <input type="text" class="form-control" name="optional_amount[]" value="{{ $value->amount ?? '' }}" />
      </div>
    </div>
    <div class="col-sm-1">

      <button type="button" onclick="removeItems({{ $value->id }})" class="remove-button">
        <i class="fas fa-trash"></i>
      </button>

    </div>
  </div>
  @endforeach

  @else

  <div class="row">
    <input type="hidden" name="row_item_ids[]">
    <div class="col-sm-4">

    </div>
    <div class="col-sm-4">
      <div class="form-group">
        <input class="form-control item-name-input" name="optional_name[]" placeholder="Optional Item" data-row-id="0">
      </div>
    </div>
    <div class="col-sm-1">
      <div class="form-group">
        <input type="text" class="form-control optional-quantity" name="optional_quantity[]" placeholder="Quantity" value="" />
      </div>
    </div>
    <div class="col-sm-2">
      <div class="form-group">
        <input class="form-control" name="optional_amount[]" placeholder="Amount" value="" />
        <div class="suggestions"></div>
      </div>
    </div>
    <div class="col-sm-1">
      <button type="button" class="btn btn-success" onclick="addOptionalItem()" style="background-color: #007D88;">
        <i class="fas fa-plus"></i>
      </button>
    </div>

  </div>
  @endif
</div>

<div class="title title-xs title-wth-divider text-primary text-uppercase my-2" style="padding-top:21px;"><span>Additional Charges</span></div>
<div class="text-primary" style="padding-top:21px;margin-bottom: 20px;">
  If you check the checkbox, this charge will be shown in the quote.</div>
  <div id="quotationChargesContainer">
    <div class="row">
      <div class="col-sm-11">
      </div>
      <div class="col-sm-1">
        <button type="button" class="btn btn-success" onclick="addQuotationCharge()" style="background-color: #007D88;">
          <i class="fas fa-plus"></i>
        </button>
      </div>
    </div>

    @if($quotationCharge->isEmpty())

      <div class="row charge-row">
        <input type="hidden" name="row_charge_ids[]">
        <div class="col-sm-3">
        </div>
        <div class="col-sm-1">
          <div class="form-check" style="display: flex; justify-content: flex-end;">
            <!-- Hidden input for unchecked state -->

            <input type="hidden" name="is_visible[]" value="0">
            <input type="checkbox" id="exampleCheckbox" name="is_visible[]" value="1" onchange="updateCheckboxValue(this)">
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <select class="form-control charge-name-input title-input" name="charge_name[0]" placeholder="Charge Type" data-row-id="0"></select>
          </div>
        </div>
        <div class="col-sm-3">
          <div class="form-group">
            <input class="form-control" name="charge_amount[0]" placeholder="Amount" />
            <div class="suggestions"></div>
          </div>
        </div>
        <div class="col-sm-1">
          <button type="button" onclick="removeChargeRow(this)" class="remove-button">
            <i class="fas fa-trash"></i>
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
      @if (Auth::check() && Auth::user()->email != 'service@yesmachinery.ae')
      <div class="col-sm-3">
        <label class="form-check-label" id="vatAmountLabel">{{ $quotation->vat_amount }}</label>
      </div>
      <input class="form-check-input" type="hidden" name="vat_amount" value="{{ $quotation->vat_amount }}">
      @else
      <div class="col-sm-3">
        <input type="text" class="form-control" id="vatAmountLabel" name="vat_amount"  value="{{ $quotation->vat_amount }}">
      </div>
      @endif

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
    <div class="row" style="padding-top:12px;">
      <div class="col-sm-5">
      </div>
      <div class="col-sm-5 text-align-left" style="width: 28.5%;">
        <label>Do you want to display the total amount in quotation?</label>
      </div>:
      <div class="col-sm-3">
        <input type="radio" id="is_total_yes" name="total_status" value="1" {{ $quotation->total_status == 1 ? 'checked' : '' }}>
        <label class="form-check-label" for="is_total_yes">
          yes
        </label>
        <input type="radio" id="is_total_no" name="total_status" value="0" {{ $quotation->total_status == 0 ? 'checked' : '' }}>
        <label class="form-check-label" for="is_total_no">
          No
        </label>
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
            @if($availability && isset($availability->working_period))
            <option value="day" {{$availability->working_period == 'day'? 'selected': ''}}>Days</option>
            <option value="week" {{$availability->working_period == 'week'? 'selected': ''}}>Weeks</option>
            <option value="month" {{$availability->working_period == 'month'? 'selected': ''}}>Months</option>
            @else
            <option value="day">Day</option>
            <option value="week">Week</option>
            <option value="month">Month</option>
            @endif
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
      <div class="row">
        <div class="col-sm-11">
        </div>
        <div class="col-sm-1">
          <button type="button" class="btn btn-success" id="add-item-btn" style="background-color: #007D88;">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
      @if($paymentCycleList->isNotEmpty())
      @foreach($paymentCycleList as $paymentCycle)
      <div class="row" data-payment-cycle-id="{{ $paymentCycle->id }}">
        <div class="col-sm-6"></div>
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
          <button type="button" onclick="removeQuotationTerms(this)" style="color: #007D88; border: 1px solid #007D88; border-radius: 4px; background-color: transparent; padding: 4px 8px; cursor: pointer;">
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
        '<div class="col-sm-6"></div>' +
        '<div class="col-sm-2" style="display: flex; align-items: center;">' +
        '<div style="text-align: left;">' +
        '<input type="text" class="form-control" name="payment_description[]" placeholder="payment" value="">' +
        '</div>' +
        '</div>' +
        '<div class="col-sm-3" style="align-items: center;">' +
        '<input  type="text" class="form-control" name="payment_name[]" placeholder="Title" value="">' +
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
        <div class="col-sm-3"></div>
        <div class="col-sm-1">
        <div class="form-check" style="display: flex; justify-content: flex-end;">
        <input type="hidden" name="is_visible[]" value="0">
        <input type="checkbox" class="form-check-input" name="is_visibles[]" value="1" onchange="updateChargeCheckboxValue(this)" />
        </div>
        </div>

        <div class="col-sm-4">
        <div class="form-group">
        <select class="form-control charge-name-input title-input" name="charge_name[]" placeholder="Charge Type"  data-row-id="${rowId}"></select>
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
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
        <div class="form-group">
        <input class="form-control item-name-input" name="optional_name[]" placeholder="Optional Item" data-row-id="${rowId}"></select>
        </div>
        </div>
        <div class="col-sm-1">
        <div class="form-group">
        <input type="text" class="form-control optional-quantity" name="optional_quantity[]" placeholder="Quantity" value="" />
        </div>
        </div>
        <div class="col-sm-2">
        <div class="form-group">
        <input class="form-control" name="optional_amount[]" placeholder="Amount" />
        </div>
        </div>
        <div class="col-sm-1">
        <button type="button" onclick="removeItem(${rowId})" class="remove-button">
        <i class="fas fa-trash"></i>
        </button>
        </div>
        </div>`;
        $('#quotationOptionalContainer').append(newRows);

      }


      function removeQuotationCharge(rowId) {
        var chargeAmount = parseFloat($('#row-' + rowId).find('input[name="charge_amount[]"]').val()) || 0;
        var totalAmount = parseFloat($('input[name="total_value"]').val()) || 0;
        var newTotalAmount = totalAmount - chargeAmount;
        $('input[name="total_value"]').val(newTotalAmount.toFixed(2));
        $('#row-' + rowId).remove();
      }

      function removeCharge(rowId) {
        var rowToRemove = $('[data-charge-id="' + rowId + '"]');
        rowToRemove.remove();
      }

      function removeChargeRow(button) {
        var row = button.closest('.row.charge-row');
        if (row) {
          row.remove();
        }
      }

      function removeItem(rowId) {
        $('#row-' + rowId).remove();
      }

      function removeItems(rowId) {
        var rowToRemove = $('[data-charge-id="' + rowId + '"]');
        rowToRemove.remove();
      }

      function removeQuotationPayment(paymentCycleId) {
        // Find the payment row with the specified data-payment-cycle-id attribute
        var paymentRowToRemove = $('[data-payment-cycle-id="' + paymentCycleId + '"]');

        // Remove the payment row from the DOM
        paymentRowToRemove.remove();
      }

      // function removeQuotationRow(button) {
      //   var row = $(button).closest('.row');
      //   var totalAmountInput = $('input[name="total_value"]');
      //   var totalAmount = parseFloat(totalAmountInput.val()) || 0;
      //   var overallTotal = 0;
      //   var vatRate = 0.05;
      //   var sumAfterDiscount = 0;
      //   var rowTotal = parseFloat(row.find('input[name="total_after_discount[]"]').val()) || 0;
      //   var newTotalAmount = totalAmount - rowTotal;
      //   totalAmountInput.val(newTotalAmount.toFixed(2));
      //   row.remove();
      //   $('input[name="total_after_discount[]"]').each(function() {
      //     var rowTotalAfterDiscount = parseFloat($(this).val()) || 0;
      //     overallTotal += rowTotalAfterDiscount;
      //     vatAmount = overallTotal * vatRate;
      //     $('#vatAmountLabel').text(vatAmount.toFixed(2));

      //   });
      // }

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
      function updateChargeCheckboxValue(checkbox) {
        // Find the corresponding hidden input element
        const hiddenInput = checkbox.previousElementSibling;

        // Set the value of the hidden input based on checkbox state
        hiddenInput.value = checkbox.checked ? 1 : 0;
      }

    </script>
