@extends('web.layout.app')

@section('title','Forgot Password')

@section('content')
<div class="container mt-5 mb-5 form-container">

  <!-- Forgot Password Form -->
  <form action="{{ route('recruiter.sendOtp') }}" method="POST" class="login-form">
      @csrf

      <!-- Heading -->
      <h3 class="text-white mb-4">Forgot Password</h3>

      <!-- Email -->
      <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Email</label>
          <input 
              type="email" 
              class="form-control" 
              id="exampleInputEmail1"  
              name="email"
              value="{{ old('email') }}"
              placeholder="Enter your email" 
              aria-describedby="emailHelp"
              required>

          <!-- Error Message -->
          @error('email')
              <span class="text-warning validation-error">{{ $message }}</span>
          @enderror
      </div>

      <!-- Continue Button -->
      <button type="submit" class="btn submit-btn-for-forms mt-2">Continue</button>
  </form>

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
