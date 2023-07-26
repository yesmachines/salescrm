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
                                <h1>Pending Orders</h1>
                            </a>
                        </div>
                        <!-- <div class="dropdown ms-3">
                            <button class="btn btn-sm btn-outline-secondary flex-shrink-0 dropdown-toggle d-lg-inline-block d-none" data-bs-toggle="dropdown">Create New</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#add_new_country">Add New Country</a>
                            </div>
                        </div> -->
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
                                        <th>Company</th>
                                        <th>Po No</th>
                                        <th>Po Date</th>
                                        <th>Po Recieved</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $i =>$order)

                                    @switch($order->status)
                                    @case('open')
                                    @php $clsStat = 'info';@endphp
                                    @break
                                    @case('partial')
                                    @php $clsStat = 'warning';@endphp
                                    @break

                                    @endswitch
                                    <tr>
                                        <td>
                                            {{ ++$i }}
                                        </td>
                                        <td>{{$order->company->company}}</td>
                                        <td>{{$order->po_number }}
                                            <span class="badge badge-soft-purple my-1  me-2">
                                                {{$order->yespo_no }}
                                            </span>
                                        </td>
                                        <td>{{$order->po_date }}</td>
                                        <td>{{$order->po_received }}</td>
                                        <td><span class="badge badge-soft-{{$clsStat}} my-1  me-2">
                                                {{$order->status }}
                                            </span>


                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex">
                                                    @if($order->short_link_code!="")
                                                        <input type="hidden" id="copyTextVal{{$order->id}}" value="{{env('APP_FRONT_URL')}}track/{{$order->short_link_code}}">
                                                        <button class="btnCopyToClipboard btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" id="{{$order->id}}" data-bs-toggle="tooltip" data-placement="top" title=""><span class="icon"><span class="feather-icon"><i data-feather="copy"></i></span></span></button>
                                                    @endif
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" data-bs-toggle="tooltip" data-placement="top" title="" data-bs-original-title="View" href="{{ route('orders.show', $order->id) }}"><span class="icon"><span class="feather-icon"><i data-feather="eye"></i></span></span></a>
                                                    <a class="btn btn-icon btn-flush-dark btn-rounded flush-soft-hover" href="{{route('orders.edit',$order->id)}}"><span class="icon"><span class="feather-icon"><i data-feather="edit"></i></span></span></a>

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

            <!-- /Create Info -->
            <!-- Edit Info -->

            <!-- /Edit Info -->
        </div>
    </div>
</div>
<script>
    function copyToClipboard(text) {

            var textArea = document.createElement( "textarea" );
            textArea.value = text;
            document.body.appendChild( textArea );

            textArea.select();

            try {
            var successful = document.execCommand( 'copy' );
            var msg = successful ? 'successful' : 'unsuccessful';
            console.log('Copying text command was ' + msg);
            } catch (err) {
            console.log('Oops, unable to copy');
            }

            document.body.removeChild( textArea );
    }
    $('.btnCopyToClipboard').click( function()
    {
        var id = $(this).attr("id");

         var clipboardText = "";

         clipboardText = $('#copyTextVal'+id).val();
         copyToClipboard(clipboardText);
         alert( "Copied to Clipboard" );
    });
</script>
@endsection
