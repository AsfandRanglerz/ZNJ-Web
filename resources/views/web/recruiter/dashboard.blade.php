@extends('web.recruiter.layout.app')

@section('title', 'Dashboard')

@section('content')




<div class="container bg-white min-vh-100 create-event-container-div">
  <div class="bg-white p-4 rounded dashboard-div-main">
    
    <h3 class="dashboard-heading">WELCOME TO YOUR DASHBOARD</h3>
    <p>Quick access to your event management.</p>

    <div class="row g-4 mt-3 justify-content-center dash-card-main-div">
      
      <!-- Card 1 -->
      <div class="col-md-4 col-12 dash-card-div">
        <a href="{{ route('web.recruiter.myevents') }}" class="d-flex align-items-center bg-white shadow p-3 rounded text-decoration-none text-dark">
          <div class="d-flex align-items-center justify-content-center bg-warning rounded-circle mt-1 icon-circle">
            <img src="public/web/assets/images/dashevent.png" alt="Event Icon" class="pt-0 dash-card-image">
          </div>
          <div class="ms-3 pt-2">
            <div class="dashboard-text-div-top ">Created Events</div>
                 <div class="dashboard-text-div-bottom ">{{ $createdEvents }}</div>
          
          </div>
        </a>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4 col-12 dash-card-div">
        <a href="{{ route('web.recruiter.myevents') }}" class="d-flex align-items-center bg-white shadow p-3 rounded text-decoration-none text-dark">
          <div class="d-flex align-items-center justify-content-center bg-warning rounded-circle mt-1 icon-circle">
            <img src="public/web/assets/images/dashticket.png" alt="Event Icon" class="pt-0 dash-card-image">
          </div>
          <div class="ms-3 pt-2">
            <div class="dashboard-text-div-top ">Joined Events</div>
                 <div class="dashboard-text-div-bottom ">{{ $joinedEvents }}</div>
          
          </div>
        </a>
      </div>

      <!-- Card 3 -->
      <div class="col-md-4 col-12 dash-card-div">
        <a href="{{ route('web.recruiter.myticket') }}" class="d-flex align-items-center bg-white shadow p-3 rounded text-decoration-none text-dark">
          <div class="d-flex align-items-center justify-content-center bg-warning rounded-circle mt-1 icon-circle">
            <img src="public/web/assets/images/dashticket.png" alt="Event Icon" class="pt-0 dash-card-image">
          </div>
          <div class="ms-3 pt-2">
            <div class="dashboard-text-div-top ">My Tickets</div>
                 <div class="dashboard-text-div-bottom ">{{ $eventTicket }}</div>
          
          </div>
        </a>
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
@endsection