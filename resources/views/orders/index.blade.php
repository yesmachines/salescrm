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
                <h1>Pending Orders</h1>
              </a>
            </div>

          </div>
          <div class="contact-options-wrap">

            <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover no-caret d-sm-inline-block d-none" href="{{route('countries.index')}}" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Refresh"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>
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
              <div class="row">
                <div class="col-sm-9">
                </div>

              </div>
              @livewire('pending-orders')
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel">Manager Approval</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="statusForm" method="POST">
          @csrf
          <div class="mb-3">
            <label for="newStatus" class="form-label">Status</label>
            <select class="form-select" name="status">
              <option value="1" selected>Approved</option>
              <option value="0">Not Approved</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Status</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>

function copyText(e) {

  let copyText = e.innerHTML;

  navigator.clipboard.writeText(copyText).then(() => {

    Swal.fire(
      'Copied!',
      "You are copied the order no: " + copyText,
      'info'
    );
  })
  /* Resolved - text copied to clipboard successfully */

}

</script>
<script>
$(document).ready(function() {
  $('.dropdown-item').on('click', function() {
    var orderId = $(this).data('order-id');
    var currentStatus = $(this).data('current-status');
    $('#statusForm').data('order-id', orderId);

  });
  $('#statusForm').on('submit', function(e) {
    e.preventDefault();

    var orderId = $('#statusForm').data('order-id');
    var formData = $(this).serialize();

    $.ajax({
      url: '{{ route('orders.manager-approval', ':orderId') }}'.replace(':orderId', orderId),
      type: 'POST',
      data: formData,
      success: function(response) {

        location.reload();
        $('#statusModal').modal('hide');
      },
      error: function(xhr, status, error) {
        alert('Error updating status: ' + error);
      }
    });
  });
});
$('#statusModal').on('show.bs.modal', function () {
  $('#newStatus').val('1');
});
</script>

@endsection
