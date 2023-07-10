@extends('surveyor.layouts.master')

@section('content')
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('surveyor.partials.sidenav')
        @include('surveyor.partials.topnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                @include('surveyor.partials.breadcrumb')

                @yield('panel')

            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>

@endsection
