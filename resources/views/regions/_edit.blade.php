{!! Form::model($region, ['method' => 'PATCH', 'route' => ['regions.update', $region->id]]) !!}
<div class="modal-body">
    <h5 class="modal-title" id="exampleModalLabel">Update Country</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <div class="row gx-3">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input class="form-control" type="text" name="state" value="{{$region->state}}" required />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="form-label">Code</label>
                <select class="form-control" name="country_id" required>
                    <option value="0">--</option>
                    @foreach($countries as $ctry)
                    <option value="{{$ctry->id}}" {{$region->country_id == $ctry->id? "selected": ""}}>{{$ctry->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer align-items-center">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
    <button type="submit" class="btn btn-primary">Update Country</button>
</div>
{!! Form::close() !!}