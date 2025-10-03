@extends('web.recruiter.layout.app')

@section('title', 'My Tickets')

@section('content')
<div class="container p-4 create-event-container-main-div">
  <div class="text-white create-event-container-div">

    <div class="px-3 pb-5 ">
      <h3 class="py-5 mx-4">MY TICKETS</h3>

      <div class="row g-3 justify-content-center">

        @forelse($tickets as $ticket)
        <!-- Dynamic Ticket -->
        <div class="col-lg-3 col-sm-4 col-6 event-ticket-div">
          <!-- Make the whole card clickable -->
          <a href="{{ route('event.detail', $ticket->event->id) }}" class="text-decoration-none text-dark">
            
            <!-- Top Black Section (Logo) -->
            <div class="bg-black d-flex justify-content-center align-items-center ticket-logo-div">
              <img src="{{  asset('public/web/assets/images/flogo.png') }}" 
                   alt="Logo" class="ticket-logo-image">
            </div>

            <!-- Bottom White Section -->
            <div class="bg-white text-black d-flex ticket-info-div">
              <div class="col-8  d-flex justify-content-between pt-2 pb-2">
                <ul class="list-unstyled left-content-of-ticket">
                  <li>Event:</li>
                  <li>Serial No:</li>
                  <li>Date:</li>
                  <li>Time:</li>
                </ul>
                <ul class="list-unstyled text-end ">
                  <li>{{ $ticket->event->title ?? 'N/A' }}</li>
                  <li>{{ $ticket->serial_no ?? 'N/A' }}</li>
                  <li>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d-m-Y') }}</li>
                  <li>{{ \Carbon\Carbon::parse($ticket->created_at)->format('h:i A') }}</li>
                </ul>
              </div>

              <!-- QR Code -->
              <div class="col-4 mt-2 pt-5 mx-4">
                @if($ticket->qr_code)
                <img src="{{ asset('public/'. $ticket->qr_code) }}" class="ticket-scan-image" alt="Scan">
                @endif
              </div>
            </div>
          </a>
        </div>
        @empty
          <p class="text-center mt-4">No tickets found.</p>
        @endforelse

      </div>
    </div>

  </div>
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

</script>