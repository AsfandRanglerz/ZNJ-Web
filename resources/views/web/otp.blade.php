@extends('web.layout.app')

@section('title','OTP')

@section('content')
<div class="container mt-5 mb-5 form-container">

  <!-- OTP Form -->
  <form action="{{ route('recruiter.verifyOtp') }}" method="POST" class="login-form">
      @csrf

      <!-- Heading -->
      <h3 class="text-white mb-4">OTP Verification</h3>

      <!-- OTP Input -->
      <div class="mb-3">
          <label for="otp" class="form-label">OTP</label>
          <input 
              type="text" 
              class="form-control" 
              id="otp" 
              name="otp"
              placeholder="Enter your 4 digit OTP" 
              aria-describedby="otpHelp"
              required>

          <!-- Resend Button -->
          <div class="d-flex justify-content-end mt-0">
              <button 
                type="button" 
                onclick="event.preventDefault(); document.getElementById('resend-form').submit();" 
                class="btn resend-btn-otp-form">
                Resend
              </button>
          </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn submit-btn-for-forms mt-2">Next</button>
  </form>

  <!-- Hidden Resend Form -->
  <form id="resend-form" action="{{ route('recruiter.resendOtp') }}" method="POST" style="display:none;">
      @csrf
      <input type="hidden" name="email" value="{{ session('reset_email') }}">
  </form>

  <!-- Error Message -->
  @if(session('error'))
      <div class="alert alert-danger mt-3">
          {{ session('error') }}
      </div>
  @endif

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    });

    document.addEventListener("click", function() {
    document.querySelectorAll(".validation-error").forEach(el => 
        el.remove());
    });



</script>
