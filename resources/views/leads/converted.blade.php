@extends('layouts.default')

@section('content')
<!-- Page Body -->
<div class="hk-pg-body py-0">
    <div class="contactapp-wrap contactapp-sidebar-toggle">

        <div class="contactapp-content">
            <div class="contactapp-detail-wrap">
                <header class="contact-header">
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a class="contactapp-title link-dark" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <h1>Converted Leads</h1>
                            </a>
                        </div>
                    </div>
                    <div class="contact-options-wrap">

                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover no-caret d-sm-inline-block d-none" href="{{route('leads.index')}}" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Refresh"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>
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
                                        <th width="15%">Company</th>
                                        <th width="10%">Phone</th>
                                        <th width="30%">Details</th>
                                        <th width="10%">Status</th>
                                        @hasanyrole('divisionmanager|salesmanager')
                                        @else
                                        <th width="10%">Assigned</th>
                                        @endhasanyrole
                                        <th width="10%">Date On</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leads as $lead)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="contact-star marked"><span class="feather-icon"><i data-feather="star"></i></span></span>
                                            </div>
                                        </td>
                                        <td>
                                            {{$lead->company->company}}
                                        </td>
                                        <td class="text-truncate"><span title="{{$lead->customer->phone}}">{{$lead->customer->phone}}</span></td>
                                        <td>{{ Str::limit($lead->details, 50) }}</td>
                                        <td><span class="badge badge-soft-violet my-1  me-2">{{$lead->lead_type}}</span></td>
                                        @hasanyrole('divisionmanager|salesmanager')
                                        @else
                                        <td>{{$lead->assigned->user->name}}</td>
                                        @endhasanyrole
                                        <td>{{$lead->enquiry_date}}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex">
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="View" href="{{ route('leads.show', $lead->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="eye"></i></span></span></a>
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

        </div>
    </div>
</div>
<!-- /Page Body -->

@endsection