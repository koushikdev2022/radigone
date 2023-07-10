@extends($activeTemplate.'layouts.frontend')

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
                        <h2 class="title">@lang('Reset Password')</h2>
                        <h3 class="sub-title">@lang('Put Your E-Mail Bellow')</h3>
                    </div>
                    <form class="account-form" method="POST" action="{{ route('user.password.email') }}">
                        @csrf
                        <div class="row ml-b-20">
                            <div class="col-lg-12 form-group">
                                <label>@lang('E-Mail Address')*</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control form--control" required>
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
