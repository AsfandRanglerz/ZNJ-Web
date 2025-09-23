<!DOCTYPE html>
<html lang="en">
<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>ZNJ - @yield('title')</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/bundles/bootstrap-social/bootstrap-social.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('public/admin/assets/img/logo.png')}}" />
    <link rel="stylesheet" href="{{asset('public/admin/assets/toastr/css/toastr.css')}}">

    @yield('style')
</head>

<body>
<div class="loader"></div>
<div id="app">
@yield('content')
</div>
<!-- General JS Scripts -->
<script src="{{ asset('public/admin/assets/js/app.min.js') }}"></script>
<!-- Template JS File -->
<script src="{{ asset('public/admin/assets/js/scripts.js') }}"></script>
<!-- Custom JS File -->
<script src="{{ asset('public/admin/assets/js/custom.js') }}"></script>
<!-- Sweet Alert -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('public/admin/assets/toastr/js/toastr.min.js')}}"></script>

</body>
<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->
@yield('script')
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
@yield('js')
<script>window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 4000);</script>
@yield('scripts')
</html>
