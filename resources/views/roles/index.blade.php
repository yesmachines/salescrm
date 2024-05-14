@extends('layouts.default')
@section('content')
<div class="container-xxl">
    <!-- Page Header -->
    <div class="hk-pg-header pg-header-wth-tab pt-7">
        <div class="d-flex">
            <div class="d-flex flex-wrap justify-content-between flex-1">
                <div class="mb-lg-0 mb-2 me-8">
                    <h1 class="pg-title">Manage Roles</h1>
                    <p>Create new roles for group of user who will get different privilages or controls over the features.</p>
                </div>

            </div>
        </div>

    </div>
    <!-- /Page Header -->
    <!-- Page Body -->
    <div class="hk-pg-body">
        <div class="row">
            <div class="col-md-12 mb-md-4 mb-3">
                <div class="card card-border mb-0 h-100">
                    <div class="card-header card-header-action">
                        <h6>User Roles</h6>
                        <div class="card-action-wrap">
                            <a href="{{route('roles.create')}}" class="btn btn-sm btn-primary ms-3"><span><span class="icon"><span class="feather-icon"><i data-feather="plus"></i></span></span><span class="btn-text">Add new</span></span></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="contact-list-view">
                            <div class="mt-2">
                                @include('layouts.partials.messages')
                            </div>
                            <table id="datable_1" class="table nowrap w-100 mb-5">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $key => $role)
                                    <tr>
                                        <td> {{ $role->id }} </td>
                                        <td>
                                            {{ $role->name }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Edit" href="{{ route('roles.edit', $role->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span></a>
                                                <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover del-button" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Delete" href="javascript:void(0);" onclick="event.preventDefault();
                                        document.getElementById('delete-form-{{ $role->id }}').submit();"><span class="icon"><span class="feather-icon"><i data-feather="trash"></i></span></span></a>
                                                {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:none',
                                                'id' => 'delete-form-'.$role->id]) !!}
                                                {!! Form::close() !!}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                  
                                </tbody>
                            </table>
                              {!! $roles->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Body -->
</div>

@endsection