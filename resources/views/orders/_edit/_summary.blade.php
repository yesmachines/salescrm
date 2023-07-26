<h5>Order Summary Details</h5>
<p>Please refer the below Order, delivery and payment information :</p>
<div id="summary-errors"></div>
{!! Form::open(array('id' => 'frmsummary', 'enctype' => 'multipart/form-data')) !!}

{!! Form::hidden('order_id', $order->id, array('id' => 'order_id')) !!}
<div class="row">
    <div class="col-12">
        <div class="summary_error_msg"></div>
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
    </div>
</div>
<div class="row my-4">
    <div class="col-xxl-12">
        <div class="row mb-2">
            <div class="col-4">
                <h6>Customer Po No.</h6>
            </div>
            <div class="col-6">
                <input type="text" id="po_number" name="po_number" class="form-control" value="{{$order->po_number}}">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>YES Po No.</h6>
            </div>
            <div class="col-6">
                <input type="text" id="yespo_no" name="yespo_no" class="form-control" value="{{$order->yespo_no}}">
            </div>

            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Po Date.</h6>
            </div>
            <div class="col-6">
                <input type="date" class="form-control" id="po_date" name="po_date" value="{{$order->po_date}}">
            </div>

            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Po Recieved.</h6>
            </div>
            <div class="col-6">
                <input type="date" class="form-control" id="po_received" name="po_received" value="{{$order->po_received}}">
            </div>

            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Order Status:</h6>
            </div>
            <div class="col-6">
                <select class="form-control" name="status" id="status">
                    <option seleceted disabled>Please select a status</option>
                    <option value="open" {{ $order->status=="open" ? 'selected' : '' }}>Open</option>
                    <option value="partial" {{ $order->status=="partial" ? 'selected' : '' }}>Partial</option>
                    <option value="closed" {{ $order->status=="closed" ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Shippment Term</h6>
            </div>
            <div class="col-6">
                <input type="text" id="shipping_term" name="shipping_term" class="form-control" value="{{isset($order->orderDelivery)?$order->orderDelivery->shipping_term: ''}}" required>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Payment Term</h6>
            </div>
            <div class="col-6">
                <input type="text" id="payment_term" name="payment_term" class="form-control" value="{{isset($order->orderDelivery)?$order->orderDelivery->payment_term:''}}" required>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Advance Received</h6>
            </div>
            <div class="col-6">
                <input type="date" class="form-control" id="advance_received" value="{{isset($order->orderDelivery->advance_received)? $order->orderDelivery->advance_received: null}}" name="advance_received">
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Delivery Target</h6>
            </div>
            <div class="col-6">
                <input type="date" class="form-control" id="delivery_target" name="delivery_target" value="{{isset($order->orderDelivery->delivery_target)?$order->orderDelivery->delivery_target: null }}" required>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row mb-2">
            <div class="col-4">
                <h6>Promised Delivery Time</h6>
            </div>
            <div class="col-6">
                <textarea rows="2" id="delivery_time" name="delivery_time" class="form-control" required> {{isset($order->orderDelivery)? $order->orderDelivery->delivery_time: ''}}</textarea>
            </div>
            <div class="col-2"></div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="summary_error_msg"></div>
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
            </div>
        </div>
        <div class="row mt-2">
            <h6> Enter Emails for OTP
                <a href="javascript:void(0);" class="addemails btn btn-primary btn-sm" style="float: right;"><i data-feather="plus"></i> Add Row</a>
            </h6>

            <table class="table form-table" id="customEmailFields">

                <tbody>
                    @if(isset($order->orderDelivery))
                    @php
                    $i=1;
                    $otp_emails = explode(",", $order->orderDelivery->otp_emails);
                    @endphp
                    @foreach($otp_emails as $otp_email)
                    <tr valign="top">
                        <td width="20%"></td>
                        <td>
                            <input type="text" class="form-control" id="itememailcustomFieldName" name="emails[]" value="{{$otp_email}}" placeholder="Email" />
                        </td>


                        <td>@if($i!=1)<a href="javascript:void(0);" class="rememailCF" title="DELETE ROW"><i class="fa fa-trash"></i></a>@endif</td>

                    </tr>
                    @php $i++; @endphp
                    @endforeach
                    @else
                    <tr valign="top">
                        <td>
                            <input type="text" class="form-control" id="itememailcustomFieldName" name="emails[]" value="" placeholder="Email" />
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="row mb-2 mt-4">

            <div class="col-4"></div>
            <div class="col-4">
                <button type="submit" id="order_details_button" class="btn btn-success me-2">Save & Continue</button>
                <a href="javascript:void(0);" onclick="skipStep1();" class="">Skip here</a>
            </div>

            <div class="col-4"></div>

        </div>
    </div>

</div>

{!! Form::close() !!}