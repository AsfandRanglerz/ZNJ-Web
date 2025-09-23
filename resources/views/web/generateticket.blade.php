@extends('web.layout.app')

@section('title','Join Event')

@section('content')
<div class="container d-flex mt-5 w-100 mb-5 form-container-join-event">
  <form class="login-form row g-3 w-100" action="{{ route('generate.ticket', $event->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="quantity" value="{{ request('quantity', 1) }}">
      <h2 class="text-white text-center mb-4">Join Event</h2>

      <!-- Name -->
      <div class="col-md-6 col-12 mb-3 mt-2">
          <label for="name" class="form-label lebel-of-join-event-input">Name</label>
          <input type="text" class="form-control input-uniform" id="name" name="name"
                 value="{{ old('name', Auth::user()->name ?? '') }}"
                 placeholder="Enter your name" required>
      </div>

      <!-- Surname -->
      <div class="col-md-6 col-12 mb-3 mt-2">
          <label for="surname" class="form-label lebel-of-join-event-input">Surname</label>
          <input type="text" class="form-control input-uniform" id="surname" name="surname"
                 value="{{ old('surname') }}"
                 placeholder="Enter your surname">
      </div>

      <!-- Age -->
      <div class="col-md-6 col-12 mb-3 mt-2">
          <label for="age" class="form-label lebel-of-join-event-input">Age</label>
          <input type="number" class="form-control input-uniform" id="age" name="age"
                 value="{{ old('age') }}"
                 placeholder="Enter your age">
      </div>

      <!-- Phone -->
      <div class="col-md-6 col-12 mb-3 mt-2">
          <label for="phoneNo" class="form-label lebel-of-join-event-input">Phone</label>
          <input type="text" class="form-control input-uniform" id="phoneNo" name="phone"
                 value="{{ old('phone', Auth::user()->phone ?? '') }}"
                 placeholder="Enter your phone number">
      </div>

      <!-- Email -->
      <div class="col-12 mb-3 mt-2">
          <label for="email" class="form-label lebel-of-join-event-input">Email</label>
          <input type="email" class="form-control input-uniform" id="email" name="email"
                 value="{{ old('email', Auth::user()->email ?? '') }}"
                 placeholder="Enter your email" required>
      </div>

      <!-- Upload Your ID -->
<div class="col-md-8 col-12 mb-3 mt-2">
    <label for="idUpload" class="form-label lebel-of-join-event-input">Upload Your ID</label>
    <input type="file" class="form-control input-uniform" id="idUpload" name="photo">
</div>


      <!-- Gender -->
      <div class="col-md-4 col-12 mb-3 mt-2">
          <label for="gender" class="form-label lebel-of-join-event-input">Gender</label>
          <select id="gender" class="form-select input-uniform" name="gender">
              <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
              <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
              <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
              <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
          </select>
      </div>

      <!-- Submit Button -->
      <div class="col-12 mb-2 text-center d-flex justify-content-center align-items-center">
          <button type="submit" class="btn mt-4 submit-btn-for-genrate-ticket">Generate Ticket</button>
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
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if($errors->any())
            toastr.error("{!! implode('<br>', $errors->all()) !!}");
        @endif
    });

    document.addEventListener("click", function() {
    document.querySelectorAll(".validation-error").forEach(el => 
        el.remove());
    });



</script>