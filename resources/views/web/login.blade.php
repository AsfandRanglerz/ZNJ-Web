@extends('web.layout.app')

@section('title','Login')

@section('content')
<div class="container mt-5 mb-5 form-container">

  <!-- Login Form -->
  <form action="{{ route('recruiter.login') }}" method="POST" class="login-form">
      @csrf

      <!-- Heading -->
      <h3 class="text-white mb-4">Log In</h3>

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input 
          type="email" 
          class="form-control" 
          id="email"  
          name="email"
          value="{{ old('email') }}"
          placeholder="Enter your email" 
          required>
        @error('email')
          <small class="text-warning validation-error">{{ $message }}</small>
        @enderror
      </div>

      <!-- Password -->
      <div class="mb-1 mt-2 position-relative">
        <label for="password" class="form-label d-flex justify-content-between align-items-center">
          Password
          <span>
            <i class="fa fa-eye-slash toggle-password" toggle="#password" style="cursor:pointer;"></i>
            <small class="mx-2">Hide</small>
          </span>
        </label>
        <input 
          type="password" 
          class="form-control" 
          id="password"  
          name="password"
          placeholder="Enter your password" 
          required>
        @error('password')
          <small class="text-warning validation-error">{{ $message }}</small>
        @enderror
      </div>

      <!-- Remember Me -->
<div class="mb-3 form-check">
  <input type="checkbox" class="form-check-input" id="remember_fake">
  <label class="form-check-label check-box-for-remember" for="remember_fake">
    Remember me
  </label>
</div>

      <!-- Submit Button -->
      <button type="submit" class="btn submit-btn-for-forms">Login</button>

      <!-- Forgot Password -->
      <div class="mt-3 d-flex justify-content-center align-items-center">
        <a href="{{ route('web.forgotpassword') }}" class="text-decoration-none login-form-anchor">Forgot your password?</a>
      </div>

      <!-- Sign Up -->
      <div class="mt-3 d-flex justify-content-center align-items-center">
        <span>Don't have an account? 
          <a href="{{ route('web.signup') }}" class="text-decoration-none login-form-anchor">Sign up</a>
        </span>
      </div>

      <!-- Divider -->
      <div class="mt-5 d-flex justify-content-center align-items-center devider-for-login-form">
        or continue with
      </div>

      <!-- Google Icon -->
      <div class="mt-2 mb-5 d-flex justify-content-center align-items-center">
        <a href="{{ route('google.login') }}">
          <img src="{{ asset('public/web/assets/images/googlett.png') }}" alt="Google" class="google-anchor-image">
        </a>
      </div>
  </form>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Toastr Messages
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @elseif(session('error'))
            toastr.error("{{ session('error') }}");
        @elseif($errors->any())
            toastr.error("{{ $errors->first() }}"); 
        @endif

        // Password Toggle
        $(".toggle-password").click(function() {
            let input = $($(this).attr("toggle"));
            let labelSpan = $(this).closest("span");

            if (input.attr("type") === "password") {
                input.attr("type", "text");
                $(this).removeClass("fa-eye-slash").addClass("fa-eye");
                labelSpan.find("small").text("Show");
            } else {
                input.attr("type", "password");
                $(this).removeClass("fa-eye").addClass("fa-eye-slash");
                labelSpan.find("small").text("Hide");
            }
        });
    });

    // Validation error auto remove on click anywhere
    document.addEventListener("click", function() {
        document.querySelectorAll(".validation-error").forEach(el => el.remove());
    });
</script>
@endsection
