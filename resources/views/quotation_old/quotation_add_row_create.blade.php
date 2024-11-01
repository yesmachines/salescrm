<div class="col-6">
  <div class="row gx-3 mt-4">
  </div>
</div>
<div class="col-6">
  <div class="row">
    <div class="col-md-6">
      <label class="form-label">Preferred Currency <span class="text-danger">*</span></label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" name="quote_currency" id="currencyDropdown" onchange="updateCurrencyLabel()" required>
          <option value="">-Select Currency-</option>
          @foreach($currencies as $currency)
          <option value="{{ $currency->code }}">{{ $currency->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <label class="form-label">Delivery Terms <span class="text-danger">*</span></label>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <select class="form-control" name="price_basis" id="paymentTerm" required onchange="updateQuotationTerms()" required>
          <option>-Select Delivery Terms-</option>
          @foreach($paymentTermList as $paymentTerm)
          <option value="{{ $paymentTerm->short_code }}" data-title="{{ $paymentTerm->title }}" data-parent="{{ $paymentTerm->id }}">{{ $paymentTerm->title }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-6">
    </div>

    <div class="col-md-6" id="sub-delivery" style="display: none;">
      <div class="form-group">
        <select class="form-control" name="sub_dropdown" id="subDropdownContainer">

        </select>
      </div>
    </div>
    <div class="col-md-6">
    </div>

    <div class="col-md-6" id="subDeliveryInput" style="display: none;">
      <div class="form-group">
        <input class="form-control" type="text" placeholder="Specify if any" name="sub_delivery_input">
      </div>
    </div>

  </div>
</div>
<div class="row">
  <div class="container padding-top-7">
    <div class="col-md-12">
      <button type="button" onclick="addRow()" class="add-button">Add New</button>
      <div class="row align-items-end" style="padding-top: 10px ">
        <div class="col-sm-3">
          <p class="item-heading">Model/Part Number</p>
        </div>
        <div class="col-sm-2">
          <p class="currency-label item-heading">Unit Price(AED)</p>

        </div>
        <div class="col-sm-1">
          <p class="item-heading">Quantity</p>
        </div>
        <div class="col-sm-2">
          <p class="currency-label item-heading">Line Total(AED)</p>
        </div>
        <div class="col-sm-1">
          <p class="item-heading">Discount(%)</p>
        </div>
        <div class="col-sm-2" style="white-space: nowrap;">
          <p class="currency-label item-heading">Total After Discount(AED)</p>
        </div>
        <div class="col-sm-1">
        </div>
      </div>
      <hr class="my-1">
      <div id="itemsContainer" style="margin-top: 12px;">
        <div class="row" style="margin-top: 12px;">
          <div class="col-sm-3">
            <select name="product[]" class="form-select category-select" placeholder="Category">
              <option value="products" selected>Products</option>
              <option value="accessories">Accessories</option>
              <option value="consumables">Consumables</option>
              <option value="spares">Spares</option>
            </select>
            <div style="margin-top: 12px;">
              <select name="item_id[]" class="form-select modelno-select" placeholder="Model" id="modelDropdown" required>

              </select>
            </div>
            <div style="margin-top: 12px;">
              <textarea class="form-control mb-1 product_description" name="item_description[]" placeholder="Description" rows="2"></textarea>
            </div>
            <div class="customCurrencySection" style="--bs-gutter-x: 0; display: none; padding-top: 3px;">
              <div class="d-flex">
                <input type="text" class="currencyConvertion form-control" placeholder="Conversion Rate" name="currency_convertion[]">
                <button id="applyCurrency" class="form-control ms-2" style="background-color: #007D88; color: white;">Apply</button>
              </div>
            </div>
            <p class="text-warning" style="display:none;"><small>If your price in AED. Please apply conversion to other currencies.</small></p>
          </div>
          <div class="col-sm-2">
            <input type="text" name="unit_price[]" placeholder="Unit Price" class="form-control unit-price" readonly>
            <div style="margin-top: 12px;">
              <span class="display-margin-price"></span>
              <span class="display-margin-percent"></span>
            </div>
          </div>
          <div class="col-sm-1">
            <input type="hidden" name="suppliers_value[]" class="form-control suppliersValue" readonly>
            <input type="number" name="quantity[]" placeholder="Quantity" class="form-control quantity" required>
            <input type="hidden" name="margin_percent[]" placeholder="Margin" class="form-control margin-percent">
            <input type="hidden" name="product_currency[]" class="form-control product-currency">
            <input type="hidden" name="product_currency_convert[]" class="form-control product-currency-convert">
            <input type="hidden" name="product_selling[]" class="form-control product-selling">
            <input type="hidden" name="product_margin[]" class="form-control product-margin">
            <input type="hidden" name="product_percent[]" class="form-control product-percent">
          </div>
          <div class="col-sm-2">
            <input type="number" name="subtotal[]" placeholder="Subtotal" class="form-control subtotal" readonly>
          </div>
          <div class="col-sm-1">
            <input type="number" name="discount[]" placeholder="Discount" class="form-control discount" id="discountInput" min="0" required>
            <div style="padding:6px;" class="warning-message"></div>
          </div>
          <div class="col-sm-2">
            <input name="total_after_discount[]" placeholder="Total" class="form-control total" readonly>
            <div style="margin-top: 12px;">
              <span class="quote-margin-price"></span>
            </div>

            <input type="hidden" name="margin_amount_row[]" placeholder="Margin" class="form-control margin-amount-row">

          </div>
          <div class="col-sm-1">
          </div>
        </div>

      </div>
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
  <div class="col-sm-3 text-align-left">
    <label class="form-check-label" id="vatAmountLabel"></label>
  </div>
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
    <input type="hidden" id="totalMarginValue" name="total_margin_value" class="form-control" readonly>

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
        <option value="days">Days</option>
        <option value="weeks">Weeks</option>
        <option value="months">Months</option>
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
      <label><input type="radio" name="buyer_site" id="buyer_sites" value="not_ready">In Case this is not ready within maximum 30 days from the date of delivery the Yesmachinery is authorize to claim open payments linked to installation
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

<!-- models -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="padding: 16px;">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Add Product</h5>
      </div>
      <div class="modal-body">
        <form id="productForm">
          <input type="hidden" name="allowed_discount" id="allowed_discount" value="0" />
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Title <span class="text-danger">*</span></label>

              </div>

              <div class="form-group">
                <input class="form-control" type="text" id="titleInput" name="title" />
                <div class="invalid-feedback">Please enter a title.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Division<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="division_id" id="divisionInput">
                  <option value="">--</option>
                  @foreach ($divisions as $division)
                  <option value="{{ $division->id }}">{{ $division->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please enter a Division.</div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Brand<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="brand_id" id="brandInput">
                  <option value="">--</option>
                  @foreach ($suppliers as $k => $sup)
                  <option value=" {{ $sup->id }}">
                    {{ $sup->brand }}
                  </option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please enter a brand.</div>
              </div>
            </div>

          </div>
          <div class="row">

            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Model No</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="model_no">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Part Number</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="part_number" />
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Description </label>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="description" rows="2" cols="1"></textarea>

              </div>
            </div>

          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Product Type</label>
              </div>
              <div class="form-group">
                <!-- <input type="hidden" readonly class="form-control" name="product_type" value="custom" /> -->
                <select class="form-control" name="product_type" disabled>
                  <option value="standard">Standard Price Product</option>
                  <option value="custom" selected>Custom Price Product</option>
                </select>
                <div class="invalid-feedback">Please enter a product type.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Payment Term<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_payment_term">
                  @foreach($paymentTerms as $paymentTerm)
                  <option value="{{ $paymentTerm->short_code }}">{{ $paymentTerm->title }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please enter a product payment term.</div>

              </div>

            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Currency<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="currency" id="currencyInput">
                  <option value="">-Select Currency-</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please enter a product currency.</div>
              </div>
            </div>
            <div class="col-md-4">
              <!-- <div class="form-group">
                <label class="form-label">Currency Conversion</label>
              </div>
              <div class="form-group">
              <input class="form-control" type="text" name="currency_rate" />
              <div class="invalid-feedback">Please enter a currency rate.</div>
            </div> -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Manager<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-select" name="manager_id" id="managerInput">
                  <option value="" selected="">--</option>
                  @foreach ($managers as $key => $row)
                  <option value="{{ $row->id }}">
                    {{ $row->user->name }}
                  </option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please select product manager.</div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Selling Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="selling_price" id="sellingPrice" />
                <div class="invalid-feedback">Please enter a price.</div>
              </div>
            </div>


            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">MOSP<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="margin_percentage" id="marginPercentage" />
                <div class="invalid-feedback">Please enter a MOSP.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Margin Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="margin_price" id="marginPrice">
                <div class="invalid-feedback">Please enter a margin price.</div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="form-label">Image</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="file" name="image" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="date" id="durationSelect">
                  <option value="1">1 Month</option>
                  <option value="3" selected>3 Month</option>
                  <option value="6">6 Month</option>
                  <option value="9">9 Month</option>
                  <option value="12">12 Month</option>
                </select>
                <div class="invalid-feedback">Please select an date.</div>
              </div>
              <div class="form-group" id="dateRangeGroup" style="display: none;">
                <label class="form-label">Dates:</label>
                <span id="dateRange"></span>
                <input type="hidden" name="start_date" id="startDateInput" />
                <input type="hidden" name="end_date" id="endDateInput" />
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Product Category<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_category" id="productcategoryInput">
                  <option value="">-Select-</option>
                  <option value="products">Products</option>
                  <option value="accessories">Accessories</option>
                  <option value="consumables">Consumables</option>
                  <option value="spares">Spares</option>
                </select>
                <div class="invalid-feedback">Please select a product category.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Status<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="status">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>
            </div>
            <div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelButton" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveProduct">Save</button>
          </div>
      </div>
      </form>

    </div>

    <input class="form-control" type="hidden" name="total_amount" />
    <input class="form-control" type="hidden" name="gross_margin" />
    <input class="form-control" type="hidden" name="product_currency" />
  </div>

</div>

<div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Existing Product's Pricing</h5>
      </div>
      <div class="modal-body">
        <p class="small">Please refer and select any of the 3 latest price of this product, else please add your own price below.</p>
        <div id="productDetails">
        </div>
        <div id="additionalText" style="cursor: pointer; color: #007D88;" data-bs-toggle="modal" data-bs-target="#additionalFieldsModal">Do you want to add a new price?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="cancelProduct" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-secondary btn-select">Select Price</button>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="additionalFieldsModal" tabindex="-1" aria-labelledby="additionalFieldsModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="additionalFieldsModalLabel">Add New Price</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Selling Price<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="selling_price" id="sellingPriceHistory" />
              <div class="invalid-feedback">Please enter a selling price.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">MOSP<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="margin_percentage" id="marginPercentageHistory" />
              <div class="invalid-feedback">Please enter a MOSP.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Margin Price</label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="margin_price" id="marginPriceHistory" readonly />
              <div class="invalid-feedback">Please enter margin price.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Currency<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <select class="form-control" name="quote_curr" id="quoteCurrencyHistory">
                <option value="">-Select Currency-</option>
                @foreach($currencies as $currency)
                <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                @endforeach
              </select>
              <div class="invalid-feedback">Please select currency.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Price Basis<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <select class="form-control" name="payment_term" id="priceBasis">
                @foreach($paymentTerms as $paymentTerm)
                <option value="{{ $paymentTerm->short_code }}">{{ $paymentTerm->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeAdditionalFieldsModal">Close</button>
        <button type="button" class="btn btn-primary" id="saveAdditionalFields">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- end models -->

<script>
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

  function removeQuotationCharge(rowId) {
    var chargeAmount = parseFloat($('#row-' + rowId).find('input[name="charge_amount[]"]').val()) || 0;
    var totalAmount = parseFloat($('input[name="total_value"]').val()) || 0;
    var newTotalAmount = totalAmount - chargeAmount;
    $('input[name="total_value"]').val(newTotalAmount.toFixed(2));
    $('#row-' + rowId).remove();
  }

  function addRow() {
    var newRow = `<div class="row" style="margin-top: 12px;">
  <div class="col-sm-3">
  <select name="product[]" class="form-select category-select" placeholder="Category">
  <option value="products" selected>Products</option>
  <option value="accessories">Accessories</option>
  <option value="consumables">Consumables</option>
  <option value="spares">Spares</option>
  </select>
  <div style="margin-top: 12px;">
  <select name="item_id[]" class="form-select modelno-select" placeholder="Model" id="modelDropdown" required style="margin-bottom: 9px;">
  </div>
  <div>
  <textarea class="form-control mb-1 product_description" name="item_description[]" placeholder="Description" rows="2"></textarea>
  </div>
  <div class="customCurrencySection" style="--bs-gutter-x: 0; display: none; padding-top: 3px;">
  <div class="d-flex">
  <input type="text" class="currencyConvertion form-control" placeholder="Conversion Rate" name="currency_convertion[]">
  <button id="applyCurrency" class="form-control ms-2"  style="background-color: #007D88; color: white;">Apply</button>
  </div>

  </div>
  <p class="text-warning" style="display:none;"><small>If your price in AED. Please apply conversion to other currencies.</small></p>
  </div>
  <div class="col-sm-2">
  <input type="text" name="unit_price[]"  placeholder="Unit Price" class="form-control unit-price" readonly>
  <div style="margin-top: 12px;">
  <span class="display-margin-price"></span>

  </div>
  <div style="margin-top: 12px;">
  <span class="display-margin-percent"></span>

  </div>
  </div>
  <div class="col-sm-1">
  <input type="number" name="quantity[]" placeholder="Quantity" class="form-control quantity">
  <input type="hidden" name="margin_percent[]" placeholder="Margin" class="form-control margin-percent">
  <input type="hidden"  name="suppliers_value[]"  class="form-control suppliersValue" >

 <input type="hidden" name="product_selling[]"  class="form-control product-selling">
 <input type="hidden" name="product_margin[]"  class="form-control product-margin">
<input type="hidden" name="product_currency[]"  class="form-control product-currency">
<input type="hidden" name="product_currency_convert[]" class="form-control product-currency-convert">
<input type="hidden" name="product_percent[]"  class="form-control product-percent">


  </div>
  <div class="col-sm-2">
  <input type="number" name="subtotal[]" placeholder="Subtotal" class="form-control subtotal" readonly>
  </div>
  <div class="col-sm-1">
  <input type="number" name="discount[]" placeholder="Discount" class="form-control discount" id="discountInput" min="0">
  <div style="padding:6px;" class="warning-message"></div>
  </div>

  <div class="col-sm-2">
  <input type="number" name="total_after_discount[]" placeholder="Total" class="form-control total" readonly>
  <div style="margin-top: 12px;">
  <span class="quote-margin-price"></span>
  </div>
  <input type="hidden" name="margin_amount_row[]" placeholder="Margin" class="form-control margin-amount-row">
  </div>
  <div class="col-sm-1">
  <button type="button" onclick="removeQuotationRow(this)" class="remove-button">
  <i class="fas fa-trash"></i>
  </button>
  </div>
  </div>`;
    $('#itemsContainer').append(newRow);
    $('#itemsContainer').find('.modelno-select').select2({
           placeholder: "Search model",
           allowClear: true // Optional, allows clearing selection
       });
    var newCategoryDropdown = $('#itemsContainer').find('.category-select').last();
    initializeModelDropdown(newCategoryDropdown);
    newCategoryDropdown.trigger('change');
  }

  function removeQuotationRow(button) {
    var row = $(button).closest('.row');
    var totalAmountInput = $('input[name="total_value"]');
    var totalAmount = parseFloat(totalAmountInput.val()) || 0;

    if (row.attr('id') === 'stableRow') {

    } else {
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
  }
  $('#cancelButton').on('click', function() {
    $('#myModal').modal('hide');
  });
</script>
<script>
  $(document).ready(function() {

    function resetModals() {
      $('#productDetails').empty();
      $('#sellingPriceHistory, #marginPercentageHistory, #quoteCurrencyHistory, #marginPriceHistory').val('');
      $('#priceBasis').val('');
    }


    $('#saveProduct').click(function() {
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
        '#productcategoryInput',
        '#durationSelect',
        '#currencyInput',
        '#managerInput'
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
        $('#productForm').submit();
      }
    });

    $('#delivery-terms-info1, #delivery-terms1, #delivery-terms2,#working_periods').on('input', function() {

      var weeksValue = $('#delivery-terms-info1').val();
      var selectedValue = $('#delivery-terms1').is(':checked') ? 'In Stock' : 'Out of Stock';
      var selectedValueId = $('input[name="delivery_terms"]:checked').val();
      var selectedPeriod = $('select[name="working_period"]').val();

      if (selectedValueId == 'is_stock') {
        var deliveryText = 'On stock subject to prior sale';
      } else {
        var deliveryText = 'Production time ' + weeksValue + ' ' + selectedPeriod + ' from the date of PO along with advance payment (if any)';
      }

      $('#quotation_terms_delivery').text(deliveryText);
    });

    $('#myModal').on('shown.bs.modal', function() {
      var modal = $(this);

      // $('#saveProduct').off('click');
      $('#saveProduct').click(function(e) {

        e.preventDefault();
        var formData = new FormData($('#productForm')[0]);
        formData.append('title', $('input[name=title]').val());
        formData.append('division_id', $('select[name=division_id]').val());
        formData.append('brand_id', $('select[name=brand_id]').val());
        formData.append('model_no', $('input[name=model_no]').val());
        formData.append('description', $('textarea[name=description]').val());
        formData.append('product_type', $('select[name=product_type]').val());
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
        formData.append('status', $('select[name=status]').val());
        formData.append('allowed_discount', $('input[name=allowed_discount]').val());
        formData.append('part_number', $('input[name=part_number]').val());

        var row = $(this).closest('.row');
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
            //console.log(response.data);
            if (response.data) {

              var model = response.data;
              var supplierName = model.supplier.brand;

              // var optgroup = modal.find('.modelno-select optgroup[label="' + supplierName + '"]');
              // if (optgroup.length === 0) {
              //   // Create a new optgroup if it doesn't exist
              //   optgroup = $('<optgroup label="' + supplierName + '">');
              //   modal.find('.modelno-select').append(optgroup);
              // }

              var row = $('#itemsContainer').find('.row').last();
              var marginSpan = row.find('.display-margin-price');
              var marginPercentSpan = row.find('.display-margin-percent');
              var marginPercent = row.find('.margin-percent');
              var productCurrency = row.find('.product-currency');
              var productCurrencyConvertFlag = row.find('.product-currency-convert');

              var quoteCurrency = $('#currencyDropdown').val();
              if (model.currency === 'aed') {
                row.find('.customCurrencySection').show();
              } else {
                row.find('.customCurrencySection').hide();
              }

              marginSpan.text('MARGIN PRICE: ' + model.margin_price)
                .css({
                  'font-size': '12px',
                  'color': 'red'
                });
              marginPercentSpan.text('MOSP (%): ' + model.margin_percent)
                .css({
                  'font-size': '12px',
                  'color': 'red',
                  'display': 'block'
                });
              marginPercent.val(model.margin_percent);
              marginSpan.show();
              marginPercentSpan.show();
              row.find('.unit-price').val(model.selling_price);
              row.find('.product-selling').val(model.selling_price);
              row.find('.product-margin').val(model.margin_price);
              row.find('.product-percent').val(model.margin_percent);
              productCurrency.val(model.currency);

              let selopt = model.title;
              if (model.modelno) selopt += ' / (' + model.modelno + ')';
              if (model.part_number) selopt += ' (' + model.part_number + ')';

              var option = $('<option></option>').attr('value', model.id).text(selopt);
              row.find('.modelno-select').append(option);

              row.find('.modelno-select').val(model.id);

              if (quoteCurrency != productCurrency.val()) {
                productCurrencyConvertFlag.val(1);
                //alert('Your selected quotation currency does not match with your product currency. Please add item price with quotation currency.');
                // return false;
              }

              modal.find('input[type=text], input[type=number], textarea').val('');
              modal.find('select').val('').trigger('change');
              modal.modal('hide');

            }

          },
        });
      });
    });

    $('#applyCurrency').click(function() {

      var row = $(this).closest('.row');
      var unitPriceInput = row.find('.unit-price');
      var quantityInput = row.find('.quantity');
      var discountInput = row.find('.discount');
      var subtotalInput = row.find('.subtotal');
      var totalInput = row.find('.total');
      var quoteMargin = row.find('.quote-margin-price');

      var row = $(this).closest('.row');

      var unitPriceInputValue = row.find('.product-selling').val();
      var unitPriceInput = row.find('.unit-price');
      var currencyConversion = row.find('.currencyConvertion').val();
      var marginSpan = row.find('.display-margin-price');
      var marginPrice = row.find('.product-margin').val();
      var marginPercentSpan = row.find('.display-margin-percent');
      var marginPercent = row.find('.product-percent').val();
      var productCurrencyConvertFlag = row.find('.product-currency-convert');

      event.preventDefault();
      var conversionRate = parseFloat(currencyConversion);

      var newSellingPrice = unitPriceInputValue / conversionRate;
      unitPriceInput.val((newSellingPrice).toFixed(2));
      if (marginPrice) {
        marginSpan.text('MARGIN PRICE: ' + (marginPrice / conversionRate).toFixed(2))
          .css({
            'font-size': '12px',
            'color': 'red'
          });
      }
      if (marginPercent) {
        marginPercentSpan.text('MARGIN PERCENT: ' + marginPercent.toFixed(2))
          .css({
            'font-size': '12px',
            'color': 'red'
          });
      }

      row.find('.quote-margin-price').hide();
      productCurrencyConvertFlag.val(0);

    });
  });
</script>
<script>
  function updateCurrencyLabel() {
    var selectedCurrency = document.getElementById("currencyDropdown").value.toUpperCase();
    var labels = document.getElementsByClassName("currency-label");
    var labelsTerm = document.getElementsByClassName("currency-label-terms");

    for (var i = 0; i < labels.length; i++) {
      var labelText = labels[i].textContent;
      labelText = labelText.replace(/\([A-Z]+\)/g, '');
      labels[i].textContent = labelText + ' (' + selectedCurrency + ')';

      if (labelsTerm[i]) {
        // var paymentTermDropdown = document.getElementById("paymentTerm");
        // var selectedOption = paymentTermDropdown.options[paymentTermDropdown.selectedIndex];
        // var paymentTitle = selectedOption.getAttribute("data-title");

        labelsTerm[i].textContent = "Quoted in" + ' ' + selectedCurrency + '. ';
        // if (paymentTitle && selectedOption.value !== "") {
        //   labelsTerm[i].textContent += paymentTitle;
        // }

        var categoryDropdown = $('select[name="product[]"]');
        // initializeModelDropdown(categoryDropdown);
      }
    }
  }

  document.getElementById('durationSelect').addEventListener('change', function() {
    var selectedValue = this.value;
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
  var responseData;

  function initializeModelDropdown(categoryDropdown) {

    var selectedCategory = categoryDropdown.val();
    var modelnoDropdown = categoryDropdown.closest('.row').find('.modelno-select');
    var unitPriceInput = categoryDropdown.closest('.row').find('.unit-price');
    var productDiscount = categoryDropdown.closest('.row').find('.discount');
    var selectedSupplierId = $('select[name="supplier_id[]"]').val();
    modelnoDropdown.empty();


    if (selectedCategory) {

      $.ajax({
        url: '/fetch-product-models',
        method: 'GET',
        data: {
          category: selectedCategory,
          supplier_id: selectedSupplierId
        },
        success: function(data) {
          console.log(data);
          modelnoDropdown.empty();

          if (data.length > 0) {
            data.forEach(function(supplier) {
              var optgroup = $('<optgroup label="' + supplier.supplier_name + '">');

              // Append "Select Model" as the first option within each supplier's section
              optgroup.prepend('<option value="" disabled selected>-Select Model-</option>');

              supplier.models.forEach(function(model) {
                // optgroup.append('<option value="' + model.id + '">' + model.title + '</option>');
                let selopt = model.title;
                if (model.modelno) selopt += ' / (' + model.modelno + ')';
                if (model.part_number) selopt += ' (' + model.part_number + ')';

                optgroup.append('<option value="' + model.id + '">' + selopt + '</option>');
              });
              modelnoDropdown.append(optgroup);
            });

            modelnoDropdown.append('<option value="product_modal" class="add-new-option">Add New</option>');

            modelnoDropdown.on('change', function() {
              if ($(this).val() === 'product_modal') {
                var row = $(this).closest('.row');
                var categorySelect = row.find('.category-select');
                var categoryValue = categorySelect.val();
                $('#myModal').modal('show');
                $('#productcategoryInput').val(categoryValue);
                $(this).val('');
              }
            });
            responseData = data;

            modelnoDropdown.trigger('change');
          } else {
            modelnoDropdown.append('<option value="">No models available</option>');
            modelnoDropdown.append('<option value="product_modal" class="add-new-option">Add New</option>');
            unitPriceInput.val('');
            productDiscount.val('');
          }
        },
        error: function(error) {
          console.error('Error fetching product models:', error);
        }
      });
    }
  }


  $(document).ready(function() {
    $('#modelDropdown').select2({
           placeholder: "Search model",
           allowClear: true
       });
    initializeAutocomplete(0);
    var selectedCategories = [];
    var selectedModelIds = [];
    var row = $(this);
    row.find('input[name="item_ids[]"]').each(function() {
      var itemId = $(this).val();
      selectedModelIds.push(itemId);
    });

    $('select[name="supplier_id[]"]').change(function() {

      var selectedSupplierId = $(this).val();
      $('select[name="product[]"]').each(function() {
        var productValues = $(this).val();
        selectedCategories.push(productValues);
      });
      fetchModels(selectedCategories, selectedSupplierId)

    });
    $(document).on('click', '.select2-selection__choice__remove', function(event) {
      event.stopPropagation();

      var optionValue = $(this).parent().attr('title');

      var rows = $('#itemsContainer').find('.row');

      rows.each(function() {

        var suppliersValue = $(this).find('.suppliersValue');

        var value = suppliersValue.val();


        if (value === optionValue) {
          $(this).remove();
        }
      });


      var selectedCategories = [];
      $('select[name="product[]"]').each(function() {
        var productValues = $(this).val();
        selectedCategories.push(productValues);
      });

      var selectedSuppliers = [];
      $('select[name="supplier_id[]"]').each(function() {
        var supplierValues = $(this).val();
        selectedSuppliers.push(supplierValues);
      });

      fetchModels(selectedCategories, selectedSuppliers);
    });



    //   function fetchModels(selectedCategory, selectedSupplierId) {

    //     var quotationId = $('#quotation_id').val();

    //     // Collect selected categories and supplier IDs
    //     var selectedCategories = [];
    //     $('select[name="product[]"]').each(function() {
    //       var productValues = $(this).val();
    //       selectedCategories.push(productValues);
    //     });


    //     var selectedSuppliers = [];
    //     $('select[name="supplier_id[]"]').each(function() {
    //       var supplierValues = $(this).val();
    //       selectedSuppliers.push(supplierValues);
    //     });

    //     // Store the selected model IDs before fetching new models
    //     var previouslySelectedModelIds = [];
    //     $('.modelno-select').each(function() {
    //       previouslySelectedModelIds.push($(this).val());
    //     });
    //     $.ajax({
    //       url: '/fetch-edit-models/' + quotationId,
    //       method: 'GET',
    //       data: {
    //         selectedCategory: selectedCategories,
    //         selectedSupplier: selectedSuppliers
    //       },
    //       success: function(data) {
    //         var modelnoDropdowns = $('.modelno-select');
    //         responseDatas = data; // Assign data to responseData

    //         modelnoDropdowns.each(function(index, dropdown) {
    //           var dropdownElement = $(dropdown);
    //           dropdownElement.empty();

    //           // Check if data is empty or null
    //           if (!data || data.length === 0) {
    //             dropdownElement.append('<option value="" disabled selected>No models available</option>');
    //             return; // Exit the loop if no data is available
    //           }

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
    //               optgroup.append('<option value="' + model.id + '">' + model.modelno + '</option>');
    //             });

    //             dropdownElement.append(optgroup);
    //           });

    //           dropdownElement.append('<option value="product_modal" class="add-new-option">Add New</option>');
    //           dropdownElement.on('change', function() {
    //             if ($(this).val() === 'product_modal') {
    //               $('#myModal').modal('show');
    //               $(this).val('');
    //             }
    //           });

    //           var selectedId = previouslySelectedModelIds[index]; // Use previously selected model ID
    //           if (selectedId) {
    //             dropdownElement.val(selectedId);
    //           }
    //         });
    //       },

    //       error: function(error) {
    //         console.error('Error fetching edit models:', error);
    //       }
    //     });
    //   }
    function fetchModels(selectedCategory, selectedSupplierId) {
      var quotationId = $('#quotation_id').val();

      // Collect selected categories and supplier IDs
      var selectedCategories = [];
      $('select[name="product[]"]').each(function() {
        var productValues = $(this).val();
        selectedCategories.push(productValues);
      });

      var selectedSuppliers = [];
      $('select[name="supplier_id[]"]').each(function() {
        var supplierValues = $(this).val();
        selectedSuppliers.push(supplierValues);
      });

      // Store the selected model IDs before fetching new models
      var previouslySelectedModelIds = [];
      $('.modelno-select').each(function() {
        previouslySelectedModelIds.push($(this).val());
      });

      $.ajax({
        url: '/fetch-edit-models/' + quotationId,
        method: 'GET',
        data: {
          selectedCategory: selectedCategories,
          selectedSupplier: selectedSuppliers
        },
        success: function(data) {
          var modelnoDropdowns = $('.modelno-select');
          responseDatas = data; // Assign data to responseData

          modelnoDropdowns.each(function(index, dropdown) {
            var dropdownElement = $(dropdown);
            dropdownElement.empty();

            // Check if data is available
            if (data && data.length > index) {
              var categoryData = data[index];
              var category = categoryData.category;
              var modelsBySupplier = categoryData.models;

              if (modelsBySupplier && Object.keys(modelsBySupplier).length > 0) {
                // If models exist, populate the dropdown
                Object.keys(modelsBySupplier).forEach(function(supplierName) {
                  var supplierModels = modelsBySupplier[supplierName];
                  var optgroup = $('<optgroup label="' + supplierName + '">');
                  optgroup.append('<option value="" disabled selected>-Select Model-</option>');

                  // Iterate over models for the current supplier
                  supplierModels.forEach(function(model) {
                    let selopt = model.title;
                    if (model.modelno) selopt += ' / (' + model.modelno + ')';
                    if (model.part_number) selopt += '(' + model.part_number + ')';

                    optgroup.append('<option value="' + model.id + '">' + selopt + '</option>');

                    // optgroup.append('<option value="' + model.id + '">' + model.title + '</option>');
                  });

                  dropdownElement.append(optgroup);
                });

                // Add the "Add New" option outside the loop
                dropdownElement.append('<option value="product_modal" class="add-new-option">Add New</option>');

                dropdownElement.prop('disabled', false); // Enable the dropdown
              } else {
                // If no models exist, show "No models available" and disable the dropdown
                dropdownElement.append('<option value="" disabled selected>No models available</option>');
                // dropdownElement.prop('disabled', true); // Disable the dropdown
                dropdownElement.append('<option value="product_modal" class="add-new-option">Add New</option>');
              }
            } else {
              // If no data is available, show "No models available" and disable the dropdown
              dropdownElement.append('<option value="" disabled selected>No models available</option>');
              // dropdownElement.prop('disabled', true); // Disable the dropdown
              dropdownElement.append('<option value="product_modal" class="add-new-option">Add New</option>');
            }

            dropdownElement.on('change', function() {

              if ($(this).val() === 'product_modal') {

                $('#myModal').modal('show');
                $(this).val('');
              }
            });

            var selectedId = previouslySelectedModelIds[index]; // Use previously selected model ID
            if (selectedId) {
              dropdownElement.val(selectedId);
            }
          });
        },

        error: function(error) {
          console.error('Error fetching edit models:', error);
        }
      });
    }

    $(document).on('change', '.category-select', function() {
      initializeModelDropdown($(this));
      var selectedCategories = $(this).val();
      var row = $(this).closest('.row');
      row.find('.customCurrencySection').hide();
      row.find('.text-warning').hide();

    });

    $(document).on('click', '.modelno-select, .category-select', function() {
      var quoteCurrency = $('#currencyDropdown').val();
      var selectedValues = $('#supplierDropdown').val();

      if (!quoteCurrency && selectedValues.length === 0) {
        alert('Please select a preferred currency and a Supplier.');
        $('#currencyDropdown').focus();
        return false;
      } else if (!quoteCurrency) {
        alert('Please select a preferred currency.');
        $('#currencyDropdown').focus();
        return false;
      } else if (selectedValues.length === 0) {
        alert('Please select a Supplier.');
        $('#supplierDropdown').focus();
        return false;
      }
    });

    $('#cancelProduct').on('click', function() {
      var row = $('#itemsContainer').find('.row').last();
      var unitPriceInput = row.find('.unit-price');
      var modelSelect = row.find('.modelno-select');
      var marginSpan = row.find('.display-margin-price');
      var marginPercentSpan = row.find('.display-margin-percent');
      unitPriceInput.val(0);
      modelSelect.val('');
      marginSpan.hide();
      marginPercentSpan.hide();
      row.find('.text-warning').hide();
      $('#customModal').modal('hide');
    });
    var paymentTerm;

    $(document).on('change', '.modelno-select', function() {
      var selectedModelId = $(this).val();
      var row = $(this).closest('.row');
      var unitPriceInput = row.find('.unit-price');
      var quantityInput = row.find('.quantity');
      var discountInput = row.find('.discount');
      var subtotalInput = row.find('.subtotal');
      var totalInput = row.find('.total');
      var suppliersValue = row.find('.suppliersValue');
      var quoteMarginInput = row.find('.quote-margin-price');
      var marginPrice = row.find('.margin');
      var marginSpan = row.find('.display-margin-price');
      var marginPercentSpan = row.find('.display-margin-percent');
      var marginPercentInput = row.find('.margin-percent');
      var quantityAlert = row.find('.warning-message');
      var itemDescription = row.find('.product_description');
      var productCurrency = row.find('.product-currency');
      var productCurrencyConvertFlag = row.find('.product-currency-convert');
      var quoteCurrency = $('#currencyDropdown').val();
      var currencyConversion = $('#currencyConvertion').val();

      quantityAlert.text('');
      marginSpan.text('');
      marginPercentSpan.text('');
      quoteMarginInput.text('');
      discountInput.val('');


      discountInput.off('input').on('input', function() {

        var discount = parseFloat(discountInput.val()) || 0;
        var subtotal = parseFloat(subtotalInput.val()) || 0.00;
        var total = subtotal - (subtotal * discount / 100);
        totalInput.val(total.toFixed(2));

        if (selectedModelData && discount > selectedModelData.allowed_discount && selectedModelData.product_type != 'custom') {
          quantityAlert.text('The entered discount cannot exceed ' + selectedModelData.allowed_discount + ', please contact admin')
            .css({
              'font-size': '12px',
              'color': 'red'
            });
          discountInput.val("");
        }
      });

      if (!selectedModelId) {
        unitPriceInput.val(0.00);
        quantityInput.val(0);
        discountInput.val(0);
        subtotalInput.val(0.00);
        totalInput.val(0.00);
        quoteMarginInput.val(0.00);
        return;
      }


      if (responseData) {

        var selectedModelData;

        responseData.forEach(function(supplier) {
          // console.log(supplier);
          var modelsArray = supplier.models;
          var model = modelsArray.find(function(model) {
            return model.id == selectedModelId;
          });

          if (model) {
            selectedModelData = model;
            return false;
          }
        });
      } else {

        var selectedModelData;
        responseDatas.forEach(function(supplier) {
          // console.log(supplier);
          Object.values(supplier.models).forEach(function(modelsArray) {
            var model = modelsArray.find(function(model) {
              return model.id == selectedModelId;
            });

            if (model) {
              selectedModelData = model;
              return false;
            }

          });
        });
      }

      if (selectedModelData) {


        itemDescription.val(selectedModelData.description);
        unitPriceInput.val(selectedModelData.selling_price);
        suppliersValue.val(selectedModelData.supplier_name);
        productCurrency.val(selectedModelData.currency);
        marginSpan.text('MARGIN PRICE: ' + selectedModelData.margin_price)
          .css({
            'font-size': '12px',
            'color': 'red'
          });
        marginPercentSpan.text('MOSP (%): ' + selectedModelData.margin_percent)
          .css({
            'font-size': '12px',
            'color': 'red',
            'display': 'block'
          });

      }

      if (quoteCurrency != productCurrency.val()) {
        productCurrencyConvertFlag.val(1);
        alert('Your selected quotation currency does not match with your product currency. Please apply currency conversion.');
        //return false;
      }
      if (selectedModelData.currency === 'aed') {
        row.find('.customCurrencySection').show();
        var selectedCurrency = $('select[name="quote_currency"]').val();

        $.ajax({
          url: '/get-currency-conversion-rate',
          method: 'GET',
          data: {
            currencyCode: selectedCurrency
          },
          success: function(data) {
            console.log(data);
            var defaultCurrencyRate = data.standard_rate;
            row.find('.currencyConvertion').val(defaultCurrencyRate);
          },
          error: function(error) {
            console.error('Error fetching default currency rate:', error);
          }
        });
      } else {
        row.find('.customCurrencySection').hide();
      }


      var selectedCurrency = $('select[name="quote_currency"]').val();

      if (selectedModelData.product_type == 'standard' && selectedCurrency == 'aed') {

        row.find('.text-warning').hide();
      } else {
        row.find('.text-warning').show();
      }
      $(document).on('click', '#applyCurrency', function() {
        var row = $(this).closest('.row');
        var unitPriceInput = row.find('.unit-price');
        var quantityInput = row.find('.quantity');
        var discountInput = row.find('.discount');
        var subtotalInput = row.find('.subtotal');
        var totalInput = row.find('.total');
        var quoteMargin = row.find('.quote-margin-price');
        quoteMargin.hide();
        quantityInput.val(0);
        discountInput.val(0);
        subtotalInput.val(0.00);
        totalInput.val(0.00);

        var row = $(this).closest('.row');
        var selectedModelId = row.find('.modelno-select').val();
        var unitPriceInput = row.find('.unit-price');
        var currencyConversion = row.find('.currencyConvertion').val();
        var marginSpan = row.find('.display-margin-price');
        var marginPercentSpan = row.find('.display-margin-percent');
        var productCurrencyConvertFlag = row.find('.product-currency-convert');

        event.preventDefault();
        var conversionRate = parseFloat(currencyConversion);
        if (!isNaN(conversionRate) && conversionRate !== 0) {
          var originalSellingPrice = parseFloat(selectedModelData.selling_price);
          var newSellingPrice = originalSellingPrice / conversionRate;
          unitPriceInput.val(newSellingPrice.toFixed(2));
          marginSpan.text('MARGIN PRICE: ' + (selectedModelData.margin_price / conversionRate).toFixed(2))
            .css({
              'font-size': '12px',
              'color': 'red'
            });
          productCurrencyConvertFlag.val(0);

        } else {
          console.error('Invalid conversion rate');
        }
      });

      if (selectedModelData) {

        // Update the input fields with data from selectedModelData
        unitPriceInput.val(selectedModelData.selling_price);
        marginPercentInput.val(selectedModelData.margin_percent);

        if (selectedModelData.product_type == 'custom' && selectedModelData.product_category == 'products') {
          $('#customModal').modal('show');
          $('#additionalText').on('click', function() {
            $('input[name="priceBasisRadio"]').prop('checked', false);
            var sellingPrice = $('#sellingPriceHistory').val();
            var marginPercentage = $('#marginPercentageHistory').val();
            var calculatedMarginPrice = sellingPrice * (marginPercentage / 100);
            $('#additionalFieldsModal').modal('show');
          });
          document.getElementById('closeAdditionalFieldsModal').addEventListener('click', function() {
            $('#customModal').modal('show');
          });

          function updateValues() {
            var sellingPrice = parseFloat($('#sellingPriceHistory').val());
            var marginPercentage = parseFloat($('#marginPercentageHistory').val());

            if (!isNaN(sellingPrice) && !isNaN(marginPercentage)) {
              var marginPrice = sellingPrice * (marginPercentage / 100);

              $('#marginPriceHistory').val(marginPrice.toFixed(2));
            } else {
              $('#marginPriceHistory').val('');
            }
          }

          $('#sellingPriceHistory, #marginPercentageHistory').on('input', updateValues);

          // Function to reset modal values
          function resetModalValues() {
            $('#sellingPriceHistory').val('');
            $('#marginPercentageHistory').val('');
            $('#quoteCurrencyHistory').val('');
            $('#marginPriceHistory').val('');
            $('input[name="product_ids"]').val('');
            $('#priceBasis').val('');
          }


          function saveAdditionalFieldsHandler(saveOperationPerformed) {

            if (!saveOperationPerformed) {

              saveOperationPerformed = true;

              $(this).prop('disabled', true);
              var sellingPrice = $('#sellingPriceHistory').val();
              var marginPercentage = $('#marginPercentageHistory').val();
              var quoteCurrency = $('#quoteCurrencyHistory').val();
              var marginPrice = $('#marginPriceHistory').val();
              var productId = $('input[name="product_ids"]').val();
              var priceBasis = $('#priceBasis').val();

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
                  price_basis: priceBasis
                },
                success: function(response) {
                  if (response.success) {

                    $('#additionalFieldsModal').modal('hide');
                    $('#customModal').modal('show');
                    refreshProductHistory();
                    resetModalValues();
                  } else {}
                },
                error: function(xhr, status, error) {
                  console.error('Error saving values:', error);
                },
                complete: function() {
                  $('#saveAdditionalFields').prop('disabled', false);
                  saveOperationPerformed = false;
                }
              });
            }
          }

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
              '#priceBasis'
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
              saveAdditionalFieldsHandler(false);
            }
          });

          $('#additionalFieldsModal').on('hidden.bs.modal', function() {
            saveOperationPerformed = false;
          });

          function formatDate(dateString) {
            var date = new Date(dateString);
            var day = ('0' + date.getDate()).slice(-2);
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();

            var formattedDate = day + '/' + month + '/' + year;
            return formattedDate;
          }

          function refreshProductHistory() {
            $.ajax({
              url: '{{ route("productHistory", ":id") }}'.replace(':id', selectedModelData.id),
              method: 'GET',
              success: function(response) {
                var productHistoryHtml = '<table class="table"><thead><tr><th></th><th>Name</th><th>Price</th><th>Margin Price</th><th>MOSP</th><th>Price Basis</th><th>Added By</th><th>Date</th></tr></thead><tbody>';
                var counter = 0;

                $.each(response, function(index, history) {
                  productHistoryHtml += '<tr>' +
                    '<td>' +
                    '<div class="form-check">' +
                    '<input class="form-check-input" type="radio" name="priceBasisRadio" value="' + history.id + '" data-row-index="' + counter + '" data-history-id="' + history.id + '" data-selling-price="' + history.selling_price + '" data-margin-price="' + history.margin_price + '"data-margin-percent="' + history.margin_percent + '"data-allowed-discount="' + history.product_discount + '"data-currency="' + history.currency + '">' +
                    '<label class="form-check-label" for="priceBasisRadio_' + counter + '"></label>' +
                    '</div>' +
                    '</td>' +
                    '<td>' + history.product_title + '</td>' +
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

          function attachEventListeners() {
            $('input[name="priceBasisRadio"]').on('click', function() {
              var rowIndex = $(this).data('row-index');
              var historyId = $(this).data('history-id');
              var sellingPrice = $(this).data('selling-price');
              var marginPrice = $(this).data('margin-price');
              var marginPercent = $(this).data('margin-percent');
              var allowedDiscount = $(this).data('allowed-discocunt');
              var currency = $(this).data('currency');

              if ($(this).prop('checked')) {
                var row = $(this).closest('.row');

                var unitPriceInput = row.find('.unit-price');
                var marginSpan = row.find('.display-margin-price');
                var marginPercentSpan = row.find('.display-margin-percent');

                var selectedCurrency = $('select[name="quote_currency"]').val();


                if (selectedCurrency !== currency) {

                  alert('The product currency (' + currency + ') does not match the preferred currency (' + selectedCurrency + ').Please add a new price');
                  $(this).prop('checked', false);

                  marginSpan.hide();
                } else {

                  marginSpan.show();
                }
              }

            });

            $('.btn-select').off('click').on('click', function() {
              var selectedRadio = $('input[name="priceBasisRadio"]:checked');
              var unitPriceInput = row.find('.unit-price');
              var marginPercentInput = row.find('.margin-percent');
              var marginSpan = row.find('.display-margin-price');
              var marginPercentSpan = row.find('.display-margin-percent');
              var productCurrency = row.find('.product-currency');
              var productCurrencyConvertFlag = row.find('.product-currency-convert');


              if (selectedRadio.length > 0) {
                var sellingPrice = selectedRadio.data('selling-price');
                var marginPrice = selectedRadio.data('margin-price');
                var marginPercent = selectedRadio.data('margin-percent');
                var allowedDiscount = selectedRadio.data('allowed-discount');
                var itemCurrency = selectedRadio.data('currency');
                unitPriceInput.val(sellingPrice);
                marginPercentInput.val(marginPercent);

                productCurrency.val(itemCurrency);
                var selectedCurrency = $('select[name="quote_currency"]').val();

                if (selectedCurrency != productCurrency.val()) {
                  productCurrencyConvertFlag.val(1);
                }
                marginSpan.text('MARGIN PRICE: ' + marginPrice)
                  .css({
                    'font-size': '12px',
                    'color': 'red'
                  });
                marginPercentSpan.text('MOSP (%): ' + marginPercent)
                  .css({
                    'font-size': '12px',
                    'color': 'red',
                    'display': 'block'
                  });
                $('#customModal').modal('hide');
              } else {

              }
            });
          }
          refreshProductHistory();
        }

        $('input[name="product_currency"]').val(selectedModelData.currency);
        marginSpan.text('MARGIN PRICE: ' + selectedModelData.margin_price)
          .css({
            'font-size': '12px',
            'color': 'red'
          });
        marginPercentSpan.text('MOSP (%): ' + selectedModelData.margin_percent)
          .css({
            'font-size': '12px',
            'color': 'red',
            'display': 'block'
          });
        marginPrice.val(selectedModelData.margin_price);
        quantityInput.val(0);
        discountInput.val(0);
        subtotalInput.val(0.00);
        totalInput.val(0.00);
      } else {
        unitPriceInput.val(0.00);
        quantityInput.val(0);
        discountInput.val(0);
        subtotalInput.val(0.00);
        totalInput.val(0.00);
      }
    });


    // $(document).on('input change', '.discount', function () {
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
    //   var quoteMargin = subtotal * (marginPercent - discount) / 100;
    //   row.find('.margin-amount-row').val(quoteMargin.toFixed(2));
    //   $('.quote-margin-price').text('Total Margin: ' + quoteMargin.toFixed(2)).css({'color': 'red', 'font-size': '12px'});
    // });
    var alertShown = false;
    $(document).on('input change', '.quantity, .discount', function(e) { //,
      e.preventDefault();

      var row = $(this).closest('.row');
      var quantity = parseFloat(row.find('.quantity').val()) || 0;
      var unitPrice = parseFloat(row.find('.unit-price').val()) || 0.00;
      var discount = parseFloat(row.find('.discount').val()) || 0;
      var marginPercent = row.find('.margin-percent').val() || 0;
      var quoteMargin = row.find('.quote-margin-price');
      var productCurrency = row.find('.product-currency').val();
      var productCurrencyConvertFlag = row.find('.product-currency-convert').val() || 0;
      var selectedCurrency = $("#currencyDropdown").val();
      var subTotal = row.find(".subtotal").val();
      alertShown = (productCurrencyConvertFlag == 1) ? true : false;

      if (productCurrency != selectedCurrency && alertShown) {
        alert('Your selected currency does not match with your product currency. Please apply currency conversion.');
        // alertShown = true; // Set the flag to true to indicate the alert has been shown
        //row.find('.unit-price').val('');
        row.find('.quantity').val('');
        // row.find('.subtotal').val('');
        return false;
      }

      // if (productCurrency != 'aed' && !alertShown) {
      //   alert('The product currency (' + productCurrency + ') does not match the preferred currency (' + selectedCurrency + ').Please add a new price');
      //   alertShown = true;
      //   row.find('.unit-price').val('');
      //   row.find('.quantity').val('');
      //   row.find('.subtotal').val('');
      //   return false;
      // }

      var subtotal = quantity * unitPrice;
      row.find('.subtotal').val(subtotal.toFixed(2));

      var total = subtotal - (subtotal * discount / 100);
      row.find('.total').val(total.toFixed(2));

      var quoteMargin = subtotal * ((marginPercent - discount) / 100);

      row.find('.margin-amount-row').val(quoteMargin.toFixed(2));

      // Recalculate total margin for all rows
      var totalMargin = 0;
      $('.row').each(function() {
        var rowMargin = parseFloat($(this).find('.margin-amount-row').val()) || 0;
        totalMargin += rowMargin;
      });


      // Update the total margin text
      // $('.quote-margin-price').text('Total Margin: ' + totalMargin.toFixed(2)).css({'color': 'red', 'font-size': '12px'});

      // Display the quote margin in the quote-margin-price element
      row.find('.quote-margin-price').text('Quote Margin: ' + quoteMargin.toFixed(2)).css({
        'color': 'green',
        'font-size': '12px'
      });
      row.find('.quote-margin-price').show();
    });


    // $(document).on('input change', '.quantity, .unit-price, .discount, #discountInput', function() {
    //   alert(777)
    //   var row = $(this).closest('.row');
    //   var quantity = parseFloat(row.find('.quantity').val()) || 0;
    //   var unitPrice = parseFloat(row.find('.unit-price').val()) || 0.00;
    //   var discount = parseFloat(row.find('.discount').val()) || 0;
    //   var margin = parseFloat(row.find('.margin').val()) || 0.00;
    //   var marginPercent = parseFloat(row.find('.margin-percent').val()) || 0.00;

    //   var subtotal = quantity * unitPrice;
    //   row.find('.subtotal').val(subtotal.toFixed(2));

    //   // Calculate total after discount
    //   var total = subtotal - (subtotal * discount / 100);
    //   row.find('.total').val(total.toFixed(2));

    //   var total;
    //   if ($(this).is('#discountInput')) {
    //     total = subtotal - (subtotal * discount / 100);
    //     row.find('.total').val(total.toFixed(2));
    //     return;
    //   }
    // });

    function calculateOverallTotal() {
      var overallTotal = 0;
      var overallMargin = 0;
      var vatRate = 0.05; // VAT rate of 5%
      var vatIncluded = $('input[name="vat_option"]:checked').val() === '1';
      var sumAfterDiscount = 0;
      var totalMargin = 0;
      var vatAmount = 0;
      var quotationCharges = 0;
      var totalMarginSum = 0;

      $('input[name="total_after_discount[]"]').each(function() {
        var rowTotalAfterDiscount = parseFloat($(this).val()) || 0;
        overallTotal += rowTotalAfterDiscount;
        sumAfterDiscount += rowTotalAfterDiscount;
      });
      $('input[name="margin_amount_row[]"]').each(function() {
        var rowTotalMargin = parseFloat($(this).val()) || 0;
        overallMargin += rowTotalMargin;
        totalMargin += rowTotalMargin;

      });

      $('input[name="charge_amount[]"]').each(function() {
        var chargeAmount = parseFloat($(this).val()) || 0;
        quotationCharges += chargeAmount;
      });

      sumAfterDiscount += quotationCharges;

      if (vatIncluded) {
        vatAmount = sumAfterDiscount * vatRate;
        sumAfterDiscount += vatAmount;
      }

      $('#totalValue').val(sumAfterDiscount.toFixed(2));
      $('#totalMarginValue').val(totalMargin.toFixed(2));

      $('input[name="total_amount"]').val(sumAfterDiscount.toFixed(2));

      $('input[name="gross_margin"]').val(totalMarginSum.toFixed(2));
      $('#vatAmountLabel').text(vatAmount.toFixed(2));

      $('input[name="vat_amount"]').val(vatAmount.toFixed(2));

      if (!vatIncluded) {
        $('#vatAmountLabel').text('0.00');

      }
    }
    $(document).on('change keyup', 'input[name="total_after_discount[]"], input[name="charge_amount[]"], input[name="quantity[]"], input[name="subtotal[]"], input[name="discount[]"], input[name="vat_option"], input[name="margin[]"]', function() {
      calculateOverallTotal();
    });

    calculateOverallTotal();

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
        sentences.push('If, for any reason, the site/space/power/other utilities/materials that are necessary for installation are not ready at the client site within 30 days from the arrival of the machine at the buyer factory, the seller (YES Machinery) is authorized to invoice the balance payment linked to the installation and commissioning.');
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
</script>

<script>
  $(document).ready(function() {
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
  });


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
</script>

<script>
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
</script>
