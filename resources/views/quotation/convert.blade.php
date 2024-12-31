@extends('layouts.default')

@section('content')
<div class="container-xxl">
  <!-- Page Header -->
  <div class="hk-pg-header pt-7 pb-4">
    <h1 class="pg-title">Convert Leads</h1>
    <p>The enquiry can be converted to quotation with values & winning possibility.</p>
  </div>
  <!-- /Page Header -->

  <!-- Page Body -->
  <div class="hk-pg-body">
    <div class="row edit-profile-wrap">
      <div class="col-lg-12 col-sm-12 col-12">
        @if (count($errors) > 0)
        <div class="alert alert-danger">
          <strong>Whoops!</strong> There were some problems with your input.<br><br>
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
        <div class="mt-2">
          @include('layouts.partials.messages')
        </div>
        <form method="POST" action="{{ route('quotations.store') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="company_id" value="{{$lead->company_id}}" />
          <input type="hidden" name="customer_id" value="{{$lead->customer_id}}" />
          <input type="hidden" name="assigned_to" value="{{$lead->assigned_to}}" />
          <input type="hidden" name="lead_id" value="{{$lead->id}}" />
          <input type="hidden" name="lead_type" value="{{$lead->lead_type}}" />
          <div class="row gx-3">
            <div class="col-6">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Lead Info</span></div>
              <div class="row gx-3 mt-4">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Company</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    {{$lead->company->company}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Region</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    {{($lead->company->region_id)?$lead->company->region->state:"--"}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Country</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    {{($lead->company->country_id)?$lead->company->country->name:"--"}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Customers</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    {{$lead->customer->fullname}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">E-Mail</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    {{$lead->customer->email}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Phone</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    {{$lead->customer->phone}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Type</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    {{$lead->lead_type}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Details</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    {{$lead->details}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Enquiry Date</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    {{$lead->enquiry_date}}
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Status</label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <span class="badge badge-soft-danger">{{$lead->leadStatus->name}}</span>
                  </div>
                </div>
              </div>

            </div>
            <div class="col-6">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Quotation Info</span></div>
              <div class="row gx-3 mt-4">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Company <span class="text-danger">*</span></label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <select class="form-select" name="quote_from" required id="from_company">
                      <option value="yesmachinery">Yesmachinery</option>
                      <option value="yesclean">Yesclean</option>
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Quotation For <span class="text-danger">*</span></label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <select class="form-select" name="quote_for" required>
                      <option value="sales">Sales</option>
                      <option value="service">Service</option>
                      <!-- <option value="spareparts">Spareparts Sale</option> -->
                      <!-- <option value="service_spareparts">Service & Spareparts</option> -->
                    </select>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label class="form-label">Supplier <span class="text-danger">*</span></label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <select class="form-select select2" name="supplier_id[]" id="supplierDropdown" multiple required>
                      <option value="">--</option>
                      @foreach ($suppliers as $k => $sup)
                      <option value="{{ $sup->id }}" data-supplier-id="{{ $sup->id }}">{{ $sup->brand }}</option>
                      {{ $sup->brand }}
                    </option>
                    @endforeach
                  </select>
                  <a href="{{route('suppliers.index')}}" target="_blank" class="btn-link small mt-1">+ Add New</a>
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Quote Submitted On <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="date" name="submitted_date" required />
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Winning Probability (%) <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="number" name="winning_probability" required />
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Closing Date <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="date" name="closure_date" required />
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Status <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <select class="form-select" name="status_id" required>
                    <option value="">--</option>
                    @foreach($quoteStatuses as $id=> $stat)
                    <option value="{{$id}}">{{$stat}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Reminder</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-check form-switch mb-1">
                  <label class="form-check-label" for="setreminder">Set Reminder</label>
                  <input type="checkbox" class="form-check-input" id="setreminder">
                </div>
                <div class="form-group">
                  <input class="form-control d-none" type="text" name="reminder" id="reminder" />
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Remarks</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <textarea class="form-control" name="remarks" row="10"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-12">
            <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Products & Delivery Terms</span></div>
          </div>
        </div>
        <div class="row gx-3 mt-3">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <label class="form-label">Preferred Currency <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <select class="form-control" name="quote_currency" id="currencyDropdown" onchange="updateCurrencyLabel()" required>
                    <option value="">-Select Currency-</option>
                    @foreach($currencies as $currency)
                    <option value="{{ $currency->code }}">{{ strtoupper($currency->code) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Currency Conversion Rate<span class="text-danger">*</span></label>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" name="currency_rate" id="currency_factor" class="form-control" required />
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                <label class="form-label">Delivery Terms <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <select class="form-control" name="price_basis" id="paymentTerm" required  required>
                    <option value="">-Select Delivery Terms-</option>
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
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="separator"></div>
          </div>
        </div>
        @include('quotation.partial.add_new_row')

        @include('quotation.partial.quotation_add_row_create')

        <div class="row gx-1">
          <div class="col-3"></div>
          <div class="col-6">
            <button type="submit" name="publish" class="btn btn-primary mt-5 me-2">Create Quotations</button>
            <button type="submit" name="draft" class="btn btn-info mt-5 me-2">Save as Draft</button>
            <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='/quotations'">Back To List</button>
          </div>
          <div class="col-4"></div>
        </div>

      </form>
    </div>
  </div>
</div>
</div>
</div>
<!-- ------------------- MODELS ------------------- -->
<!-- Add New Product -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="padding: 16px;">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Add Product</h5>
      </div>
      <div class="modal-body">
        <form id="productForm">
          <input type="hidden" name="allowed_discount" id="allowed_discount" value="0" />
          <input type="hidden" name="product_type" value="custom" />
          <input type="hidden" name="status" value="active" />
          <div class="row gx-3">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Product Details</span></div>
            </div>
          </div>

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
                <label class="form-label">Product Category<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_category" id="productcategoryInput">
                  <option value="">-Select-</option>
                  <option value="products">Products</option>
                  <option value="accessories">Accessories</option>
                  <option value="consumables">Consumables</option>
                  <option value="spares">Spares</option>
                  <option value="services">Services</option>
                </select>
                <div class="invalid-feedback">Please select a product category.</div>
              </div>
            </div>
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
            <div class="col-sm-4">
              <div class="form-group">
                <label class="form-label">Image</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="file" name="image" />
              </div>
            </div>
          </div>

          <div class="row gx-3 mt-2 mb-2">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Buying Price Details</span></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Buying Currency<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="buying_currency" id="productBuyingCurrency" required>
                  <option value="">-Select Currency-</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please select a currency.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Gross Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="gross_price" id="gross_price" required />

                <div class="invalid-feedback">Please enter a gross price.</div>
              </div>
            </div>
            <div class="col-md-4" id="purchase_discount_percent">
              <div class="form-group">
                <label class="form-label">Purchase Discount(%)</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="number" name="discount" id="purchase_discount">
                <div class="invalid-feedback">Please enter a purchase discount.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4" id="purchase_discount_price">
              <div class="form-group">
                <label class="form-label">Purchase Discount Amount<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="discount_amount" id="purchase_discount_amount" required />
                <div class="invalid-feedback">Please enter a purchase discount price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Buying Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="buying_price" id="buying_price" required />
                <div class="invalid-feedback">Please enter a buying price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="purchase_price_duration" id="purchasedurationSelect">
                  <option value="">Select</option>
                  <option value="1" selected>1 Month</option>
                  <option value="3">3 Month</option>
                  <option value="6">6 Month</option>
                  <option value="9">9 Month</option>
                  <option value="12">12 Month</option>
                </select>
                <div class="invalid-feedback">Please choose a date.</div>
              </div>

              <div class="form-group small" id="purchasedateRangeGroup" style="display: none;">
                <label class="form-label">Dates:</label>
                <span id="purchasedateRange"></span>
                <input type="hidden" name="validity_from" id="validity_from" />
                <input type="hidden" name="validity_to" id="validity_to" />
              </div>
            </div>
          </div>


          <div class="row gx-3 mt-2 mb-2">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Selling Price Details</span></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Payment Term<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_payment_term"  id="productPriceBasis" >
                  <option value="" disabled selected>Select a payment term</option>
                  @foreach($paymentTerms as $paymentTerm)
                  <option value="{{ $paymentTerm->short_code }}" data-id="{{ $paymentTerm->id }}">{{ $paymentTerm->title }}</option>
                  @endforeach
                </select>
                <input type="hidden" name="product_payment_id" id="productPaymentTermId" />
                <div class="invalid-feedback">Please enter a product payment term.</div>

              </div>

            </div>
            <div class="col-md-6">
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

          </div>
          <div id="productCustomFieldsContainer" class="col-12 mt-3">

          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Margin Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="margin_price" id="marginPrice">
                <div class="invalid-feedback">Please enter a margin price.</div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">MOSP<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="margin_percentage" id="marginPercentage" />
                <div class="invalid-feedback">Please enter a MOSP.</div>
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
                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="date" id="durationSelect">
                  <option value="1" selected>1 Month</option>
                  <option value="3">3 Month</option>
                  <option value="6">6 Month</option>
                  <option value="9">9 Month</option>
                  <option value="12">12 Month</option>
                </select>
                <div class="invalid-feedback">Please select an date.</div>
              </div>
              <div class="form-group small" id="dateRangeGroup" style="display: none;">
                <label class="form-label">Dates:</label>
                <span id="dateRange"></span>
                <input type="hidden" name="start_date" id="startDateInput" />
                <input type="hidden" name="end_date" id="endDateInput" />
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label class="form-label">Is Demo<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-check-input" type="radio" name="is_demo" id="is_demo" value="1">
                <label class="form-check-label" for="is_demo">
                  Yes
                </label>
                <input class="form-check-input" type="radio" name="is_demo" id="is_demo" value="0" checked>
                <label class="form-check-label" for="is_demo">
                  No
                </label>
              </div>
            </div>


          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveProduct">Save</button>
          </div>
        </div>
      </form>

    </div>

    <!-- <input class="form-control" type="hidden" name="total_amount" />
    <input class="form-control" type="hidden" name="gross_margin" />
    <input class="form-control" type="hidden" name="product_currency" /> -->
  </div>

</div>

<!-- Select Existing Price from history of a Product -->
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
          <button type="button" class="btn btn-secondary" id="cancelProduct" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-secondary btn-select">Select Price</button>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Add New Price for a product -->
<div class="modal fade" id="additionalFieldsModal" tabindex="-1" aria-labelledby="additionalFieldsModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="additionalFieldsModalLabel">Add New Price</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Price Basis<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <select class="form-control" name="payment_term" id="historyPriceBasis" onchange="updateQuotationPriceBasis()">
                <option value="" disabled selected>-- Select Price Basis --</option>
                @foreach($paymentTerms as $paymentTerm)
                <option value="{{ $paymentTerm->short_code }}" data-id="{{ $paymentTerm->id }}">{{ $paymentTerm->title }}</option>
                @endforeach
              </select>
              <input type="hidden" name="payment_term_id" id="historyPaymentTermId" />
            </div>
          </div>

          <div class="row gx-3 mt-2 mb-2">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Buying Price Details</span></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Gross Price<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="gross_price" id="buying_gross_price" required />
              <div class="invalid-data" style="display: none;">Please enter a gross price.</div>
            </div>
          </div>

          <div class="col-md-6" id="purchase_discount_percent">
            <div class="form-group">
              <label class="form-label">Purchase Discount(%)</label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="discount" id="buying_purchase_discount">
              <div class="invalid-data" style="display: none;">Please enter a purchase discount .</div>
            </div>
          </div>
          <div class="col-md-6" id="purchase_discount_price">
            <div class="form-group">
              <label class="form-label">Purchase Discount Amount<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="discount_amount" id="buying_purchase_discount_amount" required />
              <div class="invalid-data" style="display: none;">Please enter a purchase discount price.</div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Buying Price<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="buying_price" id="buying_prices" readonly />
              <div class="invalid-data" style="display: none;">Please enter a buying price.</div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Currency<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <select class="form-control" name="buying_currency" id="buyingCurrencyHistory">
                <option value="">-Select Currency-</option>
                @foreach($currencies as $currency)
                <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                @endforeach
              </select>
              <div class="invalid-feedback">Please select currency.</div>
            </div>
          </div>



          <div class="form-group">
            <input class="form-check-input" type="checkbox" name="default_buying_price" id="defaultBuyingPrice" value="1"/>
            <label class="form-check-label" for="defaultPriceCheckbox">
              Do you want to make this the default buying price?
            </label>
          </div>
          <div class="row gx-3 mt-2 mb-2">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Selling Price Details</span></div>
            </div>
          </div>
          <div id="customFieldsContainer" class="col-12 mt-3">
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label class="form-label">Margin Price</label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="margin_price" id="marginPriceHistory" />
              <div class="invalid-feedback">Please enter margin price.</div>
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
              <label class="form-label">Selling Price<span class="text-danger">*</span></label>
            </div>
            <div class="form-group">
              <input class="form-control" type="text" name="selling_price" id="sellingPriceHistory" readonly/>
              <div class="invalid-feedback">Please enter a selling price.</div>
            </div>
          </div>

          <div class="col-md-4">
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

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <input class="form-check-input" type="checkbox" name="default_selling_price" id="defaultSellingPrice" value="1"/>
                <label class="form-check-label" for="defaultPriceCheckbox">Do you want to make this the default selling  price?
                </label>
              </div>
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

<!-- ------------------- end models ------------------- -->

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
  $('#companyid').on('change', function(e) {
    e.preventDefault();

    let compid = $(this).val();
    let selText = $("#companyid option:selected").text();
    $('#company').val(selText);

    $.get('/customersbyid/?company_id=' + compid, function(data) {
      let opt = '';
      jQuery.each(data, function(index, value) {
        //   console.log(value);
        opt += '<option value="' + value.id + '">' + value.fullname + '</option>';
      });
      // console.log(opt)
      $('#customerid').append(opt);
    });

  });

  $('#customerid').on('change', function(e) {
    e.preventDefault();

    let custid = $(this).val();

    $.get('/customers/' + custid, function(data) {
      $('#fullname').val(data.fullname);
      // $('#company').val(data.company);
      $('#email').val(data.email);
      $('#phone').val(data.phone);
      $('#location').val(data.location);
    });
  });

  $('#setreminder').on('change', function(e) {
    e.preventDefault();
    if ($('#reminder').hasClass('d-none')) {
      $('#reminder').removeClass('d-none');
    } else {
      $('#reminder').addClass('d-none');
      $('#reminder').val('');
    }
  });
  /* Single table*/
  $('input[name="reminder"]').daterangepicker({
    singleDatePicker: true,
    timePicker: true,
    showDropdowns: false,
    //   minYear: new Date().getFullYear(),
    "cancelClass": "btn-secondary",
    locale: {
      format: 'DD/MM/YYYY hh:mm A'
    }
    // maxYear: parseInt(moment().format('YYYY'), 10)
  }, function(start, end, label) {
    //  var years = moment().diff(start, 'years');
    // alert("You are " + years + " years old!");
  });

  $("#purchasedurationSelect").on("change", function(e) {
    let selectedValue = $(this).val();
    setPurchaseDateRange(selectedValue);
  });
  // default purchase date range
  setPurchaseDateRange(1);

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

  document.getElementById('buying_gross_price').addEventListener('input', updateBuyingPrice);
  document.getElementById('buying_purchase_discount').addEventListener('input', updateBuyingPrice);
  document.getElementById('buying_purchase_discount_amount').addEventListener('input', updateBuyingPriceWithAmount);




  const marginPriceInput = document.getElementById('marginPrice');
  const marginPercentageInput = document.getElementById('marginPercentage');
  const sellingPriceInput = document.getElementById('sellingPrice');
  const buyingPriceInput = document.getElementById('buying_price');

  const marginPriceHistoryInput = document.getElementById('marginPriceHistory');
  const marginPercentageHistoryInput = document.getElementById('marginPercentageHistory');
  const sellingPriceHistoryInput = document.getElementById('sellingPriceHistory');
  const buyingPriceHistoryInput = document.getElementById('buying_prices');



  marginPriceInput.addEventListener('input', updateMarginPrice);
  marginPercentageInput.addEventListener('input', updateMarginPercentage);

  marginPriceHistoryInput.addEventListener('input', updateHistoryMarginPrice);
  marginPercentageHistoryInput.addEventListener('input', updateHistoryMarginPercentage);



  function updateHistoryMarginPrice() {
    const buyingPrice = parseFloat(buyingPriceHistoryInput.value) || 0;
    const marginPrice = parseFloat(marginPriceHistoryInput.value) || 0;

    // Sum the custom fields values
    const totalCustomFieldsValue = customsArray.reduce((sum, item) => {
      return sum + (item.value || 0);
    }, 0);

    if (buyingPrice > 0) {
      const marginPercentage = (marginPrice / buyingPrice) * 100;
      const sellingPrice = buyingPrice + marginPrice + totalCustomFieldsValue;

      marginPercentageHistoryInput.value = marginPercentage.toFixed(2);
      sellingPriceHistoryInput.value = sellingPrice.toFixed(2);
    }
  }

  function updateHistoryMarginPercentage() {
    const buyingPrice = parseFloat(buyingPriceHistoryInput.value) || 0;
    const marginPercentage = parseFloat(marginPercentageHistoryInput.value) || 0;

    // Sum the custom fields values
    const totalCustomFieldsValue = customsArray.reduce((sum, item) => {
      return sum + (item.value || 0);
    }, 0);

    if (buyingPrice > 0) {
      const marginPrice = (marginPercentage / 100) * buyingPrice;
      const sellingPrice = buyingPrice + marginPrice + totalCustomFieldsValue;

      marginPriceHistoryInput.value = marginPrice.toFixed(2);
      sellingPriceHistoryInput.value = sellingPrice.toFixed(2);
    }
  }




  function updateMarginPrice() {
    const buyingPrice = parseFloat(buyingPriceInput.value) || 0;
    const marginPrice = parseFloat(marginPriceInput.value) || 0;

    // Sum the custom fields values
    const totalCustomFieldsValue = productCustomFieldsArray.reduce((sum, item) => {
      return sum + (item.value || 0);
    }, 0);

    if (buyingPrice > 0) {
      const marginPercentage = (marginPrice / buyingPrice) * 100;
      const sellingPrice = buyingPrice + marginPrice + totalCustomFieldsValue;

      marginPercentageInput.value = marginPercentage.toFixed(2);
      sellingPriceInput.value = sellingPrice.toFixed(2);
    }
  }

  function updateMarginPercentage() {
    const buyingPrice = parseFloat(buyingPriceInput.value) || 0;
    const marginPercentage = parseFloat(marginPercentageInput.value) || 0;

    // Sum the custom fields values
    const totalCustomFieldsValue = productCustomFieldsArray.reduce((sum, item) => {
      return sum + (item.value || 0);
    }, 0);

    if (buyingPrice > 0) {
      const marginPrice = (marginPercentage / 100) * buyingPrice;
      const sellingPrice = buyingPrice + marginPrice + totalCustomFieldsValue;

      marginPriceInput.value = marginPrice.toFixed(2);
      sellingPriceInput.value = sellingPrice.toFixed(2);
    }
  }


  $('#gross_price, #purchase_discount').on('input', function() {
    updateBuyingPrice();
  });

  $('#buying_gross_price, #buying_purchase_discount').on('input', function() {
    updateHistoryBuyingPrice();
  });



  function updateBuyingPriceWithAmount() {
    let gross_price = $('#gross_price').val();
    let purchase_discount = $('#purchase_discount_amount').val();

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
  function updateHistoryBuyingPriceWithAmount() {
    let gross_price = $('#buying_gross_price').val();
    let purchase_discount = $('#buying_purchase_discount_amount').val();

    let basePrice = parseFloat(gross_price.replace(/,/g, ''));
    let dAmount = parseFloat(purchase_discount.replace(/,/g, ''));

    let buyingPriceInput = $('#buying_prices');

    if (!isNaN(basePrice) && !isNaN(dAmount)) {

      let calculatedDPrice = basePrice - dAmount;
      let dpercent = (dAmount / basePrice) * 100;
      dpercent = parseFloat(dpercent).toFixed(2);
      $('#buying_purchase_discount').val(dpercent);

      let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

      buyingPriceInput.val(formattedMarginPrice);
    }
  }
  function updateHistoryBuyingPrice() {
    let gross_price = $('#buying_gross_price').val(); // Get the value using jQuery
    let purchase_discount = $('#buying_purchase_discount').val(); // Get the value using jQuery

    let basePrice = parseFloat(gross_price.replace(/,/g, ''));
    let dPercentage = parseFloat(purchase_discount.replace(/,/g, ''));

    let buyingPriceInput = $('#buying_prices');

    if (!isNaN(basePrice) && !isNaN(dPercentage)) {

      let calculatedDPrice = basePrice * (dPercentage / 100);
      $('#buying_purchase_discount_amount').val(calculatedDPrice);
      calculatedDPrice = basePrice - calculatedDPrice;
      let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

      buyingPriceInput.val(formattedMarginPrice);
    } else if (!isNaN(basePrice)) {
      let formattedMarginPrice = numberWithCommas(basePrice.toFixed(2));

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




});


document.getElementById('historyPriceBasis').addEventListener('change', function () {

  const selectedOption = this.options[this.selectedIndex];
  const paymentTermId = selectedOption.getAttribute('data-id');


  document.getElementById('historyPaymentTermId').value = paymentTermId;
});
document.getElementById('productPriceBasis').addEventListener('change', function () {

  const selectedOption = this.options[this.selectedIndex];
  const paymentTermId = selectedOption.getAttribute('data-id');

  document.getElementById('productPaymentTermId').value = paymentTermId;
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
</script>
<script>
let customsArray = [];

document.addEventListener('DOMContentLoaded', function () {
  const priceBasisElement = document.getElementById('historyPriceBasis');
  const paymentTermIdElement = document.getElementById('historyPaymentTermId');
  const historyCustomFieldsContainer = document.getElementById('customFieldsContainer');
  const marginPercentageInput = document.getElementById('marginPercentageHistory');
  const marginPriceInput = document.getElementById('marginPriceHistory');
  const sellingPriceInput = document.getElementById('sellingPriceHistory');
  const buyingPriceInput = document.getElementById('buying_prices');




  function updateHistorySellingPrice() {
    const totalCustomFieldsValue = customsArray.reduce((sum, item) => {
      return sum + (parseFloat(item.value) || 0);
    }, 0);

    const marginPrice = parseFloat(marginPriceInput.value) || 0;
    const marginPercentage = parseFloat(marginPercentageInput.value) || 0;
    let calculatedSellingPrice;
    if (marginPrice === 0) {
      calculatedSellingPrice = totalCustomFieldsValue;

    } else {
      const basePrice = parseFloat(buyingPriceInput.value) || 0;
      console.log(basePrice);
      calculatedSellingPrice = basePrice + totalCustomFieldsValue + marginPrice;

    }

    sellingPriceInput.value = calculatedSellingPrice.toFixed(2);

  }

  marginPriceInput.addEventListener('input', updateHistorySellingPrice);

  marginPercentageInput.addEventListener('input', updateHistorySellingPrice);

  priceBasisElement.addEventListener('change', function () {
    const priceBasis = this.value;
    const paymentTermId = paymentTermIdElement.value;
    historyCustomFieldsContainer.innerHTML = '';

    if (priceBasis) {
      fetch('/get-custom-fields', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
          price_basis: priceBasis,
          payment_term_id: paymentTermId,
        }),
      })
      .then((response) => response.json())
      .then((data) => {
        historyCustomFieldsContainer.innerHTML = '';

        let rowContainer = document.createElement('div');
        rowContainer.classList.add('row');

        data.forEach((field, index) => {
          const fieldHTML = `
          <div class="form-group col-md-6">
          <label class="form-label">${field.field_name}<span class="text-danger">*</span></label>
          <input class="form-control dynamic-field" type="text"
          name="${field.short_code}" data-field-name="${field.short_code}" />
          </div>`;
          rowContainer.insertAdjacentHTML('beforeend', fieldHTML);

          if ((index + 1) % 2 === 0) {
            historyCustomFieldsContainer.appendChild(rowContainer);
            rowContainer = document.createElement('div');
            rowContainer.classList.add('row');
          }
        });

        if (rowContainer.children.length > 0) {
          customFieldsContainer.appendChild(rowContainer);
        }

        const dynamicFields = historyCustomFieldsContainer.querySelectorAll('.dynamic-field');
        dynamicFields.forEach((input) => {
          input.addEventListener('input', function () {
            const fieldName = this.getAttribute('data-field-name').trim();
            const fieldValue = parseFloat(this.value) || 0;

            const fieldIndex = customsArray.findIndex(
              (item) => item.field_name === fieldName
            );
            if (fieldIndex !== -1) {
              customsArray[fieldIndex].value = fieldValue;
            } else {
              customsArray.push({ field_name: fieldName, value: fieldValue });
            }



            updateHistorySellingPrice();
          });
        });
      })
      .catch((error) => console.error('Error fetching custom fields:', error));
    } else {
      customFieldsContainer.innerHTML = '';
    }
  });
});

</script>

<script>
let productCustomFieldsArray = [];
document.addEventListener('DOMContentLoaded', function () {
  const productPriceBasisElement = document.getElementById('productPriceBasis');
  const paymentTermIdElement = document.getElementById('productPaymentTermId');
  const customFieldsContainer = document.getElementById('productCustomFieldsContainer');
  const sellingPriceInput = document.getElementById('sellingPrice');
  const buyingPriceInput = document.getElementById('buying_price');
  const marginPriceInput = document.getElementById('marginPrice');
  const marginPercentageInput = document.getElementById('marginPercentage');

  function updateSellingPrice() {
    const totalCustomFieldsValue = productCustomFieldsArray.reduce((sum, item) => {
      return sum + (parseFloat(item.value) || 0);
    }, 0);

    const marginPrice = parseFloat(marginPriceInput.value) || 0;
    const marginPercentage = parseFloat(marginPercentageInput.value) || 0;
    let calculatedSellingPrice;
    if (marginPrice === 0) {
      calculatedSellingPrice = totalCustomFieldsValue;

    } else {
      const basePrice = parseFloat(buyingPriceInput.value) || 0;

      calculatedSellingPrice = basePrice + totalCustomFieldsValue + marginPrice;

    }

    sellingPriceInput.value = calculatedSellingPrice.toFixed(2);

  }

  marginPriceInput.addEventListener('input', updateSellingPrice);

  marginPercentageInput.addEventListener('input', updateSellingPrice);

  productPriceBasisElement.addEventListener('change', function () {
    const priceBasis = this.value;
    const paymentTermId = paymentTermIdElement.value;

    productCustomFieldsArray = [];
    customFieldsContainer.innerHTML = '';

    if (priceBasis) {
      fetch('/get-custom-fields', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({
          price_basis: priceBasis,
          payment_term_id: paymentTermId,
        }),
      })
      .then((response) => response.json())
      .then((data) => {
        customFieldsContainer.innerHTML = '';

        let rowContainer = document.createElement('div');
        rowContainer.classList.add('row');

        data.forEach((field, index) => {
          const fieldHTML = `
          <div class="form-group col-md-6">
          <label class="form-label">${field.field_name}<span class="text-danger">*</span></label>
          <input class="form-control dynamic-field" type="number" min="0"
          name="${field.short_code}" data-field-name="${field.short_code}" />
          </div>`;
          rowContainer.insertAdjacentHTML('beforeend', fieldHTML);

          if ((index + 1) % 2 === 0) {
            customFieldsContainer.appendChild(rowContainer);
            rowContainer = document.createElement('div');
            rowContainer.classList.add('row');
          }
        });

        if (rowContainer.children.length > 0) {
          customFieldsContainer.appendChild(rowContainer);
        }

        const dynamicFields = customFieldsContainer.querySelectorAll('.dynamic-field');
        dynamicFields.forEach((input) => {
          input.addEventListener('input', function () {
            const fieldName = this.getAttribute('data-field-name').trim();
            const fieldValue = parseFloat(this.value) || 0;

            // Update or add the field value in the array
            const fieldIndex = productCustomFieldsArray.findIndex(
              (item) => item.field_name === fieldName
            );
            if (fieldIndex !== -1) {
              productCustomFieldsArray[fieldIndex].value = fieldValue;
            } else {
              productCustomFieldsArray.push({ field_name: fieldName, value: fieldValue });
            }


            updateSellingPrice();
          });
        });
      })
      .catch((error) => console.error('Error fetching custom fields:', error));
    } else {
      customFieldsContainer.innerHTML = '';
    }
  });
});
</script>


<script>
$(document).ready(function () {
  $('#buying_gross_price, #buying_purchase_discount, #buying_purchase_discount_amount').on('input', function () {
    updateBuyingPrice();
  });

  function updateBuyingPrice() {

    let gross_price = $('#buying_gross_price').val();
    let purchase_discount = $('#buying_purchase_discount').val();
    let purchase_discount_amount = $('#buying_purchase_discount_amount').val();


    let basePrice = parseFloat(gross_price.replace(/,/g, '')) || 0;
    let discountPercentage = parseFloat(purchase_discount) || 0;
    let discountAmount = parseFloat(purchase_discount_amount.replace(/,/g, '')) || 0;

    if (basePrice > 0) {
      if (discountPercentage > 0 && purchase_discount_amount === '') {

        discountAmount = basePrice * (discountPercentage / 100);
        $('#buying_purchase_discount_amount').val(numberWithCommas(discountAmount.toFixed(2)));
      }

      if (discountAmount > 0 && purchase_discount === '') {
        discountPercentage = (discountAmount / basePrice) * 100;
        $('#buying_purchase_discount').val(discountPercentage.toFixed(2));
      }

      if (discountPercentage > 0) {

        discountAmount = basePrice * (discountPercentage / 100);
        $('#buying_purchase_discount_amount').val(numberWithCommas(discountAmount.toFixed(2)));
      }


      let buyingPrice = basePrice - discountAmount;
      $('#buying_prices').val(numberWithCommas(buyingPrice.toFixed(2)));
    } else {

      $('#buying_purchase_discount, #buying_purchase_discount_amount, #buying_prices').val('');
    }
  }

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  }
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  // Bind change events to the dropdowns
  const productPriceBasis = document.getElementById("productPriceBasis");
  const historyPriceBasis = document.getElementById("historyPriceBasis");
  const paymentTerm = document.getElementById("paymentTerm");

  if (productPriceBasis) {
    productPriceBasis.addEventListener("change", updateQuotationPriceBasis);
  }
  if (historyPriceBasis) {
    historyPriceBasis.addEventListener("change", updateQuotationPriceBasis);
  }
  if (paymentTerm) {
    paymentTerm.addEventListener("change", updateQuotationPriceBasis);
  }
});

function updateQuotationPriceBasis() {
  const selectedPaymentTerm = document.getElementById("productPriceBasis").value;
  const selectedHistoryPaymentTerm = document.getElementById("historyPriceBasis").value;
  const selectedDeliveryTerm = document.getElementById("paymentTerm") ? document.getElementById("paymentTerm").value : '';

  let errorMessage = "";
  let fieldToClear = null;


  if (selectedPaymentTerm && selectedDeliveryTerm && selectedPaymentTerm !== selectedDeliveryTerm) {
    errorMessage = "The selected Payment Term does not match the selected Delivery Term.";
    fieldToClear = "productPriceBasis";
  }

  else if (selectedHistoryPaymentTerm && selectedDeliveryTerm && selectedHistoryPaymentTerm !== selectedDeliveryTerm) {
    errorMessage = "The selected History Payment Term does not match the selected Delivery Term.";
    fieldToClear = "historyPriceBasis";
  }

  if (errorMessage) {
    Swal.fire({
      icon: "error",
      title: "Mismatch Detected",
      text: errorMessage,
      confirmButtonText: "OK",
    }).then(() => {
      if (fieldToClear) {
        document.getElementById(fieldToClear).value = "";
      }
    });
  }

  if (!selectedPaymentTerm) {
    document.getElementById("paymentTermError").style.display = "block";
  } else {
    document.getElementById("paymentTermError").style.display = "none";
  }
}
</script>

@endsection
