@extends('web.layout.app')

@section('title', 'Event Detail')

@section('content')
<!-- Event Search Div -->
<div class="container-fluid ">
    <img src="{{ asset($event->cover_image) }}" class="event-detail-top-image" alt="{{ $event->title }}">
</div>

<div class="text-center p-5 text-white">
    <h2>{{ $event->title }}</h2>
</div>

<!-- Calender and Time -->
<div class="container-fluid d-flex px-5 text-white">
  <div class="col-6 ">
   <span>
      <h3 class="heading-watch-calender">
        <img src="{{asset('public/web/assets/images/calender.png')}}" alt="" class="mx-3 calender-watch-image">
        {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
      </h3>
   </span>
  </div>
  <div class="col-6 text-end ">
   <span>
      <h3 class="heading-watch-calender">
        <img src="{{asset('public/web/assets/images/watch.png')}}" alt="" class="mx-3 calender-watch-image">
        {{ \Carbon\Carbon::parse($event->from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->to)->format('h:i A') }}
      </h3>
   </span>
  </div>
</div>

<!-- Ticket Venue Events -->
<ul class="nav nav-pills col-12 g-3 px-3 justify-content-center align-items-center" id="pills-tab" role="tablist">
  <li class="nav-item col-4 d-flex justify-content-center align-items-center" role="presentation">
    <button class="nav-link active btn-pill-venue-ticket-event" id="ticket-pill-tab" data-bs-toggle="pill" data-bs-target="#ticket-pill" type="button" role="tab" aria-controls="ticket-pill" aria-selected="true">Tickets</button>
  </li>
  <li class="nav-item col-4 d-flex justify-content-center align-items-center" role="presentation">
    <button class="nav-link btn-pill-venue-ticket-event" id="events-pill-tab" data-bs-toggle="pill" data-bs-target="#events-pill" type="button" role="tab" aria-controls="events-pill" aria-selected="false">Event Detail</button>
  </li>
  <li class="nav-item col-4 d-flex justify-content-center align-items-center" role="presentation">
    <button class="nav-link btn-pill-venue-ticket-event" id="venue-pill-tab" data-bs-toggle="pill" data-bs-target="#venue-pill" type="button" role="tab" aria-controls="venue-pill" aria-selected="false">Venue</button>
  </li>
</ul>

<div class="tab-content" id="pills-tabContent">
  <!-- Tickets Tab -->
  <div class="tab-pane fade show active join-fee-main-div" id="ticket-pill" role="tabpanel" aria-labelledby="ticket-pill-tab">
    <div class="container text-white d-flex py-3 join-fee ">
        <div class="col-7">
         <span>Joining Fee</span>
        </div>
        <div class="col-3">
          <span>Rs.{{ number_format($event->price, 0) }}</span>
        </div>
       <div class="col-2 text-end d-flex align-items-center justify-content-center btn-sign-plus-negative-div">
    <button class="btn btn-circle mx-2 plus-negative-btn decrement">-</button>
    <span class="mx-2 counter-value">1</span>
    <button class="btn btn-circle mx-2 plus-negative-btn increment">+</button>
</div>
    </div>
    <div class="col-12 d-flex p-5 justify-content-center align-items-center">
        <a href="javascript:void(0)" id="joinEventBtn" class="btn join-event-btn">Join Event</a>
    </div>
    <form id="ticketForm" action="{{ route('generate.ticket', $event->id) }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" id="ticketQuantity" name="quantity" value="1">
</form>
  </div>

  <!-- Event Detail Tab -->
  <div class="tab-pane fade text-white" id="events-pill" role="tabpanel" aria-labelledby="events-pill-tab">
    <div class="col-12 p-3 event-organizer-div">
        <h3 class="text-center ">Event Organizer</h3>
        <div class="p-3 d-flex align-items-center">
         <img src="{{ $event->organizer->image ?? asset('public/web/assets/images/avatar.png') }}" alt="" class="me-2 organizer-singer-musician-image">
          <span class="mx-3">
            <h4>{{ $event->organizer->name ?? 'Unknown' }}</h4>
            <p>Event Organizer</p>
          </span>
        </div>
    </div>

    <div class="col-12 p-4 description-div">
        <h3 class="text-center">Description</h3>
        <p>{{ $event->description }}</p>
    </div>

    <div class="container-fluid py-5 px-2 px-md-5 event-entertainer-div">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center">Entertainers</h3>
            </div>
        </div>
      <div class="row mt-4 g-0">
            @foreach($event->entertainers as $entertainer)
            <div class="col-12 col-md-6 d-flex align-items-center p-3">
                <img src="{{ asset($entertainer->image ?? 'public/web/assets/images/avatar.png') }}" alt="{{ $entertainer->user->name ?? '' }}" class="me-3 organizer-singer-musician-image img-fluid">
                <div>
                    <h4>{{ $entertainer->user->name ?? '' }}</h4>
                    <p>{{ $entertainer->description ?? '' }}</p>
                    <p>{{ $entertainer->talentCategory->category ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    
    </div>

    <div class="container my-3">
        <div class="row">
            <div class="col-12">
                <h3 class="text-center mb-4">User Reviews</h3>
            </div>
        </div>

        <div class="row g-3 user-review-main-div">
            @foreach($event->reviews as $review)
            <div class="col-md-3 col-6">
                <div class="p-3 bg-white text-black rounded shadow-sm h-100">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $review->user->image ?? asset('public/web/assets/images/user.png') }}" alt="User" class="rounded-circle me-3 review-user-image" >
                        <h6 class="mb-0">{{ $review->user->name }}</h6>
                    </div>
                    <p class="mb-3">{{ $review->message }}</p>
                    <div class="rating-review-user-div">
                        {{ str_repeat('★', $review->star) }}{{ str_repeat('☆', 5 - $review->star) }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-3 show-all-review-div">
          <span><p>Show All Reviews({{ $event->reviews->count() }})</p></span>
        </div>
    </div>
  </div>

  <!-- Venue Tab -->
  <div class="tab-pane fade text-white" id="venue-pill" role="tabpanel" aria-labelledby="venue-pill-tab">
    <div class="mt-3 text-center">
        <h3>Venue</h3>
    </div>

@if($event->venue)
    <div class="mt-5">
        <img src="{{ $event->venue->image ?? asset('public/web/assets/images/venue.jpg') }}" 
             alt="" class="venue-image">
    </div>
    <div class="p-4 mt-5">
        <span>
            <h4>
                <img src="{{asset('public/web/assets/images/venue-location.png')}}" 
                     alt="" class="mx-2">
                {{ $event->venue->address ?? '' }}
            </h4>
        </span>
        <div class="mt-3">
            <h2>{{ $event->venue->venueCategory->category ?? 'No Category' }}</h2>
            <p>{{ $event->venue->about_venue ?? '' }}</p>
        </div>
    </div>
    @endif

  </div>
</div>

<!-- All Events -->
<div class="row mt-3 mb-5 d-flex justify-content-center align-items-center main-div-contain-event-box">
  <div class="container-fluid mt-0 mb-4 row g-4 div-contain-event-boxes">
      <h3>More Events like this.</h3>
      @forelse($allEvents as $ev)
      <div class="col-md-3 col-sm-4 col-6 event-box-for-image-text">
        <a href="{{ route('event.detail', $ev->id) }}" class="text-decoration-none event-box-anchor">
          <img src="{{ asset($ev->cover_image) }}" alt="{{ $ev->title }}" class="event-box-iamge">
          <div class="text-white px-2 pt-2 pb-5 event-box-text-div">
            <p class="title-of-event">{{ $ev->title }}</p>
            <p class="pt-0 price-of-event-ticket">Rs.{{ number_format($ev->price, 0) }}</p>
          </div>
        </a>
      </div>
      @empty
        <p class="text-center text-white">No more events like this.</p>
      @endforelse
  </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const counterSpan = document.querySelector(".counter-value");
    const incrementBtn = document.querySelector(".increment");
    const decrementBtn = document.querySelector(".decrement");
    const hiddenInput = document.getElementById("ticketQuantity");
    const joinBtn = document.getElementById("joinEventBtn");

    incrementBtn.addEventListener("click", function() {
        let value = parseInt(counterSpan.textContent);
        counterSpan.textContent = ++value;
        hiddenInput.value = value;
    });

    decrementBtn.addEventListener("click", function() {
        let value = parseInt(counterSpan.textContent);
        if (value > 1) {
            counterSpan.textContent = --value;
            hiddenInput.value = value;
        }
    });

    // ✅ Go to generateTicket page with quantity
    joinBtn.addEventListener("click", function(e) {
        e.preventDefault();
        const qty = hiddenInput.value;
        const baseUrl = "{{ route('event.generateTicket', $event->id) }}";
        window.location.href = `${baseUrl}?quantity=${qty}`;
    });
});
</script>


@endsection