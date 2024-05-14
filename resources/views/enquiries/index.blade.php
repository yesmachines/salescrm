@extends('layouts.default')
@section('title') All Enquiries @endsection

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
                <h1>Enquiries</h1>
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
              <div class="row mb-6">
                <div class="col-md-3">
                  <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="col-md-3">
                  <input type="date" class="form-control" id="end_date" name="end_date">
                </div>

                <div class="col-md-2">
                  <button type="button" class="btn btn-primary" id="filter_btn">Filter</button>
                </div>
              </div>
              <table id="edatable_1" class="table nowrap w-100 mb-5">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Company</th>
                    <th>Brand</th>
                    <th width="25%">Details</th>
                    <th>Submitted</th>
                    <th>DateOn</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($enquiries as $cust)
                  <tr>
                    <td>
                      {{$cust->fullname}}
                    </td>
                    <td class="text-truncate">{{$cust->email}}</td>
                    <td>{{$cust->mobile}}</td>
                    <td><span class="badge badge-soft-violet my-1  me-2">{{$cust->company}}</span></td>
                    <td>{{$cust->brand}}</td>
                    <td width="25%">{{$cust->description}}</td>
                    <td>{{$cust->submitted_by}}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($cust->created_at)) }}</td>

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
<script>
  $(document).ready(function() {
    $('#filter_btn').click(function() {
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();

      window.location.href = '{{ route("enquiries.index") }}?start_date=' + start_date + '&end_date=' + end_date;

      // Set the values of date inputs
      $('#start_date').val(start_date);
      $('#end_date').val(end_date);
    });
  });
</script>


@endsection