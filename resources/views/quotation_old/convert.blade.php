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

            @include('quotation.quotation_add_row_create')

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


@endsection