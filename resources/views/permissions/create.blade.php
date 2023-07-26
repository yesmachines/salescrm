@extends('layouts.default')

@section('content')

<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pt-7 pb-4">
        <h1 class="pg-title"> Add New Permission</h1>
        <p>Add new role and assign permissions</p>
    </div>
    <!-- /Page Header -->

    <!-- Page Body -->
    <div class="hk-pg-body">
        <div class="row edit-profile-wrap">
            <div class="col-lg-12 col-sm-12 col-12">
                @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form method="POST" action="{{ route('permissions.store') }}">
                    @csrf
                    <div class="row gx-3">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Name" required>

                                @if ($errors->has('name'))
                                <span class="text-danger text-left">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                    <div class="row gx-3">
                        <div class="col-sm-12">
                            <div class="form-group">

                                <button type="button" class="btn btn-secondary me-3" onclick="window.location.href='/permissions'">Discard</button>
                                <button type="submit" class="btn btn-primary">Save permission</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- /Page Body -->
</div>


@endsection