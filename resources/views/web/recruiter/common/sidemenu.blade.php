<!-- Sidebar -->
<div id="dashboardSidebar" class="sidebar bg-black">
    <!-- Logo -->
    <a href="https://znjevents.com/" class="logo-dashboard-anchor">
        <img src="{{ asset('public/web/assets/images/flogo.png') }}" alt="Logo" class="sidebar-logo dashboard-logo">
    </a>

    <!-- Sidebar Menu -->
    <ul class="sidebar-menu mt-5">
        <li class="sidebar-item">
    <a href="{{ url('dashboard') }}" 
       class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
        <img src="{{ asset('public/web/assets/images/dashboard.png') }}" class="sidebar-icon"> Dashboard
    </a>
</li>

<li class="sidebar-item">
    <a href="{{ url('events') }}" 
       class="sidebar-link {{ request()->is('events') ? 'active' : '' }}">
        <img src="{{ asset('public/web/assets/images/myevent.png') }}" class="sidebar-icon"> All Events
    </a>
</li>
<li class="sidebar-item">
    <a href="{{ url('eventcreate') }}" 
       class="sidebar-link {{ request()->is('eventcreate') ? 'active' : '' }}">
        <img src="{{ asset('public/web/assets/images/createevent.png') }}" class="sidebar-icon"> Create Event
    </a>
</li>
<li class="sidebar-item">
    <a href="{{ url('myevents') }}" 
       class="sidebar-link {{ request()->is('myevents') ? 'active' : '' }}">
        <img src="{{ asset('public/web/assets/images/myevent.png') }}" class="sidebar-icon"> My Events
    </a>
</li>

<li class="sidebar-item">
    <a href="{{ url('mytickets') }}" 
       class="sidebar-link {{ request()->is('mytickets') ? 'active' : '' }}">
        <img src="{{ asset('public/web/assets/images/myticket.png') }}" class="sidebar-icon"> My Tickets
    </a>
</li>

<li class="sidebar-item">
    <a href="{{ route('profile.show', auth()->user()->id) }}" 
       class="sidebar-link {{ request()->is('profile*') ? 'active' : '' }}">
        <img src="{{ asset('public/web/assets/images/myprofile.png') }}" class="sidebar-icon"> My Profile
    </a>
</li>

        <li class="sidebar-item">
            <a href="{{ route('recruiter.logout') }}" class="sidebar-link"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <img src="{{ asset('public/web/assets/images/logout.png') }}" class="sidebar-icon"> Logout
            </a>

            <form id="logout-form" action="{{ route('recruiter.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>

<!-- Overlay -->
<div class="sidebar-overlay"></div>
