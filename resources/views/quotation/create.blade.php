@extends('layouts.default')

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pt-7 pb-4">
        <h1 class="pg-title">Create New Quotation</h1>
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
                <form method="POST" action="{{ route('quotations.store') }}" enctype="multipart/form-data">
                    @csrf
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
                                            <option value=" {{$id}}">{{$comp}}</option>
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
                                            <option value="">--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">New Company</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="company" id="company" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="fullname" id="fullname" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Email ID</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="email" name="email" id="email" />
                                    </div>
                                </div>
                                <div class=" col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                    </div>
                                </div>
                                <div class=" col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="phone" id="phone" required />
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
                                            <option value="{{ $row->id }}">
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
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Quotation Info</span></div>
                            <div class="row gx-3 mt-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Product Category</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select select2" name="category_id">
                                            <option value="0">--</option>
                                            @foreach ($categories as $id => $cat)
                                            <option value=" {{ $id }}">
                                                {{ $cat }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <a href="{{route('categories.index')}}" class="btn-link small mt-1">+ Add New</a>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Supplier</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select select2" name="supplier_id">
                                            <option value="0">--</option>
                                            @foreach ($suppliers as $k => $sup)
                                            <option value=" {{ $sup->id }}">
                                                {{ $sup->brand }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <a href="{{route('suppliers.index')}}" class="btn-link small mt-1">+ Add New</a>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Sales Value (AED)</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="total_amount" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Gross Margin (AED)</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="gross_margin" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Submitted On</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="date" name="submitted_date" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Winning Probability (%)</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="winning_probability" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Closing Date</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="date" name="closure_date" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select" name="status_id" required>
                                            <option selected="">--</option>
                                            @foreach($quoteStatuses as $id=> $stat)
                                            <option value="{{$id}}">{{$stat}}</option>
                                            @endforeach
                                        </select>
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
                                            <option value="{{$emp->id}}">{{ $emp->user->name}}</option>
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
                                        <textarea class="form-control" name="remarks" row="10"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gx-3">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <button type="submit" name="publish" class="btn btn-primary mt-5 me-2">Create Quotation</button>
                            <button type="submit" name="draft" class="btn btn-info mt-5 me-2">Save as Draft</button>
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
        $('#companyid').on('change', function(e) {
            e.preventDefault();

            let compid = $(this).val();
            if (!compid) {
                $('#company').val('');
            }
            let selText = $("#companyid option:selected").text();
            $('#company').val(selText);

            $.get('/customersbyid/?company_id=' + compid, function(data) {
                let opt = '<option value="">--</option>';
                jQuery.each(data, function(index, value) {
                    //   console.log(value);
                    opt += '<option value="' + value.id + '">' + value.fullname + '</option>';
                });
                // console.log(opt)
                $('#customerid').html(opt);
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
                $('#location').val(data.location).trigger('change');
            });
        });
    });
</script>

@endsection