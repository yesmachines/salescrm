@extends('layouts.default')
@section('title') Reports of Quotation Summary @endsection

@section('content')
<!-- Page Body -->
<div class="pageLoader" id="pageLoader"></div>
<div class="hk-pg-body py-0">
    <div class="contactapp-wrap">

        <div class="contactapp-content">
            <div class="contactapp-detail-wrap">
                <header class="contact-header">
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a class="contactapp-title link-dark" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                <h1>Meeting Details</h1>
                            </a>
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
                        <div class="row">
                            <div class="col-6">    
                                <div class="task-info">
                                    <div class="task-detail-body">
                                        <div class="alert alert-primary alert-wth-icon fade show mb-4" role="alert">
                                            <span class="alert-icon-wrap"><span class="feather-icon"><i class="zmdi zmdi-collection-item"></i></span></span> {{$meeting->title}}
                                        </div>
                                        <h4 class="d-flex align-items-center fw-bold mb-0 inline-editable-wrap"><span class="editable">{{$meeting->company_name}}</span></h4>
                                        <p  class="d-flex align-items-center inline-editable-wrap"><span class="editable">{{$meeting->company_representative}}</span></p>
                                        <div class="col-md-12">
                                            <div class="title title-wth-divider my-4"><span>Contact Details</span></div>
                                            <div class="row">
                                                <div class="col-sm-12 mb-3">
                                                    <div class="card">
                                                        <div class="hk-ribbon-type-1 ribbon-wth-icon ribbon-wth-flag  overhead-start">
                                                            <span><span class="feather-icon"><i class="zmdi zmdi-pin"></i></span> Location</span>
                                                        </div>
                                                        <div class="card-body pt-4 pb-0">
                                                            <h5 class="card-title">{{$meeting->location}}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="hk-ribbon-type-1 ribbon-wth-icon ribbon-wth-flag  overhead-start">
                                                            <span><span class="feather-icon"><i class="zmdi zmdi-phone-in-talk"></i></span> Phone</span>
                                                        </div>
                                                        <div class="card-body mt-4">
                                                            <h5 class="card-title">{{$meeting->phone}}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="card">
                                                        <div class="hk-ribbon-type-1 ribbon-wth-icon ribbon-wth-flag  overhead-start">
                                                            <span><span class="feather-icon"><i class="zmdi zmdi-email"></i></span> Email</span>
                                                        </div>
                                                        <div class="card-body mt-4">
                                                            <h5 class="card-title">{{$meeting->email}}</h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="title title-wth-divider my-4"><span>Meeting Date</span></div>
                                                <button aria-expanded="false" class="btn btn-info btn-rounded" type="button">{{$meeting->scheduled_at}}</button>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="title title-wth-divider my-4"><span>Status</span></div>
                                                <button aria-expanded="false" class="btn btn-warning btn-rounded" type="button">
                                                    @switch ($meeting->status)
                                                    @case(0)
                                                    Not Started
                                                    @break
                                                    @case(1)
                                                    Finished
                                                    @break
                                                    @case(2)
                                                    Shared
                                                    @break
                                                    @endswitch
                                                </button>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="title title-wth-divider my-4"><span>MQS</span></div>
                                                <button aria-expanded="false" class="btn btn-success btn-rounded" type="button">{{$meeting->mqs}}</button>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mt-5 mb-2">
                                            <div class="title title-lg mb-0"><span>Scheduled Notes</span></div>
                                        </div>
                                        <div class="card card-border note-block bg-orange-light-5">
                                            <div class="card-body">
                                                <p>{{$meeting->scheduled_notes}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($meeting->status > 0)
                            <div class="col-6">    
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Meeting Notes</h5>
                                        @if(!empty($meeting->business_card))
                                        <div class="col-md-6">
                                            <img class="card-img-top pop" src="{{asset('storage') . '/' . $meeting->business_card}}" alt="Business Card">
                                        </div>
                                        @endif
                                        <p class="card-text pt-2">{{$meeting->meeting_notes}}</p>
                                    </div>
                                    <div class="card-body pt-0">
                                        <h5 class="card-title">Products</h5>
                                        <ul class="list-group list-group-flush">
                                            @foreach($meeting->products as $product)
                                            <li class="list-group-item">
                                                {{$product->title}}
                                                <p class="text-blue">{{$product->brand}}</p>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if(!$shares->isEmpty())
                        @php $i=0 @endphp
                        <div class="row">
                            <h5 class="mt-3">Shared History</h5>
                            <div class="table-responsive">
                                <table id="shares" class="table nowrap w-100 mb-5">
                                    <thead>
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>Shared By</th>
                                            <th>Shared To</th>
                                            <th>Shared At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($shares as $share)
                                        @php $i++ @endphp
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$share->sharedBy->name}}</td>
                                            <td>{{$share->sharedTo->name}}</td>
                                            <td>{{date('M d, Y h:i A',strtotime($share->created_at))}}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex">
                                                        <a href="{{route('meetings.shared.details',$share->id)}}" class="btn btn-sm btn-icon btn-floating btn-primary btn-lg btn-rounded sh-detail">
                                                            <span class="icon"><span class="feather-icon"><i data-feather="list"></i></span></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Body -->

    <div class="hk-chat-popup">
        <header>
            <div class="media-wrap">
                <div class="media">
                    <div class="media-body">
                        <div class="user-name">Shared Details</div>
                    </div>
                </div>
            </div>
            <div class="chat-popup-action d-flex">
                <a href="javascript:void(0);" id="close_sd" class="btn btn-sm btn-icon btn-dark btn-rounded">
                    <span class="icon"><span class="feather-icon"><i data-feather="x"></i></span></span>
                </a>
            </div>
        </header>
        <div class="chat-popup-body">
            <div data-simplebar class="nicescroll-bar">
                <div>
                    <div class="contact-list-wrap sh-content">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">              
                <div class="modal-body">
                    <img src="" class="imagepreview" style="width: 100%;" >
                </div>
            </div>
        </div>
    </div>
    @endsection
    @push('scripts')
    <script type='text/javascript'>
        $(function () {
            $(document).on("click", ".sh-detail", function (e) {
                e.preventDefault();
                $('.sh-content').html('<p class="mt-20, p-5">Loading content....</p>');
                $('.hk-chat-popup').addClass('d-flex');
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    dataType: 'html',
                    processData: false,
                    contentType: false,
                    type: 'get',
                    success: function (response) {
                        $(".sh-content").html(response);
                    },
                    error: function (response) {
                        $(".sh-content").html('<p class="mt-20, p-5">Something went wrong...</p>');
                    }
                });
                return false;
            });
            $(document).on("click", "#close_sd", function (e) {
                e.preventDefault();
                $('.hk-chat-popup').removeClass('d-flex');
                return false;
            });

            $(document).on('click', '.pop', function () {
                $('.imagepreview').attr('src', $(this).attr('src'));
                $('#imagemodal').modal('show');
            });
        });
    </script>    
    @endpush