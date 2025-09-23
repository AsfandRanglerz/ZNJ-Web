<!DOCTYPE html>
<html lang="en">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ZNJ - Admin Dashboard</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/app.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{asset('public/admin/assets/toastr/css/toastr.css')}}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('public/admin/assets/img/logo.png')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- <link rel="stylesheet" href="{{ asset('public/admin/assets/css/datatables.css') }}"> --}}
    <link rel="stylesheet"
        href="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    {{-- ajax data tables css --}}
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/datatables/datatables.min.css') }}">
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

    @yield('css')

</head>

<body>
<div class="loader"></div>

<div id="app">
    <div class="main-wrapper main-wrapper-1">
        @include('admin.common.header')
        @include('admin.common.side_menu')
        @yield('content')
        @include('admin.common.footer')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<!-- General JS Scripts -->
<script src="{{ asset('public/admin/assets/js/app.min.js')}}"></script>
<!-- JS Libraies -->
<script src="{{ asset('public/admin/assets/bundles/apexcharts/apexcharts.min.js')}}"></script>
<!-- Page Specific JS File -->
<script src="{{ asset('public/admin/assets/js/page/index.js')}}"></script>
<!-- Template JS File -->
<script src="{{ asset('public/admin/assets/js/scripts.js')}}"></script>
<!-- Custom JS File -->
<script src="{{ asset('public/admin/assets/js/custom.js')}}"></script>

<script src="{{asset('public/admin/assets/toastr/js/toastr.min.js')}}"></script>
{{-- <script src="{{ asset('public/admin/assets/js/datatables.js') }}"></script> --}}
<!-- ajax datables -->
    {{-- DataTbales --}}
    <script src="{{ asset('public/admin/assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
<script>
    /*toastr popup function*/
    function toastrPopUp() {
        toastr.options = {
            "closeButton": true,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "3000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    }

    /*toastr popup function*/
    toastrPopUp();


</script>
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

@yield('js')
<script>window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 4000);</script>
@yield('scripts')
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>
