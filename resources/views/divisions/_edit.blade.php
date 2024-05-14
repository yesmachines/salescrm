{!! Form::model($division, ['method' => 'PATCH', 'route' => ['divisions.update', $division->id]]) !!}
<div class="modal-body">
    <h5 class="modal-title" id="exampleModalLabel">Update Division</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <div class="row gx-3">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input class="form-control" type="text" name="name" value="{{$division->name}}" required />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="form-label">Code</label>
                <input class="form-control" type="text" name="code" value="{{$division->code}}" required />
            </div>
        </div>
    </div>

</div>
<div class="modal-footer align-items-center">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
    <button type="submit" class="btn btn-primary">Update Division</button>
</div>
{!! Form::close() !!}
