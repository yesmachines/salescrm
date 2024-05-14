<div class="card card-border mb-0 h-100">
    <div class="card-header card-header-action">
        <h6>Sales Probability</h6>
        <div class="card-action-wrap">
            <select wire:model="period" class="form-control me-4 w-100">
                <option value="0">This Month</option>
                <option value="15">15 Days</option>
                <option value="30">30 Days</option>
                <option value="60">60 Days</option>
                <option value="90">90 Days</option>
            </select>
            <select wire:model="probability" class="form-control  w-100">
                <option value="">Any</option>
                <option value="80">> 80%</option>
                <option value="70">> 70%</option>
                <option value="60">> 60%</option>
                <option value="50">> 50%</option>
                <option value="40">> 40%</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <div class="contact-list-view">
            <table id="datable_1" class="table nowrap w-100 mb-5">
                <thead>
                    <tr>
                        <!-- <th><span class="form-check fs-6 mb-0">
                                                    <input type="checkbox" class="form-check-input check-select-all" id="customCheck1">
                                                    <label class="form-check-label" for="customCheck1"></label>
                                                </span></th> -->
                        <th>Customer</th>
                        <th class="w-25">Brand</th>
                        <th>Sales Value (AED)</th>
                        <th>Gross Margin (AED)</th>
                        <th>Expected Closing</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($salesprobability as $sales)
                    <tr>
                        <!-- <td>
                                            </td> -->
                        <td>
                            <div class="media align-items-center">
                                <div class="media-head me-2">
                                    <div class="avatar avatar-xs avatar-rounded">
                                        <img src="{{asset('dist/img/logo-avatar-1.png')}}" alt="user" class="avatar-img">
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="text-high-em">{{$sales->company->company}}</div>
                                    <div class="fs-7">{{$sales->customer->customer}}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{$sales->supplier->brand}}
                        </td>
                        <td> {{$sales->total_amount}}</td>
                        <td>
                            <span class="badge badge-soft-secondary  my-1  me-2"> {{$sales->gross_margin}}</span>
                        </td>
                        <td> {{$sales->closure_date}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $salesprobability->links() }}
        </div>
    </div>
</div>