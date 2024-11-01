<h5>Order Summary</h5>
<p>Please refer the below Order items like machines, spares & consumables to deliver :</p>
<br>
<div class="row">
    <div class="col-12">
        <div id="frmspecs"></div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="error_msg"></div>
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
<form name="add_delivery_item" id="add_delivery_item">
    <input type="hidden" name="order_delivery_id" id="order_delivery_id" value="{{isset($order->orderDelivery)?$order->orderDelivery->id: ''}}" />
    <div class="row ">

        <div class="col-xxl-12">
            <h6> Products
                <a href="javascript:void(0);" class="addCF btn btn-primary btn-sm" style="float: right;"><i data-feather="plus"></i> Add Row</a>
            </h6>

            <table class="table form-table" id="customFields">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Part No</th>
                        <th>Qty</th>
                        <th>Delivered On</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr valign="top">
                        <td>
                            <input type="text" class="form-control" id="itemcustomFieldName" name="item[]" value="" placeholder="Item" />
                        </td>
                        <td>
                            <input type="text" class="form-control" id="partcustomFieldValue" name="part_no[]" value="" placeholder="Part No" />
                        </td>
                        <td>
                            <input type="number" class="form-control" id="qtycustomFieldValue" name="qty[]" value="" placeholder="Qty" />
                        </td>
                        <td>
                            <input type="date" class="form-control" id="delivered" name="delivered[]" value="" placeholder="Delivered On" />
                        </td>
                        <td>
                            <select class="form-control" name="status[]" id="status">
                                <option value="0">Not Delivered</option>
                                <option value="1">Delivered</option>
                            </select>
                        </td>
                        <td>
                            <textarea rows="2" id="remarks" name="remarks[]" placeholder="Remarks" class="form-control"></textarea>
                        </td>

                    </tr>
                </tbody>
            </table>

            <div class="row mb-2">
                <div class="col-4">
                    <button type="submit" id="order_delivery_details_button" class="btn btn-success">Save & Continue</button>
                    <a href="javascript:void(0);" onclick="skipStep2();" class="ms-2">Skip here</a>
                </div>

            </div>

        </div>
    </div>
</form>

<div class="row mb-2">
    <div class="col-12">
        <div class="separator"></div>
    </div>
</div>

<h5>List Of Order Items</h5>

<div class="row my-4">

    <div class="col-xxl-12">
        <div id="frmtable"></div>
        <div id="load-item-tbl">
            @if(isset($order->orderDelivery) && isset($order->orderDelivery->orderItems) && !empty($order->orderDelivery->orderItems))
            @include('orders.partials._items',['items' => $order->orderDelivery->orderItems])
            @endif
        </div>

    </div>
</div>