@foreach($histories as $history)

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
            <span class="initial-wrap">{{isset($history->username)? substr($history->username, 0, 2): substr($customer, 0, 2)  }}</span>
        </div>
    </div>
    @endif
    <div class="media-body">
        <p>{{$history->comment }}</p>

        <div class="row">
            <div class="col-md-3"> <small class="text-warning">{{ date('d-m-Y h:i:s A', strtotime($history->created_at)) }}</small></div>
            <div class="col-md-3"></div>
            <div class="col-md-6">
                @if(!isset($showOnly))
                <div class="comment-action-wrap">
                    <a href="javascript:void(0);" class="delete-order-comment-row text-primary" data-id="{{$history->id}}" title="Delete">
                        <i class="fa fa-trash"></i> Delete</a>
                </div>
                @endif
            </div>
        </div>
        @if(isset($history->replies) && !empty($history->replies))
        @include('orders.partials._childlisthistorywithreply',['replies' => $history->replies,
        'customer' => $customer])
        @endif
    </div>

</div>
<div class="separator separator-light"></div>

@endforeach

<script>
    // $(".reply-box").hide();
    // $(".reply_submit").click(function(e) {

    //     var parent_id = $(this).attr('id');
    //     var main_parent_id = $("#main_parent_id" + parent_id).val();
    //     var order_id = $("#order_id" + parent_id).val();
    //     var reply = $("#reply" + parent_id).val();
    //     e.preventDefault();
    //     c
    //     // let formData = new FormData($(this)[0]);

    //     if (!reply) {
    //         $("#frmql").show();
    //         $("#frmql").addClass('alert alert-danger').text("Please enter Reply!");
    //         return false;
    //     }

    //     $('#ajxloader').html('Please wait..');
    //     $.ajax({
    //         type: 'POST',
    //         url: "{{ route('comment-reply.insert') }}",
    //         data: {
    //             parent_id: parent_id,
    //             main_parent_id: main_parent_id,
    //             order_id: order_id,
    //             reply: reply,
    //         },
    //         success: function(data) {

    //             $("#frmql").removeClass('alert alert-danger');
    //             $("#frmql").hide();
    //             if ($.isEmptyObject(data)) {
    //                 console.log("Empty Result ", data);

    //             } else {
    //                 Swal.fire(
    //                     'Success!',
    //                     'You have Replied to the comment',
    //                     'success'
    //                 ).then((result) => {
    //                     if (result.isConfirmed) {

    //                         $("#reply" + parent_id).val("");
    //                         $(".reply-box").hide();

    //                         // $('.mediarow').remove();
    //                         LoadRefreshHistory();

    //                     } else if (result.isDenied) {
    //                         // alert(2)
    //                         obj.hide();
    //                     }
    //                 });
    //                 //

    //             }
    //         }
    //     });


    // });

    // $(document).delegate('a.delete-order-reply-row', 'click', function(e) {
    //     e.preventDefault();


    //     let rid = jQuery(this).attr('data-id');
    //     rid = parseInt(rid);

    //     $.ajax({
    //         type: 'POST',
    //         url: "{{ route('reply-comment.delete') }}",
    //         data: {
    //             reply_comment_id: rid,
    //         },
    //         success: function() {
    //             $("#crow-" + rid).remove();

    //         }

    //     });



    // });
</script>