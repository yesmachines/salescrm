<div class="title title-lg mb-2"><span>The order's latest updates</span></div>
<div class="comment-block ">
    <div class="row">
        <div class="col-8">
            <div id="frmql"></div>
        </div>
        <div class="col-4">
        </div>
    </div>
    <div class="row">

        <div class="col-8">
            <form name="frmComments" id="frmComments">
                <input type="hidden" id="order_id" name="order_id" class="form-control" value="{{$order->id}}">
                <div class="form-group">
                    <div class="media">

                        <div class="media-body">
                            <div class="form-inline">
                                <textarea class="form-control me-3" required id="comment" name="comment" placeholder="Enter latest updates"></textarea>
                                <button class="btn btn-primary" id="qual_submit" type="submit">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="list-his">
                @if(isset($order->orderHistory) && !empty($order->orderHistory))
                @include('orders._show._listhistory',['histories' => $order->orderHistory, 'customer' => $order->customer->fullname])
                @endif
            </div>
        </div>
        <div class="col-4">
        </div>
    </div>



</div>