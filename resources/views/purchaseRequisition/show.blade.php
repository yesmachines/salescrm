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
                                <li class="breadcrumb-item"><a href="{{route('purchaserequisition.index')}}">Purchase Requisition</a></li>
                                <li class="breadcrumb-item"><a href="">View Details</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$purchaseRequest->pr_number}}</li>
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
                                            <h3 class="hd-bold mb-0">{{$purchaseRequest->supplier->brand}}&nbsp;
                                                <span class="badge badge-outline {{$purchaseRequest->pr_for == 'yesclean'? 'ycref': 'ymref'}}" style="font-size: 12px;"> {{$purchaseRequest->pr_number}}</span>
                                            </h3>


                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-5 mt-lg-0 mt-3">
                                    <a href="{{route('purchaserequisition.download', $purchaseRequest->id)}}" title="Download OS" class="btn btn-sm btn-primary btn-block ms-2 mt-0">
                                        <span><span class="icon"><span class="feather-icon"><i data-feather="download"></i></span></span><span>Download PR</span></span>
                                    </a>
                                    <div class="d-flex mt-3">
                                        <a href="{{ route('purchaserequisition.edit', $purchaseRequest->id) }}" class="btn btn-sm btn-secondary btn-block ms-2 mt-0">
                                            <span><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span><span>Edit</span></span>
                                        </a>
                                        <button class="btn btn-sm btn-light btn-block ms-2 mt-0" onclick="history.back();">
                                            <span><span class="icon"><span class="feather-icon"><i data-feather="chevron-left"></i></span></span><span>Back To List</button>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-8 col-lg-7">
                                    <div class="separator"></div>

                                    <h5>PR Summary Details</h5>
                                    <p>Please refer the below PR, items, delivery and payment information :</p>
                                    <br>
                                    <div class="row my-4">
                                        <div class="col-xxl-12">

                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <h6>PR Date</h6>
                                                </div>
                                                <div class="col-6">
                                                    {{$purchaseRequest->pr_date}}
                                                </div>
                                                <div class="col-2"></div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <h6>PR No.</h6>
                                                </div>
                                                <div class="col-6">
                                                    {{$purchaseRequest->pr_number }}
                                                </div>
                                                <div class="col-2"></div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <h6>Supplier</h6>
                                                </div>
                                                <div class="col-6">
                                                    {{$purchaseRequest->supplier->brand}}
                                                </div>
                                                <div class="col-2"></div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <h6>Total Price</h6>
                                                </div>
                                                <div class="col-6">
                                                    {{$purchaseRequest->total_price}} {{$purchaseRequest->currency}}
                                                </div>
                                                <div class="col-2"></div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <h6>Delivery Term</h6>
                                                </div>
                                                <div class="col-6">
                                                    {{$purchaseRequest->purchaseDelivery->delivery_term}}
                                                </div>
                                                <div class="col-2"></div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <h6>Shipping Mode</h6>
                                                </div>
                                                <div class="col-6">
                                                    {{$purchaseRequest->purchaseDelivery->shipping_mode}}
                                                </div>
                                                <div class="col-2"></div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <h6>Availability</h6>
                                                </div>
                                                <div class="col-6">
                                                    {{$purchaseRequest->purchaseDelivery->availability}}
                                                </div>
                                                <div class="col-2"></div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <h6>Warranty</h6>
                                                </div>
                                                <div class="col-6">
                                                    {{$purchaseRequest->purchaseDelivery->warranty}}
                                                </div>
                                                <div class="col-2"></div>
                                            </div>


                                            <div class="row mb-2">
                                                <div class="col-4">
                                                    <h6>Status</h6>
                                                </div>
                                                <div class="col-6">
                                                    <span class="badge badge-warning">{{$purchaseRequest->status}}</span>
                                                </div>
                                                <div class="col-2"></div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <div class="separator"></div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-xxl-12">
                                                    <h6> <b>Product Items</b></h6>
                                                    <table class="table form-table" id="productItems">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>PartNo.</th>
                                                                <th>Product</th>
                                                                <th>Unit Price</th>
                                                                <th>Qty</th>
                                                                <th>Final Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($purchaseRequest->purchaseItem as $item)
                                                            <tr>
                                                                <td width="5%">{{$loop->iteration}}</td>
                                                                <td width="10%">{{$item->partno? $item->partno: '--' }} </td>
                                                                <td width="40%">
                                                                    {{$item->item_description}}<br />
                                                                    <span class="badge badge-light">{{$item->yes_number}}</span>
                                                                </td>
                                                                <td width="20%">
                                                                    {{$item->unit_price}} {{$item->currency}}
                                                                </td>
                                                                <td width="5%">
                                                                    {{$item->quantity}}
                                                                </td>
                                                                <td width="20%">
                                                                    {{$item->total_amount}} {{$item->currency}}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                    @if(count($purchaseRequest->purchaseCharge ) >0)
                                                    <h6> <b>Additional Charges</b></h6>
                                                    <table class="table form-table mt-3" id="charges_add">

                                                        @foreach($purchaseRequest->purchaseCharge as $charge)
                                                        <tr>
                                                            <td width="5%"></td>
                                                            <td width="10%"></td>
                                                            <td width="40%">{{$charge->title }} </td>
                                                            <td width="20%"></td>
                                                            <td width="5%"></td>
                                                            <td width="20%">
                                                                {{$charge->considered}} {{$charge->currency}}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-12">
                                                    <div class="separator"></div>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-xxl-12">
                                                    <h6> <b>Payment Terms of Supplier</b></h6>
                                                    <table class="table form-table" id="paymentcustomFields">
                                                        <thead>
                                                            <tr>
                                                                <th>Payment Term</th>
                                                                <th>Remarks</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($purchaseRequest->purchasePaymentTerm as $payment)
                                                            <tr>
                                                                <td>{{$payment->payment_term}}</td>
                                                                <td>{{$payment->remarks? $payment->remarks: '--' }} </td>
                                                                <td>
                                                                    @switch($payment->status)
                                                                    @case(1)
                                                                    <span class="text-success">Paid</span>
                                                                    @break;
                                                                    @case(1)
                                                                    <span class="text-warning">Partially Paid</span>
                                                                    @break;
                                                                    @default
                                                                    <span class="text-danger">Not Paid</span>
                                                                    @break;
                                                                    @endswitch
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                                <div class="col-xxl-4 col-lg-5">
                                    <div class="content-aside mt-4">
                                        <div class="card card-bpurchaseRequest">
                                            <div class="card-body">
                                                <h6 class="mb-4">Customer Details</h6>
                                                <ul class="list-unstyled">
                                                    <li class="mb-3">
                                                        <div class="fs-7">Company</div>
                                                        <div class="text-dark fw-medium">{{$purchaseRequest->company->company}}</div>
                                                    </li>

                                                    <li class="mb-3">
                                                        <div class="fs-7">Country</div>
                                                        <div class="text-dark fw-medium">{{isset($purchaseRequest->company->country)? $purchaseRequest->company->country->name: ''}}</div>
                                                    </li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content-aside mt-4">
                                        <div class="card card-bpurchaseRequest">
                                            <div class="card-body">
                                                <h6 class="mb-4">Supplier Details</h6>
                                                <ul class="list-unstyled">
                                                    <li class="mb-3">
                                                        <div class="fs-7">Supplier</div>
                                                        <div class="text-dark fw-medium">{{$purchaseRequest->supplier->brand}}</div>
                                                    </li>

                                                    <li class="mb-3">
                                                        <div class="fs-7">Country</div>
                                                        <div class="text-dark fw-medium">{{isset($purchaseRequest->supplier->country)? $purchaseRequest->supplier->country->name: ''}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Contact Person</div>
                                                        <div class="text-dark fw-medium">{{$purchaseRequest->purchaseDelivery->supplier_contact}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Contact Email</div>
                                                        <div class="text-dark fw-medium">{{$purchaseRequest->purchaseDelivery->supplier_email}}</div>
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
@endpush

@endsection