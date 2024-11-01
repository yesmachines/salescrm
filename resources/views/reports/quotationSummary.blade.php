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
                                <h1>Quotation Report</h1>
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
                                    <div class="col-2">
                                        <label class="form-label"> Submitted From</label>

                                        <input type="text" class="form-control" name="start_date" placeholder="From" onfocus="(this.type='date')" onblur="(this.type='text')" value="{{ isset($input['start_date'])? $input['start_date']:'' }}">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label"> Submitted To</label>
                                        <input type="text" class="form-control" name="end_date" placeholder="To" onfocus="(this.type='date')" onblur="(this.type='text')" value="{{ isset($input['end_date'])? $input['end_date']:'' }}">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label"> Manager</label>
                                        <select name="assigned_to" class="form-control">
                                            <option value="">-- Select --</option>
                                            @foreach($employees as $emp)
                                            <option value="{{$emp->id}}">{{ $emp->user->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <!-- <label class="form-label"> Country</label>
                                        <select name="country_id" class="form-control">
                                            <option value="">-- Select --</option>
                                            @foreach($countries as $ct)
                                            <option value="{{$ct->id}}">{{ $ct->name}}</option>
                                            @endforeach
                                        </select> -->
                                    </div>

                                    <div class="col-2">
                                        <!-- <label class="form-label"> Supplier</label>
                                        <select name="supplier_id" class="form-control text-capitalize">
                                            <option value="">-- Select --</option>
                                            @foreach($suppliers as $sup)
                                            <option value="{{$sup->id}}">{{ $sup->brand }}</option>
                                            @endforeach
                                        </select> -->
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                        <button class="btn btn-secondary" type="button" onclick="window.location.href='/reports/quotations'">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <div class="dropdown-divider mt-2 mb-4"></div>
                            </div>
                        </div>
                        <table id="rdatable_1" class="table nowrap w-100 mb-5">
                            <thead>
                                <tr>
                                    <th width="5%">QuoteNo.</th>
                                    <th width="10%">Company</th>
                                    <th width="5%">Country</th>
                                    <th width="5%">Customer</th>
                                    <th width="5%">Contact Email</th>
                                    <th width="5%">Contact No.</th>
                                    <th width="25%">Product</th>
                                    <th width="10%">Sales Value (AED)</th>
                                    <th width="10%">Manager</th>
                                    <th width="5%">Submitted On</th>
                                    <th width="5%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quoteSummary as $i => $quote)
                                <tr>
                                    <td class="text-primary">{{ $quote->reference_no }}</td>
                                    <td>{{ $quote->company->company }}</td>
                                    <td>{{ $quote->company->country->name }}</td>
                                    <td>{{ $quote->customer->fullname }}</td>
                                    <td>{{$quote->customer->email}}</td>
                                    <td>{{$quote->customer->phone}}</td>
                                    <td width="20%" class="text-truncate overflow-hidden">@if($quote->product_model && $quote->supplier_id != 0)
                                        {{ $quote->product_model }} /{{ $quote->supplier->brand}}
                                        @else
                                        @foreach($quote->quotationItem as $item)
                                        @if(isset($item->product->supplier) )
                                        <b>{{$item->product->supplier->brand}}</b><br />
                                        @endif
                                        @if(isset($item->product->title))
                                        {{$item->product->title}}
                                        @endif
                                        @endforeach
                                        @endif
                                    </td>
                                    <td>{{ $quote->total_amount }}</td>
                                    <td>{{ $quote->assigned->user->name }}</td>
                                    <td>{{date("d-m-Y", strtotime($quote->created_at))}}</td>
                                    <td><span class="badge {{$quote->status_id == 6? 'badge-soft-success': 'badge-soft-danger'}}  my-1  me-2">{{$quote->status_id == 6? 'WON': 'PENDING'}}</span></td>
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
<script>
    $(window).on('beforeunload', function() {

        $('#pageLoader').show();

    });

    $(function() {

        $('#pageLoader').hide();
    })
</script>