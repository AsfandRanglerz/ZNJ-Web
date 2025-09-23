<nav class="navbar navbar-expand-lg  my-web-navbar">
  <div class="container-fluid">
    
     <a class="navbar-brand" href="https://znjevents.com/">
      <img src="{{ asset('public/web/assets/images/flogo.png') }}" alt="Logo" class="header-logo">
    </a>
 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
  aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="border-color: white;">
  <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
</button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto me-5 m-0 mb-2 mb-lg-0">

        <li class="nav-item ">
          <a class="nav-link my-nav-link-header" href="https://znjevents.com/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link my-nav-link-header" href="https://znjevents.com/about-us/">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link my-nav-link-header" href="#">Services<b  class="align-middle px-1 plus-sign-header">+</b></a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link my-nav-link-header " href="#" >Blog</a>
        </li>
         <li class="nav-item">
          <a class="nav-link my-nav-link-header " href="https://znjevents.com/contact-us/">Contact Us</a>
        </li> <li class="nav-item">
          <a class="nav-link my-nav-link-header " href="#" >FAQ</a>
        </li>
      </ul>
      <div class="d-flex me-2 text-white button-form-mynav">
    @if(Auth::check())
        {{-- Agar user login hai to phone aur logout button show kare --}}
        <div class="d-flex align-items-center">
            <a href="{{ route('web.recruiter.dashboard') }}" type="button" class="btn me-2 login-phone-btn" >Go To Dashboard
      
</a>


            <a href="{{ route('recruiter.logout') }}" class="btn mx-2 login-register-btn"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>

            <form id="logout-form" action="{{ route('recruiter.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    @else
        {{-- Agar login nahi hai to login aur register buttons show kare --}}
        <a class="btn login-register-btn" id="login-btn-header" href="{{ route('web.login') }}">Login</a>
        <a class="btn mx-1 login-register-btn" href="{{ route('web.signup') }}">Register</a>
    @endif
</div>


    </div>
  </div>
</nav>
