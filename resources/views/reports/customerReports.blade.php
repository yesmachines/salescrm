@extends('layouts.default')
@section('title') Customer List @endsection

@section('content')


<!-- Page Body -->
<div class="hk-pg-body py-0">
  <div class="contactapp-wrap  contactapp-sidebar-toggle">

    <div class="contactapp-content">
      <div class="contactapp-detail-wrap">
        <header class="contact-header">
          <div class="d-flex align-items-center">
            <div class="dropdown">
              <a class="contactapp-title link-dark" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <h1>Customer Report</h1>
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

            <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover no-caret d-sm-inline-block d-none" href="{{route('reports.customers')}}" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Refresh"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>
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
              @livewire('customer-reports')
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<!-- /Page Body -->
<script>
  $(document).ready(function() {
    $('#countrySelect').select2();
  });
</script>

@endsection