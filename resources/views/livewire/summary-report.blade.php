<div>
  <div class="row">
    <label for="perPage" class="mr-2">Per Page:</label>
    <div class="col-md-1 mb-1">
      <select wire:model="perPage" id="perPage" class="form-control">
        <option value="10">10</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="200">200</option>
        <option value="500">500</option>
      </select>
    </div>
    <div class="col-md-3 mb-3">
      <input type="text" placeholder="Search" class="form-control" wire:model.debounce.500ms="search" wire:change="updateSearch($event.target.value)">
    </div>
    <div class="col-md-2 mb-2">
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
    <div class="col-md-2 mb-2">
      <div wire:ignore>
        <input type="text" class="date input-box form-control" id="dateRangePicker" placeholder="Select Date Range" style="display: none;">
      </div>
    </div>
    <div class="col-md-2 mb-2">
      <button type="button" class="btn btn-primary" wire:click="downloadSummaryReport">Download</button>
    </div>

  </div>
  <div>
    <table id="" class="table normal w-100 mb-5">
      <thead>
      <tr>
          <th style="min-width: 20px;">Sl.No</th>
          <th style="width: 5%;">Summary Number #</th>
          <th style="width: 15%;">Received Date</th>
          <th style="width: 5%;">EMP</th>
          <th style="width: 10%;">Supplier/Brand</th>
          <th style="width: 20%;">Material Status</th>
          <th style="width: 20%;">Material</th>
          <th style="width: 10%;">Client</th>
          <th style="width: 10%;">Country</th>
          <th style="width: 15%;">Product</th>
          <th style="width: 10%;">VAT Status</th>
          <th style="width: 10%;">VAT Amount</th>
          <th style="width: 10%;">Total Selling Price</th>
          <th style="width: 10%;">Total Buying Price</th>
          <th style="width: 10%;">Margin</th>
      </tr>
  </thead>

      <tbody>
        @php
        $count = 0;
        @endphp
        @foreach($summaryNumbers as $index => $value)

        @php

        $count++;
        if($count %2 == 0){
          $rowtype = "even";
        }else{
          $rowtype = "odd";
        }
        @endphp


        <tr class="{{$rowtype}}">
          <td>
            {{$index + $summaryNumbers->firstItem()}}
          </td>
          <td>{{ $value->os_number ?? 'N/A' }}</td>
          <td>{{ $value->po_received ?? 'N/A' }}</td>
          <td>{{ optional($value->assigned)->user->name ?? 'N/A' }}</td>
          <td>{{ $value->supplier_brands ?? $value->stock_supplier ?? 'N/A'}}</td>
          <td>
            @if($value->material_status == 'is_stock')
            In Stock
            @elseif($value->material_status == 'out_stock')
            Out of Stock
            @else
            Stock OS
            @endif
          </td>
          <td>{{ $value->product_categories ?? 'N/A' }}</td>
          <td>{{ optional($value->company)->company ?? 'N/A' }}</td>
          <td>{{ optional($value->company)->country->name ?? 'N/A' }}</td>

          <td>{{ $value->product_names ?? $value->stock_product ?? 'N/A' }}</td>

          <td>
            @if($value->quotation && $value->quotation->is_vat == 1)
            Include
            @elseif($value->quotation && $value->quotation->is_vat == 0)
            Exclude
            @else
            N/A
            @endif
          </td>
          <td>{{ optional($value->quotation)->vat_amount ?? 'N/A' }}</td>

          <td>{{ $value->selling_price ?? '' }}</td>
          <td>{{ $value->buying_price ?? '' }}</td>
          <td>{{ $value->projected_margin ?? 'N/A' }}</td>
        </tr>

        @endforeach
      </tbody>
    </table>
    @if ($summaryNumbers->hasPages())
    {{ $summaryNumbers->appends($_GET)->onEachSide(1)->links()  }}
    @else
    <div class="nopagination"></div>
    @endif


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


</div>
