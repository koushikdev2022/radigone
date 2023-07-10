@extends('agent.layouts.master')

@section('content')
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('agent.partials.sidenav')
        @include('agent.partials.topnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">

                @include('agent.partials.breadcrumb')

                @yield('panel')

            </div><!-- bodywrapper__inner end -->
        </div><!-- body-wrapper end -->
    </div>

@endsection
