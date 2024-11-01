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
                                <li class="breadcrumb-item"><a href="{{route('orders.index')}}">All Order</a></li>
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
                                <div class="col-xxl-10 col-lg-10">
                                    <div class="media">
                                        <div class="media-head me-3">
                                            <div class="avatar avatar-logo">
                                                <span class="initial-wrap bg-success-light-5">
                                                    <img src="{{asset('dist/img/symbol-avatar-15.png')}}" alt="logo">
                                                </span>
                                            </div>
                                        </div>
                                        <div class="media-body">
                                            <h3 class="hd-bold mb-0">{{$order->company->company}}</h3>
                                            <span>by {{$order->customer->fullname}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-10 col-lg-10">
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
                                                <span class="nav-link-text">Order Items</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="tab_3" data-bs-toggle="pill" href="#tabit_3">
                                                <span class="nav-link-text">Order Updates</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content py-7">
                                        <div class="tab-pane fade show active" id="tabit_1">
                                            <!-- Basic Details -->
                                            @include('orders._edit._summary')
                                            <!-- End User Details -->
                                        </div>
                                        <div class="tab-pane fade" id="tabit_2">
                                            <!-- Basic Details -->
                                            @include('orders._edit._orderitems')
                                            <!-- End User Details -->
                                        </div><!-- -->
                                        <div class="tab-pane fade" id="tabit_3">
                                            @include('orders._edit._history')
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

        $(".reply-box").hide();
        // step 1
        $("form#frmsummary").submit(function(e) {
            e.preventDefault();

            let formData = new FormData($(this)[0]);

            if (!$('#shipping_term').val()) {
                $("#summary-errors").show();
                $("#summary-errors").addClass('alert alert-danger').text("Please enter shipment term!");
                return false;
            }
            if (!$('#payment_term').val()) {
                $("#summary-errors").show();
                $("#summary-errors").addClass('alert alert-danger').text("Please enter payment term!");
                return false;
            }
            if (!$('#delivery_time').val()) {
                $("#summary-errors").show();
                $("#summary-errors").addClass('alert alert-danger').text("Please enter promised delivery time!");
                return false;
            }

            $('#ajxloader').html('Please wait..');
            $.ajax({
                type: 'POST',
                url: "{{ route('order-details.update') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $(".summary_error_msg").removeClass('alert alert-danger');
                    $(".summary_error_msg").hide();
                    $("#frmspecs").removeClass('alert alert-danger');
                    $("#frmspecs").hide();
                    if ($.isEmptyObject(data)) {
                        console.log("Empty Result ", data);

                    } else {
                        Swal.fire(
                            'Success!',
                            'Order Summary Updated!',
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                skipStep1();
                            } else if (result.isDenied) {
                                // alert(2)
                                obj.hide();
                            }
                        });
                        //

                    }
                },
                error: function(data) {
                    $(".summary_error_msg").show();
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
                        $('.summary_error_msg').addClass('alert alert-danger').html(err_str);

                        return false;
                    }
                }
            });


        });

        function skipStep1() {
            $('#tab_1').removeClass('active');
            $('#tab_2').addClass('active');
            $('#tabit_2').addClass('active');
            $('#tabit_2').addClass('show');
            $('#tabit_1').removeClass('active');
            $('#tabit_1').removeClass('show');
        }

        // step 2
        $(document).ready(function() {
            $(".addCF").click(function() {
                $("#customFields").append(`<tr valign="top">
                <td><input type="text" class="form-control" id="itemcustomFieldName" name="item[]" value="" placeholder="Item" /> </td>
                <td><input type="text" class="form-control" id="partcustomFieldValue" name="part_no[]" value="" placeholder="Part No" /></td>
                <td><input type="number" class="form-control" id="qtycustomFieldValue" name="qty[]" value="" placeholder="Qty" /> </td>
                <td><input type="date" class="form-control" id="delivered" name="delivered[]" value="" placeholder="Delivered On" /></td>
                <td><select class="form-control" name="status[]" id="status"><option value="0">Not Delivered</option><option value="1">Delivered</option></select></td>
                <td><textarea rows="2" id="remarks" name="remarks[]" placeholder="Remarks" class="form-control"></textarea></td>
                <td><a href="javascript:void(0);" class="remCF" title="DELETE ROW"><i class="fa fa-trash"></i></a></td></tr>`);
            });

            $("#customFields").on('click', '.remCF', function() {
                $(this).parent().parent().remove();
            });
        });
        $(document).ready(function() {
            $(".addemails").click(function() {
                $("#customEmailFields").append(`<tr valign="top"> <td cols="8"></td>
                <td><input type="text" class="form-control" id="itememailcustomFieldName" name="emails[]" value="" placeholder="Emails" /> </td>
                 <td><a href="javascript:void(0);" class="rememailCF" title="DELETE ROW"><i class="fa fa-trash"></i></a></td></tr>`);
            });

            $("#customEmailFields").on('click', '.rememailCF', function() {
                $(this).parent().parent().remove();
            });
        });
        // list item table
        function RefreshTable(deliveryid) {

            $.ajax({
                type: 'post',
                url: "{{ route('order-data-delivery-history.load') }}",
                data: {
                    order_delivery_id: deliveryid,
                },
                success: function(res) {

                    $('#load-item-tbl').html(res.data);

                }
            });
        }

        // add items
        $("form#add_delivery_item").submit(function(e) {
            e.preventDefault();

            let formData = new FormData($(this)[0]);

            $('#ajxloader').html('Please wait..');
            $.ajax({
                type: 'POST',
                url: "{{ route('order-delivery-details.update') }}",
                data: formData,
                processData: false,
                contentType: false,

                success: function(data) {
                    $("#frmspecs").removeClass('alert alert-danger');
                    $("#frmspecs").hide();
                    $(".error_msg").removeClass('alert alert-danger');
                    $(".error_msg").hide();
                    $(".summary_error_msg").removeClass('alert alert-danger');
                    $(".summary_error_msg").hide();

                    if ($.isEmptyObject(data)) {
                        console.log("Empty Result ", data);

                    } else {
                        Swal.fire(
                            'Success!',
                            'Order Item Added!',
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {

                                $('#add_delivery_item')[0].reset();
                                RefreshTable(data);
                                // skipStep2();

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
                    $(".summary_error_msg").removeClass('alert alert-danger');
                    $(".summary_error_msg").hide();
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

        function skipStep2() {

            $('#tab_2').removeClass('active');
            $('#tab_3').addClass('active');
            $('#tabit_3').addClass('active');
            $('#tabit_3').addClass('show');
            $('#tabit_2').removeClass('active');
            $('#tabit_2').removeClass('show');
        }
        // delete items
        $(document).delegate('a.delete-order-delivery-row', 'click', function(e) {
            e.preventDefault();
            var rowCount = $(".edit_table_data>tbody tr").length;
            if (rowCount <= 1) {

                $("#frmtable").show();
                $("#frmtable").addClass('alert alert-danger').text("You should atleast one item for this order to exist!!");
                return false;

            } else {
                $("#frmtable").removeClass('alert alert-danger');
                $("#frmtable").hide();

                let rid = jQuery(this).attr('data-id');
                rid = parseInt(rid);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('order-data-item-history.delete') }}",
                    data: {
                        order_item_id: rid,
                    },
                    success: function() {
                        $("#row-" + rid).remove();

                    }
                });

            }

        });
        // step 3

        // load history
        function LoadRefreshHistory() {
            $(".reply-box").hide();
            let order_id = $("#get_order_id").val();

            $.ajax({
                type: 'post',
                url: "{{ route('order-history.load') }}",
                data: {
                    order_id: order_id,
                },
                success: function(res) {
                    $('.list-his').html(res.data);
                }
            });
        }

        $("form#frmComments").submit(function(e) {
            e.preventDefault();

            let formData = new FormData($(this)[0]);

            if (!$('#comment').val()) {
                $("#frmql").show();
                $("#frmql").addClass('alert alert-danger').text("Please enter Comments!");
                return false;
            }

            $('#ajxloader').html('Please wait..');
            $.ajax({
                type: 'POST',
                url: "{{ route('order-history-details.insert') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $("#frmql").removeClass('alert alert-danger');
                    $("#frmql").hide();

                    if ($.isEmptyObject(data)) {
                        console.log("Empty Result ", data);

                    } else {
                        Swal.fire(
                            'Success!',
                            'Order Status Updated!',
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {
                                $('#frmComments')[0].reset();
                                // $('.mediarow').remove();
                                LoadRefreshHistory();

                            } else if (result.isDenied) {
                                // alert(2)
                                obj.hide();
                            }
                        });
                        //

                    }
                }
            });


        });

        // delete items
        $(document).delegate('a.delete-order-comment-row', 'click', function(e) {
            e.preventDefault();
            var rowCount = $(".list-his > div.mediarow").length;
            if (rowCount > 0) {

                let rid = jQuery(this).attr('data-id');
                rid = parseInt(rid);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('order-history.delete') }}",
                    data: {
                        order_history_id: rid,
                    },
                    success: function() {
                        $("#crow-" + rid).remove();

                    }
                });

            }

        });

        $(document).delegate('a.reply-order-comment-row', 'click', function(e) {
            e.preventDefault();
            var id = jQuery(this).attr('data-id');
            $("#reply-box-" + id).toggle();

        });

        $("form#frmReplys").submit(function(e) {
            e.preventDefault();

            let parent_id = $(this).data('id');
            let order_id = $("#order_id" + parent_id).val();
            let reply = $("#reply" + parent_id).val();

            let formData = new FormData($(this)[0]);

            if (!reply) {
                $("#frmql").show();
                $("#frmql").addClass('alert alert-danger').text("Please enter Reply!");
                return false;
            }

            $('#ajxloader').html('Please wait..');
            $.ajax({
                type: 'POST',
                url: "{{ route('comment-reply.insert') }}",
                // data: {
                //     parent_id: parent_id,
                //     main_parent_id: main_parent_id,
                //     order_id: order_id,
                //     reply: reply,
                // },
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {

                    $("#frmql").removeClass('alert alert-danger');
                    $("#frmql").hide();
                    if ($.isEmptyObject(data)) {
                        console.log("Empty Result ", data);

                    } else {
                        Swal.fire(
                            'Success!',
                            'You have Replied to the comment',
                            'success'
                        ).then((result) => {
                            if (result.isConfirmed) {

                                $("#reply" + parent_id).val("");
                                $("#reply-box-" + parent_id).hide();

                                // $('.mediarow').remove();
                                LoadRefreshHistory();

                            } else if (result.isDenied) {
                                // alert(2)
                                obj.hide();
                            }
                        });
                        //

                    }
                }
            });


        });

        $(document).delegate('a.delete-order-reply-row', 'click', function(e) {
            e.preventDefault();


            let rid = jQuery(this).attr('data-id');
            rid = parseInt(rid);
            $.ajax({
                type: 'POST',
                url: "{{ route('reply-comment.delete') }}",
                data: {
                    reply_comment_id: rid,
                },
                success: function() {
                    $("#crow-" + rid).remove();

                }
            });



        });
    </script>

    @endsection