@extends('web.recruiter.layout.app')

@section('title', 'My Profile')

@section('content')
<div class="container p-4 create-event-container-main-div">
  <div class="text-white create-event-container-div">

    <div class="container">
      <!-- Profile Form Start -->
      <form class="p-4 rounded text-white" action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')
        
        <!-- Heading -->
        <h2 class="mb-4 text-white">My Profile</h2>

        <!-- Profile Image -->
        <div class="d-flex justify-content-center pt-4 pb-4 mb-4">
          <div class="position-relative d-inline-block">
            <img src="{{ $user->image ? asset($user->image) : asset('web/assets/images/myprofileimg.png') }}" 
                 class="rounded-circle myprofile-image" 
                 alt="Profile Image">

            <!-- Edit Icon -->
            <label class="position-absolute mb-4 bottom-0 end-0 bg-warning rounded-circle d-flex align-items-center justify-content-center text-white profile-edit-icon">
              <i class="bi bi-pencil-square fs-5"></i>
              <input type="file" name="image" hidden>
            </label>
            @error('image')
              <span class="text-warning validation-error">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Row 1: Name & Email -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control bg-white"
                   value="{{ old('name', $user->name) }}">
            @error('name')
              <span class="text-warning validation-error">{{ $message }}</span>
            @enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control bg-white"
                   value="{{ old('email', $user->email) }}" >
            @error('email')
              <span class="text-warning validation-error">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Row 2: Phone & Designation -->
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control bg-white"
                   value="{{ old('phone', $user->phone) }}">
            @error('phone')
              <span class="text-warning validation-error">{{ $message }}</span>
            @enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control bg-white"
                   value="{{ old('designation', $user->designation) }}">
            @error('designation')
              <span class="text-warning validation-error">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Row 3: Password & Confirm Password -->
        <div class="row mb-4">
          <div class="col-md-6">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Password</span>
              <span>
                <i class="fa fa-eye-slash toggle-password" toggle="#password" style="cursor:pointer;"></i>
                <small class="mx-2">Hide</small>
              </span>
            </label>
            <input type="password" id="password" name="password" class="form-control bg-white" placeholder="Enter password">
            @error('password')
              <span class="text-warning validation-error">{{ $message }}</span>
            @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label d-flex justify-content-between align-items-center">
              <span>Confirm Password</span>
              <span>
                <i class="fa fa-eye-slash toggle-password" toggle="#password_confirmation" style="cursor:pointer;"></i>
                <small class="mx-2">Hide</small>
              </span>
            </label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control bg-white" placeholder="Confirm password">
            @error('password_confirmation')
              <span class="text-warning validation-error">{{ $message }}</span>
            @enderror
          </div>
        </div>

        <!-- Update Button -->
        <div class="py-3 mb-3 d-flex justify-content-center align-items-center">
          <button type="submit" class="btn btn-dark px-5 text-black myprofile-update-btn">Update</button>
        </div>

      </form>
      <!-- Profile Form End -->
    </div>

  </div>
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
