@extends('layouts.default')

@section('content')


<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pt-7 pb-4">
        <h1 class="pg-title">Edit Leads</h1>
        <p>The company is used to represent an account of it and any person from their can send join here.</p>
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
                {!! Form::model($lead, ['method' => 'PATCH','enctype' => 'multipart/form-data', 'route' => ['leads.update', $lead->id]]) !!}

                <div class="row gx-3">
                    <div class="col-sm-6">
                        <div class="title title-xs title-wth-divider text-primary text-uppercase"><span>Customer Info</span></div>
                        <div class="row gx-3 mt-4">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Existing Company</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-select select2" name="company_id" id="companyid">
                                        <option value="">--</option>
                                        @foreach($companies as $id => $comp)
                                        <option value="{{$id}}" {{($lead->company_id == $id)? "selected": ""}}>{{$comp}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Existing Customer</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-select custom-select" name="customer_id" id="customerid">
                                        <option value="">--</option>
                                        @foreach($customers as $cust)
                                        <option value="{{$cust->id}}" {{($lead->customer_id == $cust->id)? "selected": ""}}>{{$cust->fullname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Company</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="company" id="company" value="{{$lead->company->company}}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="fullname" id="fullname" value="{{ $lead->customer->fullname}}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Country</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-select select2" name="country_id" id="country_id" required>
                                        <option value="">--</option>
                                        @foreach ($countries as $key => $row)
                                        <option value="{{ $row->id }}" {{($lead->company->country_id == $row->id)? "selected": "" }}>
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
                                    <select class="form-select" name="region_id" id="region_id">
                                        <option value="0">--</option>
                                        @foreach($regions as $reg)
                                        <option value="{{$reg->id}}" {{$lead->company->region_id == $reg->id ? "selected": ""}}>{{$reg->state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Country</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-select select2" name="location" id="location">
                                        <option value="">--</option>
                                        @foreach ($countries as $key => $row)
                                        <option value="{{ $row->id }}" {{($lead->customer->location == $row->id)? "selected": "" }}>
                                            {{ $row->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Email ID</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="email" name="email" id="email" value="{{$lead->customer->email}}" />
                                </div>
                            </div>
                            <div class=" col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                </div>
                            </div>
                            <div class=" col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="phone" id="phone" value="{{$lead->customer->phone}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="title title-xs title-wth-divider text-primary text-uppercase"><span>Enquiry Info</span></div>
                        <div class="row gx-3 mt-4">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Enquiry Source</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-select" name="lead_type">
                                        <option value="">--</option>
                                        <option value="internal" {{($lead->lead_type == "internal")? "selected": "" }}>Internal/ Inside</option>
                                        <option value="external" {{($lead->lead_type == "external")? "selected": "" }}>External/ Outside</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Enquiry Date</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="date" name="enquiry_date" value="{{ $lead->enquiry_date }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Assigned To</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-select" name="assigned_to">
                                        <option value="">--</option>
                                        @foreach($employees as $emp)
                                        <option value="{{$emp->id}}" {{($emp->id == $lead->assigned_to)? "selected": "" }}>{{ $emp->user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Assigned On</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="date" name="assigned_on" value="{{$lead->assigned_on}}" />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Responded On</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="date" name="respond_on" value="{{$lead->respond_on}}" />
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <textarea class="form-control" placeholder="Enter Enquiry Details" name="details" row="10">{{$lead->details}}</textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary mt-5  me-2">Update Enquiry</button>
                        <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='/leads'">Back To List</button>
                    </div>
                    <div class="col-sm-4"></div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /Page Body -->
</div>

<script type="text/javascript">
    function resetCustomers() {
        $('#company_id').val('');
        $('#company').val('');
        $('#country_id').val('').trigger('change');
        $('#region_id').val('');
        $('#regid').html('');

        $('#fullname').val('');
        $('#email').val('');
        $('#phone').val('');
    }
    $(document).ready(function() {
        $('#country_id').on('change', function(e) {
            let cid = $(this).val();
            cid = parseInt(cid);

            if (!cid || cid == 0) {
                $('#region_id').html('<option value="">--</option>');
                $('#regid').html('');
                return;
            }
            let selReg = $('#regid').html();
            let rurl = "{{route('regions.country', '')}}" + '/' + cid;

            $.get(rurl, function(data) {
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
            compid = parseInt(compid);
            if (!compid || compid == 0) {
                resetCustomers();
                return;
            }
            // get company details
            $.get('/companybyid/?company_id=' + compid, function(data) {
                // console.log(data)
                resetCustomers();
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
    });
</script>
@endsection