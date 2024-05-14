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
          <th width="20%">Quote#</th>
          <th width="20%">Company</th>
          <th width="14%">Supplier</th>
          <th width="20%">Amount</th>
          <th width="10%">Margin</th>
          @hasanyrole('divisionmanager|salesmanager')
          @else
          <th width="10%">Assigned</th>
          @endhasanyrole
          <th width="15%">Submit On</th>
          <th width="8%">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($quotations as $quote)
        <tr>
          <td class="text-truncate overflow-hidden"><span style="cursor:pointer;" class="badge badge-info badge-outline" onclick="copyText(this);">{{$quote->reference_no}}</span> </td>
          <td class="text-truncate overflow-hidden" title="{{$quote->company->company}}">
            {{ Str::limit($quote->company->company, 50) }}
          </td>
          <td class="text-truncate overflow-hidden">
            <span>
              @if($quote->supplier_id >0)
              {{$quote->supplier->brand}}
              @else
              {{$quote->suppliername}}
              @endif
              @if($quote->product_models)
              / {{ Str::limit(($quote->product_models? $quote->product_models: ''), 20) }}
              @endif
            </span>
          </td>
          <td class="text-truncate"><span class="badge badge-pink">{{number_format($quote->total_amount, 2) }}
              {{$quote->preferred_currency? $quote->preferred_currency: 'AED'}}
            </span>
          </td>
          <td>
            {{number_format($quote->gross_margin, 2)}} {{$quote->preferred_currency? $quote->preferred_currency: 'AED'}}
          </td>
          @hasanyrole('divisionmanager|salesmanager')
          @else
          <td class="text-truncate overflow-hidden">{{$quote->assigned->user->name}}</td>
          @endhasanyrole
          <td class="text-truncate overflow-hidden">{{date("d-m-Y", strtotime($quote->submitted_date))}}</td>
          <td>
            <!-- <div class="d-flex align-items-center">
                    <div class="d-flex">
                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" href="{{ route('quotations.edit', $quote->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span></a>
                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Preview" href="{{ route('quotations.show', $quote->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="eye"></i></span></span></a>
                    </div>
                </div> -->
            <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover dropdown-toggle no-caret" href="#" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <span class="icon"><span class="feather-icon"><i data-feather="more-horizontal"></i></span></span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="{{ route('quotations.edit', $quote->id) }}"><span class="feather-icon dropdown-icon"><i data-feather="edit"></i></span><span>Edit</span></a>
              <a class="dropdown-item" href="{{ route('quotations.show', $quote->id) }}"><span class="feather-icon dropdown-icon"><i data-feather="eye"></i></span><span>View</span></a>
            </div>

          </td>
        </tr>
        @endforeach
      </tbody>
    </table>

    {{ $quotations->links() }}
  </div>
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