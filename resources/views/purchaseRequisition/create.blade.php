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
                                <li class="breadcrumb-item"><a href="">Purchase Requisition</a></li>
                                <li class="breadcrumb-item"><a href="">Create New</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$order->os_number}}</li>
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
                                            @php
                                            $pr_number = str_replace("OS", "PR", $order->os_number);
                                            @endphp
                                            <h3 class="hd-bold mb-0">Purchase Requisition</h3>
                                            <span>{{$pr_number}} </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xxl-12 col-lg-12">
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
                                    <div class="mt-2">
                                        @include('layouts.partials.messages')
                                    </div>
                                    <form method="POST" action="{{ route('purchaserequisition.store') }}" enctype="multipart/form-data" id="frmpr">
                                        @csrf
                                        <input type="hidden" name="os_id" value="{{$order->id}}" />
                                        <input type="hidden" name="pr_for" value="{{$order->order_for}}" />
                                        <input type="hidden" name="created_by" value="{{$order->created_by}}" />
                                        <input type="hidden" name="pr_date" value="{{date('Y-m-d')}}" />
                                        <input type="hidden" name="pr_type" value="client" />
                                        <input type="hidden" name="company_id" value="{{$order->company_id}}" />
                                        <input type="hidden" name="status" value="pending" />

                                        <div class="row mt-4">
                                            <div class="col-xxl-12">

                                                @include('purchaseRequisition.partials._pr_items')
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-4"></div>
                                            <div class="col-4">
                                                <button type="button" class="btn btn-secondary m-2" onclick="window.location='{{ route("purchaserequisition.index") }}'">Cancel</button>
                                                <button type="submit" id="pr_details_button m-2" class="btn btn-primary" value="save-os">Create PR</button>
                                            </div>
                                            <div class="col-4"></div>
                                        </div>

                                    </form>
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
    $(function(e) {
      var iter4 = $("#chargespaymentFields").find("tbody >tr").length;

      $(".addAC").click(function() {

          ++iter4;

          let sCurrency = $('#supplier_currency').val();

          $("#chargespaymentFields").append(`<tr valign="top">
          <td><input type="text" class="form-control" name="charges[${iter4}][title]" placeholder="Customs Clearance Charge" /></td>
          <td><input type="text" class="form-control" name="charges[${iter4}][considered]" placeholder="Considered Cost" /></td>
          <td><input type="hidden" name="charges[${iter4}][currency]" value="${sCurrency}" />
          <input type="checkbox" id="charge_id_${iter4}" class="form-check-input charge_select" name="charges[${iter4}][charge_id]" checked>
          <label class="form-check-label" for="charge_id_${iter4}">SELECT</label>
          </td></tr>`);
      });
        // $('.quantity').on('keyup', function(e) {
        //     let unitprice = $(this).val();
        //     let irow = $(this).data('irow');

        //     let qty = $("input[name='item[" + irow + "][quantity]']").val();
        //     let line_total = parseFloat(unitprice) * parseInt(qty);

        //     line_total = parseFloat(line_total).toFixed(2);
        //     $("input[name='item[" + irow + "][total_amount]']").val(line_total);
        //     //console.log("Line Total", line_total)
        // });

        function CheckProducts() {

            var checkedVals = $('input.product_select:checkbox:checked').map(function() {
                return this.value;
            }).get();

            return checkedVals;
        }

        $(document).on('input change', '.quantity', function(e) {
            let row = $(this).closest('tr');
            let rowid = row.attr('id');
            let tmp = rowid.split("-");
            irow = tmp[1];

            let qty = $(this).val();
            let unitprice = row.find("input[name='item[" + irow + "][unit_price]']").val() || 0;

            let discount = row.find("input[name='item[" + irow + "][discount]']").val() || 0;

            let linetotal = parseFloat(unitprice * qty).toFixed(2)
            let total = 0;
            if (discount > 0) {
                total = linetotal - (linetotal * discount / 100);
                total = parseFloat(total).toFixed(2);
            } else {
                total = linetotal;
            }
            $("input[name='item[" + irow + "][total_amount]']").val(total);

        });

        $(document).on('input change', '.discount', function(e) {
            let row = $(this).closest('tr');
            let rowid = row.attr('id');
            let tmp = rowid.split("-");
            irow = tmp[1];

            let discount = $(this).val();
            let unitprice = row.find("input[name='item[" + irow + "][unit_price]']").val() || 0;
            let qty = row.find("input[name='item[" + irow + "][quantity]']").val() || 0;

            let linetotal = parseFloat(unitprice * qty).toFixed(2);

            let total = linetotal - (linetotal * discount / 100);
            total = parseFloat(total).toFixed(2);

            $("input[name='item[" + irow + "][total_amount]']").val(total);

        });

        // $(document).on('submit', 'form', function(event) {
        //     var form = jQuery("#frmpr");
        //     event.preventDefault();

        //     let isProduct = CheckProducts();

        //     if (isProduct == '') {
        //         Swal.fire({
        //             icon: "error",
        //             title: "Oops...",
        //             text: "Products are not selected, please choose.."
        //         });
        //         return false;
        //     }
        //     return true;

        // Swal.fire({
        //     title: "Do you really want to create PR with the selected products & quantity?",
        //     showDenyButton: true,
        //     showCancelButton: true,
        //     confirmButtonText: "Save",
        //     denyButtonText: `Don't save`
        // }).then((result) => {
        //     /* Read more about isConfirmed, isDenied below */
        //     if (result.isConfirmed) {

        //         form.submit();
        //     } else if (result.isDenied) {
        //         Swal.fire("Changes are not saved", "", "info");
        //     }
        // });

        //});

    });
</script>
@endpush
@endsection
