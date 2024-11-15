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
                                <li class="breadcrumb-item"><a href="{{ route('leads.index') }}">All Leads</a></li>
                                <li class="breadcrumb-item"><a href="">View Details</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$lead->company->company}}</li>
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
                                            <h3 class="hd-bold mb-0">{{$lead->company->company}}</h3>
                                            <span>by {{$lead->customer->fullname}}</span>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-4 col-lg-5 mt-lg-0 mt-3">
                                    <div class="">
                                        @can('leads.convert')
                                        @if($lead->status_id != 6)
                                        <a href="javascript:void(0);" id="convert-leads-to" data-id="{{$lead->id}}" class="btn btn-primary btn-block">Convert To Quotation</a>
                                        @endif
                                        @endcan
                                        <div class="d-flex mt-3">
                                            <a href="mailto:{{$lead->customer->email}}" class="btn btn-sm btn-light btn-block">
                                                <span><span class="icon"><span class="feather-icon"><i data-feather="send"></i></span></span><span>Send Mail</span></span>
                                            </a>
                                            <a href="{{ route('leads.edit', $lead->id) }}" class="btn btn-sm btn-light btn-block ms-2 mt-0">
                                                <span><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span><span>Edit</span></span>
                                            </a>
                                        </div>
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
                                                <span class="nav-link-text">Qualify Leads</span>
                                            </a>
                                        </li>

                                    </ul>
                                    <div class="tab-content py-7">
                                        <div class="tab-pane fade show active" id="tabit_1">
                                            <h5>Overview and Features</h5>
                                            <p>Please refer the below enquiry and customer information :</p>
                                            <div class="row my-7">
                                                <div class="col-xxl-12">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <h6>Details</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$lead->details}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-xxl-6">

                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Assigned To </h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$lead->assigned->user->name}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Enquiry Status </h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span class="badge badge-soft-danger ">{{$lead->leadStatus->name}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Enquiry Type </h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span>{{$lead->lead_type_label}}</span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-xxl-6 mt-xxl-0 mt-3">

                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Enquiry Date</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span> {{$lead->enquiry_date}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Assigned On</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span> {{$lead->assigned_on}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4">
                                                            <h6>Responded On</h6>
                                                        </div>
                                                        <div class="col-8">
                                                            <span> {{$lead->respond_on? $lead->respond_on : ''}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade" id="tabit_2">
                                            <div class="title title-lg mb-2"><span>To Qualify Leads</span></div>
                                            <div class="comment-block ">
                                                <div class="{{($lead->status_id == 6)? 'd-none': ''}}">
                                                    @can('leads.status')
                                                    <form class="mb-4" method="POST" id="frmStatus">
                                                        <input type="hidden" name="lead_id" id="lead_id" value="{{$lead->id}}" />
                                                        <div class="form-group">
                                                            <div class="media">

                                                                <div class="media-body">
                                                                    <div class="form-inline">
                                                                        <select name="status_id" id="status_id" class="form-control me-2" required>
                                                                            <option value="">--Status--</option>
                                                                            @foreach($leadStatuses as $id => $status)
                                                                            <option value="{{$id}}">{{$status}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <select name="priority" id="priority" class="form-control me-2" required>
                                                                            <option value="">--Priority--</option>
                                                                            <option value="low">Low</option>
                                                                            <option value="medium">Medium</option>
                                                                            <option value="high">High</option>
                                                                            <option value="sos">SOS</option>
                                                                        </select>
                                                                        <textarea class="form-control me-3" id="comment" name="comment" placeholder="Comments"></textarea>
                                                                        <button class="btn btn-primary" type="submit">Add</button>
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
                                                                @if($history->priority)
                                                                @php
                                                                $badge_cls = '';
                                                                switch($history->priority){
                                                                case 'low':
                                                                $badge_cls = 'badge-outline badge-info';
                                                                break;
                                                                case 'medium':
                                                                $badge_cls = 'badge-outline badge-warning';
                                                                break;
                                                                case 'high':
                                                                $badge_cls = 'badge-outline badge-danger';
                                                                break;
                                                                case 'sos':
                                                                $badge_cls = 'badge-danger';
                                                                break;
                                                                }
                                                                @endphp
                                                                <span class="badge badge-sm {{$badge_cls}} badge-wth-indicator badge-wth-icon ms-3 d-lg-inline-block d-none">
                                                                    <span style="text-transform: uppercase;"><i class="badge-dot ri-checkbox-blank-circle-fill"></i>{{$history->priority}}</span>
                                                                </span>
                                                                @endif
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
                                                        <div class="text-dark fw-medium">{{$lead->company->company}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Account No.</div>
                                                        <div class="text-dark fw-medium">{{$lead->company->reference_no}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Work Phone</div>
                                                        <div class="text-dark fw-medium">{{$lead->company->landphone}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Company Email</div>
                                                        <div class="text-dark fw-medium">{{$lead->company->email_address}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Address</div>
                                                        <div class="text-dark fw-medium">{{$lead->company->address}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Region</div>
                                                        <div class="text-dark fw-medium">{{($lead->company->region_id)? $lead->company->region->state: ""}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Country</div>
                                                        <div class="text-dark fw-medium">{{($lead->company->country_id)? $lead->company->country->name: ""}}</div>
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
                                                    <a href="{{$lead->company->website}}" class="link-muted">{{$lead->company->website}}</a>
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
                                                        <div class="text-dark fw-medium">{{$lead->customer->fullname}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Phone</div>
                                                        <div class="text-dark fw-medium">{{$lead->customer->phone}}</div>
                                                    </li>
                                                    <li class="mb-3">
                                                        <div class="fs-7">Email</div>
                                                        <div class="text-dark fw-medium">{{$lead->customer->email}}</div>
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
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#convert-leads-to").on('click', function(e) {
        e.preventDefault();

        let leadid = $(this).data('id');

        let url = "{{ route('leads.qualify', ':id') }}";
        url = url.replace(':id', leadid);

        let converturl = "{{ route('leads.convert', ':id') }}";
        converturl = converturl.replace(':id', leadid);
        // direct convert leads
        // window.location.href = converturl;

        $.get(url, function(data, status) {
            // alert("Data: " + data + "\nStatus: " + status);
            if (status == 'success') {

                if (data > 0) {
                    window.location.href = converturl;
                } else {
                    Swal.fire(
                        'QUALIFY',
                        "You have not yet qualified the lead, please qualify yourself before proceeding",
                        'info'
                    );
                }
            } else {
                Swal.fire(
                    'ERROR',
                    "Something went Wrong !",
                    'danger'
                );
            }

        });
    });

    $("#frmStatus").submit(function(e) {
        e.preventDefault();

        let statusid = $("#status_id").val();
        let comment = $("#comment").val();
        let leadid = $("#lead_id").val();
        let priority = $("#priority").val();

        $.ajax({
            type: 'POST',
            url: "{{ route('leads.status') }}",
            data: {
                lead_id: leadid,
                status_id: statusid,
                priority: priority,
                comment: comment
            },
            success: function(data) {

                if ($.isEmptyObject(data)) {
                    console.log("Empty Result ", data);

                } else {
                    let d = new Date(data.created_at);
                    let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                    let dateTime = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
                    let uname = data.username,
                        commt = data.comment ? data.comment : '';

                    let html = `<div class="media">
                                <div class="media-head">
                                    <div class="avatar avatar-xs avatar-rounded letter-icon" title="` + uname + `">
                                        <span class="initial-wrap">` + uname.substring(0, 2) + `</span>
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div><span class="cm-name">` + data.status + `</span>
                                    <span class="badge badge-sm badge-outline badge-secondary badge-wth-indicator badge-wth-icon ms-3 d-lg-inline-block">
                                        <span style="text-transform: uppercase;"><i class="badge-dot ri-checkbox-blank-circle-fill"></i>` + data.priority + `</span>
                                    </span></div>
                                    <p>` + commt + `</p>
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
</script>

@endsection