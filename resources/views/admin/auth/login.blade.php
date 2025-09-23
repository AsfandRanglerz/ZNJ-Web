@extends('admin.auth.layout.app')
@section('title', 'Login')
@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">

                            <div class=" text-center mt-3">
                                <img src="{{ asset('public/admin/assets/img/logo.png') }}" height="80px" width="">
                            </div>

                        <div class="card-body">
                            <form method="POST" action="{{url('login')}}" class="needs-validation" novalidate="">
                                @csrf
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email" placeholder="example@gmail.com" class="form-control" name="email" tabindex="1" required autofocus name="email">
                                    @error('email')
                                    <span class="text-danger">Email required</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label">Password</label>

                                    <div class="input-group">
                                        <input id="password" type="password" placeholder="password" class="form-control" name="password" tabindex="2" required>

                                        <div class="input-group-append">
                                            <span class="input-group-text" onclick="togglePassword()" style="cursor:pointer;">
                                                <i id="eyeIcon" class="fa fa-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @error('password')
                                    <span class="text-danger">{{$errors->first('password')}}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        {{-- <input type="checkbox" class="custom-control-input" tabindex="3" id="remember-me" name="remember"> --}}
                                        <div class="d-block">
                                            {{-- <label class="custom-control-label" for="remember-me">Remember Me</label> --}}
                                            <div class="float-right">
                                                <a href="{{url('admin-forgot-password')}}" class="text-small">
                                                    Forgot Password?
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-2">
                                    <button type="submit" class="btn btn-info btn-lg btn-block" tabindex="4">
                                        Login
                                    </button>
                                </div>
                            </form>
                            {{--<div class="text-center mt-4 mb-3">
                                <div class="text-job text-muted">Login With Social</div>
                            </div>
                            <div class="row sm-gutters">
                                <div class="col-6">
                                    <a class="btn btn-block btn-social btn-facebook">
                                        <span class="fab fa-facebook"></span> Facebook
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-block btn-social btn-twitter">
                                        <span class="fab fa-twitter"></span> Twitter
                                    </a>
                                </div>
                            </div>--}}
                        </div>
                    </div>
{{--                    <div class="mt-5 text-muted text-center">--}}
{{--                        Don't have an account? <a href="{{ route('admin.register') }}">Create One</a>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
@if(\Illuminate\Support\Facades\Session::has('error'))
        <script>
            toastr.error('{{\Illuminate\Support\Facades\Session::get('error')}}');
        </script>
    @endif
    @if(\Illuminate\Support\Facades\Session::has('message'))
        <script>
            toastr.success('{{\Illuminate\Support\Facades\Session::get('message')}}');
        </script>
    @endif
<script>
    function togglePassword() {
        const input = document.getElementById("password");
        const icon = document.getElementById("eyeIcon");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

@endsection
