{!! Form::model($conversion, ['method' => 'PATCH', 'route' => ['conversion.update', $conversion->id]]) !!}
<div class="modal-body">
  <h5 class="modal-title" id="exampleModalLabel">Update Currency Conversion</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">×</span>
  </button>
  <div class="row gx-3">
    <div class="col-sm-6">
      <div class="form-group">
        <label class="form-label">Currency</label>
        <input class="form-control" type="text" name="currency" value="{{$conversion->currency}}" required />
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label class="form-label">Standard Rate</label>
        <input class="form-control" type="text" name="standard_rate" value="{{$conversion->standard_rate}}" required />
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label class="form-label">Date On</label>
        <input class="form-control" type="date" name="date_on" value="{{$conversion->date_on}}" required />
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-select" name="status" required>
          <option value="1" {{ $conversion->status == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $conversion->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>

      </div>
    </div>
  </div>

</div>
<div class="modal-footer align-items-center">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
  <button type="submit" class="btn btn-primary">Update Currency Conversion</button>
</div>
{!! Form::close() !!}
