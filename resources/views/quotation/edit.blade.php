@extends('layouts.default')

@section('content')


<div class="container-xxl">
  <!-- Page Header -->
  <div class="hk-pg-header pt-7 pb-4">
    <h1 class="pg-title">Update Quotation</h1>
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

        {!! Form::model($quotation, ['method' => 'PATCH','enctype' => 'multipart/form-data', 'route' => ['quotations.update', $quotation->id]]) !!}
        {!! csrf_field() !!}

        <input type="hidden" value="{{$quotation->id}}" name="quotation_id" id="quotation_id">
        <div class="row gx-3">
          <div class="col-12">
            <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Customer Info</span></div>
          </div>
          <div class="col-6">
            <div class="row gx-3 mt-4">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Search Existing Company</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <select class="form-select select2" name="company_id" id="companyid" disabled>
                    <option value="">--</option>
                    @foreach($companies as $id => $comp)
                    <option value="{{$id}}" {{($quotation->company_id == $id)? "selected": ""}}>{{$comp}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Company <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="text" name="company" id="company" value="{{$quotation->company->company}}" readonly />
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Country <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <select class="form-select select2" name="country_id" id="country_id" readonly>
                    <option value="">--</option>
                    @foreach ($countries as $key => $row)
                    <option value="{{ $row->id }}" {{($quotation->company->country_id == $row->id)? "selected": "" }}>
                      {{ $row->name }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Region</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <span id="regid" class="d-none"></span>
                  <select class="form-select select2" name="region_id" id="region_id" disabled>
                    <option value="0">--</option>
                    @foreach($regions as $reg)
                    <option value="{{$reg->id}}" {{$quotation->company->region_id == $reg->id ? "selected": ""}}>{{$reg->state}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="row gx-3 mt-4">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Search Existing Customers</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <select class="form-select select2" name="customer_id" id="customerid" disabled>
                    <option selected="">--</option>
                    @foreach($customers as $cust)
                    <option value="{{$cust->id}}" {{($quotation->customer_id == $cust->id)? "selected": ""}}>{{$cust->fullname}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Full Name <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="text" name="fullname" id="fullname" value="{{ $quotation->customer->fullname}}" readonly />
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Email ID</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="email" name="email" id="email" value="{{$quotation->customer->email}}" readonly />
                </div>
              </div>
              <div class=" col-sm-6">
                <div class="form-group">
                  <label class="form-label">Phone <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class=" col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="text" name="phone" id="phone" value="{{$quotation->customer->phone}}" readonly />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row gx-3">
          <div class="col-12">
            <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Quotation Info</span></div>
          </div>
          <div class="col-6">
            <div class="row gx-3 mt-4">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">For Company <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <select class="form-select" name="quote_from" required id="from_company">
                    <option value="yesmachinery" {{($quotation->quote_from == "yesmachinery")? "selected": "" }}>Yesmachinery</option>
                    <option value="yesclean" {{($quotation->quote_from == "yesclean")? "selected": "" }}>Yesclean</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Lead Type <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <select class="form-select" name="lead_type" required>
                    <option value="">--</option>
                    <option value="internal" {{($quotation->lead_type == "internal")? "selected": "" }}>Internal/ Inside</option>
                    <option value="external" {{($quotation->lead_type == "external")? "selected": "" }}>External/ Outside</option>
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
                    <option value="sales" {{($quotation->quote_for == "sales")? "selected": "" }}>Sales</option>
                    <option value="service" {{($quotation->quote_for == "service")? "selected": "" }}>Service</option>
                    <!-- <option value="spareparts" {{($quotation->quote_for == "spareparts")? "selected": "" }}>Spareparts Sale</option>
                    <option value="service_spareparts" {{($quotation->quote_for == "service_spareparts")? "selected": "" }}>Service & Spareparts</option> -->
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
                    @foreach ($suppliers as $sup)
                    <option value="{{ $sup->id }}" @foreach ($quotationItems as $value) {{ $value->brand_id == $sup->id ? 'selected' : '' }} @endforeach>
                      {{ $sup->brand }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>


              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Assigned To <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <select class="form-select" name="assigned_to" required>
                    <option value="">--</option>
                    @foreach($employees as $emp)
                    <option value="{{$emp->id}}" {{($emp->id == $quotation->assigned_to)? "selected": "" }}>{{ $emp->user->name}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Remarks</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <textarea class="form-control" name="remarks" row="10">{{$quotation->remarks}}</textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="row gx-3 mt-4">
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Quote No.</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="text" name="reference_no" readonly required value="{{$quotation->reference_no}}" />
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Submitted On <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                @if($quotation->status_id == 6)
                <div class="form-group">
                  <input class="form-control" type="date" name="submitted_date" readonly value="{{$quotation->submitted_date}}" />
                </div>
                @else
                <div class="form-group">
                  <input class="form-control" type="date" name="submitted_date" required value="{{$quotation->submitted_date}}" />
                </div>
                @endif

              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Winning Probability (%) <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="text" name="winning_probability" required value="{{$quotation->winning_probability}}" />
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Closing Date <span class="text-danger">*</span></label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" type="date" name="closure_date" required value="{{$quotation->closure_date}}" />
                </div>
              </div>
              @if($quotation->status_id == 6)
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Win Date</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input type="date" name="win_date" id="win_date" class="form-control" value="{{$quotation->win_date}}" />
                </div>
              </div>
              @else
              <div class="col-sm-6">
                <div class="form-group">
                  <label class="form-label">Reminder</label>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-check form-switch mb-1">

                  <label class="form-check-label" for="setreminder">Set Reminder</label>
                  <input type="checkbox" class="form-check-input" id="setreminder" {{$quotation->reminder? "checked": ""}}>
                </div>
                <div class="form-group">
                  <input type="text" name="reminder" id="reminder" class="form-control {{!$quotation->reminder? 'd-none' : ''}}" />
                </div>
              </div>
              @endif
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

                  <select class="form-control" name="quote_currency" id="currencyDropdown" onchange="updateCurrencyLabel()"  required>
                    <option value="">-Select Currency-</option>
                    @foreach($currencies as $currency)
                    <option value="{{ $currency->code }}" {{ $quotation->preferred_currency == $currency->code ? 'selected' : '' }}>
                      {{ strtoupper($currency->code) }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Currency Conversion Rate<span class="text-danger">*</span></label>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <input type="text" name="currency_rate" id="currency_factor" value="{{$quotation->currency_rate}}" class="form-control" required />
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
                  <select class="form-control" name="price_basis" id="paymentTerm" required onchange="updateQuotationTerms()">
                    <option value="">-Select Delivery Terms-</option>
                    @foreach($paymentTermList as $paymentTerm)
                    <option value="{{ $paymentTerm->short_code }}" {{ $quotation->price_basis == $paymentTerm->short_code ? 'selected' : '' }} data-title="{{ $paymentTerm->title }}" data-parent="{{ $paymentTerm->id }}">{{ $paymentTerm->title }}</option>
                    @endforeach
                  </select>
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

        @if(count($quotationItems) >0)
        @include('quotation.partial.edit_new_row')
        @include('quotation.partial.quotation_add_row_edit')

        @else
        @include('quotation.partial.add_new_row')
        @include('quotation.partial.quotation_add_row_create')
        @endif
        <div class="row gx-3">
          <div class="col-12">
            <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Sales Commisions</span></div>
            <p class="small text-muted">
              <i data-feather="alert-octagon"></i>&nbsp;&nbsp;If any product managers collaboratively work on your orders, please update their commissions here.
            </p>
            <a href="javascript:void(0);" class="addSC btn btn-primary btn-sm" style="float: right;"><i data-feather="plus"></i> Add Row</a>

            <table class="table form-table" id="customFieldsSC">
              <thead>
                <tr>
                  <th>Sales Manager</th>
                  <th>Percentage (if any)</th>
                  <th>Commission (AED)</th>
                </tr>
              </thead>
              <tbody>

                @forelse($commissions as $sc)
                <tr valign="top">
                  <td>
                    <select class="form-select" name="manager_id[]" id="managerid_0">
                      <option value="">--</option>
                      @foreach($employees as $i => $emp)
                      <option value="{{$emp->id}}" {{( isset($sc->manager_id) && $sc->manager_id == $emp->id)? "selected": ""}}>{{ $emp->user->name}}</option>
                      @endforeach
                    </select>
                  </td>
                  <td>
                    <input type="number" class="form-control percentage" id="percent_0" name="percent[]" value="{{ isset($sc->percent)? $sc->percent : ''}}" placeholder="Percentage %" maxlength="2" />
                  </td>
                  <td>
                    <input type="number" class="form-control" id="commission_amount_0" name="commission_amount[]" value="{{ isset($sc->commission_amount)? $sc->commission_amount : ''}}" placeholder="Sales Commision (AED)" step="any" />
                  </td>
                  <td>
                    @if($sc->manager_id != $quotation->assigned_to)
                    <a href="javascript:void(0);" class="remSC" title="DELETE ROW" data-id="{{$sc->id}}"><i class="fa fa-trash"></i></a>
                    @endif
                  </td>
                </tr>
                @empty
                <tr valign="top">
                  <td>
                    <select class="form-select" name="manager_id[]" id="managerid_0">
                      <option value="">--</option>
                      @foreach($employees as $emp)
                      <option value="{{$emp->id}}">{{ $emp->user->name}}</option>
                      @endforeach
                    </select>
                  </td>
                  <td>
                    <input type="number" class="form-control percentage" id="percent_0" name="percent[]" value="" placeholder="Percentage %" maxlength="2" />
                  </td>
                  <td>
                    <input type="number" class="form-control" id="commission_amount_0" name="commission_amount[]" value="" placeholder="Sales Commision (AED)" step="any" />
                  </td>
                </tr>

                @endforelse
              </tbody>
            </table>
          </div>
        </div>
        <div class="row gx-3">
          <div class="col-4"></div>
          <div class="col-4">
            <button type="submit" name="publish" class="btn btn-primary mt-5 me-2">Update Quotation</button>
            <!-- <button type="submit" name="draft" class="btn btn-info mt-5 me-2">Save as Draft</button> -->
            <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='/quotations'">Back To List</button>
          </div>
          <div class="col-4"></div>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
    <!-- /Page Body -->
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
                  <select class="form-control" name="product_payment_term"  id="productPriceBasis" onchange="quotationPriceBasisAlert()">
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
                  <input class="form-control" type="text" name="product_margin_price" id="marginPriceProduct">
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
                <select class="form-control" name="payment_term" id="historyPriceBasis" onchange="quotationPriceBasisAlert()">
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

  <!-- ---------custom modal------------------- -->
  <div class="modal fade" id="customFieldsModal" tabindex="-1" aria-labelledby="customFieldsModal" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="additionalFieldsModalLabel">Edit Quote Custom Price</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <!-- Price Basis Section -->
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Price Basis<span class="text-danger">*</span></label>
                <select class="form-control" name="payment_term" id="customPriceBasis" readonly>
                  <option value="" disabled selected>-- Select Price Basis --</option>
                  @foreach($paymentTerms as $paymentTerm)
                  <option value="{{ $paymentTerm->short_code }}" data-id="{{ $paymentTerm->id }}">{{ $paymentTerm->title }}</option>
                  @endforeach
                </select>
                <input type="hidden" name="payment_term_id" id="customPaymentTermId" />
              </div>
            </div>
          </div>

          <!-- Buying Price Details -->
          <div class="row gx-3 mt-2 mb-2">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2">
                <span>Buying Price Details</span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Gross Price<span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="custom_gross_price" id="custom_gross_price" required />
                <div class="invalid-data" style="display: none;">Please enter a gross price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Purchase Discount(%)</label>
                <input class="form-control" type="text" name="custom_discount" id="custom_purchase_discount">
                <div class="invalid-data" style="display: none;">Please enter a purchase discount.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Purchase Discount Amount<span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="custom_discount_amount" id="custom_purchase_discount_amount" required />
                <div class="invalid-data" style="display: none;">Please enter a purchase discount price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Buying Price<span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="custom_buying_price" id="custom_buying_prices" readonly />
                <div class="invalid-data" style="display: none;">Please enter a buying price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Currency<span class="text-danger">*</span></label>
                <select class="form-control" name="custom_buying_currency" id="buyingCurrencyCustom">
                  <option value="">-Select Currency-</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please select currency.</div>
              </div>
            </div>
          </div>

          <!-- Selling Price Details -->
          <div class="row gx-3 mt-2 mb-2">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2">
                <span>Selling Price Details</span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="packing">Packing</label>
                <input class="form-control" type="number" min="0" id="packing" name="packing" value="0" data-field-name="packing" />
                <div class="invalid-feedback">Please enter a valid Packing.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="road_transport_to_port">Road Transport To Port</label>
                <input class="form-control" type="number" min="0" id="road_transport_to_port" name="road_transport_to_port" value="100" data-field-name="road_transport_to_port" />
                <div class="invalid-feedback">Please enter a valid Road Transport To Port.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="freight">Freight</label>
                <input class="form-control" type="number" min="0" id="freight" name="freight" value="0" data-field-name="freight" />
                <div class="invalid-feedback">Please enter a valid Freight.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="insurance">Insurance</label>
                <input class="form-control" type="number" min="0" id="insurance" name="insurance" value="0" data-field-name="insurance" />
                <div class="invalid-feedback">Please enter a valid Insurance.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="clearing">Clearing</label>
                <input class="form-control" type="number" min="0" id="clearing" name="clearing" value="0" data-field-name="clearing" />
                <div class="invalid-feedback">Please enter a valid Clearing.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="boe">BOE</label>
                <input class="form-control" type="number" min="0" id="boe" name="boe" value="0" data-field-name="boe" />
                <div class="invalid-feedback">Please enter a valid BOE.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="handling_and_local_transport">Handling and Local Transport</label>
                <input class="form-control" type="number" min="0" id="handling_and_local_transport" name="handling_and_local_transport" value="0" data-field-name="handling_and_local_transport" />
                <div class="invalid-feedback">Please enter a valid Handling and Local Transport.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="customs">Customs</label>
                <input class="form-control" type="number" min="0" id="customs" name="customs" value="0" data-field-name="customs" />
                <div class="invalid-feedback">Please enter a valid Customs.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="delivery_charge">Delivery Charge</label>
                <input class="form-control" type="number" min="0" id="delivery_charge" name="delivery_charge" value="0" data-field-name="delivery_charge" />
                <div class="invalid-feedback">Please enter a valid Delivery Charge.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="mofaic">MOFAIC</label>
                <input class="form-control" type="number" min="0" id="mofaic" name="mofaic" value="0" data-field-name="mofaic" />
                <div class="invalid-feedback">Please enter a valid MOFAIC.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label" for="surcharges">Surcharges</label>
                <input class="form-control" type="number" min="0" id="surcharges" name="surcharges" value="0" data-field-name="surcharges" />
                <div class="invalid-feedback">Please enter a valid Surcharges.</div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Margin Price</label>
                <input class="form-control" type="text" name="custom_margin_price" id="marginPriceCustom" />
                <div class="invalid-feedback">Please enter margin price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">MOSP<span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="custom_margin_percentage" id="marginPercentageCustom" />
                <div class="invalid-feedback">Please enter a MOSP.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Selling Price<span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="custom_selling_price" id="sellingPriceCustom" readonly />
                <div class="invalid-feedback">Please enter a selling price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Currency<span class="text-danger">*</span></label>
                <select class="form-control" name="quote_curr" id="quoteCurrencyCustom">
                  <option value="">-Select Currency-</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please select currency.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Hidden Inputs -->
        <input type="hidden" name="customprice" id="customprice">
        <input type="hidden" id="itemId" name="item_id">

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeAdditionalFieldsModal">Close</button>
          <button type="button" class="btn btn-primary" id="saveCustomFields">Save</button>
        </div>
      </div>
    </div>
  </div>


  <!-- ------------------- end models ------------------- -->

  <script type="text/javascript">
  $(document).ready(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    const employees = `{!! json_encode($userInfo) !!}`;

    $('#country_id').on('change', function(e) {
      let cid = $(this).val();

      if (!cid) {
        $('#region_id').html('<option value="">--</option>');
        $('#regid').html('');
      }

      let selReg = $('#regid').html();

      $.get('/regionbycountry/' + cid, function(data) {
        let opt = '<option value="">--</option>';
        jQuery.each(data, function(index, value) {
          //   console.log(value);
          opt += '<option value="' + value.id + '">' + value.state + '</option>';
        });
        // console.log(opt)
        $('#region_id').html(opt);

        if (selReg) {
          $('#region_id').val(selReg).trigger('change');
        }

      });

    });

    $('#companyid').on('change', function(e) {
      e.preventDefault();

      let compid = $(this).val();
      if (!compid) {
        $('#company').val('');
        $('#country_id').val('').trigger('change');
        $('#regid').html('');
      }
      // get company details
      $.get('/companybyid/?company_id=' + compid, function(data) {
        // console.log(data)
        $('#company').val(data.company);
        if (data.region_id) {
          $('#regid').html(data.region_id);
        }
        if (data.country_id) {
          $('#country_id').val(data.country_id).trigger('change');
        }

      });

      // List customers
      $.get('/customersbyid/?company_id=' + compid, function(data) {
        let opt = '<option value="">--</option>';
        jQuery.each(data, function(index, value) {
          //   console.log(value);
          opt += '<option value="' + value.id + '">' + value.fullname + '</option>';
        });
        $('#customerid').html(opt);
      });



    });

    $('#customerid').on('change', function(e) {
      e.preventDefault();

      let custid = $(this).val();

      $.get('/customers/' + custid, function(data) {
        $('#fullname').val(data.fullname);
        $('#email').val(data.email);
        $('#phone').val(data.phone);
      });
    });

    /************ REMINDER ********/
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
    var remind_dt = '{!! $quotation->reminder !!}';
    remind_dt = remind_dt ? remind_dt : moment().format('DD/MM/YYYY H:mm');

    $('input[name="reminder"]').daterangepicker({
      singleDatePicker: true,
      timePicker: true,
      timePicker24Hour: true,
      //showDropdowns: false,
      startDate: moment(remind_dt).format('DD/MM/YYYY H:mm'),
      //   minYear: new Date().getFullYear(),
      "cancelClass": "btn-secondary",
      locale: {
        format: 'DD/MM/YYYY H:mm'
      }
      // maxYear: parseInt(moment().format('YYYY'), 10)
    }, function(start, end, label) {
      //  var years = moment().diff(start, 'years');
      // alert("You are " + years + " years old!");
    });
    // $(".percentage").keyup(function(e) {
    //   $("#customFieldsSC").on('click', '.remSC', function() {
    //
    //   });

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



    /**************************************
    * PO Dynamic Rows
    *****************************************/
    var iter = 0;

    $(".addSC").click(function() {
      iter++;
      let dropdwn = createDropDown(iter);
      $("#customFieldsSC").append(`<tr valign="top">
      <td>${dropdwn}</td>
      <td><input type="number" class="form-control percentage" id="percent_${iter}" name="percent[]" value="" onkeyup="percentage();"/></td>
      <td><input type="number" class="form-control" id="commission_amount_${iter}" name="commission_amount[]" value="" step="any" /></td>
      <td><a href="javascript:void(0);" class="remSC" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);
    });

    $("#customFieldsSC").on('click', '.remSC', function() {
      const obj = $(this);
      if (confirm('Are you sure to delete the collaborator?')) {

        let scid = obj.data("id");
        scid = parseInt(scid);
        if (scid) {
          $.ajax({
            type: 'POST',
            url: "{{ route('salescommission.delete') }}",
            data: {
              commision_id: scid,
              quotation_id: $("#quotation_id").val()
            },
            success: function() {
              obj.parent().parent().remove();
              location.reload();
            }
          });
        } else {
          iter--;
          obj.parent().parent().remove();
        }
      } else {
        return false;
      }

    });


    function createDropDown(i) {
      let ddlCustomers = `<select class="form-select" name="manager_id[]" id="managerid_${i}"><option value="">--</option>`;
      //Add the Options to the DropDownList.
      let json = JSON.parse(employees);
      for (let x in json) {
        ddlCustomers += `<option value="${json[x].id}">${json[x].username}</option>`;
      }
      ddlCustomers += `</select>`;

      return ddlCustomers;
    }
    // Attach the event listener for modal opening
    $('#customFieldsModal').on('show.bs.modal', function (e) {
      // Get the itemId from the link that triggered the modal
      var itemId = $(e.relatedTarget).data('id');

      // Log the itemId to verify it's correct
      console.log('Item ID:', itemId);

      // Set the itemId value in the hidden input inside the modal
      $('#itemId').val(itemId);
    });
    function updateChargeCheckboxValue(checkbox) {
      // Find the corresponding hidden input element
      const hiddenInput = checkbox.previousElementSibling;

      // Set the value of the hidden input based on checkbox state
      hiddenInput.value = checkbox.checked ? 1 : 0;
    }

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
// Select DOM elements
const marginPriceCustomInput = document.getElementById('marginPriceCustom');
const marginPercentageCustomInput = document.getElementById('marginPercentageCustom');
const sellingPriceCustomInput = document.getElementById('sellingPriceCustom');
const buyingPriceCustomInput = document.getElementById('custom_buying_prices');

// Store the old values of dynamic fields to avoid multiple additions
let oldDynamicFieldValues = {};

// Add event listeners for custom fields
document.getElementById('custom_gross_price').addEventListener('input', updateCustomBuyingPrice);
document.getElementById('custom_purchase_discount').addEventListener('input', updateCustomBuyingPrice);
document.getElementById('custom_purchase_discount_amount').addEventListener('input', updateCustomBuyingPriceWithAmount);
marginPriceCustomInput.addEventListener('input', updateCustomMarginPrice);
marginPercentageCustomInput.addEventListener('input', updateCustomMarginPercentage);

// Add event listener to detect changes in dynamic custom fields
$(document).on('input', '.dynamic-field', function() {
  updateCustomSellingPrice();  // Recalculate when dynamic field changes
});

// Update Margin Price Calculation
function updateCustomMarginPrice() {
  const buyingPrice = parseFloat(buyingPriceCustomInput.value.replace(/,/g, '')) || 0;
  const marginPrice = parseFloat(marginPriceCustomInput.value.replace(/,/g, '')) || 0;

  // Calculate total custom field values (like packing, freight, etc.)
  const totalCustomFieldsValue = calculateTotalCustomFields();

  if (buyingPrice > 0) {
    // Calculate margin percentage and selling price
    const marginPercentage = (marginPrice / buyingPrice) * 100;
    const sellingPrice = buyingPrice + marginPrice + totalCustomFieldsValue;

    marginPercentageCustomInput.value = marginPercentage.toFixed(2);
    sellingPriceCustomInput.value = sellingPrice.toFixed(2);
  }

  // Recalculate selling price after updating margin
  updateCustomSellingPrice();
}

// Update Margin Percentage Calculation
function updateCustomMarginPercentage() {
  const buyingPrice = parseFloat(buyingPriceCustomInput.value.replace(/,/g, '')) || 0;
  const marginPercentage = parseFloat(marginPercentageCustomInput.value.replace(/,/g, '')) || 0;

  // Calculate total custom field values (like packing, freight, etc.)
  const totalCustomFieldsValue = calculateTotalCustomFields();

  if (buyingPrice > 0) {
    // Calculate margin price and selling price
    const marginPrice = (marginPercentage / 100) * buyingPrice;
    const sellingPrice = buyingPrice + marginPrice + totalCustomFieldsValue;

    marginPriceCustomInput.value = marginPrice.toFixed(2);
    sellingPriceCustomInput.value = sellingPrice.toFixed(2);
  }

  // Recalculate selling price after updating margin percentage
  updateCustomSellingPrice();
}

// Update Buying Price Based on Discount Amount
function updateCustomBuyingPriceWithAmount() {
  let gross_price = $('#custom_gross_price').val();
  let purchase_discount = $('#custom_purchase_discount_amount').val();

  let basePrice = parseFloat(gross_price.replace(/,/g, '')) || 0;
  let dAmount = parseFloat(purchase_discount.replace(/,/g, '')) || 0;

  if (!isNaN(basePrice) && !isNaN(dAmount)) {
    let calculatedDPrice = basePrice - dAmount;
    let dpercent = (dAmount / basePrice) * 100;
    dpercent = parseFloat(dpercent).toFixed(2);
    $('#custom_purchase_discount').val(dpercent);

    let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));
    $('#custom_buying_prices').val(formattedMarginPrice); // Updates buying price automatically
    updateCustomSellingPrice(); // Recalculate selling price
  }
}

// Update Buying Price Based on Discount Percentage
function updateCustomBuyingPrice() {
  let gross_price = $('#custom_gross_price').val();
  let purchase_discount = $('#custom_purchase_discount').val();

  let basePrice = parseFloat(gross_price.replace(/,/g, '')) || 0;
  let dPercentage = parseFloat(purchase_discount.replace(/,/g, '')) || 0;

  if (!isNaN(basePrice) && !isNaN(dPercentage)) {
    let calculatedDPrice = basePrice * (dPercentage / 100);
    $('#custom_purchase_discount_amount').val(calculatedDPrice);
    calculatedDPrice = basePrice - calculatedDPrice;
    let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));
    $('#custom_buying_prices').val(formattedMarginPrice); // Updates buying price automatically
    updateCustomSellingPrice(); // Recalculate selling price
  } else if (!isNaN(basePrice)) {
    let formattedMarginPrice = numberWithCommas(basePrice.toFixed(2));
    $('#custom_buying_prices').val(formattedMarginPrice); // Updates buying price automatically
    updateCustomSellingPrice(); // Recalculate selling price
  }
}

// Calculate the total of all dynamic custom fields (like packing, freight, etc.)
function calculateTotalCustomFields() {
  let total = 0;

  // Iterate over all dynamic fields (like packing, freight, etc.)
  $('.dynamic-field').each(function () {
    let value = parseFloat($(this).val().replace(/,/g, '')) || 0;
    const fieldId = $(this).attr('id'); // Get the dynamic field ID

    // Check if the value is different from the previous value
    if (oldDynamicFieldValues[fieldId] !== value) {
      // Subtract the old value and add the new value
      total -= oldDynamicFieldValues[fieldId] || 0;
      oldDynamicFieldValues[fieldId] = value; // Update the old value
    }

    // Add the updated value to the total
    total += value;
  });

  return total;
}

// Update selling price by considering buying price, margin, and total custom field values
function updateCustomSellingPrice() {
  const buyingPrice = parseFloat(buyingPriceCustomInput.value.replace(/,/g, '')) || 0;
  const marginPrice = parseFloat(marginPriceCustomInput.value.replace(/,/g, '')) || 0;

  // Calculate total custom fields value (packing, freight, etc.)
  const totalCustomFieldsValue = calculateTotalCustomFields();

  // Calculate the selling price
  const sellingPrice = buyingPrice + marginPrice + totalCustomFieldsValue;

  // Update the selling price input
  sellingPriceCustomInput.value = sellingPrice.toFixed(2);
  const event = new Event('input', { bubbles: true });
  sellingPriceCustomInput.dispatchEvent(event); // Trigger input event if necessary
}

// Utility function to format numbers with commas
function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

</script>



<script>
document.getElementById('gross_price').addEventListener('input', updateBuyingPrice);
document.getElementById('purchase_discount').addEventListener('input', updateBuyingPrice);
document.getElementById('purchase_discount_amount').addEventListener('input', updateBuyingPriceWithAmount);

document.getElementById('buying_gross_price').addEventListener('input', updateBuyingPrice);
document.getElementById('buying_purchase_discount').addEventListener('input', updateBuyingPrice);
document.getElementById('buying_purchase_discount_amount').addEventListener('input', updateBuyingPriceWithAmount);




const marginPriceInput = document.getElementById('marginPriceProduct');
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

            console.log("Custom Fields Array Updated:", customsArray);

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
  const priceBasisElement = document.getElementById('productPriceBasis');
  const paymentTermIdElement = document.getElementById('productPaymentTermId');
  const customFieldsContainer = document.getElementById('productCustomFieldsContainer');
  const sellingPriceInput = document.getElementById('sellingPrice');
  const buyingPriceInput = document.getElementById('buying_price');
  const marginPriceInput = document.getElementById('marginPriceProduct');
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

  priceBasisElement.addEventListener('change', function () {
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
          <input class="form-control dynamic-field" type="text"
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

            console.log("Custom Fields Array Updated:", productCustomFieldsArray);

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

let customPriceArray = [];
$(document).ready(function () {


  const quoteId = $('#quotation_id').val();

  if (quoteId) {
    fetchCustomPriceArray(quoteId);
  }


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
  loadQuotationCharges(quoteId);

});
function fetchCustomPriceArray(quoteId) {

  $.ajax({
    url: `/edit-quote-custom/${quoteId}`,
    method: 'GET',
    dataType: 'json',
    success: function (data) {
      customPriceArray = data;
      console.log(customPriceArray);
      updateQuotationCharges(customPriceArray);
    },
    error: function (xhr, status, error) {
      console.error('Error fetching custom price array:', error);
    }
  });
}

$(document).on('click', 'a[data-bs-toggle="modal"]', function () {
  var itemId = $(this).data('id');
  var quotationId = $('#quotation_id').val();

  // Fetch the item details only if they haven't been loaded previously
  if (!$('#itemId').data('loaded') || $('#itemId').val() !== itemId) {
    $.ajax({
      url: '/get-item-details/' + itemId + '/quotation/' + quotationId,
      method: 'GET',
      success: function(response) {

        // Mark as loaded to prevent resetting the data on subsequent opens
        $('#itemId').data('loaded', true);
        $('#itemId').val(itemId);

        // Define the fields with their response values
        const fields = [
          { id: '#custom_gross_price', value: response.gross_price },
          { id: '#custom_purchase_discount', value: response.purchase_discount },
          { id: '#custom_purchase_discount_amount', value: response.purchase_discount_amount },
          { id: '#custom_buying_prices', value: response.buying_price },
          { id: '#customCurrencyCustom', value: response.buying_currency },
          { id: '#marginPriceCustom', value: response.unit_margin },
          { id: '#marginPercentageCustom', value: response.mosp },
          { id: '#sellingPriceCustom', value: response.unit_price },
          { id: '#quoteCurrencyCustom', value: response.selling_currency },
          { id: '#packing', value: response.packing },
          { id: '#road_transport_to_port', value: response.road_transport_to_port },
          { id: '#freight', value: response.freight },
          { id: '#insurance', value: response.insurance },
          { id: '#clearing', value: response.clearing },
          { id: '#boe', value: response.boe },
          { id: '#handling_and_local_transport', value: response.handling_and_local_transport },
          { id: '#customs', value: response.customs },
          { id: '#delivery_charge', value: response.delivery_charge },
          { id: '#mofaic', value: response.mofaic },
          { id: '#surcharges', value: response.surcharges },
          { id: '#buyingCurrencyCustom', value: response.buying_currency }
        ];

        // Loop through each field and process based on the value
        fields.forEach(function(field) {
          if (field.value === null || field.value === undefined || field.value === '') {
            // Completely remove the entire .col-md-4 and .form-group elements
            $(field.id).closest('.col-md-4').remove();
          } else {
            // Set the field value and ensure the form group is visible
            $(field.id).val(field.value);
            $(field.id).closest('.col-md-4').show();  // Ensure the column is visible
          }
        });

        // Trigger the change event for the quote currency field
        $('#quoteCurrencyCustom').change();
      },
      error: function(xhr, status, error) {
        console.log('Error fetching data:', error);
      }
    });
  }
});

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

  const customPriceBasis = document.getElementById('customPriceBasis');
  const customPaymentTermId = document.getElementById('customPaymentTermId');
  const quotationCustomFieldsContainer = document.getElementById('quotationCustomFieldsContainer');
  const marginPercentageInput = document.getElementById('marginPercentageCustom');
  const marginPriceInput = document.getElementById('marginPriceCustom');
  const sellingPriceInput = document.getElementById('sellingPriceCustom');
  const buyingPriceInput = document.getElementById('buying_prices');
  let customsArray = [];

  // Set initial hidden input values to match dropdown selections
  customPaymentTermId.value = customPriceBasis.value;

  // Function to update the selling price
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
      calculatedSellingPrice = basePrice + totalCustomFieldsValue + marginPrice;
    }

    sellingPriceInput.value = calculatedSellingPrice.toFixed(2);
  }

  // Attach event listeners to margin inputs
  marginPriceInput.addEventListener('input', updateHistorySellingPrice);
  marginPercentageInput.addEventListener('input', updateHistorySellingPrice);
  function fetchAndDisplayCustomFields(priceBasis, paymentTermId, previousCustomFields = []) {
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
        quotationCustomFieldsContainer.innerHTML = ''; // Clear any previous custom fields

        if (data && data.length > 0) {
          let rowContainer = document.createElement('div');
          rowContainer.classList.add('row');

          // Initialize customsArray with all fields and default values
          data.forEach((field, index) => {
            const fieldHTML = `
            <div class="form-group col-md-6">
            <label class="form-label">${field.field_name}<span class="text-danger">*</span></label>
            <input class="form-control dynamic-field" type="number" min="0" value="0"
            name="${field.short_code}" data-field-name="${field.short_code}" />
            </div>`;
            rowContainer.insertAdjacentHTML('beforeend', fieldHTML);


            const fieldIndex = customsArray.findIndex(
              (item) => item.field_name === field.short_code
            );
            if (fieldIndex === -1) {
              customsArray.push({ field_name: field.short_code, value: 0 });
            }

            // Check if a previous value exists and pre-populate the field
            const previousField = previousCustomFields.find(item => item.field_name === field.short_code);
            if (previousField) {
              const fieldInput = rowContainer.querySelector(`[data-field-name="${field.short_code}"]`);
              fieldInput.value = previousField.value;

              // Only update customsArray if the value is different from the default (0) or the previous one
              if (previousField.value !== 0) {
                customsArray[fieldIndex].value = previousField.value;
              }
            }

            if ((index + 1) % 2 === 0) {
              quotationCustomFieldsContainer.appendChild(rowContainer);
              rowContainer = document.createElement('div');
              rowContainer.classList.add('row');
            }
          });

          if (rowContainer.children.length > 0) {
            quotationCustomFieldsContainer.appendChild(rowContainer);
          }

          // Add event listeners to dynamic fields for value updates
          const dynamicFields = quotationCustomFieldsContainer.querySelectorAll('.dynamic-field');
          dynamicFields.forEach((input) => {
            input.addEventListener('input', function () {
              const fieldName = this.getAttribute('data-field-name').trim();
              const fieldValue = parseFloat(this.value) || 0;

              const fieldIndex = customsArray.findIndex(
                (item) => item.field_name === fieldName
              );

              // Only update the customsArray if the value has changed
              if (fieldIndex !== -1) {
                // Update the value only if it's different from the existing one
                if (customsArray[fieldIndex].value !== fieldValue) {
                  customsArray[fieldIndex].value = fieldValue;
                }
              } else {
                customsArray.push({ field_name: fieldName, value: fieldValue });
              }

              const customPriceJSON = JSON.stringify(customsArray);
              document.getElementById('customprice').value = customPriceJSON;
              console.log('Custom Fields Array Updated:', customsArray);
              updateHistorySellingPrice();
            });
          });


          const customPriceJSON = JSON.stringify(customsArray);
          document.getElementById('customprice').value = customPriceJSON;
          console.log('Initial Custom Fields Array:', customsArray);

        } else {
          console.log('No custom fields found.');
          quotationCustomFieldsContainer.innerHTML = 'No custom fields available.';
        }

      })
      .catch((error) => {
        console.error('Error fetching custom fields:', error);
        quotationCustomFieldsContainer.innerHTML = 'Error loading custom fields.';
      });
    } else {
      quotationCustomFieldsContainer.innerHTML = '';
    }
  }


  // Function to fetch and display custom fields
  // function fetchAndDisplayCustomFields(priceBasis, paymentTermId) {
  //   if (priceBasis) {
  //     fetch('/get-custom-fields', {
  //       method: 'POST',
  //       headers: {
  //         'Content-Type': 'application/json',
  //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
  //       },
  //       body: JSON.stringify({
  //         price_basis: priceBasis,
  //         payment_term_id: paymentTermId,
  //       }),
  //     })
  //     .then((response) => response.json())
  //     .then((data) => {
  //       quotationCustomFieldsContainer.innerHTML = ''; // Clear any previous custom fields
  //
  //       if (data && data.length > 0) {
  //         let rowContainer = document.createElement('div');
  //         rowContainer.classList.add('row');
  //
  //         // Initialize customsArray with all fields and default values
  //         data.forEach((field, index) => {
  //           const fieldHTML = `
  //           <div class="form-group col-md-6">
  //           <label class="form-label">${field.field_name}<span class="text-danger">*</span></label>
  //           <input class="form-control dynamic-field" type="number" min="0" value="0"
  //           name="${field.short_code}" data-field-name="${field.short_code}" />
  //           </div>`;
  //           rowContainer.insertAdjacentHTML('beforeend', fieldHTML);
  //
  //           // Initialize customsArray with default value 0 for this field
  //           const fieldIndex = customsArray.findIndex(
  //             (item) => item.field_name === field.short_code
  //           );
  //           if (fieldIndex === -1) {
  //             customsArray.push({ field_name: field.short_code, value: 0 });
  //           }
  //
  //           if ((index + 1) % 2 === 0) {
  //             quotationCustomFieldsContainer.appendChild(rowContainer);
  //             rowContainer = document.createElement('div');
  //             rowContainer.classList.add('row');
  //           }
  //         });
  //
  //         if (rowContainer.children.length > 0) {
  //           quotationCustomFieldsContainer.appendChild(rowContainer);
  //         }
  //
  //         // Add event listeners to dynamic fields for value updates
  //         const dynamicFields = quotationCustomFieldsContainer.querySelectorAll('.dynamic-field');
  //         dynamicFields.forEach((input) => {
  //           input.addEventListener('input', function () {
  //             const fieldName = this.getAttribute('data-field-name').trim();
  //             const fieldValue = parseFloat(this.value) || 0;
  //
  //             const fieldIndex = customsArray.findIndex(
  //               (item) => item.field_name === fieldName
  //             );
  //             if (fieldIndex !== -1) {
  //               customsArray[fieldIndex].value = fieldValue;
  //             } else {
  //               customsArray.push({ field_name: fieldName, value: fieldValue });
  //             }
  //             const customPriceJSON = JSON.stringify(customsArray);
  //             document.getElementById('customprice').value = customPriceJSON;
  //             console.log('Custom Fields Array Updated:', customsArray);
  //             updateHistorySellingPrice();
  //           });
  //         });
  //
  //         // Update the hidden field initially with the full customsArray
  //         const customPriceJSON = JSON.stringify(customsArray);
  //         document.getElementById('customprice').value = customPriceJSON;
  //         console.log('Initial Custom Fields Array:', customsArray);
  //
  //       } else {
  //         console.log('No custom fields found.');
  //         quotationCustomFieldsContainer.innerHTML = 'No custom fields available.';
  //       }
  //
  //     })
  //     .catch((error) => {
  //       console.error('Error fetching custom fields:', error);
  //       quotationCustomFieldsContainer.innerHTML = 'Error loading custom fields.';
  //     });
  //   } else {
  //     quotationCustomFieldsContainer.innerHTML = '';
  //   }
  // }
  const initialPriceBasis = customPriceBasis.value;
  const selectedOption = document.getElementById('customPriceBasis').options[document.getElementById('customPriceBasis').selectedIndex];
  const dataId = selectedOption.getAttribute('data-id');
  fetchAndDisplayCustomFields(initialPriceBasis, dataId);
});
</script>
<script>
const selectedValue = document.getElementById('paymentTerm').value;
const customPriceBasis = document.getElementById('customPriceBasis');

for (let i = 0; i < customPriceBasis.options.length; i++) {
  if (customPriceBasis.options[i].value === selectedValue) {
    customPriceBasis.selectedIndex = i;
    break;
  }
}


document.addEventListener('DOMContentLoaded', function() {
  // Attach the event listener to the "Save" button
  document.getElementById('saveCustomFields').addEventListener('click', function() {

    $(this).prop('disabled', true);

    // Collect values from the form fields
    let sellingPrice = $('#sellingPriceCustom').val();
    let marginPercentage = $('#marginPercentageCustom').val();
    let quoteCurrency = $('#quoteCurrencyCustom').val();
    let marginPrice = $('#marginPriceCustom').val();
    let priceBasis = $('#customPriceBasis').val();
    let buyingGrossPrice = $('#custom_gross_price').val();
    let buyingPurchaseDiscount = $('#custom_purchase_discount').val();
    let buyingPurchaseDiscountAmount = $('#custom_purchase_discount_amount').val();
    let buyingPrices = $('#custom_buying_prices').val();
    let buyingCurrencyCustom = $('#buyingCurrencyCustom').val();
    let customprice = $('#customprice').val();
    var quotationId = $('#quotation_id').val();
    var product_id = $('#itemId').val();
    let packing = $('#packing').val();
    let roadTransportToPort = $('#road_transport_to_port').val();
    let freight = $('#freight').val();
    let insurance = $('#insurance').val();
    let clearing = $('#clearing').val();
    let boe = $('#boe').val();
    let handlingAndLocalTransport = $('#handling_and_local_transport').val();
    let customs = $('#customs').val();
    let deliveryCharge = $('#delivery_charge').val();
    let mofaic = $('#mofaic').val();
    let surcharges = $('#surcharges').val();

    let customFields = [];
    $('.custom-field').each(function() {
      customFields.push({
        name: $(this).data('name'),
        value: $(this).val()
      });
    });


    $.ajax({
      url: '{{ route("quotation.custom_price_edit") }}',
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: {
        selling_price: sellingPrice,
        margin_percentage: marginPercentage,
        quote_currency: quoteCurrency,
        margin_price: marginPrice,
        price_basis: priceBasis,
        custom_fields: customFields,
        buying_gross_price: buyingGrossPrice,
        buying_purchase_discount: buyingPurchaseDiscount,
        buying_purchase_discount_amount: buyingPurchaseDiscountAmount,
        buying_prices: buyingPrices,
        buying_currency: buyingCurrencyCustom,
        customprice: customprice,
        quotation_id: quotationId,
        product_id: product_id,
        packing: packing,
        road_transport_to_port: roadTransportToPort,
        freight: freight,
        insurance: insurance,
        clearing: clearing,
        boe: boe,
        handling_and_local_transport: handlingAndLocalTransport,
        customs: customs,
        delivery_charge: deliveryCharge,
        mofaic: mofaic,
        surcharges: surcharges
      },
      success: function(response) {

        if (response.success) {

          $('#customFieldsModal').modal('hide');

          location.reload();
        } else {
          console.error('Error in response:', response);
        }
      },
      error: function(xhr, status, error) {
        console.error('Error saving values:', error);
      },
      complete: function() {

        $('#saveCustomFields').prop('disabled', false);
      }
    });
  });
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
