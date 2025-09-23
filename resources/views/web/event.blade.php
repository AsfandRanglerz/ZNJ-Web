@extends('web.layout.app')

@section('title', 'Event')

@section('content')

<div class="container-fluid event-page-search-city-date-div">
  <h1 class="mb-2 text-white">Events</h1>
  <p>Find events that match your search criteria.</p>

  <!-- Search Form -->
  <form id="event-search-form" method="GET" action="{{ route('web.events') }}">
    <div class="row g-2 mb-5 d-flex justify-content-center align-items-center">

      <!-- Search Input -->
      <div class="col-md-6 col-12">
        <div class="input-group">
          <input type="text" name="search" value="{{ request('search') }}" 
                 class="form-control rounded search-event-feild"  
                 placeholder="Search events">
        </div>
      </div>

      <!-- Search Button -->
      <div class="col-md-2 col-4">
        <button class="btn rounded w-100 search-city-date-btn" id="search-btn-in-event" type="submit">
          Search
        </button>
      </div>

      <!-- Select City -->
   <div class="col-md-2 col-4">
    <select name="city" class="form-select search-city-date-btn">
        <option selected disabled>Select Location</option>
        @foreach($cities as $city)
            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                {{ $city }}
            </option>
        @endforeach
    </select>
</div>

      <!-- Select Date -->
      <div class="col-md-2 col-4">
        <div class="input-group">
          <input type="text" name="date" value="{{ request('date') }}" 
                 class="form-control search-city-date-btn" 
                 placeholder="Select date"
                 onfocus="(this.type='date')" 
                 onblur="if(!this.value)this.type='text'">
          <span class="input-group-text"><i class="bi bi-calendar"></i></span>
        </div>
      </div>

    </div>
  </form>
</div>


<!-- Events List -->
<div class="row mt-3 mb-5 d-flex justify-content-center align-items-center main-div-contain-event-box" id="event-list-container">
  <div class="container mt-0 mb-4 row g-4 div-contain-event-boxes">
    <h3>All Events</h3>

    @forelse($events as $event)
  <div class="col-md-3 col-sm-4 col-6 event-box-for-image-text">
    <a href="{{ route('event.detail', $event->id) }}" class="text-decoration-none event-box-anchor">
      <img src="{{ asset($event->cover_image) }}" alt="{{ $event->title }}" class="event-box-iamge">
      <div class="text-white px-2 pt-2 pb-5 event-box-text-div">
        <p class="title-of-event">{{ $event->title }}</p>
        <p class="pt-0 price-of-event-ticket">Rs.{{ number_format($event->price, 0) }}</p>
      </div>
    </a>
  </div>
@empty
  <div class="col-12 text-center">
    <p>No events found.</p>
  </div>
@endforelse



  <!-- Pagination -->
@if ($events->hasPages())
  <nav aria-label="Page navigation" class="mt-5 mb-5">
    <ul class="pagination custom-pagination justify-content-center">

      {{-- Previous --}}
      @if ($events->onFirstPage())
        <li class="page-item disabled">
          <span class="page-link pagination-sign">&lt;</span>
        </li>
      @else
        <li class="page-item">
          <a class="page-link pagination-sign" href="{{ $events->previousPageUrl() }}">&lt;</a>
        </li>
      @endif

      {{-- Page Numbers --}}
      @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
        @if ($page == $events->currentPage())
          <li class="page-item active">
            <span class="page-link">{{ $page }}</span>
          </li>
        @else
          <li class="page-item">
            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
          </li>
        @endif
      @endforeach

      {{-- Next --}}
      @if ($events->hasMorePages())
        <li class="page-item">
          <a class="page-link pagination-sign" href="{{ $events->nextPageUrl() }}">&gt;</a>
        </li>
      @else
        <li class="page-item disabled">
          <span class="page-link pagination-sign">&gt;</span>
        </li>
      @endif

    </ul>
  </nav>
@endif

</div>

@endsection

@section('scripts')


<script>
document.addEventListener("DOMContentLoaded", function() {
    let form = document.getElementById("event-search-form");
    let eventListContainer = document.querySelector("#event-list-container");

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        let formData = new FormData(form);
        let queryString = new URLSearchParams(formData).toString();

        fetch("{{ route('web.events') }}?" + queryString, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, "text/html");
            let newContent = doc.querySelector("#event-list-container").innerHTML;
            eventListContainer.innerHTML = newContent;
        })
        .catch(err => console.error(err));
    });
});
</script>
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
@endsection
