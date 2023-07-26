@extends('layouts.default')

@section('content')

<div class="hk-pg-body py-0">
    <div class="integrationsapp-wrap integrationsapp-sidebar-toggle">
        <!-- <nav class=" integrationsapp-sidebar">
        <div data-simplebar class="nicescroll-bar">
            <div class="menu-content-wrap">
                <div class="nav-header">
                    <span>Browse</span>
                </div>
                <div class="menu-group">
                    <ul class="nav nav-light navbar-nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('quotations.index')}}">
                                <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="grid"></i></span></span>
                                <span class="nav-link-text">All Quotations</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);">
                                <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="trash"></i></span></span>
                                <span class="nav-link-text">Delete</span>
                            </a>
                        </li>
                        <div class="menu-gap"></div>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);">
                                <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="upload"></i></span></span>
                                <span class="nav-link-text">Exports</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);">
                                <span class="nav-icon-wrap"><span class="feather-icon"><i data-feather="download"></i></span></span>
                                <span class="nav-link-text">Imports</span>
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
                <div class="menu-gap"></div>

            </div>
        </div>

        </nav> -->
        <div class="integrationsapp-content">
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
                                <li class="breadcrumb-item"><a href="{{route('quotations.index')}}">All Quotations</a></li>
                                <li class="breadcrumb-item"><a href="">View Details</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$quotation->company->company}}</li>
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
                    <!-- <div class="hk-sidebar-togglable"></div> -->
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
                                            <h3 class="hd-bold mb-0">{{$quotation->company->company}}</h3>
                                            <span>by {{$quotation->customer->fullname}}</span>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-5 mt-lg-0 mt-3">
                                    @if($quotation->status_id == 6)
                                    <div class="alert alert-inv alert-inv-success text-center" role="alert">
                                        <b>You have Won the Order !!</b>
                                    </div>
                                    @else
                                    @can('quotation.status')
                                    <a href="javascript:void(0);" id="update-win" class="btn btn-primary btn-block">Update to Win</a>
                                    <input type="hidden" value="6" name="statusid" id="statusid" />
                                    @endcan
                                    @endif
                                    <div class="d-flex mt-3">
                                        <a href="mailto:{{$quotation->customer->email}}" class="btn btn-sm btn-light btn-block">
                                            <span><span class="icon"><span class="feather-icon"><i data-feather="send"></i></span></span><span>Send Mail</span></span>
                                        </a>
                                        <a href="{{ route('quotations.edit', $quotation->id) }}" class="btn btn-sm btn-light btn-block ms-2 mt-0">
                                            <span><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span><span>Edit</span></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-8 col-lg-7">

                                    <div class="separator"></div>
                                    <ul class="nav nav-light nav-pills nav-pills-rounded justify-content-center">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="pill" href="#tabit_1">
                                                <span class="nav-link-text">Overview</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="pill" href="#tabit_2">
                                                <span class="nav-link-text">Qualify</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="pill" href="#tabit_3">
                                                <span class="nav-link-text">Products</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-7">
                                        <div class="tab-pane fade show active" id="tabit_1">
                                            <h5>Overview and Features</h5>
                                            <p>Please refer the below quotation and customer information :</p>
                                            <div class="row my-4">
                                                @if($quotation->reminder)
                                                <div class="col-xxl-12">
                                                    <div class="alert alert-inv alert-inv-info alert-wth-icon alert-dismissible fade show" role="alert">
                                                        <span class="alert-icon-wrap"><i class="zmdi zmdi-notifications-active"></i></span> {{$quotation->reminder}}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="col-xxl-12">
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>QuoteNo.</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$quotation->reference_no}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Sales Value</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$quotation->total_amount}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Gross Margin</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$quotation->gross_margin}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Winning Probability</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="progress-lb-wrap">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress progress-bar-rounded progress-bar-xs flex-1">
                                                                        <div class="progress-bar bg-green-dark-1 w-{{intval($quotation->winning_probability)}}" role="progressbar" aria-valuenow="{{$quotation->winning_probability}}" aria-valuemin="0" aria-valuemax="100">
                                                                        </div>
                                                                    </div>
                                                                    <div class="fs-8 ms-3">{{$quotation->winning_probability}}%</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-xxl-6">
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Lead Type</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span class="text-capitalize">{{ ($quotation->lead_type)? $quotation->lead_type : "--"}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Quotation For</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span class="text-capitalize">{{ ($quotation->quote_for)? str_replace("_", " ", $quotation->quote_for) : "--"}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Assigned To </h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$quotation->assigned->user->name}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Quote Status </h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span class="badge {{$quotation->status_id == 6? 'badge-soft-success':'badge-soft-danger' }}">{{$quotation->quoteStatus->name}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Details</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$quotation->remarks}}</span>
                                                            @if($quotation->product_models)
                                                            <p>{{$quotation->product_models}}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Last Updated On</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$quotation->updated_at}}</span>

                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-xxl-6 mt-xxl-0 mt-3">

                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Supplier</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{($quotation->supplier_id)? $quotation->supplier->brand: "--"}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Country of Origin</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$quotation->supplier_id? $quotation->supplier->country->name: "--"}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Submitted Date</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span> {{$quotation->submitted_date}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Closing Date</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span> {{$quotation->closure_date}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Is Active?</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            @switch($quotation->is_active)
                                                            @case(0)
                                                            <span class="text-warning">Draft</span>
                                                            @break

                                                            @case(1)
                                                            <span class="text-success">Active</span>
                                                            @break

                                                            @default
                                                            <span class="text-danger">Disabled </span>(Revised)
                                                            @endswitch

                                                        </div>
                                                    </div>
                                                    @if($quotation->status_id == 6)

                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Update Win Date</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <form method="post" action="{{route('quotation.windate')}}">
                                                                @csrf
                                                                <input type="hidden" id="quote_id" name="quotation_id" value="{{$quotation->id}}" />
                                                                <div class="input-group">
                                                                    <span class="input-affix-wrapper">
                                                                        <input type="date" class="form-control" name="updated_at" value="{{date('Y-m-d' , strtotime($histories[0]->updated_at) )}}" placeholder="Update Win date">
                                                                    </span>
                                                                    <button class="btn btn-primary" type="submit">SAVE</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- <div class="row">
                                                <div class="col-xxl-12">
                                                    &nbsp;
                                                </div>
                                            </div> -->

                                        </div>
                                        <div class="tab-pane fade" id="tabit_2">
                                            <div class="title title-lg mb-2"><span>To Qualify Quotes</span></div>
                                            <div class="comment-block">
                                                <div class="{{($quotation->status_id == 6)? 'd-none': ''}}">
                                                    @can('quotation.status')
                                                    <form class="mb-4" method="POST" id="frmStatus">
                                                        <input type="hidden" name="quotation_id" id="quotation_id" value="{{$quotation->id}}" />
                                                        <div class="form-group">
                                                            <div class="media">

                                                                <div class="media-body">
                                                                    <div class="form-inline">
                                                                        <select name="status_id" id="status_id" class="form-control me-2" required>
                                                                            <option value="">--Status--</option>
                                                                            @foreach($quoteStatuses as $id => $status)
                                                                            <option value="{{$id}}">{{$status}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <textarea class="form-control me-3" id="comment" name="comment" placeholder="Comments" required></textarea>
                                                                        <button class="btn btn-primary" type="submit">Add</button>
                                                                        <span id="ajxloader"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    @endcan
                                                </div>
                                                <div class="list-his">
                                                    @foreach($histories as $history)
                                                    <div class="media">
                                                        <div class="media-head">
                                                            <div class="avatar avatar-xs avatar-rounded letter-icon" title="{{$history->username}}">
                                                                <span class="initial-wrap">{{substr($history->username, 0, 2)}}</span>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <div>
                                                                <span class="cm-name">{{$history->status}}</span>
                                                            </div>
                                                            <p>{{$history->comment}}</p>
                                                            <div class="comment-action-wrap mt-3">
                                                                <span>{{date("d M Y" , strtotime($history->created_at)) }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="separator separator-light"></div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="tabit_3">
                                            <div class="review-block">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-5">
                                    <div class="content-aside mt-4">
                                        <div class="card card-border">
                                            <div class="card-body">
                                                <h6 class="mb-4">Company Info</h6>
                                                <ul class="list-unstyled">
                                                    <li class="mb-3">
                                                        <div class="fs-7">Company</div>
                                                        <div class="text-dark fw-medium">{{$quotation->company->company}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Account No.</div>
                                                        <div class="text-dark fw-medium">{{$quotation->company->reference_no}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Work Phone</div>
                                                        <div class="text-dark fw-medium">{{$quotation->company->landphone}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Company Email</div>
                                                        <div class="text-dark fw-medium">{{$quotation->company->email_address}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Address</div>
                                                        <div class="text-dark fw-medium">{{$quotation->company->address}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Region</div>
                                                        <div class="text-dark fw-medium">{{($quotation->company->region_id)? $quotation->company->region->state: ""}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Country</div>
                                                        <div class="text-dark fw-medium">{{($quotation->company->country_id)? $quotation->company->country->name: ""}}</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="card card-border">
                                        <div class="card-body">
                                            <div class="media align-items-center">
                                                <div class="media-head me-3">
                                                    <div class="avatar avatar-sm avatar-icon avatar-soft-success avatar-rounded">
                                                        <span class="initial-wrap">
                                                            <span class="feather-icon"><i data-feather="external-link"></i></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="media-body">
                                                    <h6 class="mb-0">Website</h6>
                                                    <a href="{{$quotation->company->website}}" class="link-muted">{{$quotation->company->website}}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content-aside">
                                        <div class="card card-border">
                                            <div class="card-body">
                                                <h6 class="mb-4">Customer Info</h6>
                                                <ul class="list-unstyled">

                                                    <li class="mb-3">
                                                        <div class="fs-7">Name</div>
                                                        <div class="text-dark fw-medium">{{$quotation->customer->fullname}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Phone</div>
                                                        <div class="text-dark fw-medium">{{$quotation->customer->phone}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Email</div>
                                                        <div class="text-dark fw-medium">{{$quotation->customer->email}}</div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xxl-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Quotation Histories</h5>
                                        </div>
                                        <div class="card-body">
                                            <table class="table nowrap w-100 mb-5">
                                                <thead>
                                                    <tr>
                                                        <th>Quotation No.</th>
                                                        <th width="10%">Amount(AED)</th>
                                                        <th width="20%">Winning</th>
                                                        <th>Submitted On</th>
                                                        <th>Closing On</th>
                                                        <th width="15%">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($parentQuotes) > 0)
                                                    @foreach($parentQuotes as $quote)
                                                    <tr class="{{(!$quote->parent_id)? 'bg-warning': ''}}">
                                                        <td>
                                                            <a href="{{route('quotations.show', $quote->id)}}">{{$quote->reference_no}}</a>
                                                        </td>
                                                        <td>{{$quote->total_amount}} / {{$quote->gross_margin}}</td>
                                                        <td>
                                                            <div class="progress-lb-wrap">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="progress progress-bar-rounded progress-bar-xs flex-1">
                                                                        <div class="progress-bar bg-green-dark-1 w-{{intval($quote->winning_probability)}}" role="progressbar" aria-valuenow="{{$quote->winning_probability}}" aria-valuemin="0" aria-valuemax="100">
                                                                        </div>
                                                                    </div>
                                                                    <div class="fs-8 ms-3">{{$quote->winning_probability}}%</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{$quote->submitted_date}}</td>
                                                        <td>{{$quote->closure_date}}</td>
                                                        <td>{{$quote->remarks}}</td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
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

<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Create Order</h4>
                <button type="button" class="close_submit btn close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="frmspecs"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="error_msg"></div>
                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
                <div clas="row">
                    <div clas="col-xs-12">
                        <div clas="col-xs-6">
                            <label>Customer Po.No</label>
                        </div>
                        <div clas="col-xs-6">
                            <input type="text" id="pono" name="pono" class="form-control" required>
                            <input class="form-control" id="company_id" type="hidden" value="{{$quotation->company_id}}" name="company_id" required />
                            <input class="form-control" id="customer_id" value="{{$quotation->customer_id}}" type="hidden" name="customer_id" required />
                        </div>
                        <div clas="col-xs-6">
                            <label>YES Po.No</label>
                        </div>
                        <div clas="col-xs-6">
                            <input type="text" id="yespo_no" name="yespo_no" class="form-control" required>
                        </div>
                        <div clas="col-xs-6">
                            <label>Po.Date</label>
                        </div>
                        <div clas="col-xs-6">
                            <input class="form-control" id="po_date" type="date" name="po_date" required />
                        </div>
                        <div clas="col-xs-6">
                            <label>Po.Received</label>
                        </div>
                        <div clas="col-xs-6">
                            <input class="form-control" id="po_received" type="date" name="po_received" required />

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div clas="col-xs-6">
                    <button type="button" class="close_submit btn btn-danger mx-auto" data-dismiss="modal">Close</button>
                </div>
                <div clas="col-xs-6">
                    <button type="button" id="btn-submit" class="btn btn-success mx-auto" data-dismiss="modal">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#update-win").click(function() {
        $('#myModal').modal('show');
    });
    $(".close_submit").click(function() {
        $("#myModal").modal('hide');
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#frmStatus").submit(function(e) {
        e.preventDefault();

        let statusid = $("#status_id").val();
        let comment = $("#comment").val();
        let quoteid = $("#quotation_id").val();
        $('#ajxloader').html('Please wait..');
        $.ajax({
            type: 'POST',
            url: "{{ route('quotation.status') }}",
            data: {
                quotation_id: quoteid,
                status_id: statusid,
                comment: comment
            },
            success: function(data) {

                if ($.isEmptyObject(data)) {
                    console.log("Empty Result ", data);
                    $('#ajxloader').html('Something wrong !');

                } else {
                    $('#ajxloader').html('');

                    let d = new Date(data.created_at);
                    let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    let dateTime = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
                    let uname = data.username;

                    let html = `<div class="media">
                                <div class="media-head">
                                    <div class="avatar avatar-xs avatar-rounded letter-icon" title="` + uname + `">
                                        <span class="initial-wrap">` + uname.substring(0, 2) + `</span>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div><span class="cm-name">` + data.status + `</span></div>
                                    <p>` + data.comment + `</p>
                                    <div class="comment-action-wrap mt-3">
                                        <span>` + dateTime + `</span>
                                    </div>
                                </div>
                            </div>
                            <div class="separator separator-light"></div>`;

                    $('.list-his').prepend(html);

                    $('#frmStatus')[0].reset();
                }
            }
        });

    });

    $("#btn-submit").on('click', function(e) {
        e.preventDefault();
        if (!$('#pono').val()) {
            $("#frmspecs").show();
            $("#frmspecs").addClass('alert alert-danger').text("Please enter Customer Po Number!");
            return false;
        }
        if (!$('#yespo_no').val()) {
            $("#frmspecs").show();
            $("#frmspecs").addClass('alert alert-danger').text("Please enter YES Po Number!");
            return false;
        }
        if (!$('#po_date').val()) {
            $("#frmspecs").show();
            $("#frmspecs").addClass('alert alert-danger').text("Please create a Po Date!");
            return false;
        }
        if (!$('#po_received').val()) {
            $("#frmspecs").show();
            $("#frmspecs").addClass('alert alert-danger').text("Please create a Po Received!");
            return false;
        }

        if (new Date($('#po_received').val()) < new Date($('#po_date').val())) { //compare end <=, not >=
            //your code here
            $("#frmspecs").show();
            $("#frmspecs").addClass('alert alert-danger').text("Po Received Date must be greater than Po Date!");
            return false;
        }
        let obj = $(this);

        let statusid = $("#statusid").val();
        let comment = "Win to order";
        let quoteid = $("#quotation_id").val();
        let pono = $("#pono").val();
        let yespo_no = $("#yespo_no").val();
        let po_date = $("#po_date").val();
        let po_received = $("#po_received").val();
        let company_id = $("#company_id").val();
        let customer_id = $("#customer_id").val();

        $.ajax({
            type: 'POST',
            url: "{{ route('quotationorder.status') }}",
            data: {
                quotation_id: quoteid,
                status_id: statusid,
                comment: comment,
                pono: pono,
                yespo_no: yespo_no,
                po_date: po_date,
                po_received: po_received,
                company_id: company_id,
                customer_id: customer_id,
            },
            success: function(data) {
                $("#frmspecs").removeClass('alert alert-danger');
                $("#frmspecs").hide();
                $(".error_msg").removeClass('alert alert-danger');
                $(".error_msg").hide();
                if ($.isEmptyObject(data)) {
                    console.log("Empty Result ", data);

                } else {
                    Swal.fire(
                        'Good job!',
                        'You are a Winner!',
                        'success'
                    ).then((result) => {
                        if (result.isConfirmed) {
                            //alert(1)
                            location.reload();
                        } else if (result.isDenied) {
                            // alert(2)
                            obj.hide();
                        }
                    });
                    //  

                }
            },
            error: function(data) {
                $(".error_msg").show();
                $("#frmspecs").removeClass('alert alert-danger');
                $("#frmspecs").hide();
                let err_str = '';
                if (data.responseJSON.errors) {
                    err_str =
                        '<dl class="row"><dt class="col-sm-3"></dt><dt class="col-sm-9"><p><b>Whoops!</b> There were some problems with your input.</p></dt>';
                    $.each(data.responseJSON.errors, function(key, val) {
                        err_str += '<dt class="col-sm-3">' + key.replace("_",
                                " ") + ' </dt><dd class="col-sm-9">' + val +
                            '</dd>';
                    });
                    err_str += '</dl>';
                    $('.error_msg').addClass('alert alert-danger').html(err_str);

                    return false;
                }
            }
        });
    });
</script>

@endsection