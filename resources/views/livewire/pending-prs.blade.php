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
            <input type="text" placeholder="Search by company, supplier, pr number .." class="form-control" wire:model.debounce.500ms="search" wire:change="updateSearch($event.target.value)">
        </div>
        <div class="col-md-3 mb-3">
            <select name="dateOptions" id="dateOptions" class="form-select" wire:model="selectedDateOption" wire:change="updateDateOptions">
                <option value="">-Select PR Date-</option>
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
                    <th width="10%">PR#</th>
                    <th width="10%">PR Date</th>
                    <th width="20%">Company</th>
                    <th width="15%">Supplier</th>
                    <th width="10%">Supplier Contact</th>
                    <th width="10%">Amount</th>
                    <th width="10%">Status</th>
                    <th width="5%">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                $count = 0;
                @endphp
                @foreach($purchase as $i =>$pr)
                @php
                $count++;
                if($count %2 == 0){
                $rowtype = "even";
                }else{
                $rowtype = "odd";
                }
                @endphp

                @switch($pr->status)
                @case('pending')
                @php $clsStat = 'info';@endphp
                @break
                @case('onhold')
                @php $clsStat = 'warning';@endphp
                @break

                @endswitch
                <tr class="{{$rowtype}}">
                    <td class="text-truncate overflow-hidden" title="{{$pr->pr_number}}">
                        <span style="cursor:pointer;" class="badge badge-outline {{$pr->pr_for == 'yesclean'? 'ycref': 'ymref'}}" onclick="copyText(this);">{{$pr->pr_number}}</span>

                    </td>
                    <td class="text-truncate overflow-hidden" title="{{$pr->pr_date }}">{{$pr->pr_date }}</td>

                    <td class="text-truncate overflow-hidden" title="{{$pr->company->company}}">{{$pr->company->company}}</td>

                    <td class="text-truncate overflow-hidden">
                        {{$pr->supplier->brand}}
                    </td>
                    <td class="text-truncate overflow-hidden" title="{{ $pr->purchaseDelivery?->supplier_contact }}">
                   {{ $pr->purchaseDelivery?->supplier_contact }}
                   </td>
                    <td class="text-truncate overflow-hidden" title="{{$pr->total_price }}">{{$pr->total_price }} {{$pr->currency}}</td>
                    <td class="text-truncate overflow-hidden"><span class="badge badge-soft-{{$clsStat}} my-1  me-2">
                            {{$pr->status }}
                        </span>
                    </td>
                    <td>
                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover dropdown-toggle no-caret" href="#" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="icon"><span class="feather-icon"><i data-feather="more-horizontal"></i></span></span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{route('purchaserequisition.download', $pr->id)}}"><span class="feather-icon dropdown-icon"><i data-feather="download"></i></span><span>Download</span></a>
                            <a class="dropdown-item " href="{{route('purchaserequisition.show', $pr->id)}}"><span class="feather-icon dropdown-icon"><i data-feather="eye"></i></span><span>View</span></a>
                            <a class="dropdown-item" href="{{route('purchaserequisition.edit',$pr->id)}}"><span class="feather-icon dropdown-icon"><i data-feather="edit"></i></span><span>Edit</span></a>

                            <a class="dropdown-item del-button d-none" href="#" onclick="deletePR({{$pr->id}});"><span class="feather-icon dropdown-icon"><i data-feather="trash"></i></span><span>Delete</span></a>
                            {!! Form::open(['method' => 'DELETE','route' => ['purchaserequisition.destroy', $pr->id],'style'=>'display:none',
                            'id' => 'delete-form-'.$pr->id]) !!}
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if ($purchase->hasPages())
    {{ $purchase->appends($_GET)->onEachSide(1)->links()  }}
    @else
    <div class="nopagination"></div>
    @endif

    <script>
        function deletePR(prid) {

            event.preventDefault();

            if (!prid) return;

            Swal.fire({
                title: "Are you sure?",
                text: "You are sure to delete the PR !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + prid).submit();
                }
            });

        }
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
