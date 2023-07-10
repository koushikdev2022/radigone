<div class="sidebar capsule--rounded bg_img overlay--dark"
     data-background="{{asset('assets/surveyor/images/sidebar/2.jpg')}}">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{route('surveyor.dashboard')}}" class="sidebar__main-logo"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('image')"></a>
            <a href="{{route('surveyor.dashboard')}}" class="sidebar__logo-shape"><img
                    src="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" alt="@lang('image')"></a>
            <button type="button" class="navbar__expand"></button>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{menuActive('surveyor.dashboard')}}">
                    <a href="{{route('surveyor.dashboard')}}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('admin.gateway*',3)}}">
                        <i class="menu-icon las la-credit-card"></i>
                        <span class="menu-title">@lang('Deposit')</span>
                    </a>

                    <div class="sidebar-submenu {{menuActive('surveyor.deposit*',2)}} ">
                        <ul>

                            <li class="sidebar-menu-item {{menuActive('surveyor.deposit')}} ">
                                <a href="{{route('surveyor.deposit')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Deposit Now')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('surveyor.deposit.history')}} ">
                                <a href="{{route('surveyor.deposit.history')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Deposit Log')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{menuActive('surveyor.buy_views.deposit')}} ">
                                <a href="{{route('surveyor.buy_views.deposit')}}" class="nav-link">
                                    <i class="menu-icon las la-dot-circle"></i>
                                    <span class="menu-title">@lang('Buy Views')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                 <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('surveyor.survey*')}}">
                        <i class="menu-icon las la-credit-card"></i>
                        <span class="menu-title">@lang('All Campaigns')</span>
                    </a>

                    <div class="sidebar-submenu {{menuActive('surveyor.survey*')}} ">
                        <ul>

                           <li class="sidebar-menu-item ">
                    <a href="{{ route('surveyor.survey.all') }}" class="nav-link ">
                        <i class="menu-icon lar la-question-circle"></i>
                        <span class="menu-title">@lang('Campaigns')</span>
                    </a>
                </li>
                            <li class="sidebar-menu-item ">
                    <a href="{{ route('surveyor.survey.alldump') }}" class="nav-link ">
                        <i class="menu-icon lar la-question-circle"></i>
                        <span class="menu-title">@lang('Draft Campaigns')</span>
                    </a>
                </li>
                           
                        </ul>
                    </div>
                </li>
                
                <!--<li class="sidebar-menu-item {{menuActive('surveyor.survey*')}}">-->
                <!--    <a href="{{ route('surveyor.survey.all') }}" class="nav-link ">-->
                <!--        <i class="menu-icon lar la-question-circle"></i>-->
                <!--        <span class="menu-title">@lang('Campaigns')</span>-->
                <!--    </a>-->
                <!--</li>-->
                <!--<li class="sidebar-menu-item {{menuActive('surveyor.survey*')}}">-->
                <!--    <a href="{{ route('surveyor.survey.alldump') }}" class="nav-link ">-->
                <!--        <i class="menu-icon lar la-question-circle"></i>-->
                <!--        <span class="menu-title">@lang('Draft Campaigns')</span>-->
                <!--    </a>-->
                <!--</li>-->
                <li class="sidebar-menu-item {{menuActive('surveyor.calendarview*')}}">
                    <a href="{{ route('surveyor.calendarview') }}" class="nav-link ">
                        <i class="menu-icon lar la-calendar-alt"></i>
                        <span class="menu-title">@lang('Calendar')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('surveyor.report*')}}">
                    <a href="{{ route('surveyor.report') }}" class="nav-link ">
                        <i class="menu-icon las la-chart-bar"></i>
                        <span class="menu-title">@lang('Report')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('surveyor.transactions*')}}">
                    <a href="{{ route('surveyor.transactions') }}" class="nav-link ">
                        <i class="menu-icon las la-comment-dollar"></i>
                        <span class="menu-title">@lang('Transactions')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('surveyor.ticket*')}}">
                    <a href="{{ route('surveyor.ticket') }}" class="nav-link ">
                        <i class="menu-icon las la-ticket-alt"></i>
                        <span class="menu-title">@lang('Support Tickets')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('surveyor.twofactor*')}}">
                    <a href="{{ route('surveyor.twofactor') }}" class="nav-link ">
                        <i class="menu-icon las la-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Security')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item {{menuActive('surveyor.profile')}}">
                    <a href="{{ route('surveyor.profile') }}" class="nav-link ">
                        <i class="menu-icon las la-user-circle"></i>
                        <span class="menu-title">@lang('Profile')</span>
                    </a>
                </li>




                <li class="sidebar-menu-item {{menuActive('surveyor.password')}}">
                    <a href="{{route('surveyor.password')}}" class="nav-link ">
                        <i class="menu-icon las la-key"></i>
                        <span class="menu-title">@lang('Change Password')</span>
                    </a>
                </li>

                <li class="sidebar-menu-item">
                    <a href="{{ route('surveyor.logout') }}" class="nav-link ">
                        <i class="menu-icon las la-sign-out-alt"></i>
                        <span class="menu-title">@lang('Logout')</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
