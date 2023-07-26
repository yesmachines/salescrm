<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@hasSection('title')
        @yield('title')
        @else
        Sales CRM - Yesmachinery
        @endif
    </title>

    <meta name="description" content="A modern CRM Dashboard Template with reusable and flexible components for your SaaS web applications by hencework. Based on Bootstrap." />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">

    <!-- Daterangepicker CSS -->
    <link href="{{asset('vendors/daterangepicker/daterangepicker.css')}}" rel="stylesheet" type="text/css" />

    <!-- Data Table CSS -->
    <link href="{{asset('vendors/datatables.net-bs5/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendors/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Dropify CSS -->
    <link href="{{asset('vendors/dropify/dist/css/dropify.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- select2 CSS -->
    <link href="{{asset('vendors/select2/dist/css/select2.min.css')}}" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">


    <link href="{{asset('vendors/fullcalendar/main.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- CSS -->
    <link href="{{asset('dist/css/style.css')}}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />

    <!-- jQuery -->
    <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    @livewireStyles
</head>

<body>
    <!-- Wrapper -->
    <div class="hk-wrapper" data-layout="vertical" data-layout-style="default" data-menu="light" data-footer="simple">
        <!-- Top Navbar -->
        @include('layouts.partials._topnav')
        <!-- /Top Navbar -->

        <!-- Horizontal Nav -->
        @include('layouts.partials._header')
        <!-- /Horizontal Nav -->


        <!-- Main Content -->
        <div class="hk-pg-wrapper ">
            @yield('content')
            <!-- Page Footer -->
            <div class="hk-footer">
                <footer class="container-xxl footer">
                    <div class="row">
                        <div class="col-xl-8">
                            <p class="footer-text"><span class="copy-text">Bigleap © 2023 All rights reserved.</span> <a href="http://bigleap.ae" class="" target="_blank">www.bigleap.ae</a><span class="footer-link-sep"></p>
                        </div>
                        <div class="col-xl-4">

                        </div>
                    </div>
                </footer>
            </div>
            <!-- / Page Footer -->

        </div>
    </div>

    <!-- Bootstrap Core JS -->
    <script src="{{asset('vendors/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>

    <!-- FeatherIcons JS -->
    <script src="{{asset('dist/js/feather.min.js')}}"></script>

    <!-- Fancy Dropdown JS -->
    <script src="{{asset('dist/js/dropdown-bootstrap-extended.js')}}"></script>

    <!-- Simplebar JS -->
    <script src="{{asset('vendors/simplebar/dist/simplebar.min.js')}}"></script>

    <!-- Data Table JS -->
    <script src="{{asset('vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-bs5/js/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('vendors/datatables.net-select/js/dataTables.select.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendors/moment/min/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendors/daterangepicker/daterangepicker.js')}}"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

    <!-- Select2 JS -->
    <script src="{{asset('vendors/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{asset('dist/js/select2-data.js')}}"></script>

    <!-- Dropify JS -->
    <script src="{{asset('vendors/dropify/dist/js/dropify.min.js')}}"></script>
    <script src="{{asset('dist/js/dropify-data.js')}}"></script>

    <!-- Init JS -->
    <script src="{{asset('dist/js/init.js')}}"></script>
    <script src="{{asset('dist/js/contact-data.js')}}"></script>
    <script src="{{asset('dist/js/chips-init.js')}}"></script>

    <script src="{{asset('dist/js/daterangepicker-data.js')}}"></script>



    <script src="{{asset('vendors/fullcalendar/main.min.js')}}"></script>
    <script src="{{asset('dist/js/fullcalendar-init.js')}}"></script>

    <!-- Sweat Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    @livewireScripts
</body>

</html>