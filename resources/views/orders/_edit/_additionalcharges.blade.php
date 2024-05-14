@foreach ($orderCharges as $key => $value)
<tr valign="top">
    <td width="30%">
        <input type="hidden" name="charges[{{$key}}][charge_id]" value="{{$value->id}}" />
        <input type="text" class="form-control" name="charges[{{$key}}][title]" placeholder="Packing Charge" value="{{ $value->title ?? '' }}" />
    </td>
    <td width="20%">
        <input type="number" class="form-control" name="charges[{{$key}}][considered]" placeholder="Considered Cost" value="{{ $value->considered ?? '' }}" />
    </td>
    <td width="30%">
        <textarea rows="2" name="charges[{{$key}}][remarks]" placeholder="Remarks" class="form-control">{{ $value->remarks ?? '' }}</textarea>
    </td>
    <td><a href="javascript:void(0);" class="remAC" title="DELETE ROW" data-id="{{$value->id}}"><i class="fa fa-trash"></i></a></td>
</tr>
@endforeach