@extends('layouts.default')

@section('content')


<!-- Page Body -->
<div class="hk-pg-body py-0">
    <div class="contactapp-wrap contactapp-sidebar-toggle">

        <div class="contactapp-content">
            <div class="contactapp-detail-wrap">
                <header class="contact-header">
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a class="contactapp-title link-dark" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <h1>Manage Employee Areas</h1>
                            </a>
                        </div>
                        <div class="dropdown ms-3">
                            <button class="btn btn-sm btn-outline-secondary flex-shrink-0 dropdown-toggle d-lg-inline-block d-none" data-bs-toggle="dropdown">Create New</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createAreaModal">Add New Region</a>
                            </div>
                        </div>
                    </div>
                    <div class="contact-options-wrap">

                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover no-caret d-sm-inline-block d-none" href="{{route('areas.index')}}" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Refresh"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>

                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover hk-navbar-togglable d-sm-inline-block d-none" href="#" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Collapse">
                            <span class="icon">
                                <span class="feather-icon"><i data-feather="chevron-up"></i></span>
                                <span class="feather-icon d-none"><i data-feather="chevron-down"></i></span>
                            </span>
                        </a>
                    </div>
                    <!-- <div class="hk-sidebar-togglable"></div> -->
                </header>
                <div class="contact-body">
                    <div data-simplebar class="nicescroll-bar">

                        <div class="contact-list-view">
                            <div class="mt-2">
                                @include('layouts.partials.messages')
                            </div>
                            <table id="com_datatable" class="table nowrap w-100 mb-5">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($areas as $i => $area)
                                    <tr>
                                        <td>
                                            {{ ++$i }}
                                        </td>
                                        <td>{{$area->name}}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex">
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" id="edit_area" href="{{route('areas.edit',$area->id)}}" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#editAreaModal" data-bs-toggle="tooltip" data-placement="top" data-id="{{ $area->id }}"><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Create Info -->
            <div id="createAreaModal"  class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <form method="POST" action="{{ route('areas.store') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create New Area</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row gx-3">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">Name</label>
                                            <input class="form-control" type="text" name="name" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">Timezone</label>
                                            {!! Form::select('timezone',  $timezones, 228, ['class' => 'form-control', 'id' => 'createTimezone']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Create Info -->
            <!-- Edit Info -->
            <div id="editAreaModal"  class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div id="modal-loader" style="display: none; text-align: center;">
                            Loading...
                        </div>
                        <div id="ediSection"></div>
                    </div>
                </div>
            </div>
            <!-- /Edit Info -->
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#createAreaModal').on('shown.bs.modal', function () {
        $('#createTimezone').select2({
            dropdownParent: $('#createAreaModal')
        });
    });

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '#edit_area', function (e) {
            e.preventDefault();
            let cid = $(this).data('id');
            $('#ediSection').html('');
            $('#modal-loader').show();

            eurl = $(this).attr('href');

            $.ajax({
                url: eurl,
                type: 'GET',
                cache: false,
                dataType: 'html',
                processData: false
            })
                    .done(function (data) {
                        $('#ediSection').html(data);
                        $('#editAreaModal').find('#editTimezone').select2({
                            dropdownParent: $('#editAreaModal')
                        });
                        $('#modal-loader').hide();
                    })
                    .fail(function () {
                        $('#ediSection').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                        $('#modal-loader').hide();
                    });
        });
    });
</script>
@endsection