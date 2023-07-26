<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name', 'Sales CRM') }}</title>
    <meta name="description" content="Sales CRM | Yesmachinery" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}">
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">

    <!-- CSS -->
    <link href="{{asset('dist/css/style.css')}}" rel="stylesheet" type="text/css">
</head>

<body>
    <!-- Wrapper -->
    <div class="hk-wrapper hk-pg-auth" data-footer="simple">
        <!-- Main Content -->
        <div class="hk-pg-wrapper pt-0 pb-xl-0 pb-5">
            <div class="hk-pg-body pt-0 pb-xl-0">
                <!-- Container -->
                @yield('content')
                <!-- /Container -->
            </div>
            <!-- /Page Body -->

            <!-- Page Footer -->
            <div class="hk-footer border-0">
                <footer class="container-xxl footer">
                    <div class="row">
                        <div class="col-xl-8 text-center">
                            <p class="footer-text pb-0"><span class="copy-text">Bigleap © 2023 All rights reserved.</span> <a href="http://bigleap.ae" class="" target="_blank">www.bigleap.ae</a></p>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- / Page Footer -->

        </div>
        <!-- /Main Content -->
    </div>
    <!-- /Wrapper -->

    <!-- jQuery -->
    <script src="{{asset('vendors/jquery/dist/jquery.min.js')}}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{asset('vendors/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script>

    <!-- FeatherIcons JS -->
    <script src="{{asset('dist/js/feather.min.js')}}"></script>

    <!-- Fancy Dropdown JS -->
    <script src="{{asset('dist/js/dropdown-bootstrap-extended.js')}}"></script>

    <!-- Simplebar JS -->
    <script src="{{asset('vendors/simplebar/dist/simplebar.min.js')}}"></script>

    <!-- Init JS -->
    <script src="{{asset('dist/js/init.js')}}"></script>
</body>

</html>