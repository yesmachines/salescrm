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

    <table id="" class="table nowrap w-100 mb-5">
      <thead>
        <tr>
          <th>S No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Company</th>
          <th>Country</th>
          <th>RefNo.</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @php
        $count = 0;
        @endphp
        @foreach($customers as $i =>$cust)
        @php
        $count++;
        if($count %2 == 0){
          $rowtype = "even";
        }else{
          $rowtype = "odd";
        }
        @endphp
        <tr  class="{{$rowtype}}">
          <td>{{ $i +$customers->firstItem()}}</td>

          <td>
            <div class="media align-items-center">
              <div class="media-head me-2">
                <div class="avatar avatar-xs avatar-rounded">
                  <img src="{{asset('dist/img/avatar1.jpg')}}" alt="user" class="avatar-img">
                </div>
              </div>
              <div class="media-body">
                <span class="d-block text-high-em">{{$cust->fullname}}</span>
              </div>
            </div>
          </td>
          <td class="text-truncate" title="{{$cust->email}}">{{$cust->email}}</td>
          <td>{{$cust->phone}}</td>
          <td><span class="badge badge-soft-violet my-1  me-2">{{$cust->company->company}}</span></td>
          <td>
            {{($cust->company->region_id)? $cust->company->region->state: ''}}
            {{ ($cust->company->region_id && $cust->company->country_id)?", ": ""}}
            {{($cust->company->country_id)? $cust->company->country->name: '--'}}
          </td>
          <td><span class="badge badge-soft-danger my-1  me-2">
            {{$cust->company->reference_no}}</span>
          </td>
          <td>
            <div class="d-flex align-items-center">
              <div class="d-flex">
                <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" href="{{ route('customers.edit', $cust->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span></a>
                <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover del-button" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Delete" href="javascript:void(0);" onclick="event.preventDefault();
                document.getElementById('delete-form-{{ $cust->id }}').submit();"><span class="icon"><span class="feather-icon"><i data-feather="trash"></i></span></span></a>
                {!! Form::open(['method' => 'DELETE','route' => ['customers.destroy', $cust->id],'style'=>'display:none',
                'id' => 'delete-form-'.$cust->id]) !!}
                {!! Form::close() !!}
              </div>

            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div style="padding-top:10px;">
    @if ($customers->hasPages())
    {{ $customers->appends($_GET)->onEachSide(1)->links()  }}
    @else
    <div class="nopagination"></div>
    @endif
  </div>

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
