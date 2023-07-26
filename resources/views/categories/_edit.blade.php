{!! Form::model($category, ['method' => 'PATCH', 'route' => ['categories.update', $category->id]]) !!}
<div class="modal-body">
    <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <div class="row gx-3">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input class="form-control" type="text" name="name" required value="{{$category->name}}" />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="form-label">Parent (if any)</label>
                <select class="form-select" name="parent_id">
                    <option value="0">--</option>
                    @foreach ($parents as $id => $value)
                    <option value=" {{ $id }}" {{($category->parent_id == $id)? "selected": ""}}>
                        {{ $value }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <label class="form-label">Details</label>
                <textarea class="form-control" name="description">{{$category->description}}</textarea>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer align-items-center">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
    <button type="submit" class="btn btn-primary">Update</button>
</div>
{!! Form::close() !!}