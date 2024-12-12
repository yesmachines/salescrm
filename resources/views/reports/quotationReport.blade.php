@extends('layouts.default')
@section('title') Reports of Quotation Summary @endsection

@section('content')


<!-- Page Body -->
<div class="pageLoader" id="pageLoader"></div>
<div class="hk-pg-body py-0">
  <div class="contactapp-wrap  contactapp-sidebar-toggle">

    <div class="contactapp-content">
      <div class="contactapp-detail-wrap">
        <header class="contact-header">
          <div class="d-flex align-items-center">
            <div class="dropdown">
              <a class="contactapp-title link-dark" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <h1>Brand-Wise Quotation Report</h1>
              </a>
            </div>

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
              <form method="GET">
                <div class="row">

                  <!-- Supplier dropdown aligned with proper column size -->
                  <div class="col-5">
                    <select name="supplier_id" class="form-control text-capitalize">
                      <option value="" disabled selected>-- Select --</option>
                      @foreach($suppliers as $sup)
                      <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->brand }}</option>
                      @endforeach
                    </select>
                  </div>


                  <!-- Buttons aligned properly with spacing -->
                  <div class="col-3">
                    <button class="btn btn-primary" type="submit">Search</button>
                    <button class="btn btn-secondary" type="button" onclick="window.location.href='/reports/quotation-summary'">Reset</button>
                   <button class="btn btn-success" type="submit" id="exportBtn" name="export" value="true">Export</button>
                  </div>
                </div>
              </form>

            </div>
            <div class="row mt-4">
              <div class="col">
                <div class="dropdown-divider mt-2 mb-4"></div>
              </div>
            </div>
            <table id="" class="table nowrap w-100 mb-5">
              <thead>
                <tr>
                  <th width="5%">QuoteNo.</th>
                  <th width="25%">Supplier</th>
                  <th width="10%">Margin Price(AED)</th>
                  <th width="5%">Submitted On</th>
                  <th width="5%">Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($quoteSummary as $i => $quote)
                <tr>
                  <td class="text-primary">{{ $quote->reference_no }}</td>

                  <td width="20%" class="text-truncate overflow-hidden">
                    @if($quote->supplier_id != 0)
                    {{ $quote->supplier->brand }}
                    @else
                    @php
                    $filteredItems = $quote->quotationItem->filter(function ($item) use ($supplierId) {
                      return isset($item->supplier->brand) && $item->supplier->id == $supplierId;
                    });
                    @endphp

                    @if($filteredItems->isNotEmpty())
                    <b>{{ $filteredItems->first()->supplier->brand }}</b><br />
                    @endif

                    @endif
                  </td>

                </td>

                <td>{{ $quote->gross_margin }}</td>
                <td>{{date("d-m-Y", strtotime($quote->created_at))}}</td>
                <td><span class="badge {{$quote->status_id == 6? 'badge-soft-success': 'badge-soft-danger'}}  my-1  me-2">{{$quote->status_id? $quote->quoteStatus->name: '--'}}</span></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div>
            {{ $quoteSummary->appends(['supplier_id' => request('supplier_id')])->links('pagination::bootstrap-5') }}
          </div>


        </div>

      </div>
    </div>
  </div>


</div>
</div>
</div>
<!-- /Page Body -->

@endsection
<script>
$(window).on('beforeunload', function() {

  $('#pageLoader').show();

});

$(function() {

  $('#pageLoader').hide();
})

</script>
