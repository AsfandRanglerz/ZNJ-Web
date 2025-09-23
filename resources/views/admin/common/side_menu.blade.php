@php
  use App\Models\Event; // Import the User model
  $event = Event::where('seen','0')->where('delete_request','1')->get(); // Get all users from the database using the User model
@endphp
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand ">
            <a href="{{ URL::TO('admin/dashboard')}}"><img alt="image" src="{{ asset('public/admin/assets/img/logo.png')}}" class="header-logo"/></a>
        </div>
        <ul class="sidebar-menu">
            <li class="dropdown {{ (request()->is('admin/dashboard')) ? 'active' : '' }}">
                <a href="{{url('/admin/dashboard')}}" class="nav-link"><i data-feather="monitor"></i><span>Dashboard</span></a>
            </li>
            {{-- <li class="dropdown {{ (request()->is('admin/recruiter*')) ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>Recruiter</span></a>
                <ul class="dropdown-menu active">
                    <li><a class="nav-link" href="{{url('/admin/recruiter')}}">recruiter</a></li>
                </ul>
            </li>
            <li class="dropdown {{ (request()->is('admin/entertainer*')) ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>Entertainer</span></a>
                <ul class="dropdown-menu active">
                    <li><a class="nav-link" href="{{url('/admin/entertainer')}}">entertainer</a></li>
                </ul>
            </li> --}}
           <li class="dropdown 
                 {{ 
        (
            (request()->is('admin/users/index*') || 
             request()->is('admin/recruiter*') || 
             (request()->is('admin/entertainer*') && !request()->is('admin/entertainer/talent/categories*')) || 
             (request()->is('admin/venue-providers*') && !request()->is('admin/venue-providers/venue/categories*'))
            )
        ) 
        ? 'active' : '' 
    }}">
                <a href="{{ route('admin.user.index') }}" class="nav-link">
                    <i class="fa fa-users"></i><span>Users</span>
                </a>
            </li>

            {{-- <li class="dropdown {{ (request()->is('admin/Privacy-policy')) ? 'active' : '' }}">
                <a href="{{url('/admin/Privacy-policy')}}" class="nav-link"><i data-feather="monitor"></i><span>Privacy&Policy</span></a>
            </li> --}}

            {{-- <li class="dropdown {{ (request()->is('admin/term-condition')) ? 'active' : '' }}">
                <a href="{{url('/admin/term-condition')}}" class="nav-link"><i data-feather="monitor"></i><span>Term&Condition</span></a>
                </li> --}}
                @php
                    $isCategoryActive = request()->is('admin/entertainer/talent/categories') || 
                                        request()->is('admin/venue-providers/venue/categories');
                    @endphp

                    <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown {{ $isCategoryActive ? 'active' : '' }}">
                        <i class="fa fa-list-alt"></i><span>Categories</span>
                    </a>

                    <ul class="dropdown-menu {{ $isCategoryActive ? 'show' : '' }}">
                        <li class="{{ request()->is('admin/entertainer/talent/categories*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('admin/entertainer/talent/categories/') }}">Talent Category</a>
                        </li>
                        <li class="{{ request()->is('admin/venue-providers/venue/categories*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('admin/venue-providers/venue/categories/') }}">Venue Category</a>
                        </li>
                    </ul>
                    </li>

            <li class="dropdown {{ (request()->is('admin/pages/intro-video*')) ? 'active' : '' }}">
                <a  href="{{url('/admin/pages/intro-video')}}" class="nav-link"> <i class="fa fa-play-circle-o"></i><span> Introduction Video</span></a>
            </li>
            <li class="dropdown {{ (request()->is('admin/feature-ads-packages*')) ? 'active' : '' }}">
                <a  href="{{url('/admin/feature-ads-packages')}}" class="nav-link"> <i class="fa fa-gift"></i><span>Feature Ads</span></a>
            </li>
            {{-- <li class="dropdown {{ (request()->is('admin/notification*')) ? 'active' : '' }}">
                <a  href="{{url('/admin/notification')}}" class="nav-link"> <i class="fa fa-bell"></i><span>Notification</span></a>
            </li> --}}
            <li class="dropdown {{ (request()->is('admin/chat*')) ? 'active' : '' }}">
                <a  href="{{url('/admin/chat')}}" class="nav-link"> <i class="fa fa-comments"></i><span>Chat</span></a>
            </li>
            {{-- <li class="dropdown {{ (request()->is('admin/payment*')) ? 'active' : '' }}">
                <a  href="{{url('/admin/payment')}}" class="nav-link"> <i class="fa fa-cc-stripe"></i><span>Payment Detail</span></a>
            </li> --}}
           @php
            $isPaymentActive = request()->is('admin/payment*') || 
                            request()->is('admin/feature-payment*') || 
                            request()->is('admin/ticket-payment*');
                @endphp

                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown {{ $isPaymentActive ? 'active' : '' }}">
                        <i class="fa fa-list-alt"></i> <span>Payment</span>
                    </a>

                    <ul class="dropdown-menu {{ $isPaymentActive ? 'show' : '' }}">
                        <li class="{{ request()->is('admin/payment*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/admin/payment') }}">Guest Payment</a>
                        </li>
                        <li class="{{ request()->is('admin/feature-payment*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/admin/feature-payment') }}">Feature Payment</a>
                        </li>
                        <li class="{{ request()->is('admin/ticket-payment*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ url('/admin/ticket-payment') }}">Event Ticket Payment</a>
                        </li>
                    </ul>
                </li>


            <li class="dropdown {{ (request()->is('')) ? 'active' : '' }}">
                <a  href="{{url('#')}}" class="nav-link"> <i class="fas fa-hand-holding-usd"></i><span>Finance Management</span></a>
            </li>
            <li class="dropdown {{ (request()->is('admin/account-deletion-request*')) ? 'active' : '' }}">
                <a href="{{url('admin/account-deletion-request')}}" class="nav-link"> <i
                        class="fas fa-hand-holding-usd"></i><span>Account Deletion
                        Request</span></a>
            </li>
            <li class="dropdown {{ (request()->is('admin/event-deletion-request*')) ? 'active' : '' }}">
                <a href="{{url('admin/event-deletion-request')}}" class="nav-link"> <i
                        class="fas fa-hand-holding-usd"></i><span>Deleted Events
                        </span><span class="badge position-absolute w-auto rounded" style="right: 16px;background: rgb(247, 83, 18); color:#fff">{{$event->count()}}</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/Privacy-policy') || request()->is('admin/privacy-policy-edit') ? 'active' : '' }}">
                <a href="{{ url('/admin/Privacy-policy') }}" class="nav-link"><i class="fa fa-lock"></i><span>Privacy
                    Policy</span></a>
            </li>
            <li class="dropdown {{ request()->is('admin/about-us*') ? 'active' : '' }}">
                <a href="{{ url('/admin/about-us') }}" class="nav-link"><i class="fa fa-info-circle"></i><span>About
                    Us</span></a>
            </li>

            {{-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="layout"></i><span>Categories</span></a>
                <ul class="dropdown-menu active">
                    <li><a href="{{ route('entertainer.talent.categories.index') }}" class="nav-link {{ (request()->is('/entertainer/talent/categories/*')) ? 'active' : '' }}">Talent Categories</a></li>
                </ul>
            </li> --}}
            {{-- <li class="dropdown">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i data-feather="layout"></i><span>Pages</span></a>
                <ul class="dropdown-menu active">

                </ul>

            </li> --}}
        </ul>
        </aside>
</div>
