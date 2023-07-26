@extends('layouts.default')

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pt-7 pb-4">
        <h1 class="pg-title">Create New Company</h1>
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
                <form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row gx-3">
                        <div class="col-sm-6">
                            <div class="title title-xs title-wth-divider text-primary text-uppercase"><span>Company Info</span></div>
                            <div class="row gx-3 mt-4">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Company *</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="company" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Address</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="address" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Landphone</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="landphone" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Company Email</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="email" name="email_address" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Website</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="website" />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Country*</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <select class="form-select select2" name="country_id" id="country_id" required>
                                            <option value="0">--</option>
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
                                        <select class="form-select select2" name="region_id" id="region_id">
                                            <option value="0">--</option>
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
                                        <label class="form-label">Full Name*</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="fullname" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Email ID*</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="email" name="email" required />
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">Phone*</label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="phone" />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row gx-3">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary mt-5">Create Company</button>
                            <button type="button" class="btn btn-secondary mt-5" onclick="window.location.href='/customers'">Discard</button>
                        </div>
                        <div class="col-sm-4"></div>
                    </div>
                </form>
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