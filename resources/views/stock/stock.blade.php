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
                <h1>Stock OS</h1>
              </a>
            </div>
            <div class="dropdown ms-3">
              <button class="btn btn-sm btn-outline-secondary flex-shrink-0 dropdown-toggle d-lg-inline-block d-none" data-bs-toggle="dropdown">Create New</button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{route('stock.create')}}">Add New Stock</a>
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
              <div>
                <table id="datable_1" class="table nowrap w-100 mb-5">
                  <thead>
                    <tr>
                      <th>S.NO</th>
                      <th>Type</th>
                      <th width="25%">Supplier</th>
                      <th>OS Number</th>
                      <th>OS Date</th>
                      <th>Buying Price (AED)</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($stocks as $key=> $stock)

                    <tr>
                      <td>
                        {{ $key+1}}
                      </td>
                      <td>
                        {{$stock->purchase_mode}}
                      </td>
                      <td>
                        {{$stock->getStockBrandAttribute()}}
                      </td>

                      <td>
                        <span class="badge badge-outline {{$stock->order_for == 'yesclean'? 'ycref': 'ymref'}}">
                          {{$stock->os_number}}</span>
                      </td>
                      <td>{{$stock->os_date}}</td>
                      <td>
                        {{$stock->buying_price}}
                      </td>
                      <td>
                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover dropdown-toggle no-caret" href="#" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                          <span class="icon"><span class="feather-icon"><i data-feather="more-horizontal"></i></span>
                        </a>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('stock.download', $stock->id) }}" title="Download OS"><span class="feather-icon dropdown-icon"><i data-feather="download"></i></span><span>Download OS</span></a>
                          <a class="dropdown-item" data-bs-toggle="tooltip" data-placement="top" title="Edit" data-bs-original-title="Edit" href="{{ route('stock.edit', $stock->id) }}">
                            <span class="feather-icon dropdown-icon"><i data-feather="edit"></i></span><span>Edit</span></a>
                          <a class="dropdown-item" href="{{route('purchaserequisition.createstock',$stock->id)}}"><span class="feather-icon dropdown-icon"><i data-feather="plus-circle"></i></span><span>Create PR</span></a>

                          <a class="dropdown-item del-button d-none" href="#" onclick="deleteOrder({{$stock->id}});"><span class="feather-icon dropdown-icon"><i data-feather="trash"></i></span><span>Delete</span></a>
                          {!! Form::open(['method' => 'DELETE','route' => ['stock.destroy', $stock->id],'style'=>'display:none',
                          'id' => 'delete-form-'.$stock->id]) !!}
                          {!! Form::close() !!}


                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                {{ $stocks->links() }}
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /Page Body -->
<script type="text/javascript">
  function deleteOrder(orid) {

    event.preventDefault();

    if (!orid) return;

    Swal.fire({
      title: "Are you sure?",
      text: "You are sure to delete the Order !",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('delete-form-' + orid).submit();
      }
    });

  }
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