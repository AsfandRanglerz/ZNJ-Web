<nav class="navbar bg-black text-white dashboard-nav">
  <div class="container-fluid d-flex justify-content-between align-items-center">

    <!-- Left: Toggle Button -->
    <button class="btn p-0 m-0 border-2" type="button" id="sidebarToggle">
      <i class="fa fa-bars text-white fs-4 mt-1 px-2"></i> <!-- White Hamburger -->
    </button>

    <!-- Right: Profile Image + Name -->
    <div class="d-flex align-items-center">
      <span class="text-white me-2">
        {{ Auth::check() ? Auth::user()->name : 'Guest' }}
      </span>
      
    <img src="{{ Auth::check() && Auth::user()->image 
    ? asset(Auth::user()->image) 
    : asset('public/web/assets/images/avatar.png') }}" 
    alt="Profile Image"
        class="rounded-circle profile-image">
    </div>

  </div>
</nav>
