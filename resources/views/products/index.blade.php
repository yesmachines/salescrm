@extends('layouts.default')

@section('content')
<!-- Page Body -->

</style>
<div class="hk-pg-body py-0">
  <div class="contactapp-wrap contactapp-sidebar-toggle">
    <div class="contactapp-content">
      <div class="contactapp-detail-wrap">
        <header class="contact-header">
          <div class="d-flex align-items-center">
            <div class="dropdown">
              <a class="contactapp-title link-dark" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <h1>Products</h1>
              </a>
            </div>
            <div class="dropdown ms-3">
              <button class="btn btn-sm btn-outline-secondary flex-shrink-0 dropdown-toggle d-lg-inline-block d-none" data-bs-toggle="dropdown">Create New</button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="{{route('products.create')}}">Add New Product</a>
              </div>
            </div>
          </div>
          <div class="contact-options-wrap">

            <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover no-caret d-sm-inline-block d-none" href="{{route('leads.index')}}" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Refresh"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>
            <div class="v-separator d-lg-block d-none"></div>
            <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover dropdown-toggle no-caret  d-lg-inline-block d-none  ms-sm-0" href="#" data-bs-toggle="dropdown"><span class="icon" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Manage Contact"><span class="feather-icon"><i data-feather="settings"></i></span></span></a>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item" href="#">Import</a>
              <a class="dropdown-item" href="">Export</a>
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
              <form action="{{ route('products.index') }}" method="GET" class="form-inline mb-3">
                @csrf
                <div class="row">
                  <div class="col-md-4">
                    <div class="input-group">
                      <input type="text" name="query" class="form-control" placeholder="Search products" value="{{ request()->query('query') }}" aria-label="Search products" aria-describedby="button-search">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <select class="form-control select2" name="brand_id" id="brandInput">
                        <option value="">Select Suppliers</option>
                        @foreach ($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ request()->query('brand_id') == $sup->id ? 'selected' : '' }}>
                          {{ $sup->brand }}
                        </option>
                        @endforeach
                      </select>
                      <div class="invalid-data" style="display: none;">Please select a brand.</div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="input-group">
                      <button class="btn" type="submit" id="button-search" style="background-color: #007D88; color: white;">Search</button>
                    </div>
                  </div>
                </div>
              </form>
              <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%;">
                  <thead>
                    <tr>
                      <th style="font-size: 16px;">Sl.No</th>
                      <th style="font-size: 16px;">Title</th>
                      <th style="font-size: 16px;">Brand</th>
                      <th style="font-size: 16px;">Model</th>
                      <th style="font-size: 16px;">Part Number</th>
                      <th style="font-size: 16px;">Selling Price</th>
                      <th style="font-size: 16px;">MOSP</th>
                      <th style="font-size: 16px;">Category</th>
                      <th style="font-size: 16px;">Action</th>
                    </tr>

                  </thead>
                  <tbody>
                    @php
                    $count = 0;
                    @endphp
                    @foreach ($products as $index => $product)
                    @php
                    $count++;
                    if($count %2 == 0){
                      $rowtype = "even";
                    }else{
                      $rowtype = "odd";
                    }
                    @endphp
                    <tr class="{{$rowtype}}">
                      <td>{{$index + $products->firstItem()}}</td>
                      <td>{{$product->title}} &nbsp;<span class="badge badge-soft-danger">{{$product->product_type}}</span></td>
                      <td> {{ $product->supplier->brand ?? '' }}</td>
                      <td>{{$product->modelno  ?? ''}}</td>
                      <td>{{$product->part_number ?? ''}}</td>
                      <td>{{$product->selling_price}} {{$product->currency}}</td>
                      <td>{{$product->margin_percent}} %<br />
                        <small class="text-muted">({{$product->margin_price}} {{$product->currency}})</small>
                      </td>
                      <td>{{$product->product_category}}</td>
                      <td>
                        <div class="d-flex align-items-center">
                          <div class="d-flex">
                            <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" href="{{ route('products.edit', $product->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span></a>
                            <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover del-button" data-bs-toggle="tooltip" data-placement="top" title="Delete" href="javascript:void(0);" onclick="confirmDelete('{{ $product->id }}');">
                              <span class="icon"><span class="feather-icon"><i data-feather="trash"></i></span></span>
                            </a>

                            {!! Form::open(['method' => 'DELETE','route' => ['products.destroy', $product->id],'style'=>'display:none',
                            'id' => 'delete-form-'.$product->id]) !!}
                            {!! Form::close() !!}

                          </div>

                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>

                </table>
                {{ $products->appends(request()->query())->links() }}
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<script>
function confirmDelete(productId) {
  if (confirm("Are you sure you want to delete this product?")) {
    document.getElementById('delete-form-' + productId).submit();
  }
}
</script>


@endsection
