@extends('layouts.default')

@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pt-7 pb-4">
        <h1 class="pg-title">Add new role</h1>
        <p>Add new role and assign permissions.</p>
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
                <form method="POST" action="{{ route('roles.store') }}">
                    @csrf
                    <div class="row gx-3">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Name" required>
                            </div>
                        </div>
                        <div class="col-sm-6"></div>
                    </div>
                    <div class="row gx-3">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="permissions" class="form-label">Assign Permissions</label>
                                <table class="table table-striped">
                                    <thead>
                                        <th scope="col" width="1%"><input type="checkbox" name="all_permission" class="all_permission"></th>
                                        <th scope="col" width="20%">Name</th>
                                        <th scope="col" width="1%">Guard</th>
                                    </thead>

                                    @foreach($permissions as $permission)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="permission[{{ $permission->name }}]" value="{{ $permission->name }}" class='permission '>
                                        </td>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->guard_name }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                                <button type="button" class="btn btn-secondary me-3" onclick="window.location.href='/roles'">Discard</button>
                                <button type="submit" class="btn btn-primary">Save user</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- /Page Body -->
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('[name="all_permission"]').on('click', function() {

            if ($(this).is(':checked')) {
                $.each($('.permission'), function() {
                    $(this).prop('checked', true);
                });
            } else {
                $.each($('.permission'), function() {
                    $(this).prop('checked', false);
                });
            }

        });
    });
</script>

@endsection