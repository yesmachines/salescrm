@foreach($replies as $reply)
<div class="media mediarow" id="crow-{{$reply->id}}">
    @if(isset($reply->user))
    <div class="media-head">
        <div class="avatar avatar-xs avatar-rounded letter-icon" title="{{ $reply->user->name }}">
            <span class="initial-wrap">{{substr($reply->user->name, 0, 2)}}</span>
        </div>
    </div>
    @else
    <div class="media-head">
        <div class="avatar avatar-xs avatar-rounded letter-icon" title="{{ $reply->username }}">
            <span class="initial-wrap">
                {{ (isset($reply->username))? substr($reply->username,0, 2) : substr($customer, 0, 2)}}
            </span>
        </div>
    </div>
    @endif
    <div class="media-body">
        <p>{{$reply->comment }}</p>

        <div class="row">
            <div class="col-md-3">
                <small class="text-warning">{{ date('d-m-Y h:i:s A', strtotime($reply->created_at)) }}</small>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-6">
                @if(!isset($showOnly))
                <div class="comment-action-wrap">
                    <a href="javascript:void(0);" class="delete-order-comment-row text-primary" data-id="{{$reply->id}}" title="Delete">
                        <i class="fa fa-trash"></i> Delete</a>&nbsp;
                    <a href="javascript:void(0);" class="reply-order-comment-row text-primary" data-id="{{$reply->id}}" title="Reply">
                        <i class="fa fa-reply"></i> Reply</a>
                </div>
                @endif
            </div>

        </div>
        <div class="row">
            <div id="reply-box-{{$reply->id}}" class="reply-box mt-4">
                <form name="frmReplys" id="frmReplys" data-id="{{$reply->id}}">
                    <input type="hidden" id="order_id{{$reply->id}}" name="order_id" class="form-control" value="{{$reply->order_id}}">
                    <input type="hidden" id="parent_id{{$reply->id}}" name="parent_id" class="form-control" value="{{$reply->id}}">
                    <input type="hidden" id="main_parent_id{{$reply->id}}" name="main_parent_id" class="form-control" value="{{$reply->parent_id}}">

                    <div class="form-group">
                        <div class="media">

                            <div class="media-body">
                                <div class="form-inline">
                                    <textarea class="form-control me-3" required id="reply{{$reply->id}}" name="reply" placeholder="Reply for the comments"></textarea>
                                    <button class="reply_submit btn btn-primary" type="submit">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($reply->replies) && !empty($reply->replies))
        @include('orders.partials._childlisthistorywithoutreply',['replies2' => $reply->replies, 'customer' => $customer])
        @endif
    </div>

</div>

@endforeach