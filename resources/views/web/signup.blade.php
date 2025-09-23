@extends('web.layout.app')

@section('title','Sign Up')

@section('content')
<div class="container mt-5 mb-5 form-container">

  <!-- Signup Form -->
  <form action="{{ route('recruiter.signup') }}" method="POST" class="login-form">
      @csrf

      <!-- Heading -->
      <h3 class="text-white mb-4">Sign Up</h3>

      <!-- Name -->
      <div class="mb-3 mt-2">
        <label for="name" class="form-label">Name</label>
        <input 
          type="text" 
          class="form-control" 
          id="name" 
          name="name"
          value="{{ old('name') }}"
          placeholder="Enter your name">
        @error('name')
          <small class="text-warning validation-error">{{ $message }}</small>
        @enderror
      </div>

      <!-- Phone -->
      <div class="mb-3 mt-2">
        <label for="phoneNo" class="form-label">Phone</label>
        <input 
          type="text" 
          class="form-control" 
          id="phoneNo" 
          name="phone"
          value="{{ old('phone') }}"
          placeholder="Enter your phone">
        @error('phone')
          <small class="text-warning validation-error">{{ $message }}</small>
        @enderror
      </div>

      <!-- Email -->
      <div class="mb-3 mt-2">
        <label for="exampleInputEmail1" class="form-label">Email</label>
        <input 
          type="email" 
          class="form-control" 
          id="exampleInputEmail1" 
          name="email"
          value="{{ old('email') }}"
          placeholder="Enter your email">
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
          placeholder="Enter your password">
        @error('password')
          <small class="text-warning validation-error">{{ $message }}</small>
        @enderror
      </div>

      <!-- Confirm Password -->
      <div class="position-relative mt-2">
        <label for="confirmPassword" class="form-label d-flex justify-content-between align-items-center">
          Confirm Password
          <span>
            <i class="fa fa-eye-slash toggle-password" toggle="#confirmPassword" style="cursor:pointer;"></i>
            <small class="mx-2">Hide</small>
          </span>
        </label>
        <input 
          type="password" 
          class="form-control" 
          id="confirmPassword" 
          name="password_confirmation"
          placeholder="Enter same password">
        @error('password_confirmation')
          <small class="text-warning validation-error">{{ $message }}</small>
        @enderror
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn mt-5 submit-btn-for-forms">Create Account</button>
  </form>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
  // Toastr Messages
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if($errors->any())
            toastr.error("{!! implode('<br>', $errors->all()) !!}");
        @endif

        // Password Toggle with "Hide / Show"
        $(".toggle-password").click(function() {
            let input = $($(this).attr("toggle"));
            let labelSpan = $(this).closest("span"); // 👈 span ke andar icon + small text dono hain

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

