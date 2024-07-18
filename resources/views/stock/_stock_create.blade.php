@extends('layouts.default')

@section('content')

<div class="hk-pg-body py-0">
  <div class="integrationsapp-wrap integrationsapp-sidebar-toggle">

    <div class="integrationsapp-content" style="left: 0rem;">
      <div class="integrationsapp-detail-wrap">

        <div class="integrations-body">

          <div data-simplebar class="nicescroll-bar">


            <div class="row">
              <div class="dropdown">
                <a class="contactapp-title link-dark" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  <h4>Create New Sock</h4>
                </a>
              </div>
              <div class="col-xxl-12 col-lg-12">
                <form method="POST" action="{{ route('stock.store') }}" enctype="multipart/form-data">
                  @csrf
                  <div class="separator"></div>
                  <div class="tab-content py-7">

                    @include('stock._stock._stocksummary')

                    @include('stock._stock._stockitems')
                    @include('stock._stock._stocksupplier')
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
@push('scripts')
<script type="text/javascript">

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
$(document).ready(function() {
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
  <td><input type="hidden" name="clientpayment[${iter}][payment_id]"  />
  <textarea class="form-control" name="clientpayment[${iter}][payment_term]" placeholder="Payment Term"></textarea></td>
  <td><input type="text" class="form-control " name="clientpayment[${iter}][expected_date]" placeholder="Expected Date" /></td>
  <td>${dropdwn}</td>
  <td><textarea rows="2" name="clientpayment[${iter}][payment_remark]" placeholder="Remarks" class="form-control"></textarea></td>
  <td><a href="javascript:void(0);" class="remPT" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);

});


$("#paymentcustomFields").on('click', '.remPT', function() {
  var url = "{{ route('orders.deletePayment') }}";
  let obj = $(this);
  let cpid = obj.data('id');


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

      // ajax delete existing payment id
      if (cpid) {

        const formDataDel = new FormData();
        formDataDel.append("paymentId", cpid);

        $.ajax({
          url: url,
          method: 'POST',
          data: formDataDel,
          dataType: 'JSON',
          contentType: false,
          cache: false,
          processData: false,
          success: function(response) {
            console.log(response);

            if (response.data) {
              iter--;
              obj.parents('tr').remove();
            }
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
      } else {
        // if not exists in db, remove only
        iter--;
        obj.parents('tr').remove();
      }
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


/**************************************
* Items Dynamic Rows
*****************************************/
var iter2 = $("#itemcustomFields").find("tbody >tr").length;

$(".addIT").click(function() {

  ++iter2;
  let dropdwn = createDropDown2(iter2);

  let newRow = $(`<tr valign="top">
  <td>
  <textarea class="form-control" name="item_name[]" placeholder="Item"></textarea></td>
  <td><input type="text" class="form-control" name="partno[]" placeholder="Part No" /></td>
  <td> <input type="number" class="form-control" name="quantity[]" placeholder="Quantity" /></td>
  <td><input type="text" class="form-control" name="yes_number[]" placeholder="YesNo." /></td>
  <td><input type="number" class="form-control" name="total_amount[]" step="any" placeholder="Total Amount" /></td>
  <td><input type="text" class="form-control datepick" name="expected_delivery[]" placeholder="Expected Delivery" /></td>
  <td width="10%">
  <select class="form-control" name="status[]" id="status">
  <option value="0" {{ 0 ? 'selected' : '' }}>Not Delivered</option>
  <option value="1" {{ 1 ? 'selected' : '' }}>Delivered</option>
  </select>
  </td>
  <td><textarea rows="2" name="item_remark[]" placeholder="Remarks" class="form-control"></textarea></td>
  <td><a href="javascript:void(0);" class="remIT" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);
  $("#itemcustomFields").append(newRow);
  newRow.find('.datepick').daterangepicker({
   singleDatePicker: true,
   autoUpdateInput: false,
   cancelClass: "btn-secondary",
   locale: {
     format: 'YYYY-MM-DD'
   }
 });

 // Set the date when a date is selected
 newRow.find('.datepick').on('apply.daterangepicker', function(ev, picker) {
   $(this).val(picker.startDate.format('YYYY-MM-DD'));
 });
  newRow.find('input[name="total_amount[]"]').on('input', calculateTotalAmount);
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


/**************************************
* Supplier Payment Dynamic Rows
*****************************************/
var iter3 = $("#supplierpaymentFields").find("tbody >tr").length;

$(".addST").click(function() {

  ++iter3;
  let dropdwn = createDropDown3(iter3);

  let newRow = $(`<tr valign="top">
  <td><input type="hidden" name="supplierpayment[${iter3}][payment_id]"  />
  <textarea class="form-control" name="payment_term[]" placeholder="Payment Term"></textarea></td>
  <td><input type="text" class="form-control datepick" name="expected_date[]" placeholder="Expected Date" /></td>
  <td>
  <select class=" form-control" name="status[]">
  <option value="0">Not Done</option>
  <option value="1">Done</option>
  <option value="2">Partially Done</option>
  </select>
  </td>
  <td><textarea rows="2" name="payment_remark[]" placeholder="Remarks" class="form-control"></textarea></td>
  <td><a href="javascript:void(0);" class="remST" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);

  $("#supplierpaymentFields tbody").append(newRow);

  // Initialize date picker for the newly added row
  newRow.find('.datepick').daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    cancelClass: "btn-secondary",
    locale: {
      format: 'YYYY-MM-DD'
    }
  });

  // Set the date when a date is selected
  newRow.find('.datepick').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });

});

$("#supplierpaymentFields").on('click', '.remST', function() {

  var url = "{{ route('orders.deletePayment') }}";
  let obj = $(this);
  let spid = obj.data('id');

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
      // ajax delete existing payment id
      if (spid) {

        const formDataDel = new FormData();
        formDataDel.append("paymentId", spid);

        $.ajax({
          url: url,
          method: 'POST',
          data: formDataDel,
          dataType: 'JSON',
          contentType: false,
          cache: false,
          processData: false,
          success: function(response) {
            console.log(response);

            if (response.data) {
              iter3--;
              obj.parents('tr').remove();
            }
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
      } else {
        // if not exists in db, remove only
        iter3--;
        obj.parents('tr').remove();
      }
    }
  });
});

function createDropDown3(i) {
  let ddlSPStatus = `<select class=" form-control" name="supplierpayment[${i}][status]">
  <option value="0">Not Done</option>
  <option value="1">Done</option>
  <option value="2">Partially Done</option></select>`;
  return ddlSPStatus;
}
/**************************************
* Additional Charges Dynamic Rows
*****************************************/
var iter4 = $("#chargespaymentFields").find("tbody >tr").length;

$(".addAC").click(function() {

  ++iter4;

  $("#chargespaymentFields").append(`<tr valign="top">
  <td>
  <input type="text" class="form-control" name="charges[]" placeholder="Customs Clearance Charge" ></td>
  <td><input type="number" class="form-control" name="considered[]" placeholder="Considered Cost" ></td>
  <td><textarea rows="2" name="charge_remark[]" placeholder="Remarks" class="form-control"></textarea></td>
  <td><a href="javascript:void(0);" class="remAC" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);
});

$("#chargespaymentFields").on('click', '.remAC', function() {
  var url = "{{ route('orders.deleteCharge') }}";
  let obj = $(this);
  let acid = obj.data('id');

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

      // ajax delete existing charge id
      if (acid) {

        const formDataDel = new FormData();
        formDataDel.append("chargeId", acid);

        $.ajax({
          url: url,
          method: 'POST',
          data: formDataDel,
          dataType: 'JSON',
          contentType: false,
          cache: false,
          processData: false,
          success: function(response) {
            console.log(response);

            if (response.data) {
              iter4--;
              obj.parents('tr').remove();
            }
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
      } else {
        // if not exists in db, remove only
        iter4--;
        obj.parents('tr').remove();
      }
      // iter4--;
      // $(this).parents('tr').remove();
    }
  });
});

</script>
<script>
function calculateTotalAmount() {
  let total = 0;
  const totalAmountFields = document.querySelectorAll('input[name="total_amount[]"]');
  totalAmountFields.forEach(function(field) {
    total += parseFloat(field.value) || 0;
  });

  document.getElementById('buying_price').value = total.toFixed(2);
}
const totalAmountFields = document.querySelectorAll('input[name="total_amount[]"]');
totalAmountFields.forEach(function(field) {
  field.addEventListener('input', calculateTotalAmount);
});
calculateTotalAmount();
</script>

@endpush

@endsection
