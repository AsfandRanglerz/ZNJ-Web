@extends('web.layout.app')

@section('title','Set Password')

@section('content')
<div class="container mt-5 mb-5 form-container">

  <!-- Password Form -->
  <form action="{{ route('recruiter.setPassword') }}" method="POST" class="login-form">
      @csrf

      <!-- Heading -->
      <h3 class="text-white mb-4">Set Password</h3>

      <!-- Password -->
      <div class="mb-1 position-relative">
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

      <!-- Confirm Password -->
      <div class="position-relative mb-1 mt-2">
        <label for="confirm_password" class="form-label d-flex justify-content-between align-items-center">
          Confirm Password
          <span>
            <i class="fa fa-eye-slash toggle-password" toggle="#confirm_password" style="cursor:pointer;"></i>
            <small class="mx-2">Hide</small>
          </span>
        </label>
        <input 
          type="password" 
          class="form-control" 
          id="confirm_password" 
          name="password_confirmation"
          placeholder="Confirm your password" 
          required>
        @error('password_confirmation')
          <small class="text-warning validation-error">{{ $message }}</small>
        @enderror
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn mt-3 submit-btn-for-forms">Continue</button>
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

        // Password Toggle
        $(".toggle-password").click(function() {
            let input = $($(this).attr("toggle"));
            let labelSpan = $(this).closest("span"); // 👈 parent span jisme icon + small dono hain

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


