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
                    <select class="form-control" name="price_basis" id="paymentTerm" required onchange="updateQuotationTerms()" required>
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
                <input type="text" readonly class="form-control" name="product_type" value="custom" />
                <!-- <select class="form-control" name="product_type" disabled>
                                    <option value="standard">Standard Price Product</option>
                                    <option value="custom" selected>Custom Price Product</option>
                                </select> -->
                <!-- <div class="invalid-feedback">Please enter a product type.</div> -->
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
                  <option value="1" selected>1 Month</option>
                  <option value="3">3 Month</option>
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
              <input class="form-control" type="text" name="margin_price" id="marginPriceHistory" />
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
        newRow += '<td><textarea class="form-control" name="item_description[]" rows="2">' + title + '</textarea></td>';
        newRow += '<td><input type="text" class="form-control unit-price" name="unit_price[]" value="' + unitprice + '" readonly/><p class="text-danger">MOSP: <span class="mosp-label">' + mosp + '</span>%</p></td>';
        newRow += '<td><input type="number" class="form-control quantity" name="quantity[]" min="1" value="' + qty + '"/></td>';
        newRow += '<td><input type="text" class="form-control subtotal" name="subtotal[]" value="' + subtotal + '" readonly/></td>';
        newRow += '<td><input type="number" class="form-control discount" name="discount[]" min="0" value="' + discount + '"/></td>';
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
        text: "You are sure to delete the product !",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          iter--;
          let row = $(this).parents('tr');
          removeQuotationRow(row);

          $(this).parents('tr').remove();
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
        newRow += '<td><input type="number" class="form-control quantity" name="quantity[]" min="1" value="' + qty + '"/></td>';
        newRow += '<td><input type="text" class="form-control subtotal" name="subtotal[]" value="' + subtotal + '" readonly/></td>';
        newRow += '<td><input type="number" class="form-control discount" name="discount[]" min="0" value="' + discount + '"/></td>';
        newRow += '<td><input type="text" class="form-control total-price" name="total_after_discount[]" value="' + total + '" readonly/><p class="text-danger">New Margin: <span class="new-margin-label">' + newmargin + '</span></p></td>';
        newRow += `<td><input type="hidden" name="item_id[]" value="${selProductId}"/>
                            <input type="hidden" name="brand_id[]" value="${brandId}"/>
                            <input type="hidden" name="product_selling_price[]" value="${sellingPrice}"/>
                            <input type="hidden" class="margin-percent" name="margin_percent[]" value="${mosp}"/>
                            <input type="hidden" class="allowed-discount" name="allowed_discount[]" value="${allowedDiscount}"/>
                            <input type="hidden" name="product_margin[]" value="${marginPrice}"/>
                            <input type="hidden" name="product_currency[]" value="${itemCurrency}"/>
                            <input type="hidden" name="margin_amount_row[]" value="${newmargin}" class="margin-amount-row">
                            <a href="javascript:void(0);" data-id="drow-${selProductId}" class="del-item"><i class="fas fa-trash"></i></a>
                            </td>`;

        newRow += '</tr>';

        $("#product_item_tbl").find('tbody').append(newRow);
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

    // end
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

  function attachEventListeners() {
    $('input[name="priceBasisRadio"]').on('click', function() {
      let rowIndex = $(this).data('row-index');
      let historyId = $(this).data('history-id');
      let sellingPrice = $(this).data('selling-price');
      let marginPrice = $(this).data('margin-price');
      let marginPercent = $(this).data('margin-percent');
      let allowedDiscount = $(this).data('allowed-discocunt');
      let currency = $(this).data('currency');

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
      let priceBasis = $('#priceBasis').val();

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

  function createNewProduct(isValid) {
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
      formData.append('status', $('select[name=status]').val());
      formData.append('allowed_discount', $('input[name=allowed_discount]').val());
      formData.append('part_number', $('input[name=part_number]').val());

      // 
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
            newRow += '<tr id="irow-' + selProductId + '">';
            newRow += '<td><textarea class="form-control" name="item_description[]" rows="2">' + title + '</textarea></td>';
            newRow += '<td><input type="text" class="form-control unit-price" name="unit_price[]" value="' + unitprice + '" readonly/><p class="text-danger">MOSP: <span class="mosp-label">' + mosp + '</span>%</p></td>';
            newRow += '<td><input type="number" class="form-control quantity" name="quantity[]" min="1" value="' + qty + '"/></td>';
            newRow += '<td><input type="text" class="form-control subtotal" name="subtotal[]" value="' + subtotal + '" readonly/></td>';
            newRow += '<td><input type="number" class="form-control discount" name="discount[]" min="0" value="' + discount + '"/></td>';
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
    var vatIncluded = $('input[name="vat_option"]:checked').val(); // error
    var sumAfterDiscount = 0;
    var totalMargin = 0;
    var vatAmount = 0;
    var quotationCharges = 0;
    var totalMarginSum = 0;
    // total amount
    $('input[name="total_after_discount[]"]').each(function() {
      var rowTotalAfterDiscount = parseFloat($(this).val()) || 0;
      //  overallTotal += rowTotalAfterDiscount;
      sumAfterDiscount += rowTotalAfterDiscount;
    });
    // total margins
    $('input[name="margin_amount_row[]"]').each(function() {
      var rowTotalMargin = parseFloat($(this).val()) || 0;
      // overallMargin += rowTotalMargin;
      totalMargin += rowTotalMargin;
    });
    // total charges
    $('input[name="charge_amount[]"]').each(function() {
      var chargeAmount = parseFloat($(this).val()) || 0;
      quotationCharges += chargeAmount;
    });
    // add charges + total
    sumAfterDiscount += quotationCharges;
    //vat amount
    if (vatIncluded == 1) {
      vatAmount = sumAfterDiscount * vatRate;
      sumAfterDiscount += vatAmount;
    }

    $('#totalValue').val(sumAfterDiscount.toFixed(2));
    $('#totalMarginValue').val(totalMargin.toFixed(2));

    //$('input[name="total_amount"]').val(sumAfterDiscount.toFixed(2));

    //$('input[name="gross_margin"]').val(totalMargin.toFixed(2));
    $('#vatAmountLabel').text(vatAmount.toFixed(2));

    $('input[name="vat_amount"]').val(vatAmount.toFixed(2));

    if (!vatIncluded) {
      $('#vatAmountLabel').text('0.00');

    }
  }
</script>

@endsection