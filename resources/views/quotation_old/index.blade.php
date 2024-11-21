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
                                <!-- @can('quotations.create')
                                <a class="dropdown-item" href="{{route('quotations.create')}}">Add New Quote</a>
                                @endcan -->
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
                            @livewire('quotation-list')
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