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
                    <option value="sales" {{($quotation->quote_for == "service")? "selected": "" }}>Service</option>
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
                  <select class="form-select select2" name="supplier_id[]" multiple>
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
         @if($quotationItems)
    @include('quotation.quotation_add_row_edit')
@else
    @include('quotation.quotation_add_row_create')
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
                      <input type="number" class="form-control" id="commission_amount_0" name="commission_amount[]" value="{{ isset($sc->commission_amount)? $sc->commission_amount : ''}}" placeholder="Sales Commision (AED)" />
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
                      <input type="number" class="form-control" id="commission_amount_0" name="commission_amount[]" value="" placeholder="Sales Commision (AED)" />
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

          </form>
        </div>
      </div>
    </div>
    <!-- /Page Body -->
  </div>

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
      $(".percentage").keyup(function(e) {
        $("#customFieldsSC").on('click', '.remSC', function() {

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
      <td><input type="number" class="form-control" id="commission_amount_${iter}" name="commission_amount[]" value="" /></td>
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
      });
    });
  </script>
  @endsection