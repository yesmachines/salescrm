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
                                <li class="breadcrumb-item"><a href="{{route('orders.index')}}">Orders</a></li>
                                <li class="breadcrumb-item"><a href="">Create New</a></li>
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
                                            <h3 class="hd-bold mb-0">{{$quotation->company->company}}</h3>
                                            <span>by {{$quotation->customer->fullname}}</span>
                                            <span class="text-info">from {{$quotation->company->country->name}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-12 col-lg-12">
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
                                            @include('orders.partials._summary')
                                            <!-- End User Details -->
                                        </div>
                                        <div class="tab-pane fade" id="tabit_2">
                                            <!-- Basic Details -->
                                            @include('orders.partials._client')
                                            <!-- End User Details -->
                                        </div><!-- -->
                                        <div class="tab-pane fade" id="tabit_3">
                                            @include('orders.partials._supplier')
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
    $('.todaydatepick').daterangepicker({
        singleDatePicker: true,
        "cancelClass": "btn-secondary",
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('.datepick').daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        "cancelClass": "btn-secondary",
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $('.datepick').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
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

    /**************************************
     * Payment Term Client Dynamic Rows
     *****************************************/
    var iter = $("#paymentcustomFields").find("tbody >tr").length;

    $(".addPT").click(function() {

        ++iter;
        let dropdwn = createDropDown(iter);

        $("#paymentcustomFields").append(`<tr valign="top">
      <td><textarea class="form-control" name="clientpayment[${iter}][payment_term]" placeholder="Payment Term"></textarea></td>
      <td><input type="text" class="form-control datepick" name="clientpayment[${iter}][expected_date]" placeholder="Expected Date" /></td>
      <td>${dropdwn}</td>
      <td><textarea rows="2" name="clientpayment[${iter}][remarks]" placeholder="Remarks" class="form-control"></textarea></td>
      <td><a href="javascript:void(0);" class="remPT" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);
    });

    $("#paymentcustomFields").on('click', '.remPT', function() {
        // if (confirm('Are you sure to delete the payment term?')) {
        //     iter--;
        //     $(this).parents('tr').remove();
        // } else {
        //     return false;
        // }
        Swal.fire({
            title: "Are you sure?",
            text: "You are sure to delete the payment term!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                iter--;
                $(this).parents('tr').remove();
            }
        });
    });

    function createDropDown(i) {
        let ddlPStatus = `<select class=" form-control" name="clientpayment[${i}][status]">
        <option value="0">Not Received</option>
        <option value ="1">Received</option>
        <option value ="2">Partially Received</option></select>`;
        return ddlPStatus;
    }
    /**************************************
     * Summary Save - Step1
     *****************************************/
    $('#frmosummary').on('submit', function(event) {
        event.preventDefault();

        var url = "{{route('orders.savestep1')}}";

        $.ajax({
            url: url,
            method: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                // $(form).trigger("reset");
                if (response.data) {
                    let order = response.data;
                    $("#order_id_step2").val(order.id);
                    //   $("#order_id_step3").val(order.id);

                    nextStep();
                }
                console.log(response.success);
            },
            error: function(response) {
                var errors = response.responseJSON;

                let errorsHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors.errors, function(k, v) {
                    errorsHtml += '<li>' + v + '</li>';
                });
                errorsHtml += '</ul></di>';
                $('.summary_error_msg').html(errorsHtml);

                console.log(response);
            }
        });
    });

    /**************************************
     * Items Dynamic Rows
     *****************************************/
    var iter2 = $("#itemcustomFields").find("tbody >tr").length;

    $(".addIT").click(function() {

        ++iter2;
        let dropdwn = createDropDown2(iter2);

        $("#itemcustomFields").append(`<tr valign="top">
      <td><input type="hidden" name="item[${iter2}][product_id]" />
      <textarea class="form-control" name="item[${iter2}][item_name]" placeholder="Item"></textarea></td>
      <td><input type="text" class="form-control" name="item[${iter2}][partno]" placeholder="Part No" /></td>
      <td> <input type="number" class="form-control" name="item[${iter2}][quantity]" placeholder="Quantity" /></td>
      <td><input type="text" class="form-control" name="item[${iter2}][yes_number]" placeholder="YesNo." /></td>
      <td><input type="text" class="form-control" name="item[${iter2}][total_amount]" placeholder="Total Amount" /></td>
      <td><input type="text" class="form-control datepick" name="item[${iter2}][expected_delivery]" placeholder="Expected Delivery" /></td>
      <td>${dropdwn}</td>
      <td><textarea rows="2" name="item[${iter2}][remarks]" placeholder="Remarks" class="form-control"></textarea></td>
      <td><a href="javascript:void(0);" class="remIT" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);
    });

    $("#itemcustomFields").on('click', '.remIT', function() {
        // if (confirm('Are you sure to delete the Item?')) {
        //     iter2--;
        //     $(this).parents('tr').remove();
        // } else {
        //     return false;
        // }
        Swal.fire({
            title: "Are you sure?",
            text: "You are sure to delete the order item!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                iter2--;
                $(this).parents('tr').remove();
            }
        });
    });

    function createDropDown2(i) {
        let ddlPStatus = `<select class=" form-control" name="item[${i}][status]">
        <option value="0">Not Delivered</option>
        <option value ="1">Delivered</option></select>`;
        return ddlPStatus;
    }
    /**************************************
     * Item Save - Step2
     *****************************************/
    $('#add_delivery_item').on('submit', function(event) {
        event.preventDefault();

        var url = "{{route('orders.savestep2')}}";

        let order_id = $("#order_id_step2").val().trim();
        if (order_id == '') {
            alert("Please complete & save step 1 details");
            return false;
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                // $(form).trigger("reset");
                // alert(response.success)
                if (response.data) {
                    let order = response.data;
                    // $("#order_id_step2").val(order.id);
                    $("#order_id_step3").val(order.id);

                    nextStep();
                }
                console.log(response.success);
            },
            error: function(response) {
                var errors = response.responseJSON;

                let errorsHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors.errors, function(k, v) {
                    errorsHtml += '<li>' + v + '</li>';
                });
                errorsHtml += '</ul></di>';
                $('.client_error_msg').html(errorsHtml);

                console.log(response);
            }
        });
    });

    /**************************************
     * Supplier Payment Dynamic Rows
     *****************************************/
    var iter3 = $("#supplierpaymentFields").find("tbody >tr").length;

    $(".addST").click(function() {

        ++iter3;
        let dropdwn = createDropDown3(iter3);

        $("#supplierpaymentFields").append(`<tr valign="top">
      <td><textarea class="form-control" name="supplierpayment[${iter3}][payment_term]" placeholder="Payment Term"></textarea></td>
      <td><input type="text" class="form-control datepick" name="supplierpayment[${iter3}][expected_date]" placeholder="Expected Date" /></td>
      <td>${dropdwn}</td>
      <td><textarea rows="2" name="supplierpayment[${iter3}][remarks]" placeholder="Remarks" class="form-control"></textarea></td>
      <td><a href="javascript:void(0);" class="remST" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);
    });

    $("#supplierpaymentFields").on('click', '.remST', function() {
        // if (confirm('Are you sure to delete the supplier term?')) {
        //     iter3--;
        //     $(this).parents('tr').remove();
        // } else {
        //     return false;
        // }
        Swal.fire({
            title: "Are you sure?",
            text: "You are sure to delete the supplier term!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                iter3--;
                $(this).parents('tr').remove();
            }
        });
    });

    function createDropDown3(i) {
        let ddlSPStatus = `<select class=" form-control" name="supplierpayment[${i}][status]">
        <option value="0">Not Done</option>
        <option value ="1">Done</option>
        <option value ="2">Partially Done</option></select>`;
        return ddlSPStatus;
    }
    /**************************************
     * Additional Charges Dynamic Rows
     *****************************************/
    var iter4 = $("#chargespaymentFields").find("tbody >tr").length;

    $(".addAC").click(function() {

        ++iter4;

        $("#chargespaymentFields").append(`<tr valign="top">
      <td><input type="text" class="form-control" name="charges[${iter4}][title]" placeholder="Customs Clearance Charge" /></td>
      <td><input type="text" class="form-control" name="charges[${iter4}][considered]" placeholder="Considered Cost" /></td>
      <td><textarea rows="2" name="charges[${iter4}][remarks]" placeholder="Remarks" class="form-control"></textarea></td>
      <td><a href="javascript:void(0);" class="remAC" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);
    });

    $("#chargespaymentFields").on('click', '.remAC', function() {
        // if (confirm('Are you sure to delete the Additional Charges?')) {
        //     iter4--;
        //     $(this).parents('tr').remove();
        // } else {
        //     return false;
        // }
        Swal.fire({
            title: "Are you sure?",
            text: "You are sure to delete the Additional Charges!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                iter4--;
                $(this).parents('tr').remove();
            }
        });
    });

    /**************************************
     * Supplier Save - Step3
     *****************************************/
    $('#add_supplier_details').on('submit', function(event) {
        event.preventDefault();

        var url = "{{route('orders.savestep3')}}";

        let order_id = $("#order_id_step3").val().trim();
        if (order_id == '') {
            alert("Please complete and save step2 details");
            return false;
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(response) {
                // $(form).trigger("reset");
                // alert(response.success)
                if (response.data) {
                    let order = response.data;

                    Swal.fire({
                        title: "Good job!",
                        text: "You have created order successfully!",
                        icon: "success"
                    });
                    setTimeout(() => {
                        window.location.href = "{{route('orders.index')}}";
                    }, 2000);

                }
            },
            error: function(response) {
                var errors = response.responseJSON;

                let errorsHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors.errors, function(k, v) {
                    errorsHtml += '<li>' + v + '</li>';
                });
                errorsHtml += '</ul></di>';
                $('.supplier_error_msg').html(errorsHtml);

                console.log(response);
            }
        });
    });
</script>
@endpush
@endsection