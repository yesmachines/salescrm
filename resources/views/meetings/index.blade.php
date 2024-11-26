@extends('layouts.default')
@section('title') Reports of Quotation Summary @endsection

@section('content')
<!-- Page Body -->
<div class="pageLoader" id="pageLoader"></div>
<div class="hk-pg-body py-0">
    <div class="contactapp-wrap  contactapp-sidebar-toggle">

        <div class="contactapp-content">
            <div class="contactapp-detail-wrap">
                <header class="contact-header">
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a class="contactapp-title link-dark" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <h1>Meetings</h1>
                            </a>
                            <p class="text-danger">All Meeting schedules will be listed based on the "{{config('app.timezone')}}" timezone.</p>
                        </div>

                    </div>
                    <div class="contact-options-wrap">

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
                            <form method="GET">
                                <div class="row">
                                    <div class="col-2">
                                        <label class="form-label">Date From</label>

                                        <input type="text" class="form-control" name="start_date" id="start_date" placeholder="From" onfocus="(this.type = 'date')" onblur="(this.type = 'text')" value="{{ isset($input['start_date'])? $input['start_date']:'' }}">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Date To</label>
                                        <input type="text" class="form-control" name="end_date" id="end_date" placeholder="To" onfocus="(this.type = 'date')" onblur="(this.type = 'text')" value="{{ isset($input['end_date'])? $input['end_date']:'' }}">
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Manager</label>
                                        <select name="assigned_to" id="assigned_to" class="form-control">
                                            <option value="">-- Any --</option>
                                            @foreach($users as $emp)
                                            <option value="{{$emp->id}}">{{ $emp->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label class="form-label">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">-- Any --</option>
                                            <option value="0">Not Started</option>
                                            <option value="1">Finished</option>
                                            <option value="2">Shared Only</option>
                                        </select>
                                    </div>
                                    <div class="col-4 text-align-right">
                                        <button class="btn btn-primary filter" type="submit">Search</button>
                                        <button class="btn btn-secondary" type="button" onclick="window.location.href = '/reports/quotations'">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <div class="dropdown-divider mt-2 mb-4"></div>
                            </div>
                        </div>
                        <table id="listTable" class="table nowrap table-hover table-striped w-100 mb-5">
                            <thead>
                                <tr>
                                    <th>Tilte</th>
                                    <th>Company Name</th>
                                    <th>Created By</th>
                                    <th>Scheduled At</th>
                                    <th>MQS</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Page Body -->
@endsection
@push('scripts')
<script type='text/javascript'>
                                            $(function () {
                                                oTable = $('#listTable').DataTable({
                                                    scrollX: true,
                                                    autoWidth: false,
                                                    ordering: false,
                                                    responsive: true,
                                                    processing: true,
                                                    serverSide: true,
                                                    language: {
                                                        search: "Search",
                                                        searchPlaceholder: "Title, Company",
                                                        sLengthMenu: "_MENU_items",
                                                        paginate: {
                                                            next: '', // or '→'
                                                            previous: '' // or '←' 
                                                        }
                                                    },
                                                    ajax: {
                                                        url: "{!! route('meetings.datatable') !!}",
                                                        type: 'post',
                                                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                                                        data: function (d) {
                                                            d.start_date = $("#start_date").val(),
                                                                    d.end_date = $("#end_date").val(),
                                                                    d.assigned_to = $("#assigned_to").val(),
                                                                    d.status = $("#status").val()
                                                        }
                                                    },
                                                    columns: [
                                                        {data: 'title', name: 'title'},
                                                        {data: 'company_name', name: 'company_name'},
                                                        {data: 'user.name', name: 'user.name'},
                                                        {data: 'scheduled_at', name: 'scheduled_at', searchable: false},
                                                        {data: 'mqs', name: 'mqs', searchable: false},
                                                        {data: 'status', name: 'status', searchable: false},
                                                        {data: 'actions', name: 'actions', searchable: false}
                                                    ],
                                                    "drawCallback": function () {
                                                        feather.replace();
                                                        $('.dataTables_paginate > .pagination').addClass('custom-pagination pagination-simple');
                                                    }
                                                });
                                                $("#listTable").parent().addClass('table-responsive');
                                                $(".filter").on("click", function (event) {
                                                    event.preventDefault();
                                                    oTable.draw();
                                                });
                                            });
</script>    
@endpush