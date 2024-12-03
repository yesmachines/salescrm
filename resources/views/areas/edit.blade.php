{!! Form::model($area, ['method' => 'PATCH', 'route' => ['areas.update', $area->id]]) !!}
<div class="modal-header">
    <h5 class="modal-title">Update Area</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row gx-3">
        <div class="col-sm-12">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input class="form-control" type="text" name="name" value="{{$area->name}}"  required />
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="form-label">Timezone</label>
                {!! Form::select('timezone', $timezones, $area->timezone, ['class' => 'form-control', 'id' => 'editTimezone']) !!}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
{!! Form::close() !!}