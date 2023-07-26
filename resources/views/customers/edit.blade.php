@extends('layouts.default')

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pt-7 pb-4">
        <h1 class="pg-title">Edit Customer</h1>
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
                {!! Form::model($customer, ['method' => 'PATCH','enctype' => 'multipart/form-data', 'route' => ['customers.update', $customer->id]]) !!}
                <div class="row gx-3">
                    <div class="col-sm-6">
                        <input type="hidden" name="company_id" value="{{ $customer->company_id }}" />
                        <div class="title title-xs title-wth-divider text-primary text-uppercase"><span>Company Info</span></div>
                        <div class="row gx-3 mt-4">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Company</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="company" required value="{{ $customer->company->company }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="address" value="{{ $customer->company->address }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Landphone</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="landphone" value="{{ $customer->company->landphone }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Company Email</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="email" name="email_address" value="{{ $customer->company->email_address }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Website</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="website" value="{{ $customer->company->website }}" />
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
                                        <option value="0">--</option>
                                        @foreach ($countries as $key => $row)
                                        <option value="{{ $row->id }}" {{$customer->company->country_id == $row->id ? 'selected': ''}}>
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
                                    <select class="form-select select2" name="region_id" id="region_id">
                                        <option value="0">--</option>
                                        @foreach($regions as $reg)
                                        <option value="{{$reg->id}}" {{$customer->company->region_id == $reg->id ? "selected": ""}}>{{$reg->state}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="title title-xs title-wth-divider text-primary text-uppercase"><span>Personal Info</span></div>
                        <div class="row gx-3 mt-4">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="fullname" required value="{{ $customer->fullname }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Email ID</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="email" name="email" required value="{{ $customer->email }}" />
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="phone" value="{{ $customer->phone }}" />
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row gx-3">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary mt-5">Update Customer</button>
                        <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='/customers'">Discard</button>
                    </div>
                    <div class="col-4"></div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /Page Body -->
</div>
<script>
    $(document).ready(function() {
        $('#country_id').on('change', function(e) {
            let cid = $(this).val();

            $.get('/regionbycountry/' + cid, function(data) {
                let opt = '<option value="">--</option>';
                jQuery.each(data, function(index, value) {
                    //   console.log(value);
                    opt += '<option value="' + value.id + '">' + value.state + '</option>';
                });
                // console.log(opt)
                $('#region_id').html(opt);
            });

        });
    });
</script>
@endsection