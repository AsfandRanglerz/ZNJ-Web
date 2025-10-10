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
        <div class="col-lg-6 mb-lg-0 mb-3">
            <label class="form-label">Event Title <span class="text-warning">*</span></label>
            <input type="text" name="title" class="form-control bg-white"  value="{{ old('title') }}" placeholder="Enter event title">
            @error('title')
            <div class="text-warning">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-6">
            <label class="form-label">Insert Cover Photo <span class="text-warning">*</span></label>
            <input type="file" name="cover_image"  class="form-control form-control-lg bg-white">
            @error('cover_image')
            <div class="text-warning">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Event Information -->
    <div class="row mb-3">
        <div class="col-12">
            <label class="form-label">Event Information <span class="text-warning">*</span></label>
            <textarea name="about_event" class="form-control bg-white" rows="5"  placeholder="Enter event information">{{ old('about_event') }}</textarea>
            @error('about_event')
            <div class="text-warning">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Date & Time -->
    <div class="row mb-3">
        <div class="col-lg-6 mb-lg-0 mb-3">
            <label class="form-label">Start Date <span class="text-warning">*</span></label>
            <input type="date" name="date" value="{{ old('date') }}" class="form-control bg-white">
            @error('date')
            <div class="text-warning">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-6">
            <label class="form-label">End Date <span class="text-warning">*</span></label>
            <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control bg-white">
            @error('end_date')
            <div class="text-warning">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-6 mt-3">
            <label class="form-label">Start Time <span class="text-warning">*</span></label>
            <input type="time" name="from" value="{{ old('from') }}" class="form-control bg-white">
            @error('from')
            <div class="text-warning">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-6 mt-3">
            <label class="form-label">End Time <span class="text-warning">*</span></label>
            <input type="time" name="to" value="{{ old('to') }}" class="form-control bg-white">
            @error('to')
            <div class="text-warning">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Joining Fee, Ticket Price, No of Seats -->
   <div class="row mb-3">
    <div class="col-lg-4 mb-lg-0 mb-3">
        <label class="form-label">Joining Type <span class="text-warning">*</span></label>
        <select name="joining_type" id="joining_type" class="form-control form-control-lg bg-white">
            <option value="" disabled {{ old('joining_type') ? '' : 'selected' }} hidden>Choose joining type</option>
            <option value="Paid" {{ old('joining_type') == 'Paid' ? 'selected' : '' }}>Paid</option>
            <option value="Free" {{ old('joining_type') == 'Free' ? 'selected' : '' }}>Free</option>
        </select>
        @error('joining_type')
            <div class="text-warning">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-4 mb-lg-0 mb-3">
        <label class="form-label">Ticket Price <span class="text-warning">*</span></label>
        <input type="number" name="price" id="ticket_price" class="form-control bg-white" 
               value="{{ old('price', 0) }}" placeholder="Enter ticket price">
        @error('price')
            <div class="text-warning">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-lg-4">
        <label class="form-label">No. of Seats <span class="text-warning">*</span></label>
        <input type="number" name="seats" class="form-control bg-white" value="{{ old('seats') }}" placeholder="Enter seats">
        @error('seats')
            <div class="text-warning">{{ $message }}</div>
        @enderror
    </div>
</div>

        <!-- Entertainer, Venue, Event Type -->
 

  
@php
    // Group entertainers by their user_id (unique entertainer)
    $groupedEntertainers = $entertainers->groupBy('user_id');
@endphp

<div class="row mb-3">
  <div class="col-lg-4 mb-lg-0 mb-3">
    <label class="form-label">Select Entertainers <span class="text-warning">*</span></label>
    <select name="entertainer_id[]" class="form-control form-control-lg bg-white select2" multiple>
        @foreach($groupedEntertainers as $userId => $group)
            @php
                $entertainer = $group->first();
                $name = $entertainer->user->name ?? 'Unnamed Entertainer';
                // collect all professions for this entertainer
                $professions = $group->map(fn($e) => $e->talentCategory->category ?? 'N/A')->unique()->implode(', ');
            @endphp
            <option value="{{ $entertainer->id }}" 
                @if(collect(old('entertainer_id'))->contains($entertainer->id)) selected @endif>
                {{ $name }} - {{ $professions }}
            </option>
        @endforeach
    </select>
    @error('entertainer_id')
      <div class="text-warning">{{ $message }}</div>
    @enderror
  </div>








            <div class="col-lg-4 mb-lg-0 mb-3">
              <label class="form-label">Select Venue <span class="text-warning">*</span></label>
              <select name="venue_id" class="form-control form-control-lg bg-white">
                  <option value="" disabled {{ old('venue_id') ? '' : 'selected' }} selected hidden >Choose Venue</option>
                  @foreach($venues as $venue)
                      <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>
                          {{ $venue->venueCategory->category ?? 'No Category' }}
                      </option>
                  @endforeach
              </select>
                @error('venue_id')
                <div class="text-warning">{{ $message }}</div>
                @enderror
          </div>

            <div class="col-lg-4">
            <label class="form-label">Event Type <span class="text-warning">*</span></label>
            <select name="event_type" class="form-control form-control-lg bg-white">
                <option value="" disabled {{ old('event_type') ? '' : 'selected' }} selected hidden>Choose event type</option>
                <option value="Private" {{ old('event_type') == 'Private' ? 'selected' : '' }}>Private</option>
                <option value="Public" {{ old('event_type') == 'Public' ? 'selected' : '' }}>Public</option>
            </select>
                @error('event_type')
                <div class="text-warning">{{ $message }}</div>
                @enderror
            </div>

        <!-- Description -->
        <div class="container-fluid mt-3 mb-4">
        <div class="col-12">
            <label class="form-label">Description <span class="text-warning">*</span></label>
            <textarea name="description" class="form-control bg-white" rows="6" placeholder="Enter event description">{{ old('description') }}</textarea>
            @error('description')
            <div class="text-warning">{{ $message }}</div>
            @enderror
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
    $(document).ready(function () {

        // Display success message
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        // Display error message
        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

      @if($errors->any())
    @foreach($errors->all() as $error)
        toastr.error("{{ $error }}");
    @endforeach
    @endif
    });
</script>
@endsection
