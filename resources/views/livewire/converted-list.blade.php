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
      </select>
    </div>
    <div class="col-md-4 mb-4">
      <input type="text" placeholder="Search" class="form-control" wire:model.debounce.500ms="search" wire:change="updateSearch($event.target.value)">
    </div>
    <div class="col-md-3 mb-3">
      <select name="dateOptions" id="dateOptions" class="form-select" wire:model="selectedDateOption" wire:change="updateDateOptions">
        <option>Select Date</option>
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
    <table id="" class="table nowrap w-100 mb-5" style="table-layout: fixed; width: 100%;">
      <thead>
        <tr>
          <th style="display: none;"><span class="form-check mb-0">
            <input type="checkbox" class="form-check-input check-select-all" id="customCheck1">
            <label class="form-check-label" for="customCheck1"></label>
          </span></th>
          <th width="25%">Company</th>
          <th width="10%">Phone</th>
          <th width="20%">Details</th>
          <th width="10%">Status</th>
          @hasanyrole('divisionmanager|salesmanager')
          @else
          <th width="10%">Assigned</th>
          @endhasanyrole
          <th width="12%">Date On</th>
          <th width="7%">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($leads as $lead)
        <tr>
          <td style="display: none;">
            <div class="d-flex align-items-center">
              <span class="contact-star marked"><span class="feather-icon"><i data-feather="star"></i></span></span>
            </div>
          </td>
          <td class="text-truncate overflow-hidden" title="{{$lead->company->company}}">
            {{$lead->company->company}}
          </td>
          <td class="text-truncate overflow-hidden" title="{{$lead->customer->phone}}"><span title="{{$lead->customer->phone}}">{{$lead->customer->phone}}</span></td>
          <td class="text-truncate overflow-hidden" title="{{$lead->details}}">{{ Str::limit($lead->details) }}</td>
          <td class="text-truncate overflow-hidden" title="{{$lead->lead_type}}"><span class="badge badge-soft-violet my-1  me-2">{{$lead->lead_type}}</span></td>
          @hasanyrole('divisionmanager|salesmanager')
          @else
          <td class="text-truncate overflow-hidden" title="{{$lead->assigned->user->name}}">{{$lead->assigned->user->name}}</td>
          @endhasanyrole
          <td class="text-truncate overflow-hidden" title="{{$lead->enquiry_date}}">{{$lead->enquiry_date}}</td>
          <td>
            <div class="d-flex align-items-center">
              <div class="d-flex">
                <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="View" href="{{ route('leads.show', $lead->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="eye"></i></span></span></a>
              </div>

            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{ $leads->links() }}


  <script>
  document.addEventListener("livewire:load", function () {
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
          onClose: function (selectedDates, dateStr, instance) {
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
  document.addEventListener('DOMContentLoaded', function () {
    // Initial icons replacement
    feather.replace();

    // Listen for Livewire update and load events
    document.addEventListener('livewire:update', function () {
      feather.replace();
    });

    Livewire.hook('message.processed', (message, component) => {
      feather.replace();
    });
  });
  </script>
</div>
