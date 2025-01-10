@extends('layouts.default')

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pt-7 pb-4">
        <h1 class="pg-title">Edit Employee</h1>
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
                {!! Form::model($employee, ['method' => 'PATCH','enctype' => 'multipart/form-data', 'route' => ['employees.update', $employee->id]]) !!}
                <div class="">
                    <div class="">
                        <h5 class="mb-5">Edit Employee</h5>
                        <input type="hidden" name="user_id" value="{{ $employee->user_id}}" />
                        <div class="row gx-3">
                            <div class="col-sm-2 form-group">
                                <div class="dropify-square">
                                    <input type="file" class="dropify-1" name="image_url" />
                                </div>
                            </div>
                            <div class="col-sm-10 form-group">
                                <img src="{{asset('storage/'. $employee->image_url)}}" class="profile-avatar" alt="" width="100">
                            </div>
                        </div>
                        <div class="title title-xs title-wth-divider text-primary text-uppercase my-4"><span>Basic Info</span></div>
                        <div class="row gx-3">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input class="form-control" type="text" name="name" required value="{{$employee->user->name}}" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Employee ID</label>
                                    <input class="form-control" type="text" name="emp_num" required value="{{$employee->emp_num}}" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Email ID</label>
                                    <input class="form-control" type="email" name="email" required value="{{$employee->user->email}}" />
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Password</label>
                                    <input class="form-control" type="password" name="password" placeholder="*******" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Confirm Password</label>
                                    <input class="form-control" type="password" name="confirm-password" placeholder="*******" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Phone</label>
                                    <input class="form-control" type="text" name="phone" value="{{$employee->phone}}" />
                                </div>
                            </div>
                        </div>
                        <div class="row gx-3">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Divisions</label>
                                    <select class="form-select" name="division" required>
                                        <option value="">--</option>
                                        <option value="sd" {{$employee->division == "sd" ? "selected": ""}}>Steel Machinery</option>
                                        <option value="is" {{$employee->division == "is" ? "selected": ""}}>Industrial Solutions</option>
                                        <option value="ct" {{$employee->division == "ct" ? "selected": ""}}>Cleaning Technology</option>
                                        <option value="serv" {{$employee->division == "serv" ? "selected": ""}}>Maintenance and Service</option>
                                        <option value="ops" {{$employee->division == "ops" ? "selected": ""}}>Operations and Logistics</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">Designation</label>
                                    <input class="form-control" type="text" name="designation" value="{{$employee->designation}}" />
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="form-label">ACL</label>
                                    <select class="form-select" name="roles">
                                        <option value="">--</option>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @if(in_array($role->id, $userRoles) ) selected @endif> {{ $role->name }} </option>
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
                                <option value="">--</option>
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
                                            <input class="form-control" type="text" name="target_value" value="{{$target->target_value}}" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        @php
                                        $current = Carbon\Carbon::now()->format('Y');
                                        $next = Carbon\Carbon::now()->format('Y')+1;
                                        @endphp
                                        <div class="form-group">
                                            <label class="form-label">Fiscal Year</label>
                                            <select class="form-select" name="fiscal_year" required>
                                                <option value="{{ $current}}" {{$target->fiscal_year ==  $current ? "selected": ""}}>{{ $current}}</option>
                                                <option value="{{$next}}" {{$target->fiscal_year == $next ? "selected": ""}}>{{$next}}</option>
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
                                                <input type="checkbox" id="user-{{$emp->id}}" class="form-check-input" name="manager_id[{{ $emp->id }}]" value="{{$emp->id }}" {{ in_array($emp->id, $selManagers) ? "checked" : ""}}>
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
                                                    <input type="checkbox" id="area-{{$area->id}}" class="form-check-input" name="area_id[{{ $area->id }}]" value="{{$area->id }}" {{ in_array($area->id, $empAreaIds) ? "checked" : ""}}>
                                                    <label class="form-check-label" for="area-{{$area->id}}">{{ $area->name }}</label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>

                    </div>

                    <div class="row gx-3">
                        <div class="col-4"></div>
                        <div class="col-4">
                            @can('employees.index')
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='/employees'">Discard</button>
                            @endcan
                            <button type="submit" class="btn btn-primary">Update Employee</button>
                        </div>
                        <div class="col-4"></div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /Page Body -->
</div>
@endsection
