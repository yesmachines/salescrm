@extends('layouts.default')

@section('content')


<!-- Page Body -->
<div class="hk-pg-body py-0">
    <div class="contactapp-wrap contactapp-sidebar-toggle">
        <!-- <nav class="contactapp-sidebar">
            <div data-simplebar class="nicescroll-bar">
                <div class="menu-content-wrap">
                    <button type="button" class="btn btn-primary btn-rounded btn-block mb-4" data-bs-toggle="modal" data-bs-target="#add_new_contact">
                        Add new customer
                    </button>
                    <div class="menu-group">
                        <ul class="nav nav-light navbar-nav flex-column">
                            <li class="nav-item active">
                                <a class="nav-link" href="javascript:void(0);">
                                    <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="inbox"></i></span></span>
                                    <span class="nav-link-text">All Customers</span>
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
                                <h1>Customers</h1>
                            </a>
                        </div>
                        <div class="dropdown ms-3">
                            <button class="btn btn-sm btn-outline-secondary flex-shrink-0 dropdown-toggle d-lg-inline-block d-none" data-bs-toggle="dropdown">Create New</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{route('customers.create')}}">Add New Company</a>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#add_new_contact">Add New Customers</a>
                            </div>
                        </div>
                    </div>
                    <div class="contact-options-wrap">

                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover no-caret d-sm-inline-block d-none" href="{{route('customers.index')}}" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Refresh"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>
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
                               @livewire('customer-list')
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit Info -->
            <div id="add_new_contact" class="modal fade add-new-contact" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <form method="POST" action="{{ route('customers.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h5 class="mb-5">Create New Customers</h5>
                                <div class="title title-xs title-wth-divider text-primary text-uppercase my-4"><span>Basic Info</span></div>
                                <div class="row gx-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Existing Company</label>
                                            <select class="form-select model-select2" name="company_id" id="companyid">
                                                <option value="">--</option>
                                                @foreach($companies as $id => $comp)
                                                <option value="{{$id}}">{{$comp}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">New Company</label>
                                            <input class="form-control" type="text" name="company" id="company" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Full Name</label>
                                            <input class="form-control" type="text" name="fullname" required />
                                        </div>

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Email ID</label>
                                            <input class="form-control" type="email" name="email" required />
                                        </div>

                                    </div>
                                </div>
                                <div class="row gx-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Phone</label>
                                            <input class="form-control" type="text" name="phone" />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- <div class="form-group">
                                            <label class="form-label">Country</label>
                                            <select class="form-select model-select2" name="location" required>
                                                <option value="0">--</option>
                                                @foreach ($countries as $key => $row)
                                                <option value="{{ $row->id }}">
                                                    {{ $row->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div> -->
                                    </div>

                                </div>
                            </div>
                            <div class="modal-footer align-items-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
                                <button type="submit" class="btn btn-primary">Create Customers</button>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('#companyid').on('change', function(e) {
            e.preventDefault();

            let compid = $(this).val();
            if (!compid) {
                $('#company').val('');
            } else {
                let selText = $("#companyid option:selected").text();
                $('#company').val(selText);
            }


        });

    });
</script>
@endsection
