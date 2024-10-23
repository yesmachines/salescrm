<div>
  <div class="row">
    <div class="col-md-5">
      <div class="input-group mb-1">
        <select class="form-control" wire:model="selectedCountry">
          <option value="">Select Country</option>
          @foreach ($countries as $country)
          <option value="{{ $country->id }}">{{ $country->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="col-md-3">
      <div class="input-group mb-3">
        <button wire:click="exportToExcel" class="btn btn-primary waves-effect waves-light">Export to Excel</button>
      </div>
    </div>
  </div>
  <div>
    <div style="overflow-x: auto;">
      <table id="" class="table nowrap w-100 mb-5">
        <thead>
          <tr>
            <th width="9%">#</th>
            <th width="16%">Name</th>
            <th width="13%">Company</th>
            <th width="17%">Country</th>
            <th width="10%">email</th>
            <th width="9%">phone</th>
            <th width="13%">Reference No</th>
          </tr>
        </thead>
        <tbody>
          @php
          $count = 0;
          @endphp

          @foreach ($customersData as $index => $value)
          @php
          $count++;
          if($count %2 == 0){
          $rowtype = "even";
          }else{
          $rowtype = "odd";
          }
          @endphp
          <tr class="{{$rowtype}}">
            <td class="text-truncate overflow-hidden">{{$index + $customersData->firstItem()}}</td>
            <td class="text-truncate overflow-hidden" title="{{$value->fullname}}">{{$value->fullname}}</td>
            <td class="text-truncate overflow-hidden" title="{{$value->company_name}}">{{$value->company_name}}</td>
            <td class="text-truncate overflow-hidden" title="{{$value->country_name ?? ''}}">{{$value->country_name ?? ''}}</td>
            <td class="text-truncate overflow-hidden" title="{{ $value->email ?? '' }}">{{ $value->email ?? '' }}</td>
            <td class="text-truncate overflow-hidden" title="{{ $value->phone ?? '' }}">{{ $value->phone ?? '' }}</td>
            <td class="text-truncate overflow-hidden" title="{{$value->company_reference_no}}">{{$value->company_reference_no}}</td>
          </tr>
          @endforeach

        </tbody>
      </table>
    </div>
    @if ($customersData->hasPages())
    {{ $customersData->appends($_GET)->onEachSide(1)->links()  }}
    @else
    <div class="nopagination"></div>
    @endif


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
    <script>
      function updateStatus(customerId) {
        var checkbox = $('#toggleActionButtons_' + customerId);
        var status = checkbox.is(':checked') ? 1 : 0;

        $.ajax({
          url: '/customers/' + customerId,
          type: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          contentType: 'application/json',
          data: JSON.stringify({
            status: status
          }),
          success: function(response) {
            console.log(response.message); // Log success message
            // You can also update the UI or do something else on success
          },
          error: function(xhr, status, error) {
            console.error('Error:', error); // Log error status
            // Handle errors here
          }
        });
      }
    </script>
  </div>