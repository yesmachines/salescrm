<div>
  <div class="row">
    <label for="perPage" class="mr-2">Per Page:</label>
    <div class="col-md-2 mb-2">
      <select wire:model="perPage" id="perPage" class="form-control">
        <option value="10">10</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="200">200</option>
        <option value="500">500</option>
        <!-- Add more options as needed -->
      </select>
    </div>
    <div class="col-md-4 mb-4">
      <input type="text" placeholder="Search by company, customer, os number, po number .." class="form-control" wire:model.debounce.500ms="search" wire:change="updateSearch($event.target.value)">
    </div>
    <div class="col-md-3 mb-3">
      <select name="dateOptions" id="dateOptions" class="form-select" wire:model="selectedDateOption" wire:change="updateDateOptions">
        <option value="">-Select OS Date-</option>
        <option value="all">All</option>
        <option value="today">Today</option>
        <option value="yesterday">Yesterday</option>
        <option value="month">Current Month</option>
        <option value="1_month">Last Month</option>
        <option value="3_months">Last 3 Months</option>
        <option value="6_months">Last 6 Months</option>
        <option value="custom">Select a Date</option>
      </select>
    </div>
    <div class="col-md-3 mb-3">
      <div wire:ignore>
        <input type="text" class="date input-box form-control" id="dateRangePicker" placeholder="Select Date Range" style="display: none;">
      </div>
    </div>
  </div>

  <div style="overflow-x: auto;">
    <table id="" class="table nowrap w-100 mb-5">
      <thead>
        <tr>
          <th width="10%">Order#</th>
          <th width="10%">OS Date</th>
          <th width="20%">Company</th>
          <th width="15%">Supplier</th>
          <th width="10%">Po No</th>
          <th width="10%">Po Date</th>
          <th width="10%">Amount (AED)</th>
          <th width="10%">Status</th>
          <th width="5%">Action</th>
        </tr>
      </thead>
      <tbody>
        @php
        $count = 0;
        @endphp
        @foreach($orders as $i =>$order)
        @php
        $count++;
        if($count %2 == 0){
        $rowtype = "even";
        }else{
        $rowtype = "odd";
        }
        @endphp
        <tr class="{{$rowtype}}">
          <td class="text-truncate overflow-hidden">
            <span style="cursor:pointer;" class="badge badge-primary badge-outline" onclick="copyText(this);">{{$order->os_number}}</span>
            <a href="{{route('orders.download', $order->id)}}" title="Download OS"><i class="fa fa-download" aria-hidden="true"></i></a>
          </td>
          <td class="text-truncate overflow-hidden" title="{{$order->os_date }}">{{$order->os_date }}</td>
          <td class="text-truncate overflow-hidden" title="{{$order->company->company}}">{{$order->company->company}}</td>
          <td class="text-truncate overflow-hidden">
            @foreach($order->orderSupplier as $sup)
            {{$sup->supplier->brand}}
            @endforeach
          </td>
          <td class="text-truncate overflow-hidden" title="{{$order->po_number }}">{{$order->po_number }}</td>
          <td class="text-truncate overflow-hidden" title="{{$order->po_date }}">{{$order->po_date }}</td>
          <td class="text-truncate overflow-hidden" title="{{$order->selling_price }}">{{$order->selling_price }}</td>
          <td class="text-truncate overflow-hidden"><span class="badge badge-soft-secondary my-1  me-2">
              {{$order->status }}
            </span>
          </td>
          <td>
            <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover dropdown-toggle no-caret" href="#" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <span class="icon"><span class="feather-icon"><i data-feather="more-horizontal"></i></span></span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{ route('orders.show', $order->id) }}"><span class="feather-icon dropdown-icon"><i data-feather="eye"></i></span><span>View</span></a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @if ($orders->hasPages())
  {{ $orders->appends($_GET)->onEachSide(1)->links()  }}
  @else
  <div class="nopagination"></div>
  @endif
  <script>
    document.addEventListener("livewire:load", function() {
      const dateOptions = document.getElementById("dateOptions");
      const dateRangeInput = document.getElementById("dateRangePicker");
      let selectedOption;

      dateOptions.addEventListener("change", (event) => {
        selectedOption = event.target.value;

        if (selectedOption === "custom") {
          dateRangeInput.style.display = "block";

          flatpickr("#dateRangePicker", {
            mode: "range",
            dateFormat: "Y-m-d",
            onClose: function(selectedDates, dateStr, instance) {
              @this.emit('dateRangePicker', {
                startDate: selectedDates[0] ? selectedDates[0].toISOString() : null,
                endDate: selectedDates[1] ? selectedDates[1].toISOString() : null,
              });
            },
          });

          setTimeout(() => {
            dateRangeInput.focus();
          }, 200);
        } else {
          dateRangeInput.style.display = "none";
        }
      });
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initial icons replacement
      feather.replace();

      // Listen for Livewire update and load events
      document.addEventListener('livewire:update', function() {
        feather.replace();
      });

      Livewire.hook('message.processed', (message, component) => {
        feather.replace();
      });
    });
  </script>
</div>