<!-- SHOW ORDERS -->
<div class="row">
    <div class="col-xxl-6">
        <span class="text-dark fw-medium">Shipping Term :</span>
        <span class="m-4">{{$order_delivery->shipping_term}}</span>
    </div>
    <div class="col-xxl-6">
        <span class="text-dark fw-medium">Payment Term :</span>
        <span class="m-4">{{$order_delivery->payment_term }}</span>
    </div>
</div>
<div class="row">
    <div class="col-xxl-6">
        <span class="text-dark fw-medium">Advance Received :</span>
        <span class="m-4">{{ $order_delivery->advance_received }}</span>
    </div>

    <div class="col-xxl-6">
        <span class="text-dark fw-medium">Last Updated On :</span>
        <span class="m-4">{{$order_delivery->updated_at }}</span>
    </div>
</div>
<div class="row">
    <div class="col-xxl-6">
        <span class="text-dark fw-medium">Promised Delivery Time :</span>
        <span class="m-4">{{ $order_delivery->delivery_time }}</span>
    </div>

    <div class="col-xxl-6">
        <span class="text-dark fw-medium">Delivery Target :</span>
        <span class="m-4">{{ $order_delivery->delivery_target }}</span>
    </div>
</div>
<div class="separator"></div>
<div class="row">
    <div class="col-xxl-12">
        <h6 class="mt-3">List Of Items</h6>
    </div>
</div>
<div class="row">
    <div class="col-xxl-12">
        @include('orders.partials._items',['items' => $order_delivery->orderItems, 'showOnly' => true])
    </div>
</div>