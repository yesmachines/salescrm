@extends('layouts.default')
@section('title') Reports of Win Standing @endsection

@section('content')


<!-- Page Body -->
<div class="hk-pg-body py-0">
    <div class="contactapp-wrap  contactapp-sidebar-toggle">
        <!-- <nav class="contactapp-sidebar">
            <div data-simplebar class="nicescroll-bar">
                <div class="menu-content-wrap">
                    <button type="button" class="btn btn-primary btn-rounded btn-block mb-4" data-bs-toggle="modal" data-bs-target="#add_new_visitor">
                        Add new customer
                    </button>
                    <div class="menu-group">
                        <ul class="nav nav-light navbar-nav flex-column">
                            <li class="nav-item active">
                                <a class="nav-link" href="javascript:void(0);">
                                    <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="inbox"></i></span></span>
                                    <span class="nav-link-text">All Enquiries</span>
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
                                <h1>Win Standing Summary</h1>
                            </a>
                        </div>
                        <!-- <div class="dropdown ms-3">
                            <button class="btn btn-sm btn-outline-secondary flex-shrink-0 dropdown-toggle d-lg-inline-block d-none" data-bs-toggle="dropdown">Create New</button>
                            <div class="dropdown-menu">

                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#add_new_visitor">Add New Customers</a>
                            </div>
                        </div> -->
                    </div>
                    <div class="contact-options-wrap">

                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover no-caret d-sm-inline-block d-none" href="{{route('enquiries.index')}}" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Refresh"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>
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
                            <div class="row">
                                <div class="col">
                                    <form method="GET">
                                        <label class="form-label">Search By Submitted Date</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="closing_start_date" placeholder="From" onfocus="(this.type='date')" onblur="(this.type='text')" value="{{ isset($input['closing_start_date'])? $input['closing_start_date']:'' }}">
                                            <input type="text" class="form-control" name="closing_end_date" placeholder="To" onfocus="(this.type='date')" onblur="(this.type='text')" value="{{ isset($input['closing_end_date'])? $input['closing_end_date']:'' }}">
                                            <button class="btn btn-primary" type="submit">Search</button>
                                            <button class="btn btn-secondary" type="button" onclick="window.location.href='/reports/winstanding'">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="dropdown-divider mt-2 mb-4"></div>
                                </div>
                            </div>
                            <table id="rdatable_1" class="table nowrap w-100 mb-5">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Employee</th>
                                        <th>No. of Quotes</th>
                                        <th>Gross Margin (AED)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($winSummary as $i => $win)
                                    <tr>
                                        <td>{{ $i + 1}}</td>
                                        <td>{{$win->employee}}</td>
                                        <td>{{$win->quotecount}}</td>
                                        <td>{{$win->value}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /Page Body -->

@endsection