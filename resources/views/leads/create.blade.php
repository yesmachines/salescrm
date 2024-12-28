@extends('layouts.default')

@section('content')


<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pt-7 pb-4">
        <h1 class="pg-title">Create New Lead</h1>
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
                <div class="mt-2">
                    @include('layouts.partials.messages')
                </div>
                <form method="POST" action="{{ route('leads.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row gx-3">
                        <div class="col-6">
                            <div class="title title-xs title-wth-divider text-primary text-uppercase">
                                <span>Customer Info</span>
                            </div>
                            <p class="text-warning">Please search a company in the existing database first, then if its not there create a new entry. </p>
                            <div class="row gx-3 mt-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Search Existing Company</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <!-- <select class="form-select select2" name="company_id" id="companyid">
                                            <option value="">--</option>
                                            @foreach($companies as $id => $comp)
                                            <option value="{{$id}}">{{$comp}}</option>
                                            @endforeach
                                        </select> -->
                                        <input id="company_id" type="hidden" name="company_id" class="form-control">
                                        <input class="typeahead form-control" id="search-company" type="text" placeholder="Search By Company Name">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Search Existing Customers</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select" name="customer_id" id="customerid">
                                            <option value="">--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="separator"></div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">New Company <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="company" id="company" required />
                                    </div>
                                    <div id="companyErrorMessage" class="text-danger" style="display: none;color:red;">Similar name found ! Duplicate entries will invite penalties. Verify with CRM Manager.</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="fullname" id="fullname" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Country <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select" name="country_id" id="country_id" required>
                                            <option value="">--</option>
                                            @foreach ($countries as $key => $row)
                                            <option value="{{ $row->id }}">
                                                {{ $row->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Region/ City</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <span id="regid" class="d-none"></span>
                                        <select class="form-select" name="region_id" id="region_id">
                                            <option value="0">--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Email ID <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="email" name="email" id="email" required />
                                    </div>
                                </div>
                                <div class=" col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class=" col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="phone" id="phone" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title title-xs title-wth-divider text-primary text-uppercase"><span>Enquiry Info</span></div>
                            <div class="row gx-3 mt-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Enquiry Source <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select" name="lead_type" id="lead_type" required>
                                            <option value="">--</option>
                                            @foreach ($enquirySource as $k => $enum)
                                            <option value="{{$k}}">{{ $enum->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Enquiry Mode</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select" name="enquiry_mode" id="enquiry_mode">
                                            <option value="">--</option>
                                            @foreach ($enquiryMode as $k => $enum)
                                            <option value="{{$k}}">{{ $enum->label() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div id="expodiv" class="row" style="padding: 0px; margin: 0px; --bs-gutter-x: 1.0rem; display: none;">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Expo</label>
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select" name="expo_id" id="expo_id">
                                            <option value="">--</option>
                                            @foreach ($expo as $k => $v)
                                            <option value="{{$k}}">{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Enquiry Date <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="date" name="enquiry_date" required />
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
                                            <option value="{{$emp->id}}">{{ $emp->user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Assigned On <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="date" name="assigned_on" required />
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
                                            @foreach($leadStatuses as $id=> $stat)
                                            <option value="{{$id}}">{{$stat}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Responded On</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="date" name="respond_on" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="form-label">Enquiry Details <span class="text-danger">*</span></label>
                                        <textarea class="form-control" placeholder="Enter Enquiry Details" name="details" row="10" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-3">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary mt-5 me-2">Create Enquiry</button>
                            <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='/leads'">Back To List</button>
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

        // $('#companyid').on('change', function(e) {
        //     e.preventDefault();

        //     let compid = $(this).val();
        //     if (!compid) {
        //         $('#company').val('');
        //         $('#country_id').val('').trigger('change');
        //         $('#regid').html('');
        //     }
        //     // get company details
        //     $.get('/companybyid/?company_id=' + compid, function(data) {
        //         // console.log(data)
        //         $('#company').val(data.company);
        //         if (data.region_id) {
        //             $('#regid').html(data.region_id);
        //         }
        //         if (data.country_id) {
        //             $('#country_id').val(data.country_id).trigger('change');
        //         }

        //     });

        //     // List customers
        //     $.get('/customersbyid/?company_id=' + compid, function(data) {
        //         let opt = '<option value="">--</option>';
        //         jQuery.each(data, function(index, value) {
        //             //   console.log(value);
        //             opt += '<option value="' + value.id + '">' + value.fullname + '</option>';
        //         });
        //         $('#customerid').html(opt);
        //     });
        // });

        $('#customerid').on('change', function(e) {
            e.preventDefault();

            let custid = $(this).val();

            $.get('/customers/' + custid, function(data) {
                $('#fullname').val(data.fullname);
                $('#email').val(data.email);
                $('#phone').val(data.phone);
            });
        });
        
        $('#lead_type').on('change', function(e) {
            var val = $(this).val();
            if(val == 'expo'){
                $("#expodiv").show();
                $("#expo_id").attr("required",true);
            } else {
                $("#expodiv").hide();
                $("#expo_id").attr("required",false);
                 $('#expo_id option').removeAttr('selected');
            }
            $("#expo_id").val("");
        });
    });
</script>

<script type="text/javascript">
    var path = "{{ route('customers.loadcompanies') }}";

    $("#search-company").autocomplete({
        minLength: 2,
        source: function(request, response) {
            $.ajax({
                url: path,
                type: 'GET',
                dataType: "json",
                data: {
                    search: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        search: function(event, ui) {
            var value = $("#search-company").val();
            // If not a number and less than three chars, cancel
            if (isNaN(value) && value.length < 2) {
                event.preventDefault();
            }
        },
        select: function(event, ui) {
            $("#search-company").val(ui.item.label); // display the selected text           

            customersByCompanyId(ui.item.value);
            // console.log(ui.item);
            return false;
        },
        change: function(event, ui) {
            let cid = ui.item ? ui.item.value : 0;
            customersByCompanyId(cid);
        },
        response: function(event, ui) {
            if (!ui.content.length) {
                var noResult = {
                    value: "0",
                    label: "No results found"
                };
                ui.content.push(noResult);
                //$("#message").text("No results found");
            } else {
                $("#message").empty();
            }
        }
    });

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

    function customersByCompanyId(compid) {
        compid = parseInt(compid);
        if (!compid || compid == 0) {
            resetCustomers();
            return;
        }
        // get company details
        $.get('/companybyid/?company_id=' + compid, function(data) {
            // reset forms
            resetCustomers();

            $("#company_id").val(data.id);
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
    }
    document.addEventListener('DOMContentLoaded', function() {
        var companyInput = document.getElementById('company');
        var companyErrorMessage = document.getElementById('companyErrorMessage');
        companyInput.addEventListener('input', function() {
            var companyName = this.value.trim();

            if (companyName !== '') {

                $.ajax({
                    url: '/check-company',
                    method: 'get',
                    data: {
                        companyName: companyName
                    },
                    success: function(response) {

                        if (response.exists) {
                            companyErrorMessage.style.display = 'block';
                            companyInput.classList.add('is-invalid');
                        } else {
                            companyErrorMessage.style.display = 'none';
                            companyInput.classList.remove('is-invalid');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput = document.getElementById('search-company');
        var validationMessage = document.getElementById('companyErrorMessage');
        var companyInput = document.getElementById('company');
        searchInput.addEventListener('input', function() {
            validationMessage.style.display = 'none';
            companyInput.classList.remove('is-invalid');
        });
    });
</script>

@endsection