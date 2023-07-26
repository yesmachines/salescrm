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
                <div class="row gx-3">
                    <div class="col-6">
                        <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Customer Info</span></div>
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
                                        <option value="{{$id}}" {{($quotation->company_id == $id)? "selected": ""}}>{{$comp}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Existing Customers</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-select" name="customer_id" id="customerid">
                                        <option selected="">--</option>
                                        @foreach($customers as $cust)
                                        <option value="{{$cust->id}}" {{($quotation->customer_id == $cust->id)? "selected": ""}}>{{$cust->fullname}}</option>
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
                                    <input class="form-control" type="text" name="company" id="company" value="{{$quotation->company->company}}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="fullname" id="fullname" value="{{ $quotation->customer->fullname}}" />
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
                                        <option value="{{ $row->id }}" {{($quotation->company->country_id == $row->id)? "selected": "" }}>
                                            {{ $row->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Region*</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <span id="regid" class="d-none"></span>
                                    <select class="form-select" name="region_id" id="region_id">
                                        <option value="0">--</option>
                                        @foreach($regions as $reg)
                                        <option value="{{$reg->id}}" {{$quotation->company->region_id == $reg->id ? "selected": ""}}>{{$reg->state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Email ID</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="email" name="email" id="email" value="{{$quotation->customer->email}}" />
                                </div>
                            </div>
                            <div class=" col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                </div>
                            </div>
                            <div class=" col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="phone" id="phone" value="{{$quotation->customer->phone}}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Quotation Info</span></div>
                        <div class="row gx-3 mt-4">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Lead Type</label>
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
                                    <label class="form-label">Quotation For</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-select" name="quote_for" required>
                                        <option value="sales" {{($quotation->quote_for == "sales")? "selected": "" }}>Sales</option>
                                        <option value="spareparts" {{($quotation->quote_for == "spareparts")? "selected": "" }}>Spareparts Sale</option>
                                        <option value="service_spareparts" {{($quotation->quote_for == "service_spareparts")? "selected": "" }}>Service & Spareparts</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Supplier</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select class="form-select select2" name="supplier_id" required>
                                        <option value="">--</option>
                                        @foreach ($suppliers as $k => $sup)
                                        <option value=" {{ $sup->id }}" {{$quotation->supplier_id == $sup->id? "selected": ""}}>
                                            {{ $sup->brand }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Product Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <textarea class="form-control" row="4" col="4" name="product_models" placeholder="Product Name, Models, Spares, Accessories, Consumables">{{$quotation->product_models}}</textarea>
                                </div>
                            </div>
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
                                    <label class="form-label">Sales Value (AED)</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="total_amount" required value="{{$quotation->total_amount}}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Gross Margin (AED)</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="gross_margin" required value="{{$quotation->gross_margin}}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Submitted On</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="date" name="submitted_date" required value="{{$quotation->submitted_date}}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Winning Probability (%)</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="winning_probability" required value="{{$quotation->winning_probability}}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Closing Date</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="date" name="closure_date" required value="{{$quotation->closure_date}}" />
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
                                        <option selected="">--</option>
                                        @foreach($employees as $emp)
                                        <option value="{{$emp->id}}" {{($emp->id == $quotation->assigned_to)? "selected": "" }}>{{ $emp->user->name}}</option>
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
                                    <input type="checkbox" class="form-check-input" id="setreminder" {{$quotation->reminder? "checked": ""}}>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="reminder" id="reminder" class="form-control {{!$quotation->reminder? 'd-none' : ''}}" />
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
    });
</script>
@endsection