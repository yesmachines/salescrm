@extends('layouts.default')

@section('content')


<!-- Page Body -->
<div class="hk-pg-body py-0">
    <div class="contactapp-wrap contactapp-sidebar-toggle">
        <!-- <nav class="contactapp-sidebar">
            <div data-simplebar class="nicescroll-bar">
                <div class="menu-content-wrap">
                    <button type="button" class="btn btn-primary btn-rounded btn-block mb-4" data-bs-toggle="modal" data-bs-target="#add_new_contact">
                        Add new employees
                    </button>
                    <div class="menu-group">
                        <ul class="nav nav-light navbar-nav flex-column">
                            <li class="nav-item active">
                                <a class="nav-link" href="javascript:void(0);">
                                    <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="inbox"></i></span></span>
                                    <span class="nav-link-text">All Employees</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);">
                                    <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="trash-2"></i></span></span>
                                    <span class="nav-link-text">Deleted</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="separator separator-light"></div>
                    <div class="menu-group">
                        <ul class="nav nav-light navbar-nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);">
                                    <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="upload"></i></span></span>
                                    <span class="nav-link-text">Export</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);">
                                    <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="download"></i></span></span>
                                    <span class="nav-link-text">Import</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="javascript:void(0);">
                                    <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="printer"></i></span></span>
                                    <span class="nav-link-text">Print</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </nav> -->
        <div class="contactapp-content">
            <div class="contactapp-detail-wrap">
                <header class="contact-header">
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a class="contactapp-title link-dark" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <h1>Employees</h1>
                            </a>
                        </div>
                        <div class="dropdown ms-3">
                            <button class="btn btn-sm btn-outline-secondary flex-shrink-0 dropdown-toggle d-lg-inline-block d-none" data-bs-toggle="dropdown">Create New</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{route('employees.create')}}">Add New Employee</a>
                                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#add_new_contact">Create Quickly</a>
                            </div>
                        </div>
                    </div>
                    <div class="contact-options-wrap">

                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover no-caret d-sm-inline-block d-none" href="{{route('employees.index')}}" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Refresh"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>
                        <div class="v-separator d-lg-block d-none"></div>
                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover dropdown-toggle no-caret  d-lg-inline-block d-none  ms-sm-0" href="#" data-bs-toggle="dropdown"><span class="icon" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Manage Contact"><span class="feather-icon"><i data-feather="settings"></i></span></span></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Import</a>
                            <a class="dropdown-item" href="#">Export</a>
                        </div>

                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover hk-navbar-togglable d-sm-inline-block d-none" href="#" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Collapse">
                            <span class="icon">
                                <span class="feather-icon"><i data-feather="chevron-up"></i></span>
                                <span class="feather-icon d-none"><i data-feather="chevron-down"></i></span>
                            </span>
                        </a>
                    </div>
                    <!-- <div class="hk-sidebar-togglable"></div> -->
                </header>
                <div class="contact-body">
                    <div data-simplebar class="nicescroll-bar">

                        <div class="contact-list-view">
                            <div class="mt-2">
                                @include('layouts.partials.messages')
                            </div>
                            <table id="datable_1" class="table nowrap w-100 mb-5">
                                <thead>
                                    <tr>
                                        <th><span class="form-check mb-0">
                                                <input type="checkbox" class="form-check-input check-select-all" id="customCheck1">
                                                <label class="form-check-label" for="customCheck1"></label>
                                            </span></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Designation</th>
                                        <th>EmpId</th>
                                        <th>Roles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $emp)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="contact-star marked"><span class="feather-icon"><i data-feather="star"></i></span></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="media align-items-center">
                                                <div class="media-head me-2">
                                                    <div class="avatar avatar-xs avatar-rounded">
                                                        @if(isset($emp->image_url) && $emp->image_url)
                                                        <img src="{{asset('storage/'. $emp->image_url)}}" alt="user" class="avatar-img">
                                                        @else
                                                        <img src="{{asset('dist/img/avatar1.jpg')}}" alt="user" class="avatar-img">
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <span class="d-block text-high-em">{{$emp->user->name}}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-truncate">{{$emp->user->email}}</td>
                                        <td>{{$emp->phone}}</td>
                                        <td><span class="badge badge-soft-violet my-1  me-2">{{$emp->division}}</span>/<span class="badge badge-soft-danger  my-1  me-2">{{$emp->designation}}</span></td>
                                        <td>{{$emp->emp_num}}</td>
                                        <td>{{$emp->user->roles[0]->name}}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex">
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" href="{{ route('employees.edit', $emp->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span></a>
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover del-button" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Delete" href="javascript:void(0);" onclick="event.preventDefault();
                                        document.getElementById('delete-form-{{ $emp->id }}').submit();"><span class="icon"><span class="feather-icon"><i data-feather="trash"></i></span></span></a>
                                                    {!! Form::open(['method' => 'DELETE','route' => ['employees.destroy', $emp->id],'style'=>'display:none',
                                                    'id' => 'delete-form-'.$emp->id]) !!}
                                                    {!! Form::close() !!}
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit Info -->
            <div id="add_new_contact" class="modal fade add-new-contact" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
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
                                                <option selected="">--</option>
                                                <option value="sd">Steel Machinery</option>
                                                <option value="is">Industrial Solutions</option>
                                                <option value="ct">Cleaning Technology</option>
                                                <option value="serv">Maintenance and Service</option>
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
                                                <option selected="">--</option>
                                                @foreach ($roles as $key => $value)
                                                <option value=" {{ $key }}">
                                                    {{ $value }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-sm-12">

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
                            <div class="modal-footer align-items-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
                                <button type="submit" class="btn btn-primary">Create Employee</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Edit Info -->
        </div>
    </div>
</div>
<!-- /Page Body -->

@endsection