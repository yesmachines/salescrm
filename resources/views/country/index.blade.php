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
                                <h1>Manage Country</h1>
                            </a>
                        </div>
                        <div class="dropdown ms-3">
                            <button class="btn btn-sm btn-outline-secondary flex-shrink-0 dropdown-toggle d-lg-inline-block d-none" data-bs-toggle="dropdown">Create New</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#add_new_country">Add New Country</a>
                            </div>
                        </div>
                    </div>
                    <div class="contact-options-wrap">

                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover no-caret d-sm-inline-block d-none" href="{{route('countries.index')}}" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Refresh"><span class="icon"><span class="feather-icon"><i data-feather="refresh-cw"></i></span></span></a>
                        <div class="v-separator d-lg-block d-none"></div>
                        <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover dropdown-toggle no-caret  d-lg-inline-block d-none  ms-sm-0" href="#" data-bs-toggle="dropdown"><span class="icon" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Manage Contact"><span class="feather-icon"><i data-feather="settings"></i></span></span></a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Import</a>
                            <a class="dropdown-item" href="#">Export</a>
                        </div>

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
                                        <th>code</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($countries as $i => $ctry)
                                    <tr>
                                        <td>
                                            {{ ++$i }}
                                        </td>
                                        <td>{{$ctry->name}}</td>
                                        <td>{{$ctry->code}}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex">
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" id="edit_ctry" href="#" title="" data-bs-original-title="Edit" data-bs-toggle="modal" data-bs-target="#edit_ctry_modal" data-bs-toggle="tooltip" data-placement="top" data-id="{{ $ctry->id }}"><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span></a>

                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover del-button" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="Delete" href="javascript:void(0);" onclick="event.preventDefault();
                                        document.getElementById('delete-form-{{ $ctry->id }}').submit();"><span class="icon"><span class="feather-icon"><i data-feather="trash"></i></span></span></a>
                                                    {!! Form::open(['method' => 'DELETE','route' => ['countries.destroy', $ctry->id],'style'=>'display:none',
                                                    'id' => 'delete-form-'.$ctry->id]) !!}
                                                    {!! Form::close() !!}
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
            <div id="add_new_country" class="modal fade add-new-contact" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <form method="POST" action="{{ route('countries.store') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h5 class="mb-5">Create New Country</h5>
                                <div class="row gx-3">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Name</label>
                                            <input class="form-control" type="text" name="name" required />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">Code</label>
                                            <input class="form-control" type="text" name="code" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer align-items-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Discard</button>
                                <button type="submit" class="btn btn-primary">Create Country</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Create Info -->
            <!-- Edit Info -->
            <div id="edit_ctry_modal" class="modal fade add-new-contact" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div id="modal-loader" style="display: none; text-align: center;">
                            Loading...
                        </div>
                        <div id="editCountry"></div>
                    </div>
                </div>
            </div>
            <!-- /Edit Info -->
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '#edit_ctry', function(e) {
            // e.preventDefault();
            let cid = $(this).data('id');
            $('#editCountry').html('');
            $('#modal-loader').show();

            let eurl = "{{ route('countries.edit', ':countryid') }}";
            eurl = eurl.replace(':countryid', cid);

            $.ajax({
                    url: eurl,
                    type: 'GET',
                    cache: false,
                    dataType: 'html',
                    processData: false
                })
                .done(function(data) {
                    let adata = $.parseJSON(data);
                    $('#editCountry').html('');

                    if (adata.success == true) {
                        $('#editCountry').html(adata.html);
                    }
                    // load response 
                    $('#modal-loader').hide(); // hide ajax loader   
                })
                .fail(function() {
                    $('#editCountry').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
                    $('#modal-loader').hide();
                });
        });
    });
</script>
@endsection