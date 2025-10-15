<nav class="navbar navbar-expand-lg my-web-navbar">
  <div class="container-fluid">

    <a class="navbar-brand" href="https://znjevents.com/">
      <img src="{{ asset('public/web/assets/images/flogo.png') }}" alt="Logo" class="header-logo">
    </a>

    {{-- 🔹 Go To Dashboard (Mobile View Only) --}}
    @if(Auth::check())
      <a href="{{ route('web.recruiter.dashboard') }}" 
         id="goToDashboardBtn"
         class="btn login-phone-btn d-lg-none me-2" 
         style="position: absolute; right: 60px;  z-index: 10;">
        Go To Dashboard
      </a>
    @endif

    <button class="navbar-toggler" id="menuToggle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="border-color: white;">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto me-5 m-0 mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link my-nav-link-header" href="https://znjevents.com/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link my-nav-link-header" href="https://znjevents.com/about-us/">About Us</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle my-nav-link-header" 
             href="https://znjevents.com/services/" 
             id="servicesDropdown" role="button" 
             data-bs-toggle="dropdown" aria-expanded="false">
            Services <b class="align-middle px-1 plus-sign-header">+</b>
          </a>
          <ul class="dropdown-menu drop-down-for-services" aria-labelledby="servicesDropdown">
            <li><a class="dropdown-item drop-down-items-for-services" href="https://znjevents.com/service/event-creation/">Event Creation</a></li>
            <li><a class="dropdown-item drop-down-items-for-services" href="https://znjevents.com/service/talent-booking/">Talent Booking</a></li>
            <li><a class="dropdown-item drop-down-items-for-services" href="https://znjevents.com/service/venue-booking/">Venue Booking</a></li>
            <li><a class="dropdown-item drop-down-items-for-services" href="https://znjevents.com/service/vendor-services/">Vendor Services</a></li>
            <li><a class="dropdown-item drop-down-items-for-services" href="https://znjevents.com/service/ticket-sales/">Ticket Sales</a></li>
            <li><a class="dropdown-item drop-down-items-for-services" href="https://znjevents.com/service/event-management/">Event Management</a></li>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link my-nav-link-header" href="https://znjevents.com/blog/">Blog</a></li>
        <li class="nav-item"><a class="nav-link my-nav-link-header" href="https://znjevents.com/contact-us/">Contact Us</a></li>
        <li class="nav-item"><a class="nav-link my-nav-link-header" href="https://znjevents.com/faqs/">FAQ's</a></li>
      </ul>

      <div class="d-flex me-2 text-white button-form-mynav">
        @if(Auth::check())
          <div class="d-flex align-items-center">
            <a href="{{ route('web.recruiter.dashboard') }}" type="button" class="btn me-2 login-phone-btn">Go To Dashboard</a>

            <a href="{{ route('recruiter.logout') }}" class="btn mx-2 login-register-btn"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              Logout
            </a>

            <form id="logout-form" action="{{ route('recruiter.logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        @else
          <a class="btn login-register-btn" id="login-btn-header" href="{{ route('web.login') }}">Login</a>
          <a class="btn mx-1 login-register-btn" href="{{ route('web.signup') }}">Register</a>
        @endif
      </div>
    </div>
  </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
  // 🔹 Services dropdown double-click fix
  let link = document.getElementById("servicesDropdown");
  let clickTimer = null;

  link.addEventListener("click", function (e) {
    if (clickTimer == null) {
      e.preventDefault();
      clickTimer = setTimeout(() => {
        clickTimer = null;
      }, 300);
    } else {
      clearTimeout(clickTimer);
      clickTimer = null;
      window.location.href = link.getAttribute("href");
    }
  });

  // 🔹 Dashboard button hide/show on collapse
  const navbar = document.getElementById("navbarSupportedContent");
  const goToDashboardBtn = document.getElementById("goToDashboardBtn");

  if (navbar && goToDashboardBtn) {
    // When menu opens → hide button
    navbar.addEventListener("show.bs.collapse", function () {
      goToDashboardBtn.style.display = "none";
    });

    // When menu closes → show button again
    navbar.addEventListener("hidden.bs.collapse", function () {
      goToDashboardBtn.style.display = "block";
    });
  }
});
</script>
