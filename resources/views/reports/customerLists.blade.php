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
                <h1>Customers</h1>
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
          <div class="row">
            <div class="col-md-6">
              <form method="GET">
                <div class="input-group mb-4">
                  <input type="text" class="form-control" name="start_date" placeholder="From" onfocus="(this.type='date')" onblur="(this.type='text')" value="{{ isset($input['start_date'])? $input['start_date']:'' }}">
                  <input type="text" class="form-control" name="end_date" placeholder="To" onfocus="(this.type='date')" onblur="(this.type='text')" value="{{ isset($input['end_date'])? $input['end_date']:'' }}">
                </div>
              </div>
              <div class="col-md-3">
                <div class="input-group mb-3">
                  <select class="form-control" id="countrySelect" name="country_id">
                    <option value="">Select Country</option>
                    @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{ isset($input['country']) && $input['country'] == $country->id ? 'selected' : '' }}>
                      {{ $country->name }}
                    </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="input-group mb-3">
                  <button class="btn btn-primary" type="submit">Search</button>
                  <button class="btn btn-secondary" type="button" onclick="window.location.href='/reports/customers'">Reset</button>
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
                <th>Name</th>
                <th>Company</th>
                <th>Reference No</th>
                <th>Country</th>
                <th>email</th>
                <th>phone</th>
              </tr>
            </thead>
            <tbody>

              @foreach ($customersData as $index => $value)

              <tr>
                <td>{{$index + 1}}</td>
                <td>{{$value->fullname}}</td>
                <td>{{$value->company->company}}</td>
                <td>{{$value->company->reference_no}}</td>
                <td>{{$value->country->name ?? ''}}</td>
                <td>{{ $value->email ?? '' }}</td>
                <td>{{ $value->phone ?? '' }}</td>
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
  $('#countrySelect').select2();
});
</script>

@endsection
