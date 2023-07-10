<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @include('partials.seo')
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $general->sitename(__($page_title) ?? '') }}</title>
        <link rel="preconnect" href="https://fonts.gstatic.com">

        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

        <!-- fontawesome css link -->
        <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/fontawesome-all.min.css')}}">
        <!-- lineawesome css link -->
        <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/line-awesome.min.css')}}">
        <!-- bootstrap css link -->
        <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/bootstrap.min.css')}}">
        <!-- favicon -->
        <link rel="shortcut icon" href="{{getImage(imagePath()['logoIcon']['path'] .'/favicon.png')}}" type="image/x-icon">
        <!-- swipper css link -->
        <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/swiper.min.css')}}">
        <!-- line-awesome-icon css -->
        <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/line-awesome.min.css')}}">
        <!-- animate.css -->
        <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/animate.css')}}">
        <!-- main style css link -->
        <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/style.css')}}">
        <!-- site color -->
        <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/color.php?color1='.$general->base_color.'&color2='.$general->secondary_color)}}">

        @stack('style-lib')

        @stack('style')

        <style>
            *.unselectable {
                -moz-user-select: -moz-none;
                -khtml-user-select: none;
                -webkit-user-select: none;

                /*
                  Introduced in IE 10.
                  See http://ie.microsoft.com/testdrive/HTML5/msUserSelect/
                */
                -ms-user-select: none;
                user-select: none;
            }
        </style>
    </head>
    <body unselectable="on"  class="bg--gray unselectable">

        <div class="preloader-area">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
                <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
            </svg>
        </div>
        <a href="#" class="scrollToTop"><i class="las la-angle-double-up"></i></a>

        <section class="page-container">
            @include($activeTemplate.'partials.sidenav')
            <div class="body-wrapper">
                <div class="body-header-area d-flex flex-wrap align-items-center justify-content-between mb-10-none">
                    <div class="body-header-left">
                        <h3 class="title">{{__($page_title)}}</h3>
                    </div>
                    <div class="body-header-right dropdown">
                        <button type="button" class="" data-toggle="dropdown" data-display="static" aria-haspopup="true"
                            aria-expanded="false">
                            <div class="header-user-area d-flex flex-wrap align-items-center justify-content-between">
                                <div class="header-user-thumb">
                                    <a href="#0"><img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.auth()->user()->image,imagePath()['profile']['user']['size'])}}" alt="@lang('user-image')"></a>
                                </div>
                                <div class="header-user-content">
                                    <span>{{__(auth()->user()->fullname)}}</span>
                                </div>
                                <span class="header-user-icon"><i class="las la-chevron-circle-down"></i></span>
                            </div>
                        </button>
                        <div class="dropdown-menu dropdown-menu--sm p-0 border-0 dropdown-menu-right">
                            <a href="{{ route('user.change-password') }}"
                                class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                <i class="dropdown-menu__icon las la-lock"></i>
                                <span class="dropdown-menu__caption">@lang('Change Password')</span>
                            </a>
                            <a href="{{route('user.profile-setting')}}"
                                class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                <i class="dropdown-menu__icon las la-user-circle"></i>
                                <span class="dropdown-menu__caption">@lang('Profile Settings')</span>
                            </a>
                            <a href="{{route('user.sponsor_block_list')}}"
                                class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                <i class="dropdown-menu__icon las la-user-circle"></i>
                                <span class="dropdown-menu__caption">@lang('Sponsor Block List')</span>
                            </a>
                            <a href="{{ route('user.logout') }}"
                                class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                                <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                                <span class="dropdown-menu__caption">@lang('Logout')</span>
                            </a>
                        </div>
                    </div>
                </div>

                @yield('content')

            </div>
            <div class="copyright-wrapper">
                <div class="copyright-area">
                    <p>{{__(@$footer_content->data_values->copyright)}}</p>
                </div>
            </div>
        </section>

        <!-- jquery -->
        <script src="{{asset($activeTemplateTrue.'js/jquery-3.3.1.min.js')}}"></script>
        <!-- bootstrap js -->
        <script src="{{asset($activeTemplateTrue.'js/bootstrap.bundle.min.js')}}"></script>
        <!-- swipper js -->
        <script src="{{asset($activeTemplateTrue.'js/swiper.min.js')}}"></script>
        <!-- wow js file -->
        <script src="{{asset($activeTemplateTrue.'js/wow.min.js')}}"></script>
        <!-- main -->
        <script src="{{asset($activeTemplateTrue.'js/main.js')}}"></script>

        @stack('script-lib')

        @stack('script')

        @include('partials.plugins')

        @include($activeTemplate.'partials.notify')
        <!--<script>-->
        <!--    $(document).ready(function() {-->
        <!--        $('body').bind('cut copy', function(e) {-->
        <!--            e.preventDefault();-->
        <!--        });-->
        <!--        $("body").on("contextmenu", function(e) {-->
        <!--            return false;-->
        <!--        });-->
        <!--    });-->
        <!--    $(function () {-->
        <!--        $('[data-toggle="popover"]').popover()-->
        <!--    })-->
        <!--</script>-->

    </body>
</html>
