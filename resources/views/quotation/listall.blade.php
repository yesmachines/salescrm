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
                                <h1>List All Quotations</h1>
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
                                        <th width="10%">Quote#</th>
                                        <th width="20%">Company</th>
                                        <th width="20%">Supplier</th>
                                        <th width="20%">Amount (AED)</th>
                                        <th width="10%">Status</th>
                                        <th width="5%">Assigned</th>
                                        <th width="15%">Submit On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quotations as $quote)
                                    <tr class="{{($quote->parent_id >0)? 'lightRed': ''}}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="contact-star marked"> </span>
                                            </div>
                                        </td>
                                        <td><span style="cursor:pointer;" class="badge badge-info badge-outline" onclick="copyText(this);">{{$quote->reference_no}}</span> </td>
                                        <td title="{{$quote->company->company}}">
                                            <a href="{{ route('quotations.show', $quote->id) }}"> {{ Str::limit($quote->company->company, 40) }}
                                                <span class="icon"><span class="feather-icon"><i data-feather="arrow-up-right"></i></span></span>
                                            </a>
                                        </td>
                                        <td title="{{($quote->supplier_id? $quote->supplier->brand: '')}}">
                                            {{ Str::limit(($quote->supplier_id? $quote->supplier->brand: ''), 20) }}
                                            @if($quote->product_models)
                                            / {{ Str::limit(($quote->product_models? $quote->product_models: ''), 20) }}
                                            @endif
                                        </td>
                                        <td class="text-truncate">{{$quote->total_amount}} / {{$quote->gross_margin}}</td>
                                        <td>
                                            <span class="badge {{$quote->status_id == 6? 'badge-soft-success': 'badge-soft-danger'}}  my-1  me-2">{{$quote->status_id? $quote->quoteStatus->name: '--'}}</span>
                                        </td>
                                        <td class="text-truncate">{{$quote->assigned->user->name}}</td>
                                        <td>{{date("d-m-Y", strtotime($quote->submitted_date))}}</td>
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
    function copyText(e) {

        let copyText = e.innerHTML;

        navigator.clipboard.writeText(copyText).then(() => {

            Swal.fire(
                'Copied!',
                "You are copied the quote no: " + copyText,
                'info'
            );
        })
        /* Resolved - text copied to clipboard successfully */

    }
</script>

@endsection