@extends('layouts.default')

@section('content')

<div class="hk-pg-body py-0">
    <div class="integrationsapp-wrap integrationsapp-sidebar-toggle">

        <div class="integrationsapp-content" style="left: 0rem;">
            <div class="integrationsapp-detail-wrap">
                <header class="integrations-header">
                    <div class="d-flex align-items-center flex-1">
                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover flex-shrink-0" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Collapse">
                            <span class="btn-icon-wrap">
                                <span class="feather-icon"><i data-feather="chevron-left"></i></span>
                            </span>
                        </a>
                        <div class="v-separator d-sm-inline-block d-none"></div>
                        <nav class="ms-1 ms-sm-0" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{route('orders.index')}}">Order</a></li>
                                <li class="breadcrumb-item"><a href="">View Details</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$order->company->company}}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="integrations-options-wrap">
                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover hk-navbar-togglable d-md-inline-block d-none" href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Collapse">
                            <span class="btn-icon-wrap">
                                <span class="feather-icon"><i data-feather="chevron-up"></i></span>
                                <span class="feather-icon d-none"><i data-feather="chevron-down"></i></span>
                            </span>
                        </a>
                    </div>
                </header>
                <div class="integrations-body">
                    <div data-simplebar class="nicescroll-bar">
                        <div class="container mt-md-7 mt-3">
                            <div class="row">
                                <div class="col-xxl-8 col-lg-7">
                                    <div class="media">
                                        <div class="media-head me-3">
                                            <div class="avatar avatar-logo">
                                                <span class="initial-wrap bg-success-light-5">
                                                    <img src="{{asset('dist/img/symbol-avatar-15.png')}}" alt="logo">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h3 class="hd-bold mb-0">{{$order->company->company}}&nbsp;
                                                <span class="badge badge-warning" style="font-size: 12px;"> {{$order->os_number}}</span>
                                            </h3>
                                            <span>by <i>{{$order->customer->fullname}}</i></span>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-5 mt-lg-0 mt-3">
                                    @if($order->status == 'draft')
                                    <p class="text-danger">Your order summary is only a draft version. Kindly finish it in order to begin the download process.</p>
                                    @endif
                                    <a href="{{route('orders.download', $order->id)}}" title="Download OS" class="btn btn-sm btn-primary btn-block ms-2 mt-0">
                                        <span><span class="icon"><span class="feather-icon"><i data-feather="download"></i></span></span><span>Download OS</span></span>
                                    </a>

                                    <div class="d-flex mt-3">
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-secondary btn-block ms-2 mt-0">
                                            <span><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span><span>Edit</span></span>
                                        </a>
                                        <button class="btn btn-sm btn-light btn-block ms-2 mt-0" onclick="history.back();">
                                            <span><span class="icon"><span class="feather-icon"><i data-feather="chevron-left"></i></span></span><span>Back To List</button>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-8 col-lg-7">
                                    <input type="hidden" id="get_order_id" name="get_order_id" class="form-control" value="{{$order->id}}">
                                    <div class="separator"></div>
                                    <ul class="nav nav-light nav-pills nav-pills-rounded justify-content-center">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="tab_1" data-bs-toggle="pill" href="#tabit_1">
                                                <span class="nav-link-text">Order Summary</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab_2" data-bs-toggle="pill" href="#tabit_2">
                                                <span class="nav-link-text">Items & Delivery</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab_3" data-bs-toggle="pill" href="#tabit_3">
                                                <span class="nav-link-text">Supplier Details</span>
                                            </a>
                                        </li>

                                    </ul>

                                    <div class="tab-content py-7">
                                        <div class="tab-pane fade show active" id="tabit_1">
                                            <!-- Basic Details -->
                                            @include('orders._show._summary')
                                            <!-- End User Details -->
                                        </div>
                                        <div class="tab-pane fade" id="tabit_2">
                                            <!-- Basic Details -->
                                            @include('orders._show._client')
                                            <!-- End User Details -->
                                        </div><!-- -->
                                        <div class="tab-pane fade" id="tabit_3">
                                            @include('orders._show._supplier')
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-5">
                                    <div class="content-aside mt-4">
                                        <div class="card card-border">
                                            <div class="card-body">
                                                <h6 class="mb-4">Customer Details</h6>
                                                <ul class="list-unstyled">
                                                    <li class="mb-3">
                                                        <div class="fs-7">Company</div>
                                                        <div class="text-dark fw-medium">{{$order->company->company}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Customer</div>
                                                        <div class="text-dark fw-medium">{{$order->customer->fullname}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Customer Email</div>
                                                        <div class="text-dark fw-medium">{{$order->customer->email}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Contact Mobile</div>
                                                        <div class="text-dark fw-medium">{{$order->customer->phone}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Country</div>
                                                        <div class="text-dark fw-medium">{{isset($order->company->country)? $order->company->country->name: ''}}</div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@push('scripts')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // STEPS
    $(function() {
        $('.nav-item').on('click', function() {
            $('.nav-link').removeClass('active');
            $(this).find('.nav-link').addClass('active').blur();
        });

        $('.next-step, .prev-step').on('click', function(e) {
            var $activeTab = $('.tab-pane.active');

            // $('.btn-circle.btn-info').removeClass('btn-info').addClass('btn-default');
            $('.nav-item').find('.nav-link').removeClass('active');

            if ($(e.target).hasClass('next-step')) {
                var nextTab = $activeTab.next('.tab-pane').attr('id');
                $('[href="#' + nextTab + '"]').addClass('btn-info').removeClass('btn-default');
                $('[href="#' + nextTab + '"]').tab('show');
            } else {
                var prevTab = $activeTab.prev('.tab-pane').attr('id');
                $('[href="#' + prevTab + '"]').addClass('btn-info').removeClass('btn-default');
                $('[href="#' + prevTab + '"]').tab('show');
            }
        });
    });

    function nextStep() {
        var $activeTab = $('.tab-pane.active');
        var nextTab = $activeTab.next('.tab-pane').attr('id');
        $('[href="#' + nextTab + '"]').addClass('btn-info').removeClass('btn-default');
        $('[href="#' + nextTab + '"]').tab('show');
    }
</script>
@endpush

@endsection