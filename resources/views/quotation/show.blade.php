@extends('layouts.default')

@section('content')

<div class="hk-pg-body py-0">
  <div class="integrationsapp-wrap integrationsapp-sidebar-toggle">

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
                  @if($quotation->is_active == 2)
                  <div class="alert alert-danger">
                    The manager was revised this quotation so that this is now disabled.
                  </div>
                  @endif
                </div>
                <div class="col-xxl-4 col-lg-5 mt-lg-0 mt-3 {{$quotation->is_active == 2? 'd-none':'' }}">
                  @if($quotation->status_id == 6)
                  <div class="alert alert-inv alert-inv-success text-center" role="alert">
                    <b>You have Won the Order !!</b>
                  </div>
                  <!-- <a href="{{route('orders.createnew', $quotation->id) }}" class="btn btn-primary btn-block">Create OS</a> -->
                  @else
                  @can('quotation.status')
                  <a href="javascript:void(0);" id="update-win" class="btn btn-primary btn-block">Update to Win</a>
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
                  <a href="{{ route('quotation.download', ['id' => $quotation->id, 'action' => 'preview']) }}" class="btn btn-secondary btn-block mt-2">Preview Quotation</a>

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
                        <span class="nav-link-text">Qualify Quotations</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-bs-toggle="pill" href="#tabit_3">
                        <span class="nav-link-text">Quotation Items</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-bs-toggle="pill" href="#tabit_4">
                        <span class="nav-link-text">Quotation Terms</span>
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
                              <span>{{$quotation->total_amount}} {{$quotation->preferred_currency? $quotation->preferred_currency : 'aed'}}</span>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4">
                              <h6>Gross Margin</h6>
                            </div>
                            <div class="col-8">
                              <span>{{$quotation->gross_margin}} {{$quotation->preferred_currency? $quotation->preferred_currency : 'aed'}}</span>
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
                          @if($quotation->product_models)
                          <div class="row mb-2">
                            <div class="col-4">
                              <h6>Product Models</h6>
                            </div>
                            <div class="col-8">
                              <p>{{$quotation->product_models}}</p>
                            </div>
                          </div>
                          @endif

                          <div class="row mb-2">
                            <div class="col-4">
                              <h6>Price Basis</h6>
                            </div>
                            <div class="col-8">
                              <p>{{$quotation->price_basis}}</p>
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
                              <span>{{($quotation->supplier_id)? $quotation->supplier->brand: ""}}
                                @foreach($quotationItems as $item)
                                {{$item->supplier->brand}}<br />
                                @endforeach
                              </span>
                            </div>
                          </div>
                          <div class="row mb-2">
                            <div class="col-4">
                              <h6>Country of Origin</h6>
                            </div>
                            <div class="col-8">
                              <span>{{$quotation->supplier_id? $quotation->supplier->country->name: ""}}
                                @foreach($quotationItems as $item)
                                {{$item->supplier->country->name}}<br />
                                @endforeach
                              </span>
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
                          <div class="row mb-2">
                            <div class="col-4">
                              <h6>Remarks</h6>
                            </div>
                            <div class="col-8">
                              <span>{{$quotation->remarks}}</span>
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
                                    <input type="date" class="form-control" name="win_date" value="{{date('Y-m-d' , strtotime($quotation->win_date) )}}" placeholder="Update Win date">
                                  </span>
                                  <button class="btn btn-primary" type="submit">SAVE</button>
                                </div>
                              </form>
                            </div>
                          </div>
                          @endif
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <div class="separator"></div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12">
                          <h5>Sales Commisions</h5>
                          <table class="table">
                            @forelse($commissions as $sale)
                            <tr>
                              <td>{{$sale->manager->user->name}}</td>
                              <td>{{$sale->percent}} %</td>
                              <td>{{$sale->commission_amount}} AED</td>
                            </tr>
                            @empty
                            <tr>
                              <td>-No data-</td>
                            </tr>
                            @endforelse
                          </table>
                        </div>
                      </div>

                    </div>
                    <div class="tab-pane fade" id="tabit_2">
                      <div class="title title-lg mb-2"><span>To Qualify Quotes</span></div>
                      <div class="comment-block">
                        <div class="{{($quotation->status_id == 6 || $quotation->is_active == 2)? 'd-none': ''}}">
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
                      <div class="title title-lg mb-2"><span>Quotation Items</span></div>
                      <div class="comment-block">
                        @if ($quotationItems->isEmpty())
                        <div style="text-align: center;">
                          <p>No records found</p>
                        </div>
                        @else
                        <table style="border-collapse: collapse; width: 100%;">
                          <thead>
                            <tr>
                              <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">Model No</th>
                              <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">Unit Price</th>
                              <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">Quantity</th>
                              <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">Discount</th>
                              <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">Total After Discount</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($quotationItems as $item)
                            <tr>
                              <td style="border: 1px solid #dddddd; padding: 8px;">{{ $item->supplier->brand }} <br /> {{ $item->description }}</td>
                              <td style="border: 1px solid #dddddd; padding: 8px;">{{ $item->unit_price }} {{$quotation->preferred_currency? $quotation->preferred_currency : 'aed'}}</td>
                              <td style="border: 1px solid #dddddd; padding: 8px;">{{ $item->quantity }}</td>
                              <td style="border: 1px solid #dddddd; padding: 8px;">{{ $item->discount }}</td>
                              <td style="border: 1px solid #dddddd; padding: 8px;">{{ $item->total_after_discount }} {{$quotation->preferred_currency? $quotation->preferred_currency : 'aed'}}</td>
                            </tr>
                            @endforeach
                            @foreach ($quotationCharges as $value)
                            <tr>
                              <td colspan="4" style="border: 1px solid #dddddd; padding: 8px;">{{ $value->title }}</td>
                              <td colspan="4" style="border: 1px solid #dddddd; padding: 8px;">{{ $value->amount }} {{$quotation->preferred_currency? $quotation->preferred_currency : 'aed'}}</td>
                            </tr>
                            @endforeach

                            <tr>
                              <td colspan="4" style="border: 1px solid #dddddd; padding: 8px;">Vat Amount</td>
                              <td colspan="4" style="border: 1px solid #dddddd; padding: 8px;">{{$quotation->vat_amount }} {{$quotation->preferred_currency? $quotation->preferred_currency : 'aed'}}</td>
                            </tr>
                            <tr>

                              <td colspan="4" style="border: 1px solid #dddddd; padding: 8px;">Total Amount</td>
                              <td colspan="4" style="border: 1px solid #dddddd; padding: 8px;">{{ $quotation->total_amount }} {{$quotation->preferred_currency? $quotation->preferred_currency : 'aed'}}</td>
                            </tr>
                          </tbody>
                        </table>
                        @endif
                      </div>
                    </div>

                    <div class="tab-pane fade" id="tabit_4">
                      <div class="title title-lg mb-2"><span>Quotation Terms</span></div>
                      <div class="comment-block">
                        <table style="border-collapse: collapse; width: 100%;">
                          <thead>
                            <tr>
                              <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">Title.</th>
                              <th style="border: 1px solid #dddddd; background-color: #f2f2f2; text-align: left; padding: 8px;">Description</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($quotationTerms as $term)
                            <tr>
                              <td style="border: 1px solid #dddddd; padding: 8px;">{{ $term->title}}</td>
                              <td style="border: 1px solid #dddddd; padding: 8px;">{{ $term->description }}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>

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
        <h4 class="modal-title">Update Quotation Status to Win</h4>
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
          <div clas="col-12">
            <p class="text-danger mb-2">
              <i data-feather="alert-octagon"></i>&nbsp;&nbsp;Please read the below Use Cases carefully.
            </p>
            <ol class="small text-muted" style="line-height: 24px;">
              <li><b>2 Quotations have 1 OS</b></li>
              <li><b>1 Quotation has 2 PO's</b></li>
              <li><b>NO PO's</b></li>
              <li><b>Repeat Orders by messages, email or verbal conversation without PO's</b></li>
            </ol>
            <p class="mb-2">Before placing an order, please create a new quotation if any of these use cases apply to you, or revised the ones that already exist.
            </p>
          </div>
        </div>
        <div class="row">
          <div clas="col-6">
            <div class="form-group">
              <label for="win_date">Win Date</label>
              <input type="text" class="form-control" id="win_date" name="single" disabled />
            </div>
          </div>
          <div clas="col-6">
            <input type="hidden" value="6" name="statusid" id="statusid" />
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div clas="col-xs-6">
          <button type="button" class="close_submit btn btn-danger mx-auto" data-dismiss="modal">Close</button>
        </div>
        <div clas="col-xs-6">
          <!-- <button type="button" class="btn btn-success mx-auto btn-submit-wq" value="update" data-dismiss="modal">Update</button> -->
          <button type="button" class="btn btn-primary mx-auto btn-submit-wq" value="update_os" data-dismiss="modal">Update & Create OS</button>
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

  $(".btn-submit-wq").on('click', function(e) {

    e.preventDefault();
    if (!$('#statusid').val() || !$("#quotation_id").val()) {
      $("#frmspecs").show();
      $("#frmspecs").addClass('alert alert-danger').text("Cannot update, some fields are missing!");
      return false;
    }
    let obj = $(this),
      submit_type = obj.val();

    let statusid = $("#statusid").val();
    let comment = "Win to order";
    let quoteid = $("#quotation_id").val();

    $.ajax({
      type: 'POST',
      url: "{{ route('quotationorder.status') }}",
      data: {
        quotation_id: quoteid,
        status_id: statusid,
        comment: comment
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
            'You have Won the Order !',
            'success'
          ).then((result) => {
            if (result.isConfirmed) {

              if (submit_type == 'update_os') {
                let ourl = "{{route('orders.createnew', ':id') }}";
                ourl = ourl.replace(":id", quoteid);

                location.href = ourl;
              } else {
                location.reload();
              }
            } else if (result.isDenied) {

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
  /**************************************
   * PO Dynamic Rows
   *****************************************/
  var iter = 0;
  $(document).ready(function() {
    $(".addPO").click(function() {
      iter++;
      $("#customFieldsPO").append(`<tr valign="top">
            <td><input type="text" class="form-control" id="yespo_no_${iter}" name="yespo_no[]" value="" placeholder="YES OS No" /></td>
            <td><input type="text" class="form-control" id="pono_${iter}" name="pono[]" value="" placeholder="Customer PO No" /></td>
            <td><input type="date" class="form-control" id="po_date_${iter}" name="po_date[]" value="" placeholder="PO Date" /></td>
            <td><input type="date" class="form-control" id="po_received_${iter}" name="po_received[]" value="" placeholder="PO Received Date" /></td>
            <td><a href="javascript:void(0);" class="remPO" title="DELETE ROW"><i class="fa fa-trash"></i></a></td></tr>`);
    });

    $("#customFieldsPO").on('click', '.remPO', function() {
      iter--;
      $(this).parent().parent().remove();
    });
  });
</script>

@endsection