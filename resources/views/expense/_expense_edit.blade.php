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
                  <h4>Update Other Expense</h4>
                </a>
              </div>
              <div class="col-xxl-12 col-lg-12">
                <form method="POST" action="{{ route('expense.update', $stock->id) }}" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="separator"></div>


                  @include('expense._edit._expensesummary')

                  @include('expense._edit._expenseitems')
                  @include('expense._edit._expensesupplier')

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


function initializeDatePicker(selector) {
  $(selector).daterangepicker({
    singleDatePicker: true,
    autoUpdateInput: false,
    cancelClass: "btn-secondary",
    locale: {
      format: 'YYYY-MM-DD'
    }
  }).on('apply.daterangepicker', function (e, picker) {
    $(this).val(picker.startDate.format('YYYY-MM-DD'));
  });
}

// Apply date pickers
initializeDatePicker('.todaydatepick');
initializeDatePicker('.datepick');
initializeDatePicker('.datep');
initializeDatePicker('.datepicks');

// STEPS
$(function() {
  $('.nav-item').on('click', function() {
    $('.nav-link').removeClass('active');
    $(this).find('.nav-link').addClass('active').blur();
  });

  $('.next-step, .prev-step').on('click', function(e) {
    var $activeTab = $('.tab-pane.active');
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
  <td><input type="text" class="form-control datepick" name="clientpayment[${iter}][expected_date]" placeholder="Expected Date" /></td>
  <td>${dropdwn}</td>
  <td><textarea rows="2" name="clientpayment[${iter}][payment_remark]" placeholder="Remarks" class="form-control"></textarea></td>
  <td><a href="javascript:void(0);" class="remPT" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);

});

function createDropDown(i) {
  let ddlPStatus = `<select class=" form-control" name="clientpayment[${i}][status]">
  <option value="0">Not Received</option>
  <option value ="1">Received</option>
  <option value ="2">Partially Received</option></select>`;
  return ddlPStatus;
}

/**************************************
* Supplier Payment Dynamic Rows
*****************************************/
var iter3 = $("#supplierpaymentFields").find("tbody >tr").length;

$(".addST").click(function() {

  ++iter3;
  let dropdwn = createDropDown3(iter3);

  $("#supplierpaymentFields").append(`<tr valign="top">
  <td><input type="hidden" name="supplierpayment[${iter3}][payment_id]"  />
  <textarea class="form-control" name="payment_term[]" placeholder="Payment Term"></textarea></td>
  <td><input type="text" class="form-control datep" name="expected_date[]" placeholder="Expected Date" /></td>
  <td>
  <select class=" form-control" name="status[]">
  <option value="0">Not Done</option>
  <option value="1">Done</option>
  <option value="2">Partially Done</option>
  </select>
  </td>
  <td><textarea rows="2" name="payment_remark[]" placeholder="Remarks" class="form-control"></textarea></td>
  <td><a href="javascript:void(0);" class="remST" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);

  // Reinitialize datepicker
  initializeDatePicker('.datep');
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

  // Reinitialize datepicker
  initializeDatePicker('.datepicks');
});
$(document).on('click', '.remAC', function () {
  Swal.fire({
    title: "Are you sure?",
    text: "You are sure to delete this charge row!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      $(this).closest('tr').remove();
    }
  });
});

//remove payment row
$(document).on('click', '.remST', function () {
  Swal.fire({
    title: "Are you sure?",
    text: "You are sure to delete this payment row!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      $(this).closest('tr').remove();
    }
  });
});

</script>

<script>
$(document).ready(function () {

  function calculateTotal(row) {
      const unitPrice = parseFloat($(row).find('input[name="unit_price[]"]').val()) || 0;
      const quantity = parseFloat($(row).find('input[name="quantity[]"]').val()) || 0;
      const discount = parseFloat($(row).find('input[name="discount[]"]').val()) || 0;

      const subtotal = unitPrice * quantity;
      let discountAmount = 0;
      if (discount > 0 && discount <= 100) {
          discountAmount = (subtotal * discount) / 100;
      } else {
          discountAmount = discount;
      }
      const total = subtotal - discountAmount;
      $(row).find('input[name="total_amount[]"]').val(total.toFixed(2));
  }


  function calculateTotalAmount(totalAmountToRemove = 0) {
    let total = 0;


    $('input[name="total_amount[]"]').each(function () {
      total += parseFloat($(this).val()) || 0;
    });

    total -= totalAmountToRemove;


    $('#buying_price').val(total.toFixed(2));
  }

  $("#itemcustomFields").on('click', '.remIT', function () {
    const row = $(this).parents('tr');
    const totalAmountToRemove = parseFloat(row.find('input[name="total_amount[]"]').val()) || 0;

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
        row.remove(); // Remove the row
        calculateTotalAmount();
      }
    });
  });


  const firstRow = $('#itemcustomFields tr').first();
  calculateTotal(firstRow);

  $('#itemcustomFields').on('input change', 'input[name="unit_price[]"], input[name="quantity[]"], input[name="discount[]"]', function () {


    const row = $(this).closest('tr');
    calculateTotal(row);

    calculateTotalAmount();
  });


  $('.addIT').click(function () {

    const newRow = `
    <tr valign="top">
    <td width="15%">
    <textarea class="form-control" name="item_name[]" placeholder="Item"></textarea>
    </td>
    <td>
    <input type="text" class="form-control" name="partno[]" placeholder="Part No" />
    </td>
    <td>
    <input type="text" class="form-control" name="unit_price[]" placeholder="Unit Price" />
    </td>
    <td>
    <input type="number" class="form-control" name="quantity[]" placeholder="Quantity" />
    </td>
    <td>
    <input type="text" class="form-control" name="discount[]" placeholder="Discount" />
    </td>
    <td>
    <input type="number" class="form-control" name="total_amount[]" step="any" placeholder="Total Amount" readonly/>
    </td>
    <td width="10%">
    <select class="form-control" name="status[]" id="status">
    <option value="0" {{ 0 ? 'selected' : '' }}>Not Delivered</option>
    <option value="1" {{ 1 ? 'selected' : '' }}>Delivered</option>
    </select>
    </td>
    <td>
    <textarea rows="2" name="item_remark[]" placeholder="Remarks" class="form-control"></textarea>
    </td>
    <td>
    <a href="javascript:void(0);" class="remIT" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a>
    </td>
    </tr>
    `;
    $("#itemcustomFields").append(newRow);
    const newRowElem = $("#itemcustomFields tr").last();
    newRowElem.find('input[name="unit_price[]"], input[name="quantity[]"], input[name="discount[]"]').on('input change', function () {

      calculateTotal(newRowElem);
      calculateTotalAmount();
    });
    newRowElem.find('.datepicks').daterangepicker({
      singleDatePicker: true,
      autoUpdateInput: false,
      "cancelClass": "btn-secondary",
      locale: {
        format: 'YYYY-MM-DD'
      }
    }).on('apply.daterangepicker', function (e, picker) {

      $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });
  });


  $('#itemcustomFields tr').each(function () {
    calculateTotal(this);
  });
  calculateTotalAmount();
});

</script>


@endpush

@endsection
