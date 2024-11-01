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
                                            <h3 class="hd-bold mb-0">{{$order->yespo_no}}</h3>
                                            <span>by <i>{{$order->company->company}}</i></span>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-5 mt-lg-0 mt-3">
                                    <div class="d-flex mt-3">
                                        <button class="btn btn-secondary btn-block" onclick="history.back();">Back To List</button>
                                        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-light btn-block ms-2 mt-0">
                                            <span><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span><span>Edit</span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-8 col-lg-7">
                                    <input type="hidden" id="get_order_id" name="get_order_id" class="form-control" value="{{$order->id}}">
                                    <div class="separator"></div>
                                    <ul class="nav nav-light nav-pills nav-pills-rounded justify-content-center">
                                        <!-- <li class="nav-item">
                                            <a class="nav-link active" id="tab_1" data-bs-toggle="pill" href="#tabit_1">
                                                <span class="nav-link-text">Order Details</span>
                                            </a>
                                        </li> -->
                                        <li class="nav-item">
                                            <a class="nav-link active" id="tab_2" data-bs-toggle="pill" href="#tabit_2">
                                                <span class="nav-link-text">Order Summary</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab_3" data-bs-toggle="pill" href="#tabit_3">
                                                <span class="nav-link-text">Order Updates </span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-7">
                                        <!-- <div class="tab-pane fade show active" id="tabit_1">
                                            <h5>Order Details</h5>
                                            <p>Please refer the below Order and customer information :</p>
                                            <div class="row my-4">

                                                <div class="col-xxl-12">
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Po No.</h6>
                                                        </div>
                                                        <div class="col-8">

                                                            <p>{{$order->po_number}}</p>

                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Po Date.</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <p>{{$order->po_date}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Po Recieved.</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <p>{{$order->po_received}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Status:</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <p>{{$order->status}}</p>
                                                        </div>
                                                    </div>

                                                </div>
                                    </div>
                                </div> -->
                                        <div class="tab-pane fade show active" id="tabit_2">

                                            <h5>Delivery Details</h5>
                                            <div class="row my-4">

                                                <div class="col-xxl-12">
                                                    @if(isset($order->orderDelivery) && !empty($order->orderDelivery))
                                                    @include('orders.partials._delivery',['order_delivery' => $order->orderDelivery])
                                                    @else
                                                    <p class="text-muted small">-- No information --</p>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>


                                        <div class="tab-pane fade" id="tabit_3">
                                            <div class="title title-lg mb-4"><span>Latest Order Updates </span></div>
                                            <div class="comment-block ">

                                                <div class="list-his">
                                                    @if(isset($order->orderHistory) && !empty($order->orderHistory))
                                                    @include('orders.partials._listhistory',['histories' => $order->orderHistory,
                                                    'customer' => $order->customer->fullname, 'showOnly' => true])
                                                    @else
                                                    <p class="text-muted small">-- No information --</p>
                                                    @endif
                                                </div>


                                            </div>
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
                                                        <div class="fs-7">Customer PO No</div>
                                                        <div class="text-dark fw-medium">{{$order->po_number}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">YES PO No</div>
                                                        <div class="text-dark fw-medium">{{$order->yespo_no}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">PO Date</div>
                                                        <div class="text-dark fw-medium">{{$order->po_date}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">PO Received On</div>
                                                        <div class="text-dark fw-medium">{{$order->po_received}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Order Status</div>
                                                        <div class="text-dark fw-medium">
                                                            <span class="badge badge-soft-danger my-1 me-2">
                                                                {{$order->status}}</span>
                                                        </div>
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

@endsection