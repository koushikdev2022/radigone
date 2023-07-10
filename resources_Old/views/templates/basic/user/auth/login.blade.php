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
                            <h2 class="title">@lang('Login Your Account Now')</h2>
                            <h3 class="sub-title">@lang('Do not Have An Account')? <a href="{{route('user.register')}}">@lang('Register Now')</a></h3>
                        </div>
                        <form class="account-form" method="POST" action="{{ route('user.login')}}" onsubmit="return submitUserForm();">
                            @csrf
                            <div class="row ml-b-20">
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Username')*</label>
                                    <input type="text" name="username" value="{{ old('username') }}" class="form-control form--control" required>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Password')*</label>
                                    <input type="password" class="form-control form--control" name="password" required>
                                </div>
                                <div class="col-lg-12 form-group google-captcha">
                                    @php echo recaptcha() @endphp
                                </div>
                                <div class="col-lg-12">
                                    @include($activeTemplate.'partials.custom-captcha')
                                </div>
                                <div class="col-lg-12 form-group">
                                    <div class="checkbox-wrapper d-flex flex-wrap align-items-center">
                                        <div class="checkbox-item">
                                            <label><a href="{{route('user.password.request')}}">@lang('Forgot Password')?</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="submit-btn">@lang('Login Now')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        "use strict";
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span style="color:red;">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
        
        function verifyCaptcha() {
            document.getElementById('g-recaptcha-error').innerHTML = '';
        }
    </script>
@endpush
