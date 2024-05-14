@extends('layouts.default')

@section('content')
<div class="container-xxl">
  <!-- Page Header -->
  <div class="hk-pg-header pt-7 pb-4">
    <h1 class="pg-title">Import Products</h1>

  </div>
  @if(session('success'))
  <div class="alert alert-success" role="alert" style="text-align: center;">
    {{ session('success') }}
  </div>
  @endif
  <form action="{{ route('import.excel') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row" style="padding-top:10px;">
      <div class="col-md-3">

        <label class="form-label">Division</label>

        <div class="form-group">
          <select class="form-control select2" name="division_id" id="divisionInput" required>
            <option value="0">--</option>
            @foreach ($divisions as $division)
            <option value="{{ $division->id }}">
              {{ $division->name }}
            </option>
            @endforeach
          </select>
          <div class="invalid-data"  style="display: none;">Please select a division.</div>
        </div>

      </div>
      <div class="col-md-3">

        <label class="form-label">Brand</label>

        <div class="form-group">
          <select class="form-control select2" name="brand_id" id="brandInput" required>
            <option value="">--</option>
            @foreach ($suppliers as $k => $sup)
            <option value=" {{ $sup->id }}">
              {{ $sup->brand }}
            </option>
            @endforeach
          </select>
          <div class="invalid-data"  style="display: none;">Please select a brand.</div>
        </div>

      </div>
      <div class="col-md-3">

        <label class="form-label">Manager</label>

        <div class="form-group">
          <select class="form-select select2" name="manager_id" id="managerInput" required>
            <option value="" selected="">--</option>
            @php
            $sortedManagers = $managers->sortBy('user.name');
            @endphp
            @foreach ($sortedManagers as $key => $row)
            <option value="{{ $row->id }}">
              {{ $row->user->name }}
            </option>
            @endforeach
          </select>
          <div class="invalid-data"  style="display: none;">Please select a  manager.</div>
        </div>

      </div>

      <div class="col-sm-6" style="padding:50px;">


    <div style="padding:20px;">
        <a href="{{ route('download.excel.template') }}">Download Excel Template</a>
            </div>

        <label for="file-upload" id="file-label" style="position: relative; cursor: pointer; background-color: #007bff; color: #fff; padding: 10px 20px; border-radius: 5px; margin-bottom: 10px; transition: background-color 0.3s;">
          <span>Choose file</span>
          <input type="file" id="file-upload" name="file" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;" onchange="updateFileName(this)" required>
        </label>
        <button type="submit" style="background-color: #28a745; color: #fff; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;">Import</button>
      </form>

    </div>
  </div>
  <script>
  function updateFileName(input) {
    const fileName = input.files[0].name;
    document.getElementById('file-label').querySelector('span').textContent = fileName;
  }
</script>
@endsection
