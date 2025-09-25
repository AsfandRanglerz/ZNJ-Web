@extends('web.recruiter.layout.app')

@section('title', 'Create Event')

@section('content')
<div class="container p-4 create-event-container-main-div">
  <div class="text-white create-event-container-div">

    <div class="container py-5">

      <!-- Form Start -->
     <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="p-4 rounded">
     @csrf
        <!-- Heading -->
        <h2 class="mb-4">Create Event</h2>

         <!-- Event Title & Cover Photo -->
    <div class="row mb-3">
        <div class="col-lg-6">
            <label class="form-label">Event Title</label>
            <input type="text" name="title" class="form-control bg-white" placeholder="Enter event title" required>
        </div>
        <div class="col-lg-6">
            <label class="form-label">Insert Cover Photo</label>
            <input type="file" name="cover_image" class="form-control form-control-lg bg-white">
        </div>
    </div>

    <!-- Event Information -->
    <div class="row mb-3">
        <div class="col-12">
            <label class="form-label">Event Information</label>
            <textarea name="about_event" class="form-control bg-white" rows="5" placeholder="Enter event information"></textarea>
        </div>
    </div>

    <!-- Date & Time -->
    <div class="row mb-3">
        <div class="col-lg-6">
            <label class="form-label">Start Date</label>
            <input type="date" name="date" class="form-control bg-white" required>
        </div>
        <div class="col-lg-6">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control bg-white" required>
        </div>
        <div class="col-lg-6 mt-3">
            <label class="form-label">Start Time</label>
            <input type="time" name="from" class="form-control bg-white" required>
        </div>
        <div class="col-lg-6 mt-3">
            <label class="form-label">End Time</label>
            <input type="time" name="to" class="form-control bg-white" required>
        </div>
    </div>

    <!-- Joining Fee, Ticket Price, No of Seats -->
    <div class="row mb-3">
        <div class="col-lg-4">
        <label class="form-label">Joining Type</label>
        <select name="joining_type" class="form-control form-control-lg bg-white" required>
            <option value="" disabled selected hidden >Choose joining type</option>
            <option value="Paid">Paid</option>
            <option value="Free">Free</option>
        </select>
      </div>

        <div class="col-lg-4">
            <label class="form-label">Ticket Price</label>
            <input type="number" name="price" class="form-control bg-white" placeholder="Enter ticket price">
        </div>
        <div class="col-lg-4">
            <label class="form-label">No. of Seats</label>
            <input type="number" name="seats" class="form-control bg-white" placeholder="Enter seats">
        </div>
    </div>

        <!-- Entertainer, Venue, Event Type -->
        <div class="row mb-3">
          <div class="col-lg-4 ">
            <label class="form-label">Select Entertainers</label>
            <select name="entertainer_id[]" class="form-control form-control-lg bg-white  select2" multiple>
                @foreach($entertainers as $entertainer)
                    <option value="{{ $entertainer->id }}" >
                        {{ $entertainer->user->name ?? 'Unnamed Entertainer' }}
                    </option>
                @endforeach
            </select>
            </div>


<div class="col-lg-4">
              <label class="form-label">Select Venue</label>
              <select name="venue_id" class="form-control form-control-lg bg-white" required>
                  <option value="" disabled selected hidden >Choose Venue</option>
                  @foreach($venues as $venue)
                      <option value="{{ $venue->id }}">
                          {{ $venue->venueCategory->category ?? 'No Category' }}
                      </option>
                  @endforeach
              </select>
          </div>

<div class="col-lg-4">
  <label class="form-label">Event Type</label>
  <select name="event_type" class="form-control form-control-lg bg-white" required>
    <option value="" disabled selected hidden>Choose event type</option>
    <option value="Private">Private</option>
    <option value="Public">Public</option>
  </select>
</div>



        <!-- Description -->
        <div class="container-fluid mt-3 mb-4">
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control bg-white" rows="6" placeholder="Enter event description"></textarea>
        </div>
    </div>

        <!-- Submit Button -->
        <div class="text-center">
          <button type="submit" class="btn btn-light text-black px-5 create-event-submit-btn">Submit</button>
        </div>

      </form>
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
    });




</script>
@endsection