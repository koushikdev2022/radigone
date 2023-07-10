@extends($activeTemplate .'layouts.frontend')
@section('content')
    @include($activeTemplate.'partials.breadcrumb')

    <section class="account-section ptb-80">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6">
                    <div class="account-form-area bg-overlay-black section--bg">
                        <div class="account-logo-area text-center">
                            <div class="account-logo">
                                <a href="{{route('home')}}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('logo')"></a>
                            </div>
                        </div>
                        <div class="account-header text-center">
                            <h2 class="title">@lang('Please Verify Your Mobile to Get Access')</h2>
                            <h3 class="sub-title">@lang('Your Mobile Number') : {{auth()->user()->mobile}}</h3>
                        </div>
                        <form class="account-form" method="POST" action="{{route('user.verify_sms')}}">
                            @csrf
                            <div class="row ml-b-20">
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Enter Verification Code')*</label>
                                    <input type="text" class="form-control form--control" name="sms_verified_code[]" required>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <div class="checkbox-wrapper d-flex flex-wrap align-items-center">
                                        <div class="checkbox-item">
                                            <label><a href="{{route('user.send_verify_code')}}?type=phone">@lang('Resend code')</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="submit-btn">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
