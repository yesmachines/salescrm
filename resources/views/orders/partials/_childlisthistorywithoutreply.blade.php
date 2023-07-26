@foreach($replies2 as $history)
<div class="media mediarow" id="crow-{{$history->id}}">
    @if(isset($history->user))
    <div class="media-head">
        <div class="avatar avatar-xs avatar-rounded letter-icon" title="{{ $history->user->name }}">
            <span class="initial-wrap">{{substr($history->user->name, 0, 2)}}</span>
        </div>
    </div>
    @else
    <div class="media-head">
        <div class="avatar avatar-xs avatar-rounded letter-icon" title="{{ $history->username }}">
            <span class="initial-wrap">{{isset($history->username)? substr($history->username, 0, 2): substr($customer, 0, 2) }}</span>
        </div>
    </div>
    @endif
    <div class="media-body">
        <p>{{$history->comment }}</p>
        <div class="row">
            <div class="col-md-3"><small class="text-warning">{{ date('d-m-Y h:i:s A', strtotime($history->created_at)) }}</small></div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                @if(!isset($showOnly))
                <div class="comment-action-wrap">
                    <a href="javascript:void(0);" class="delete-order-comment-row text-primary" data-id="{{$history->id}}" title="Delete">
                        <i class="fa fa-trash"></i> Delete</a>
                </div>
                @endif
            </div>
        </div>

    </div>

</div>
<!-- <div class="separator separator-light"></div> -->
@endforeach