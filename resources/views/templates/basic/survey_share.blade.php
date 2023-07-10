@extends($activeTemplate.'layouts.frontend')

@section('content')
@include($activeTemplate.'partials.breadcrumb')

    <section class="client-section pt-80 pb-80">
        <div class="container">
            <div class="row justify-content-center ml-b-30">
                <div class="col-lg-12">
                    <div class="client-area section--bg bg-overlay-black bg_img" data-background="{{asset($activeTemplateTrue.'images/banner.svg')}}">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 text-center">
                                <div class="section-header white">
                                    <h2 class="section-title">@lang('Login or Register to Continue This Survey')</h2>
                                    <span class="title-border"></span>
                                </div>
                            </div>
                        </div>
                        <section class="about-section">
                            <div class="container">
                                <div class="row justify-content-center mb-30-none">
                                    <div class="col-xl-12  col-md-12 mb-30">
                                        <div class="about-area text-center">
                                            <div class="about-btn">
                                                <a href="{{route('user.login')}}" class="btn--base">@lang('Login')</a>
                                                <a href="{{route('user.register')}}" class="btn--base">@lang('Register')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('custom-seo')
    @include('partials.seo', ['seo_contents'=>$survey])
@endpush
