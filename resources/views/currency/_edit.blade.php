{!! Form::model($currency, ['method' => 'PATCH', 'route' => ['currency.update', $currency->id]]) !!}
<div class="modal-body">
  <h5 class="modal-title" id="exampleModalLabel">Update Currency</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">×</span>
  </button>
  <div class="row gx-3">
    <div class="col-sm-6">
      <div class="form-group">
        <label class="form-label">Name</label>
        <input class="form-control" type="text" name="name" value="{{$currency->name}}" required />
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label class="form-label">Code</label>
        <input class="form-control" type="text" name="code" value="{{$currency->code}}" required />
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-select" name="status" required>
          <option value="1" {{ $currency->status == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $currency->status == 0 ? 'selected' : '' }}>Inactive</option>
        </select>

      </div>
    </div>
  </div>

</div>
<div class="modal-footer align-items-center">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
  <button type="submit" class="btn btn-primary">Update Currency</button>
</div>
{!! Form::close() !!}
