@extends('web.recruiter.layout.app')

@section('title', 'My Events')

@section('content')
<div class="container p-4 create-event-container-main-div">
  <div class="text-white p-3 create-event-container-div">

    <ul class="nav col-sm-6 col-8 nav-pills mx-4 mb-3 py-1 bg-black rounded" id="pills-tab" role="tablist">
      <li class="nav-item create-join-recruiter-pills px-2 rounded" role="presentation">
        <button class="nav-link active create-join-recruiter-pills-btn" id="create-event-tab" data-bs-toggle="pill" data-bs-target="#create-event" type="button" role="tab" aria-controls="create-event" aria-selected="true">
          Created Events
        </button>
      </li>
      <li class="nav-item create-join-recruiter-pills px-2 rounded" role="presentation">
        <button class="nav-link create-join-recruiter-pills-btn" id="join-event-tab" data-bs-toggle="pill" data-bs-target="#join-event" type="button" role="tab" aria-controls="join-event" aria-selected="false">
          Joined Events
        </button>
      </li>
    </ul>

    <div class="tab-content p-0" id="pills-tabContent">
      
      <!-- Created Events -->
      <div class="tab-pane fade show p-0 active" id="create-event" role="tabpanel" aria-labelledby="create-event-tab">
        <div class="row mt-3 mb-5 p-0 d-flex justify-content-center align-items-center main-div-contain-event-box">
          <div class="container-fluid p-0 mt-0 mb-4 row g-4 div-contain-event-boxes">
            <h3>EVENTS YOU HAVE CREATED</h3>
            
            @forelse($createdEvents as $event)
              <div class="col-lg-3 col-sm-4 col-6 event-box-for-image-text">
                <a href="{{ route('event.detail', ['id' => $event->id]) }}" class="text-decoration-none event-box-anchor-recruiter">
                  <img src="{{ asset($event->cover_image) }}" alt="" class="event-box-iamge-recruiter">

                  <div class="text-white px-2 pt-2 pb-5 event-box-text-div-recruiter">
                    <p class="title-of-event">{{ $event->title }}</p>
                    <p class="pt-0 price-of-event-ticket-recruiter">Rs.{{ $event->price ?? 'Free' }}</p>
                    <button type="button" class="btn btn-sm btn-blak position-absolute bottom-0 end-0 m-0 p-2">
                      <img src="{{ asset('public/web/assets/images/edit-icon.png') }}" class="edit-icon-create-join-event-recruiter-image" alt="Edit">
                    </button>
                  </div>
                </a>
              </div>
            @empty
              <p>No events created.</p>
            @endforelse

          </div>
        </div>
      </div>

      <!-- Joined Events -->
      <div class="tab-pane fade" id="join-event" role="tabpanel" aria-labelledby="join-event-tab">
        <div class="row mt-3 mb-5 p-0 d-flex justify-content-center align-items-center main-div-contain-event-box">
          <div class="container-fluid p-0 mt-0 mb-4 row g-4 div-contain-event-boxes">
            <h3>EVENTS YOU HAVE JOINED</h3>
            
            @forelse($joinedEvents as $event)
              <div class="col-lg-3 col-sm-4 col-6 event-box-for-image-text">
                <a href="{{ route('event.detail', ['id' => $event->id]) }}" class="text-decoration-none event-box-anchor-recruiter">
                  <img src="{{ asset($event->cover_image) }}" alt="" class="event-box-iamge-recruiter">

                  <div class="text-white px-2 pt-2 pb-5 event-box-text-div-recruiter">
                    <p class="title-of-event">{{ $event->title }}</p>
                    <p class="price-of-event-ticket-recruiter">Rs.{{ $event->price ?? 'Free' }}</p>
                    <button type="button" class="btn btn-sm btn-blak position-absolute bottom-0 end-0 m-0 p-2">
                      <img src="{{ asset('public/web/assets/images/edit-icon.png') }}" class="edit-icon-create-join-event-recruiter-image" alt="Edit">
                    </button>
                  </div>
                </a>
              </div>
            @empty
              <p>No events joined.</p>
            @endforelse

          </div>
        </div>
      </div>

    </div>
  </div>   
</div>
@endsection
