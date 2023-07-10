<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        @if(request()->routeIs('survey.share'))
            @stack('custom-seo')
        @else
            @include('partials.seo')
        @endif

        <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
        <!-- odometer css -->
        <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/odometer.css')}}">
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
<body unselectable="on" class="unselectable">

    <div class="preloader-area">
        <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
            <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none" />
            <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8" />
        </svg>
    </div>

    <a href="#" class="scrollToTop"><i class="las la-angle-double-up"></i></a>
    @include($activeTemplate.'partials.header')
        @yield('content')
    @include($activeTemplate.'partials.footer')

    <!-- jquery -->
    <script src="{{asset($activeTemplateTrue.'js/jquery-3.3.1.min.js')}}"></script>
    <!-- bootstrap js -->
    <script src="{{asset($activeTemplateTrue.'js/bootstrap.min.js')}}"></script>
    <!-- swipper js -->
    <script src="{{asset($activeTemplateTrue.'js/swiper.min.js')}}"></script>
    <!-- viewport js -->
    <script src="{{asset($activeTemplateTrue.'js/viewport.jquery.js')}}"></script>
    <!-- odometer js -->
    <script src="{{asset($activeTemplateTrue.'js/odometer.min.js')}}"></script>
    <!-- wow js file -->
    <script src="{{asset($activeTemplateTrue.'js/wow.min.js')}}"></script>
    <!-- main -->
    <script src="{{asset($activeTemplateTrue.'js/main.js')}}"></script>

    @stack('script-lib')

    @stack('script')

    @include('partials.plugins')

    @include($activeTemplate.'partials.notify')


    <script>
        (function ($) {
            "use strict";
            $(document).on("change", ".langSel", function() {
                window.location.href = "{{url('/')}}/change/"+$(this).val() ;
            });
        })(jQuery);
    </script>

    <script>
        $(document).ready(function() {
            $('body').bind('cut copy', function(e) {
                e.preventDefault();
            });
            $("body").on("contextmenu", function(e) {
                return false;
            });
        });
    </script>

</body>
</html>
