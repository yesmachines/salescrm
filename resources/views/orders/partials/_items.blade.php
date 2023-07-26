<table class="table edit_table_data" id="table_load">
    <thead class="bg-success p-2 text-dark bg-opacity-25">
        <tr>
            <th>Item</th>
            <th>Part No</th>
            <th>Qty</th>
            <th>Delivered On</th>
            <th>Status</th>
            <th>Remarks</th>
            @if(!isset($showOnly))
            <th>Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($items as $itm)
        <tr valign="top" id="row-{{$itm->id}}">
            <td>{{$itm->item}}</td>
            <td>{{$itm->partno?$itm->partno: '--'}}</td>
            <td>{{$itm->quantity}}</td>
            <td>{{$itm->delivered?date('Y-m-d', strtotime($itm->delivered)): '--'}}</td>
            <td>{{$itm->status?'Delivered': 'Not Delivered'}}</td>
            <td>{{$itm->remarks?$itm->remarks: '--'}}</td>
            @if(!isset($showOnly))
            <td>
                <a href="javascript:void(0);" class="delete-order-delivery-row" data-id="{{$itm->id}}" title="Delete">
                    <i class="fa fa-trash"></i></a>
            </td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
