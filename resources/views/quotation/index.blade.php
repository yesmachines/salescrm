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
                                <h1>Pending Quotations</h1>
                            </a>
                        </div>
                        <div class="dropdown ms-3">
                            <button class="btn btn-sm btn-outline-secondary flex-shrink-0 dropdown-toggle d-lg-inline-block d-none" data-bs-toggle="dropdown">Create New</button>
                            <div class="dropdown-menu">
                                @can('quotations.create')
                                <a class="dropdown-item" href="{{route('quotations.create')}}">Add New Quote</a>
                                @endcan
                                <a class="dropdown-item" href="{{route('customers.create')}}">Add New Company</a>
                            </div>
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
                                        <th width="15%">Supplier</th>
                                        <!-- <th width="10%">Amount (AED)</th> -->
                                        <th width="5%">Winning (%)</th>
                                        <th width="10%">Status</th>
                                        @hasanyrole('divisionmanager|salesmanager')
                                        @else
                                        <th width="5%">Assigned</th>
                                        @endhasanyrole
                                        <th width="15%">LastUpdate</th>
                                        <th width="10%">Actions</th>
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
                                            {{ Str::limit($quote->company->company, 40) }}
                                            <!-- <span class="feather-icon text-warning"><i data-feather="bell"></i></span><span> -->
                                        </td>

                                        <td title="{{($quote->supplier_id? $quote->supplier->brand: '')}}">
                                            {{ Str::limit(($quote->supplier_id? $quote->supplier->brand: ''), 20) }}
                                        </td>
                                        <!-- <td class="text-truncate">{{$quote->total_amount}} / {{$quote->gross_margin}}</td> -->
                                        <td>
                                            <span class="badge badge-pink">{{$quote->winning_probability}}%</span>
                                            <!-- <div class="progress-lb-wrap">
                                                <div class="d-flex align-items-center">
                                                    <div class="progress progress-bar-rounded progress-bar-xs flex-1">
                                                        <div class="progress-bar bg-green-dark-1 w-{{intval($quote->winning_probability)}}" role="progressbar" aria-valuenow="{{$quote->winning_probability}}" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <div class="fs-8 ms-3">{{$quote->winning_probability}}%</div>
                                                </div>
                                            </div> -->
                                        </td>
                                        <td>
                                            <span class="badge {{$quote->status_id == 6? 'badge-soft-success': 'badge-soft-danger'}}  my-1  me-2">{{$quote->status_id? $quote->quoteStatus->name: '--'}}</span>/
                                            <span class="badge badge-soft-secondary my-1  me-2">{{$quote->is_active == 0 ? 'Draft': 'Active'}}</span>
                                        </td>
                                        @hasanyrole('divisionmanager|salesmanager')
                                        @else
                                        <td class="text-truncate">{{$quote->assigned->user->name}}</td>
                                        @endhasanyrole
                                        <td>{{date("d-m-Y", strtotime($quote->updated_at))}}</td>

                                        <td class="text-right"><a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover dropdown-toggle no-caret" href="#" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="icon"><span class="feather-icon"><i data-feather="more-horizontal"></i></span></span></a>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('quotations.show', $quote->id) }}"><span class="feather-icon dropdown-icon"><i data-feather="eye"></i></span><span>Preview</span></a>
                                                <a class="dropdown-item" href="{{ route('quotations.edit', $quote->id) }}"><span class="feather-icon dropdown-icon"><i data-feather="edit"></i></span><span>Edit</span></a>
                                                <a class="dropdown-item" href="{{ route('quotation.download', $quote->id) }}"><span class="feather-icon dropdown-icon"><i data-feather="download"></i></span><span>Download</span></a>
                                                <a class="dropdown-item" href="#" id="new_reminder" data-bs-toggle="modal" data-bs-target="#set_new_reminder" data-id="{{$quote->id}}" data-rel="{{$quote->reminder}}"><span class="feather-icon dropdown-icon"><i data-feather="bell"></i></span><span>Set a Reminder</span></a>

                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('quotation.revision', $quote->id) }}"><span class="feather-icon dropdown-icon"><i data-feather="refresh-cw"></i></span><span>Revision</span></a>
                                                <a class="dropdown-item" href="javascript:void(0);" onclick="event.preventDefault();
                                        document.getElementById('delete-form-{{ $quote->id }}').submit();"><span class="feather-icon dropdown-icon"><i data-feather="trash-2"></i></span><span>Delete</span></a>
                                                {!! Form::open(['method' => 'DELETE','route' => ['quotations.destroy', $quote->id],'style'=>'display:none',
                                                'id' => 'delete-form-'.$quote->id]) !!}
                                                {!! Form::close() !!}
                                            </div>
                                        </td>

                                        <!-- <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex">
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Download" href="{{ route('quotation.download', $quote->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="download"></i></span></span></a>
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Revision" href="{{ route('quotation.revision', $quote->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" href="{{ route('quotations.edit', $quote->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span></a>
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Preview" href="{{ route('quotations.show', $quote->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="eye"></i></span></span></a>
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover del-button" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Delete" href="javascript:void(0);" onclick="event.preventDefault();
                                        document.getElementById('delete-form-{{ $quote->id }}').submit();"><span class="icon"><span class="feather-icon"><i data-feather="trash"></i></span></span></a>
                                                    {!! Form::open(['method' => 'DELETE','route' => ['quotations.destroy', $quote->id],'style'=>'display:none',
                                                    'id' => 'delete-form-'.$quote->id]) !!}
                                                    {!! Form::close() !!}
                                                </div>

                                            </div>
                                        </td> -->
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

<!-- Set Reminder -->
<div id="set_new_reminder" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form class="mb-4" method="POST" id="frmremind">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h5 class="mb-4">Set a Reminder</h5>
                    <input type="hidden" name="quotation_id" id="quotation_id" />
                    <div class="row gx-3">
                        <div class="col-sm-12">
                            <div id="reminder_date"></div>
                        </div>
                    </div>
                    <div class="row gx-3">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <input class="form-control" name="single-dt" type="date" />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label">Start Time</label>
                                <input class="form-control input-single-timepicker" name="singletime" type="text" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer align-items-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary">Update Reminder</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /Set Reminder -->
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
    $(document).ready(function() {
        /* Single Date*/
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#new_reminder').on('click', function(e) {
            e.preventDefault();

            let qid = $(this).data('id');
            let reminder = $(this).data('rel');

            $('#quotation_id').val(qid);
            $('#reminder_date').addClass('alert alert-info').html(reminder);

        });

        $("#frmremind").submit(function(e) {
            e.preventDefault();

            let quotation_id = $("#quotation_id").val(),
                sdate = $('input[name="single-dt"]').val(),
                stime = $('input[name="singletime"]').val(),
                reminderdt = moment(sdate + " " + stime).format('YYYY-MM-DD H:mm');

            $.ajax({
                type: 'POST',
                url: "{{ route('quotation.setreminder') }}",
                data: {
                    quotation_id: quotation_id,
                    reminder: reminderdt
                },
                success: function(data) {

                    // console.log(data)

                    if ($.isEmptyObject(data)) {
                        console.log("Empty Result ", data);
                    } else {
                        // Swal.fire(
                        //     'Reminder !',
                        //     'You are a Winner!',
                        //     'success'
                        // ).then((result) => {
                        //     if (result.isConfirmed) {
                        //         //alert(1)
                        //         location.reload();
                        //     } else if (result.isDenied) {
                        //         // alert(2)
                        //         obj.hide();
                        //     }
                        // });
                        //  
                        $('#set_new_reminder').modal('hide');
                        $('#frmremind')[0].reset();
                        location.reload();

                    }
                }
            });
        });
    });
</script>

@endsection