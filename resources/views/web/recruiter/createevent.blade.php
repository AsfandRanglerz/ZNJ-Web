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
            <input type="text" name="title" class="form-control bg-white" value="{{ old('title') }}" placeholder="Enter event title">
            @error('title')
            <div class="text-warning">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-lg-6">
            <label class="form-label">Insert Cover Photo <span class="text-warning">*</span></label>
            <input type="file" name="cover_image" class="form-control form-control-lg bg-white">
            @error('cover_image')
            <div class="text-warning">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Event Information -->
        <div class="row mb-3">
          <div class="col-12">
            <label class="form-label">Event Information <span class="text-warning">*</span></label>
            <textarea name="about_event" class="form-control bg-white" rows="5" placeholder="Enter event information">{{ old('about_event') }}</textarea>
            @error('about_event')
            <div class="text-warning">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Date & Time -->
        <div class="row mb-3">
          <div class="col-lg-6 mb-lg-0 mb-3">
            <label class="form-label">Start Date <span class="text-warning">*</span></label>
            <input type="text" name="date" value="{{ old('date') }}" class="form-control bg-white" placeholder="Start Date">
            @error('date')
            <div class="text-warning">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-lg-6">
            <label class="form-label">End Date <span class="text-warning">*</span></label>
            <input type="text" name="end_date" value="{{ old('end_date') }}" class="form-control bg-white" placeholder="End Date">
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

        <!-- Joining Type, Ticket Price, No. of Seats -->
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

          <div class="col-lg-4 mb-lg-0 mb-3" id="ticket_price_div">
            <label class="form-label">Ticket Price <span class="text-warning">*</span></label>
            <input type="number" name="price" id="ticket_price" class="form-control bg-white" value="{{ old('price', 0) }}" placeholder="Enter ticket price">
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

        <!-- Event Type, Category, Entertainer -->
         <div class="row mb-3">
  <!-- Event Type -->
<div class="col-md-6 mb-lg-0 mb-3">
  <label class="form-label">Event Type <span class="text-warning">*</span></label>
  <select name="event_type" id="event_type" class="form-select form-select-lg bg-white select2">
    <option value="" disabled {{ old('event_type') ? '' : 'selected' }} hidden>Choose event type</option>
    <option value="Private" {{ old('event_type') == 'Private' ? 'selected' : '' }}>Private</option>
    <option value="Public" {{ old('event_type') == 'Public' ? 'selected' : '' }}>Public</option>
  </select>
  @error('event_type')
    <div class="text-warning">{{ $message }}</div>
  @enderror
</div>

  <!-- Category -->
  <div class="col-md-6 mb-lg-0 mb-3">
    <label class="form-label">Select Category <span id="category_required" class="text-warning">*</span></label>
    <select id="categoryDropdown" class="form-control form-control-lg bg-white select2" multiple>
      @foreach($categories as $category)
        <option value="{{ $category->id }}">{{ $category->category }}</option>
      @endforeach
    </select>
  </div>
</div>

<!-- Entertainer & Venue -->
<div class="row mb-3">
  <div class="col-md-6 mb-lg-0 mb-3">
    <label class="form-label">Select Entertainers <span id="entertainer_required" class="text-warning">*</span></label>
    <select name="entertainer_id[]" id="entertainerDropdown" class="form-control form-control-lg bg-white select2" multiple>
      <option value="">Select category first</option>
    </select>
    @error('entertainer_id')
    <div class="text-warning">{{ $message }}</div>
    @enderror
  </div>

  <div class="col-md-6 mb-lg-0 mb-3">
    <label class="form-label">Select Venue <span id="venue_required" class="text-warning">*</span></label>
    <select name="venue_id" id="venue" class="form-control form-control-lg bg-white">
      <option value="" disabled {{ old('venue_id') ? '' : 'selected' }} hidden>Choose Venue</option>
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
</div>


        <!-- Description -->
        <div class="row mt-3 mb-4">
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
$(document).ready(function() {

    // toastr messages
    @if (session('success'))
      toastr.success("{{ session('success') }}");
    @endif
    @if (session('error'))
      toastr.error("{{ session('error') }}");
    @endif
    @if($errors->any())
      @foreach($errors->all() as $error)
        toastr.error("{{ $error }}");
      @endforeach
    @endif

    // Category → Entertainers dynamic loading
    $('#categoryDropdown').on('change', function() {
        let categoryIds = $(this).val();
        $('#entertainerDropdown').html('<option>Loading...</option>');
        if (categoryIds.length > 0) {
            $.ajax({
                url: '{{ route("entertainers.byCategory") }}',
                type: 'GET',
                data: { category_ids: categoryIds },
                success: function(response) {
                    $('#entertainerDropdown').empty();
                    if (response.length > 0) {
                        $.each(response, function(index, entertainer) {
                            $('#entertainerDropdown').append(`<option value="${entertainer.id}">${entertainer.name}</option>`);
                        });
                    } else {
                        $('#entertainerDropdown').append('<option>No entertainers found</option>');
                    }
                },
                error: function() {
                    $('#entertainerDropdown').html('<option>Error loading entertainers</option>');
                }
            });
        } else {
            $('#entertainerDropdown').html('<option>Select category first</option>');
        }
    });

    // ✅ Handle Joining Type (Free → hide ticket price)
    function handleJoiningType() {
        const joiningType = $('#joining_type').val();
        if (joiningType === 'Free') {
            $('#ticket_price_div').hide();
            $('#ticket_price').val(0);
        } else {
            $('#ticket_price_div').show();
            $('#ticket_price').val('');
        }
    }

    // ✅ Handle Event Type (Private → optional / Public → required)
    function handleEventType() {
        const eventType = $('#event_type').val();
        if (eventType === 'Private') {
            $('#entertainerDropdown').removeAttr('required');
            $('#venue').removeAttr('required');
            $('#entertainer_required').hide();
            $('#venue_required').hide();
            $('#category_required').hide();
        } else if (eventType === 'Public') {
            $('#entertainerDropdown').attr('required', 'required');
            $('#venue').attr('required', 'required');
            $('#entertainer_required').show();
            $('#venue_required').show();
            $('#category_required').show();
        }
    }

    // Initialize on page load
    handleJoiningType();
    handleEventType();

    // Bind change events
    $('#joining_type').on('change', handleJoiningType);
    $('#event_type').on('change', handleEventType);
           console.log("Flatpickr initializing...");

flatpickr("input[name='date']", {
    dateFormat: "Y-m-d",
    minDate: "today",
    allowInput: true,
    disableMobile: true, // disables phone's native picker
    altInput: true,
    altFormat: "d M Y",
    appendTo: document.body,
    onChange: function(selectedDates, dateStr, instance) {
        // Automatically set minDate of end_date after selecting start date
        const endPicker = document.querySelector("input[name='end_date']")?._flatpickr;
        if (endPicker && selectedDates.length > 0) {
            endPicker.set('minDate', dateStr);
        }
    }
});

flatpickr("input[name='end_date']", {
    dateFormat: "Y-m-d",
    allowInput: true,
    disableMobile: true,
    altInput: true,
    altFormat: "d M Y",
    appendTo: document.body,
});

});
</script>

@endsection
@push('scripts')
<script>
  $(document).ready(function() {
    $('#event_type').select2({
      placeholder: "Choose event type",
      allowClear: true,
      width: '100%'
    });
  });
</script>
@endpush
