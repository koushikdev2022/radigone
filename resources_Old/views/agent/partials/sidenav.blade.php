<div class="sidebar capsule--rounded bg_img overlay--dark"
     data-background="{{asset('assets/surveyor/images/sidebar/2.jpg')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="#" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            <a href="#" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('agent.dashboard')}}">
                    <a href="{{route('agent.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>


                <li class="sidebar-menu-item {{menuActive('agent.refferrals')}}">
                    <a href="{{route('agent.refferrals')}}" class="nav-link ">
                        <i class="menu-icon las la-comment-dollar"></i>
                        <span class="menu-title">@lang('Refferrals')</span>
                    </a>
                </li>


                <li class="sidebar-menu-item {{menuActive('agent.earnings*')}}">
                    <a href="{{route('agent.earnings')}}" class="nav-link ">
                        <i class="menu-icon las la-comment-dollar"></i>
                        <span class="menu-title">@lang('Earnings')</span>
                    </a>
                </li>


                <li class="sidebar-menu-item {{menuActive('agent.transactions*')}}">
                    <a href="{{route('agent.transactions')}}" class="nav-link ">
                        <i class="menu-icon las la-comment-dollar"></i>
                        <span class="menu-title">@lang('Transactions')</span>
                    </a>
                </li>

               <!--<li class="sidebar-menu-item {{menuActive('agent.ticket*')}}">-->
               <!--     <a href="{{ route('agent.ticket') }}" class="nav-link ">-->
               <!--         <i class="menu-icon las la-ticket-alt"></i>-->
               <!--         <span class="menu-title">@lang('Support Tickets')</span>-->
               <!--     </a>-->
               <!-- </li>-->

                <li class="sidebar-menu-item {{menuActive('agent.twofactor*')}}">
                    <a href="{{route('agent.twofactor')}}" class="nav-link ">
                        <i class="menu-icon las la-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Security')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('agent.profile')}}">
                    <a href="{{route('agent.profile')}}" class="nav-link ">
                        <i class="menu-icon las la-user-circle"></i>
                        <span class="menu-title">@lang('Profile')</span>
                    </a>
                </li>




                <li class="sidebar-menu-item {{menuActive('agent.password')}}">
                    <a href="{{route('agent.password')}}" class="nav-link ">
                        <i class="menu-icon las la-key"></i>
                        <span class="menu-title">@lang('Change Password')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('agent.logout') }}" class="nav-link ">
                        <i class="menu-icon las la-sign-out-alt"></i>
                        <span class="menu-title">@lang('Logout')</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
