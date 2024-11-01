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
<!-- Add New Product -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="padding: 16px;">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Add Product</h5>
      </div>
      <div class="modal-body">
        <form id="productForm">
          <input type="hidden" name="allowed_discount" id="allowed_discount" value="0" />
          <input type="hidden" name="product_type" value="custom" />
          <input type="hidden" name="status" value="active" />
          <div class="row gx-3">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Product Details</span></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Title <span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" id="titleInput" name="title" />
                <div class="invalid-feedback">Please enter a title.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Division<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="division_id" id="divisionInput">
                  <option value="">--</option>
                  @foreach ($divisions as $division)
                  <option value="{{ $division->id }}">{{ $division->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please enter a Division.</div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Brand<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="brand_id" id="brandInput">
                  <option value="">--</option>
                  @foreach ($suppliers as $k => $sup)
                  <option value=" {{ $sup->id }}">
                    {{ $sup->brand }}
                  </option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please enter a brand.</div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Model No</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="model_no">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Part Number</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="part_number" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Description </label>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="description" rows="2" cols="1"></textarea>

              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Product Category<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_category" id="productcategoryInput">
                  <option value="">-Select-</option>
                  <option value="products">Products</option>
                  <option value="accessories">Accessories</option>
                  <option value="consumables">Consumables</option>
                  <option value="spares">Spares</option>
                </select>
                <div class="invalid-feedback">Please select a product category.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Manager<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-select" name="manager_id" id="managerInput">
                  <option value="" selected="">--</option>
                  @foreach ($managers as $key => $row)
                  <option value="{{ $row->id }}">
                    {{ $row->user->name }}
                  </option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please select product manager.</div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="form-label">Image</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="file" name="image" />
              </div>
            </div>
          </div>



          <div class="row gx-3 mt-2 mb-2">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Selling Price Details</span></div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Payment Term<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="product_payment_term">
                  @foreach($terms as $paymentTerm)
                  <option value="{{ $paymentTerm->short_code }}">{{ $paymentTerm->title }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please enter a product payment term.</div>

              </div>

            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Currency<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="currency" id="currencyInput">
                  <option value="">-Select Currency-</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please enter a product currency.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Selling Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="selling_price" id="sellingPrice" />
                <div class="invalid-feedback">Please enter a price.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">MOSP<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="margin_percentage" id="marginPercentage" />
                <div class="invalid-feedback">Please enter a MOSP.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Margin Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="margin_price" id="marginPrice">
                <div class="invalid-feedback">Please enter a margin price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="date" id="durationSelect">
                  <option value="1" selected>1 Month</option>
                  <option value="3">3 Month</option>
                  <option value="6">6 Month</option>
                  <option value="9">9 Month</option>
                  <option value="12">12 Month</option>
                </select>
                <div class="invalid-feedback">Please select an date.</div>
              </div>
              <div class="form-group small" id="dateRangeGroup" style="display: none;">
                <label class="form-label">Dates:</label>
                <span id="dateRange"></span>
                <input type="hidden" name="start_date" id="startDateInput" />
                <input type="hidden" name="end_date" id="endDateInput" />
              </div>
            </div>
          </div>


          <div class="row gx-3 mt-2 mb-2">
            <div class="col-12">
              <div class="title title-xs title-wth-divider text-primary text-uppercase my-2"><span>Buying Price Details</span></div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Buying Currency<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="buying_currency" id="buying_currency" required>
                  <option value="">-Select Currency-</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please select a currency.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Gross Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="gross_price" id="gross_price" required />

                <div class="invalid-feedback">Please enter a gross price.</div>
              </div>
            </div>
            <div class="col-md-4" id="purchase_discount_percent">
              <div class="form-group">
                <label class="form-label">Purchase Discount(%)</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="number" name="discount" id="purchase_discount">
                <div class="invalid-feedback">Please enter a purchase discount.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4" id="purchase_discount_price">
              <div class="form-group">
                <label class="form-label">Purchase Discount Amount<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="discount_amount" id="purchase_discount_amount" required />
                <div class="invalid-feedback">Please enter a purchase discount price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Buying Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="buying_price" id="buying_price" required />
                <div class="invalid-feedback">Please enter a buying price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="purchase_price_duration" id="purchasedurationSelect">
                  <option value="">Select</option>
                  <option value="1" selected>1 Month</option>
                  <option value="3">3 Month</option>
                  <option value="6">6 Month</option>
                  <option value="9">9 Month</option>
                  <option value="12">12 Month</option>
                </select>
                <div class="invalid-feedback">Please choose a date.</div>
              </div>

              <div class="form-group small" id="purchasedateRangeGroup" style="display: none;">
                <label class="form-label">Dates:</label>
                <span id="purchasedateRange"></span>
                <input type="hidden" name="validity_from" id="validity_from" />
                <input type="hidden" name="validity_to" id="validity_to" />
              </div>
            </div>
          </div>



          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveProduct">Save</button>
          </div>
      </div>
      </form>

    </div>

    <!-- <input class="form-control" type="hidden" name="total_amount" />
    <input class="form-control" type="hidden" name="gross_margin" />
    <input class="form-control" type="hidden" name="product_currency" /> -->
  </div>

</div>

<!-- Add New Product -->
<div class="modal fade" id="addpurchase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="padding: 16px;">
      <div class="modal-header">
        <h5 class="modal-title" id="productModalLabel">Add Buying Price</h5>
      </div>
      <div class="modal-body">
        <form id="productPurchaseForm">
          <input type="hidden" name="product_id" id="bp_product_id" />

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Buying Currency<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="buying_currency" id="bp_buying_currency" required>
                  <option value="">-Select Currency-</option>
                  @foreach($currencies as $currency)
                  <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please select a currency.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Gross Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="gross_price" id="bp_gross_price" required />

                <div class="invalid-feedback">Please enter a gross price.</div>
              </div>
            </div>
            <div class="col-md-4" id="purchase_discount_percent">
              <div class="form-group">
                <label class="form-label">Purchase Discount(%)</label>
              </div>
              <div class="form-group">
                <input class="form-control" type="number" name="discount" id="bp_purchase_discount">
                <div class="invalid-feedback">Please enter a purchase discount.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4" id="purchase_discount_price">
              <div class="form-group">
                <label class="form-label">Purchase Discount Amount<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="discount_amount" id="bp_purchase_discount_amount" required />
                <div class="invalid-feedback">Please enter a purchase discount price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Buying Price<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <input class="form-control" type="text" name="buying_price" id="bp_buying_price" required />
                <div class="invalid-feedback">Please enter a buying price.</div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-label">Price Validity Period<span class="text-danger">*</span></label>
              </div>
              <div class="form-group">
                <select class="form-control" name="purchase_price_duration" id="bp_purchasedurationSelect">
                  <option value="">Select</option>
                  <option value="1" selected>1 Month</option>
                  <option value="3">3 Month</option>
                  <option value="6">6 Month</option>
                  <option value="9">9 Month</option>
                  <option value="12">12 Month</option>
                </select>
                <div class="invalid-feedback">Please choose a date.</div>
              </div>

              <div class="form-group small" id="bp_purchasedateRangeGroup" style="display: none;">
                <label class="form-label">Dates:</label>
                <span id="bp_purchasedateRange"></span>
                <input type="hidden" name="validity_from" id="bp_validity_from" />
                <input type="hidden" name="validity_to" id="bp_validity_to" />
              </div>
            </div>
          </div>



          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveProductPurchase">Save</button>
          </div>
      </div>
      </form>

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

  // $(".addIT").click(function() {

  //   ++iter2;
  //   let dropdwn = createDropDown2(iter2);

  //   let newRow = $(`<tr valign="top" id="irow-${iter2}">
  //   <td><select class="form-control product-items" name="item_id[]">
  //       <option value="">--Model --</option>
  //   </select></td>
  //   <td><textarea class="form-control" name="item_name[]" placeholder="Item"></textarea></td>
  //   <td><input type="text" class="form-control" name="partno[]" placeholder="Part No" /></td>
  //   <td> <input type="number" class="form-control" name="quantity[]" placeholder="Quantity" /></td>
  //   <td><input type="text" class="form-control" name="yes_number[]" placeholder="YesNo." /></td>
  //   <td><input type="number" class="form-control purchase_amount" name="total_amount[]" step="any" placeholder="Total Amount"/></td>
  //   <td><input type="text" class="form-control datepick" name="expected_delivery[]" placeholder="Expected Delivery" /></td>
  //   <td width="10%">
  //   <select class="form-control" name="status[]" id="status">
  //   <option value="0">Not Delivered</option>
  //   <option value="1">Delivered</option>
  //   </select>
  //   </td>
  //   <td><textarea rows="2" name="item_remark[]" placeholder="Remarks" class="form-control"></textarea></td>
  //   <td><a href="javascript:void(0);" class="remIT" title="DELETE ROW" data-id=""><i class="fa fa-trash"></i></a></td></tr>`);

  //   $("#itemcustomFields").append(newRow);
  //   newRow.find('.datepick').daterangepicker({
  //     singleDatePicker: true,
  //     autoUpdateInput: false,
  //     cancelClass: "btn-secondary",
  //     locale: {
  //       format: 'YYYY-MM-DD'
  //     }
  //   });

  //   // Set the date when a date is selected
  //   newRow.find('.datepick').on('apply.daterangepicker', function(ev, picker) {
  //     $(this).val(picker.startDate.format('YYYY-MM-DD'));
  //   });
  //   newRow.find('input[name="total_amount[]"]').on('input', calculateTotalAmount);
  // });

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
        calculateTotalAmount();
      }
    });
  });

  // function createDropDown2(i) {
  //   let ddlPStatus = `<select class=" form-control" name="item[${i}][status]">
  // <option value="0">Not Delivered</option>
  // <option value ="1">Delivered</option></select>`;
  //   return ddlPStatus;
  // }
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

  function calculateTotalAmount() {
    let total = 0;
    const totalAmountFields = document.querySelectorAll('input[name="total_amount[]"]');
    totalAmountFields.forEach(function(field) {
      total += parseFloat(field.value) || 0;
    });

    document.getElementById('total_buying_price').value = total.toFixed(2);
  }

  // const totalAmountFields = document.querySelectorAll('input[name="total_amount[]"]');
  // totalAmountFields.forEach(function(field) {
  //   alert(11)
  //   field.addEventListener('input', calculateTotalAmount);
  // });
  // calculateTotalAmount();

  var productData = [];

  function searchProducts() {
    let selSuppliers = $("#supplier").val();

    if (!selSuppliers) return false;

    let productDropdown = $("#product_item_search");
    productDropdown.html("");

    $.ajax({
      url: '/fetch-product-models',
      method: 'GET',
      data: {
        // category: selectedCategory,
        supplier_id: selSuppliers
      },
      success: function(resp) {
        let data = resp;
        productData = [];

        if (data) {
          // for dropdown listing
          productDropdown.append('<option value="">-Select Products-</option>');
          for (let brand in data) {
            let products = data[brand];
            let optgroup = $('<optgroup label="' + brand + '">');

            for (let pi in products) {
              let item = products[pi];

              let selopt = item.title;
              if (item.modelno) selopt += ' / ' + item.modelno + '';
              if (item.part_number) selopt += ' / ' + item.part_number + '';

              optgroup.append('<option value="' + item.id + '">' + selopt + '</option>');

              productData[item.id] = item;
            }

            optgroup.append('</optgroup>');
            productDropdown.append(optgroup);
          }
          productDropdown.val('').trigger('change');

        } else {
          productDropdown.append('<option value="">No models available</option>');
        }
      },
      error: function(error) {
        console.error('Error fetching product models:', error);
      }
    });
  }

  function addRowWithBuyingPrice(productid, productsel) {
    let newRow = '',
      buying_price = 0,
      unit_price = 0,
      qty = 1,
      title = '',
      part_number = '';

    if (!productid && !productsel['title']) return false;

    part_number = productsel['part_number'];
    title = productsel['title'];


    let rowExists = $("#irow-" + productid);
    if (rowExists) {
      rowExists.remove();
    }

    $.ajax({
      url: '/buyingprice',
      method: 'GET',
      data: {
        // category: selectedCategory,
        product_id: productid
      },
      success: function(resp) {
        let data = resp.data;

        if (data) {
          unit_price = data;
          buying_price = unit_price * qty;
        }
        let addBtn = '';
        if (buying_price == 0)
          addBtn = `<a href="javascript:void(0);" class="b-price-add btn btn-primary btn-sm" data-pid="${productid}" data-bs-toggle="modal" data-bs-target="#addpurchase"> <i class="fas fa-plus"></i> ADD</a>`;

        newRow += '<tr id="irow-' + productid + '">';
        newRow += '<td width="15%"><textarea class="form-control" name="item_name[]" placeholder="Item">' + title + '</textarea></td>';
        newRow += '<td><input type="text" class="form-control" name="partno[]" placeholder="Part No" value="' + part_number + '" /></td>';
        newRow += `<td><input type="text" class="form-control" placeholder="Unit Price" name="unit_price[]" value="${unit_price}" readonly/></td>`;
        newRow += '<td><input type="number" class="form-control quantity" name="quantity[]" min="1" value="' + qty + '"/></td>';
        newRow += '<td><input type="text" class="form-control" name="yes_number[]" placeholder="YesNo." /></td>';
        newRow += '<td><input type="text" class="form-control linediscount" name="discount[]" placeholder="Discount %" /></td>';
        newRow += '<td><input type="text" class="form-control purchase_amount" readonly name="total_amount[]" placeholder="Total Amount" value="' + buying_price + '"/>' + addBtn + '</td>';
        newRow += '<td><input type="text" class="form-control datepick" name="expected_delivery[]" placeholder="Expected Delivery" /></td>';
        newRow += `<td><select class="form-control" name="status[]" id="status">
          <option value="0"> Not Delivered </option>
          <option value="1"> Delivered </option>
          </select></td>`;
        newRow += '<td><textarea rows="2" name="item_remark[]" placeholder="Remarks" class="form-control"></textarea></td>';
        newRow += `<td><input type="hidden" name="item_id[]" value="${productid}"/>        
      <a href="javascript:void(0);" class="remIT" title="DELETE ROW" data-id="drow-${productid}"><i class="fa fa-trash"></i></a>
      </td>`;
        newRow += '</tr>';

        $("#itemcustomFields").find('tbody').append(newRow);

        calculateTotalAmount();
      },
      error: function(error) {
        console.error('Error fetching product models:', error);
      }
    });
  }

  function createNewProduct(isValid) {
    let asset_url = `{{ env('APP_URL') }}`;
    if (isValid) {
      let formData = new FormData($('#productForm')[0]);

      formData.append('title', $('input[name=title]').val());
      formData.append('division_id', $('select[name=division_id]').val());
      formData.append('brand_id', $('select[name=brand_id]').val());
      formData.append('model_no', $('input[name=model_no]').val());
      formData.append('description', $('textarea[name=description]').val());
      formData.append('product_type', $('input[name=product_type]').val());
      formData.append('payment_term', $('select[name=product_payment_term]').val());
      formData.append('currency', $('select[name=currency]').val());
      formData.append('currency_rate', $('input[name=currency_rate]').val());
      formData.append('manager_id', $('select[name=manager_id]').val());
      formData.append('selling_price', $('input[name=selling_price]').val());
      formData.append('margin_percentage', $('input[name=margin_percentage]').val());
      formData.append('margin_price', $('input[name=margin_price]').val());
      formData.append('freeze_discount', $('input[name=freeze_discount]').is(':checked') ? 1 : 0);
      formData.append('image', $('input[name=image]')[0].files[0]);
      formData.append('start_date', $('input[name=start_date]').val());
      formData.append('end_date', $('input[name=end_date]').val());
      formData.append('product_category', $('select[name=product_category]').val());
      formData.append('status', $('input[name=status]').val());
      formData.append('allowed_discount', $('input[name=allowed_discount]').val());
      formData.append('part_number', $('input[name=part_number]').val());

      formData.append('buying_currency', $('select[name=buying_currency]').val());
      formData.append('gross_price', $('input[name=gross_price]').val());
      formData.append('discount', $('input[name=discount]').val());
      formData.append('discount_amount', $('input[name=discount_amount]').val());
      formData.append('buying_price', $('input[name=buying_price]').val());
      formData.append('validity_from', $('input[name=validity_from]').val());
      formData.append('validity_from', $('input[name=validity_from]').val());
      //
      $.ajax({
        url: "{{ route('products.ajaxsave') }}",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.data) {
            let newProductData = response.data;

            let productsel = [],
              title = '';

            let selProductId = newProductData.id;

            title = newProductData.title;
            if (newProductData.modelno) title += ' / ' + newProductData.modelno + '';
            if (newProductData.part_number) title += ' / ' + newProductData.part_number + '';

            productsel['title'] = title;
            productsel['part_number'] = newProductData.part_number;

            addRowWithBuyingPrice(selProductId, productsel);

            // reset modal
            let modal = $("#myModal");
            modal.find('input[type=text], input[type=number], textarea').val('');
            modal.find('select').val('').trigger('change');

            modal.modal('hide');

          }

        },
      });
    }
  }

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  function updateMarginPercentage() {
    var sellingPriceStr = $('#sellingPrice').val();
    var marginPriceStr = $('#marginPrice').val();

    if (sellingPriceStr.trim() === '') {
      return;
    }

    var basePrice = parseFloat(sellingPriceStr.replace(/,/g, ''));
    var marginPrice = parseFloat(marginPriceStr.replace(/,/g, ''));

    console.log('Base Price:', basePrice);
    console.log('Margin Price:', marginPrice);

    var marginPercentageInput = $('#marginPercentage');

    if (!isNaN(basePrice) && !isNaN(marginPrice) && basePrice !== 0) {
      if (basePrice <= marginPrice) {
        alert('Selling price should be greater than margin price.');
        marginPercentageInput.val('');
      } else {
        var calculatedMarginPercentage = (marginPrice / basePrice) * 100;
        var formattedMarginPercentage = calculatedMarginPercentage.toFixed(2);
        marginPercentageInput.val(formattedMarginPercentage);
      }
    } else {
      marginPercentageInput.val('');
    }
  }

  function updateMarginPrice() {
    var sellingPriceStr = $('#sellingPrice').val(); // Get the value using jQuery
    var marginPercentageStr = $('#marginPercentage').val(); // Get the value using jQuery

    var basePrice = parseFloat(sellingPriceStr.replace(/,/g, ''));
    var marginPercentage = parseFloat(marginPercentageStr.replace(/,/g, ''));

    var marginPriceInput = $('#marginPrice');

    if (!isNaN(basePrice) && !isNaN(marginPercentage)) {

      var calculatedMarginPrice = basePrice * (marginPercentage / 100);
      var formattedMarginPrice = numberWithCommas(calculatedMarginPrice.toFixed(2));

      marginPriceInput.val(formattedMarginPrice);
    }
  }


  function setPriceValidity(selectedValue) {
    var currentDate = new Date();
    var startDateInput = document.getElementById('startDateInput');
    var endDateInput = document.getElementById('endDateInput');
    if (selectedValue !== "") {
      var startDate = new Date(currentDate);
      var endDate = new Date(currentDate);
      endDate.setMonth(currentDate.getMonth() + parseInt(selectedValue));
      var startDateFormatted = startDate.toISOString().split('T')[0];
      var endDateFormatted = endDate.toISOString().split('T')[0];
      startDateInput.value = startDateFormatted;
      endDateInput.value = endDateFormatted;

      var dateRange = startDateFormatted + ' to ' + endDateFormatted;
      document.getElementById('dateRange').innerText = dateRange;
      dateRangeGroup.style.display = 'block';
    } else {
      startDateInput.value = '';
      endDateInput.value = '';
      document.getElementById('dateRange').innerText = "";
      dateRangeGroup.style.display = 'none';
    }
  }


  $(document).ready(function() {

    jQuery('#supplier').on('change', function() {
      let selSuppliers = $(this).val();

      if (selSuppliers != null && selSuppliers != '') {
        $("#product_item_search").removeAttr('disabled');
        $("#add-new-product").removeClass("d-none");
        // enable
        $("#itemcustomFields").find('tbody').html('');
        calculateTotalAmount();

        searchProducts();

      } else {
        $("#product_item_search").attr('disabled', 'disabled');
        $("#add-new-product").addClass("d-none");
        // disable
      }
    });

    jQuery('#product_item_search').on('change', function(e) {
      e.preventDefault();
      let productid = $(this).val();
      if (!productid) return false;

      let title = '',
        productsel = {};

      if (productData.hasOwnProperty(productid)) {
        title = productData[productid].title;
        if (productData[productid].modelno) title += ' / ' + productData[productid].modelno + '';
        if (productData[productid].part_number) title += ' / ' + productData[productid].part_number + '';

        productsel['title'] = title;
        productsel['part_number'] = productData[productid].part_number;

      }

      addRowWithBuyingPrice(productid, productsel);


    });

    $('#saveProduct').on('click', function(e) {
      e.preventDefault();
      var isValid = true;

      // Remove the is-invalid class and hide the invalid feedback for all elements
      $('.is-invalid').removeClass('is-invalid');
      $('.invalid-feedback').hide();

      // Define the required fields by their IDs
      var requiredFields = [
        '#titleInput',
        '#divisionInput',
        '#brandInput',
        '#sellingPrice',
        '#marginPercentage',
        '#marginPrice',
        '#productcategoryInput',
        '#durationSelect',
        '#currencyInput',
        '#managerInput',
        '#buying_currency',
        '#gross_price',
        '#purchase_discount',
        '#purchase_discount_amount',
        '#buying_price'
      ];


      // Validate each field based on its value
      requiredFields.forEach(function(field) {
        var value = $(field).val().trim();

        // Check if the field value is empty
        if (value === '') {
          $(field).addClass('is-invalid').next('.invalid-feedback').show();
          isValid = false;
        }
      });

      // If all fields pass validation, submit the form
      if (isValid) {
        createNewProduct(isValid);

      }
    });

    // FOR ADD NEW PRODUCT
    document.getElementById('sellingPrice').addEventListener('input', updateMarginPrice);
    document.getElementById('marginPercentage').addEventListener('input', updateMarginPrice);
    $('#sellingPrice, #marginPrice').on('input', function() {
      updateMarginPercentage();
    });

    setPriceValidity(1);

    // SET For Buying Price
    document.getElementById('gross_price').addEventListener('input', updateBuyingPrice);
    document.getElementById('purchase_discount').addEventListener('input', updateBuyingPrice);
    document.getElementById('purchase_discount_amount').addEventListener('input', updateBuyingPriceWithAmount);

    $("#purchasedurationSelect").on("change", function(e) {
      let selectedValue = $(this).val();
      setPurchaseDateRange(selectedValue);
    });

    // default purchase date range
    setPurchaseDateRange(1);

    $('#purchase_discount').on('input', function() {
      var gross_price = $('#gross_price').val();
      if (gross_price.trim() === '') {
        alert('Please enter the gross price first.');
        $(this).val(''); // Clear the margin price input
      }
    });

    $('#purchase_discount_amount').on('input', function() {
      var gross_price = $('#gross_price').val();
      if (gross_price.trim() === '') {
        alert('Please enter the gross price first.');
        $(this).val(''); // Clear the margin price input
      }
    });

  });

  function updateBuyingPriceWithAmount() {
    let gross_price = $('#gross_price').val(); // Get the value using jQuery
    let purchase_discount = $('#purchase_discount_amount').val(); // Get the value using jQuery

    let basePrice = parseFloat(gross_price.replace(/,/g, ''));
    let dAmount = parseFloat(purchase_discount.replace(/,/g, ''));

    let buyingPriceInput = $('#buying_price');

    if (!isNaN(basePrice) && !isNaN(dAmount)) {

      let calculatedDPrice = basePrice - dAmount;
      let dpercent = (dAmount / basePrice) * 100;
      dpercent = parseFloat(dpercent).toFixed(2);
      $('#purchase_discount').val(dpercent);

      let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

      buyingPriceInput.val(formattedMarginPrice);
    }
  }

  function updateBuyingPrice() {

    let gross_price = $('#gross_price').val(); // Get the value using jQuery
    let purchase_discount = $('#purchase_discount').val(); // Get the value using jQuery

    let basePrice = parseFloat(gross_price.replace(/,/g, ''));
    let dPercentage = parseFloat(purchase_discount.replace(/,/g, ''));

    let buyingPriceInput = $('#buying_price');

    if (!isNaN(basePrice) && !isNaN(dPercentage)) {

      let calculatedDPrice = basePrice * (dPercentage / 100);
      $('#purchase_discount_amount').val(calculatedDPrice);
      calculatedDPrice = basePrice - calculatedDPrice;
      let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

      buyingPriceInput.val(formattedMarginPrice);
    } else if (!isNaN(basePrice)) {
      let formattedMarginPrice = numberWithCommas(basePrice.toFixed(2));

      buyingPriceInput.val(formattedMarginPrice);
    }
  }

  function setPurchaseDateRange(selectedValue) {
    let currentDate = new Date();
    let validity_from = document.getElementById('validity_from');
    let validity_to = document.getElementById('validity_to');
    let dateRangeGroup = document.getElementById('purchasedateRangeGroup');

    if (selectedValue !== "") {
      let startDate = new Date(currentDate);
      let endDate = new Date(currentDate);
      endDate.setMonth(currentDate.getMonth() + parseInt(selectedValue));

      let startDateFormatted = startDate.toISOString().split('T')[0];
      let endDateFormatted = endDate.toISOString().split('T')[0];

      validity_from.value = startDateFormatted;
      validity_to.value = endDateFormatted;

      let dateRange = `${startDateFormatted} to ${endDateFormatted}`;

      document.getElementById('purchasedateRange').innerText = dateRange;
      dateRangeGroup.style.display = 'block';

    } else {
      validity_from.value = '';
      validity_to.value = '';
      document.getElementById('purchasedateRange').innerText = "";
      dateRangeGroup.style.display = 'none';
    }
  }

  // update quantity
  $(document).on('input change', '.quantity', function(e) {
    let row = $(this).closest('tr');

    let quantity = row.find('input[name="quantity[]"]').val() || 0;
    let unitprice = row.find('input[name="unit_price[]"]').val() || 0;
    let discount = row.find('input[name="discount[]"]').val() || 0;

    let linetotal = parseFloat(unitprice * quantity).toFixed(2);
    let discountAmt = 0;
    if (discount > 0) {
      discountAmt = linetotal * (discount / 100);
    }
    if (discountAmt > 0) {
      linetotal = parseFloat(linetotal - discountAmt).toFixed(2);
    }

    row.find('input[name="total_amount[]"]').val(linetotal);

    calculateTotalAmount();

  });

  $(document).on('input change', '.linediscount', function(e) {
    let row = $(this).closest('tr');

    let quantity = row.find('input[name="quantity[]"]').val() || 0;
    let unitprice = row.find('input[name="unit_price[]"]').val() || 0;
    let discount = row.find('input[name="discount[]"]').val() || 0;

    let linetotal = parseFloat(unitprice * quantity).toFixed(2);
    let discountAmt = 0;
    if (discount > 0) {
      discountAmt = linetotal * (discount / 100);
    }
    if (discountAmt > 0) {
      linetotal = parseFloat(linetotal - discountAmt).toFixed(2);
    }

    row.find('input[name="total_amount[]"]').val(linetotal);

    calculateTotalAmount();

  });






  /*******************************
   * Buying price Modal
   * **************************/

  $('#bp_gross_price, #bp_purchase_discount').on('input', function() {
    updateBuyingPrice_2();
  });
  $('#bp_purchase_discount_amount').on('input', function() {
    updateBuyingPriceWithAmount_2();
  });

  $("#bp_purchasedurationSelect").on("change", function(e) {
    let selectedValue = $(this).val();
    setPurchaseDateRange_2(selectedValue);
  });

  // default purchase date range
  setPurchaseDateRange_2(1);

  $('#bp_purchase_discount').on('input', function() {
    var gross_price = $('#bp_gross_price').val();
    if (gross_price.trim() === '') {
      alert('Please enter the gross price first.');
      $(this).val(''); // Clear the margin price input
    }
  });

  $('#bp_purchase_discount_amount').on('input', function() {
    var gross_price = $('#bp_gross_price').val();
    if (gross_price.trim() === '') {
      alert('Please enter the gross price first.');
      $(this).val(''); // Clear the margin price input
    }
  });

  function updateBuyingPriceWithAmount_2() {
    let gross_price = $('#bp_gross_price').val(); // Get the value using jQuery
    let purchase_discount = $('#bp_purchase_discount_amount').val(); // Get the value using jQuery

    let basePrice = parseFloat(gross_price.replace(/,/g, ''));
    let dAmount = parseFloat(purchase_discount.replace(/,/g, ''));

    let buyingPriceInput = $('#bp_buying_price');

    if (!isNaN(basePrice) && !isNaN(dAmount)) {

      let calculatedDPrice = basePrice - dAmount;
      let dpercent = (dAmount / basePrice) * 100;
      dpercent = parseFloat(dpercent).toFixed(2);
      $('#bp_purchase_discount').val(dpercent);

      let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

      buyingPriceInput.val(formattedMarginPrice);
    }
  }

  function updateBuyingPrice_2() {

    let gross_price = $('#bp_gross_price').val(); // Get the value using jQuery
    let purchase_discount = $('#bp_purchase_discount').val(); // Get the value using jQuery

    let basePrice = parseFloat(gross_price.replace(/,/g, ''));
    let dPercentage = parseFloat(purchase_discount.replace(/,/g, ''));

    let buyingPriceInput = $('#bp_buying_price');

    if (!isNaN(basePrice) && !isNaN(dPercentage)) {

      let calculatedDPrice = basePrice * (dPercentage / 100);
      $('#bp_purchase_discount_amount').val(calculatedDPrice);
      calculatedDPrice = basePrice - calculatedDPrice;
      let formattedMarginPrice = numberWithCommas(calculatedDPrice.toFixed(2));

      buyingPriceInput.val(formattedMarginPrice);
    } else if (!isNaN(basePrice)) {
      let formattedMarginPrice = numberWithCommas(basePrice.toFixed(2));

      buyingPriceInput.val(formattedMarginPrice);
    }
  }

  function setPurchaseDateRange_2(selectedValue) {
    let currentDate = new Date();
    let validity_from = document.getElementById('bp_validity_from');
    let validity_to = document.getElementById('bp_validity_to');
    let dateRangeGroup = document.getElementById('bp_purchasedateRangeGroup');

    if (selectedValue !== "") {
      let startDate = new Date(currentDate);
      let endDate = new Date(currentDate);
      endDate.setMonth(currentDate.getMonth() + parseInt(selectedValue));

      let startDateFormatted = startDate.toISOString().split('T')[0];
      let endDateFormatted = endDate.toISOString().split('T')[0];

      validity_from.value = startDateFormatted;
      validity_to.value = endDateFormatted;

      let dateRange = `${startDateFormatted} to ${endDateFormatted}`;

      document.getElementById('bp_purchasedateRange').innerText = dateRange;
      dateRangeGroup.style.display = 'block';

    } else {
      validity_from.value = '';
      validity_to.value = '';
      document.getElementById('bp_purchasedateRange').innerText = "";
      dateRangeGroup.style.display = 'none';
    }
  }

  $('#addpurchase').on('show.bs.modal', function(e) {
    let btn = $(e.relatedTarget);
    let pid = btn.data('pid');
    $(this).find('input[name="product_id"]').val(pid);

    $(this).appendTo("body")

  });

  $('#saveProductPurchase').on('click', function(e) {
    e.preventDefault();
    var isValid = true;
    // Remove the is-invalid class and hide the invalid feedback for all elements
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').hide();

    // Define the required fields by their IDs
    var requiredFields = [
      '#bp_product_id',
      '#bp_buying_currency',
      '#bp_gross_price',
      '#bp_purchase_discount',
      '#bp_purchase_discount_amount',
      '#bp_buying_price'
    ];


    // Validate each field based on its value
    requiredFields.forEach(function(field) {
      var value = $(field).val().trim();

      // Check if the field value is empty
      if (value === '') {
        $(field).addClass('is-invalid').next('.invalid-feedback').show();
        isValid = false;
      }
    });

    // If all fields pass validation, submit the form
    if (isValid) {
      createPurchaseProduct(isValid);
    }

  });

  function convertBuyingPrice(productid) {
    if (!productid) return;

    $.ajax({
      url: '/buyingprice',
      method: 'GET',
      data: {
        product_id: productid
      },
      success: function(resp) {
        let data = resp.data;

        let buying_price = 0,
          unit_price = 0,
          qty = 1;

        if (data) {
          qty = $("#irow-" + productid).find('input[name="quantity[]"]').val();

          unit_price = data;
          buying_price = unit_price * qty;

          $("#irow-" + productid).find('input[name="unit_price[]').val(unit_price);
          $("#irow-" + productid).find(".purchase_amount").val(buying_price);

          $("#irow-" + productid).find(".b-price-add").addClass("d-none");
        }

        calculateTotalAmount();

      },
      error: function(error) {
        console.error('Error fetching product models:', error);
      }
    });
  }

  function createPurchaseProduct(isValid) {
    let asset_url = `{{ env('APP_URL') }}`;
    if (isValid) {
      let formData = new FormData($('#productPurchaseForm')[0]);

      formData.append('product_id', $('#addpurchase').find('input[name=product_id]').val());
      formData.append('buying_currency', $('#addpurchase').find('select[name=buying_currency]').val());
      formData.append('gross_price', $('#addpurchase').find('input[name=gross_price]').val());
      formData.append('discount', $('#addpurchase').find('input[name=discount]').val());
      formData.append('discount_amount', $('#addpurchase').find('input[name=discount_amount]').val());
      formData.append('buying_price', $('#addpurchase').find('input[name=buying_price]').val());
      formData.append('validity_from', $('#addpurchase').find('input[name=validity_from]').val());
      formData.append('validity_from', $('#addpurchase').find('input[name=validity_from]').val());
      //
      $.ajax({
        url: "{{ route('products.savebuyingprice') }}",
        method: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
          if (response.data) {
            let newPurchaseData = response.data;

            convertBuyingPrice(newPurchaseData.product_id); // to AED

            // reset modal
            let modal = $("#addpurchase");
            modal.find('input[type=text], input[type=number], textarea').val('');
            modal.find('select').val('').trigger('change');

            modal.modal('hide');

          }

        },
        error: function(error) {
          console.error('Error fetching product buying price:', error);
        }
      });
    }
  }
</script>

@endpush

@endsection