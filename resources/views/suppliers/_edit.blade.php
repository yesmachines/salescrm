{!! Form::model($supplier, ['method' => 'PATCH', 'route' => ['suppliers.update', $supplier->id]]) !!}
<div class="modal-body">
    <h5 class="modal-title" id="exampleModalLabel">Update Supplier</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <div class="row gx-3 mt-4">
        <div class="col-sm-4 form-group">
            <div class="dropify-square">
                <input type="file" class="dropify-1" name="logo_url" />
            </div>
        </div>
        <div class="col-sm-3 form-group">
            @if(isset($supplier->logo_url) && $supplier->logo_url)
            <img src="{{asset('storage/'.$supplier->logo_url)}}" alt="logo" width="100" />
            @endif
        </div>
        <div class="col-sm-5 form-group">

        </div>
    </div>
    <div class="row gx-3">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="form-label">Brand</label>
                <input class="form-control" type="text" name="brand" value="{{$supplier->brand}}" required />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="form-label">Country of Origin</label>
                <select class="form-select" name="country_id" required>
                    <option selected="">--</option>
                    @foreach ($countries as $key => $row)
                    <option value=" {{ $row->id }}" {{($supplier->country_id == $row->id)? "selected": ""}}>
                        {{ $row->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row gx-3">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="form-label">Website</label>
                <input class="form-control" type="text" name="website" value="{{$supplier->website}}" />
            </div>
        </div>
        <div class="col-sm-6">
            <!-- <div class="form-group">
                <label class="form-label">Details</label>
                <textarea class="form-control" name="description">{{$supplier->description}}</textarea>
            </div> -->
            <div class="form-group">
                <label class="form-label">Manager Assigned</label>
                <select class="form-select" name="manager_id">
                    <option value="0" selected="">--</option>
                    @foreach ($managers as $key => $row)
                    <option value="{{ $row->id }}" {{$supplier->manager_id == $row->id? "selected": ""}}>
                        {{ $row->user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer align-items-center">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
    <button type="submit" class="btn btn-primary">Update</button>
</div>
{!! Form::close() !!}