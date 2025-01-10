@extends('layouts.default')

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pt-7 pb-4">
        <h1 class="pg-title">Create New Employee</h1>
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
                <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="">
                        <div class="">
                            <h5 class="mb-5">Create New Employee</h5>

                            <div class="row gx-3">
                                <div class="col-sm-2 form-group">
                                    <div class="dropify-square">
                                        <input type="file" class="dropify-1" name="image_url" />
                                    </div>
                                </div>
                                <div class="col-sm-10 form-group">

                                </div>
                            </div>
                            <div class="title title-xs title-wth-divider text-primary text-uppercase my-4"><span>Basic Info</span></div>
                            <div class="row gx-3">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                        <input class="form-control" type="text" name="name" required />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">Employee ID</label>
                                        <input class="form-control" type="text" name="emp_num" required />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">Email ID</label>
                                        <input class="form-control" type="email" name="email" required />
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <input class="form-control" type="password" name="password" required />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">Confirm Password</label>
                                        <input class="form-control" type="password" name="confirm-password" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">Phone</label>
                                        <input class="form-control" type="text" name="phone" />
                                    </div>
                                </div>
                            </div>
                            <div class="row gx-3">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">Divisions</label>
                                        <select class="form-select" name="division" required>
                                            <option value="">--</option>
                                            <option value="sd">Steel Machinery</option>
                                            <option value="is">Industrial Solutions</option>
                                            <option value="ct">Cleaning Technology</option>
                                            <option value="serv">Maintenance and Service</option>
                                            <option value="ops">Operations and Logistics</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">Designation</label>
                                        <input class="form-control" type="text" name="designation" />
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">ACL</label>
                                        <select class="form-select" name="roles" required>
                                            <option value="">--</option>
                                            @foreach ($roles as $key => $value)
                                            <option value=" {{ $key }}">
                                                {{ $value }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('superadmin'))
                            <div class="row gx-3">
                              <div class="col-sm-4">
                                <div class="form-group">
                                  <label class="form-label">Status</label>
                                  <select class="form-select" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            @endif

                            <div class="row gx-3">
                                <div class="col-sm-6">

                                    <div class="title title-xs title-wth-divider text-primary text-uppercase my-4"><span>Assigned Target</span></div>
                                    <div class="row gx-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label">Target Value</label>
                                                <input class="form-control" type="text" name="target_value" required />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label">Fiscal Year</label>
                                                <select class="form-select" name="fiscal_year" required>
                                                    <option value="{{Carbon\Carbon::now()->format('Y')}}">{{Carbon\Carbon::now()->format('Y')}}</option>
                                                    <option value="{{Carbon\Carbon::now()->format('Y') + 1}}">{{Carbon\Carbon::now()->format('Y') + 1}}</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row gx-3">
                                <div class="col-sm-6">
                                    <div class="title title-xs title-wth-divider text-primary text-uppercase my-4"><span>Reported To</span></div>
                                    <div class="row gx-3">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                @foreach($managers as $emp)
                                                <div class="form-check form-check-sm mt-2">
                                                    <input type="checkbox" id="user-{{$emp->id}}" class="form-check-input" name="manager_id[{{ $emp->id }}]" value="{{$emp->id }}">
                                                    <label class="form-check-label" for="user-{{$emp->id}}">{{ $emp->user->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">

                                    <div class="title title-xs title-wth-divider text-primary text-uppercase my-4"><span>Areas</span></div>
                                    <div class="row gx-3">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                @foreach($areas as $area)
                                                <div class="form-check form-check-sm mt-2">
                                                    <input type="checkbox" id="area-{{$area->id}}" class="form-check-input" name="area_id[{{ $area->id }}]" value="{{$area->id }}">
                                                    <label class="form-check-label" for="area-{{$area->id}}">{{ $area->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <div class="row gx-3 mt-4">
                            <div class="col-4"></div>
                            <div class="col-4">
                                <button type="button" class="btn btn-secondary" onclick="window.location.href='/employees'">Discard</button>
                                <button type="submit" class="btn btn-primary">Create Employee</button>
                            </div>
                            <div class="col-4"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Page Body -->
</div>
@endsection
