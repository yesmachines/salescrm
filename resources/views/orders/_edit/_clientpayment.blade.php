@foreach($paymentTermsClient as $key => $value)
<tr valign="top">
    <td width="30%">
        <input type="hidden" name="clientpayment[{{$key}}][payment_id]" value="{{$value->id}}" />
        <textarea class="form-control" name="clientpayment[{{$key}}][payment_term]" placeholder="Payment Term">{{$value->payment_term}}</textarea>
    </td>
    <td width="20%">
        <input type="text" class="form-control datepick" name="clientpayment[{{$key}}][expected_date]" placeholder="Expected Date" value="{{$value->expected_date}}" />
    </td>
    <td width="20%">
        <select class="form-control" name="clientpayment[{{$key}}][status]">
            <option value="0" @if($value->status == 0) selected @endif>Not Done</option>
            <option value="1" @if($value->status == 1) selected @endif>Done</option>
            <option value="2" @if($value->status == 2) selected @endif>Partially Done</option>
        </select>
    </td>
    <td width="30%">
        <textarea rows="2" name="clientpayment[{{$key}}][remarks]" placeholder="Remarks" class="form-control">  @if($value->remarks)
                                  {{$value->remarks}}
                                  @endif</textarea>
    </td>
    <td><a href="javascript:void(0);" class="remPT" title="DELETE ROW" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a></td>
</tr>
@endforeach