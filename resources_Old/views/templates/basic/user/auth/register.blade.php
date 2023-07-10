@extends($activeTemplate.'layouts.frontend')
@section('content')
@include($activeTemplate.'partials.breadcrumb')

    <section class="account-section ptb-80">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-10">
                    <?php  if($val=='1'){ ?>
                    <div class="account-form-area bg-overlay-black section--bg">
                        <div class="account-logo-area text-center">
                            <div class="account-logo">
                                <a href="{{route('home')}}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('logo')"></a>
                            </div>
                        </div>
                        <div class="account-header text-center">
                            <h2 class="title">@lang('Register Your Account Now')</h2>
                            <h3 class="sub-title">@lang('Already Have An Account')? <a href="{{route('user.login')}}">@lang('Login Now')</a></h3>
                        </div>
                        <form class="account-form" action="{{ route('user.register') }}" method="POST" onsubmit="return submitUserForm();">
                            @csrf
                            <div class="row ml-b-20">
                                <div class="col-lg-6 form-group">
                                    <label>@lang('First Name')*</label>
                                    <input type="text" class="form-control form--control" name="firstname" value="{{ old('firstname') }}" required>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Last Name')*</label>
                                    <input type="text" class="form-control form--control" name="lastname" value="{{ old('lastname') }}" required>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Username')*</label>
                                    <input type="text" class="form-control form--control" name="username" value="{{ old('username') }}" required>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label>@lang('Mobile')</label>

                                    <div class="input-group ">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <div class="account-select">
                                                <select name="country_code" class="form--control">
                                                    @include('partials.country_code')
                                                </select>
                                                </div>
                                            </span>
                                        </div>
                                        <input type="text" name="mobile" class="form-control form--control" maxlength="10" onkeypress="return /[0-9]/i.test(event.key)" placeholder="@lang('Your Phone Number')">
                                    </div>
                                </div>
                                 <div class="col-lg-6 form-group">
                                    <label>Whatsapp Number</label>

                                    <div class="input-group ">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <div class="account-select">
                                                <select name="country_code_wp" class="form--control">
                                                    @include('partials.country_code')
                                                </select>
                                                </div>
                                            </span>
                                        </div>
                                        <input type="text" name="whatsaap" class="form-control form--control" maxlength="10" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Whatsapp Number">
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Email')*</label>
                                    <input type="email" class="form-control form--control" name="email" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control form--control" name="address" value="{{ old('address') }}" required>
                                </div>

                                <div class="col-lg-6 form-group">
                                     <label>Gander</label>
                                  <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label class="radio-inline">
                                          <input type="radio" name="gander" value="male">Male
                                        </label>
                                     </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="radio-inline">
                                          <input type="radio" name="gander" value="female">Female
                                        </label>
                                    </div>
                                 </div>

                                </div>
                                <div class="col-lg-6 form-group">
                                     <label>Marital status-</label>
                                  <div class="row">
                                    <div class="col-lg-4 form-group">
                                        <label class="radio-inline">
                                          <input type="radio" name="marital" value="single">Single
                                        </label>
                                     </div>
                                    <div class="col-lg-4 form-group">
                                        <label class="radio-inline">
                                          <input type="radio" name="marital" value="married">Married
                                        </label>
                                    </div>
                                 </div>

                                </div>


                                <div class="col-lg-6 form-group">
                                    <label>@lang('Country')</label>
                                    <input type="text" name="country" class="form-control form--control">
                                </div>
                                 <div class="col-lg-6 form-group">
                                    <label>State</label>
                                    <input type="text" name="state" class="form-control form--control">
                                </div>
                                 <div class="col-lg-6 form-group">
                                    <label>PIN Code</label>
                                    <input type="text" name="pincode" class="form-control form--control">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Password')*</label>
                                    <input type="password" class="form-control form--control" name="password" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="registration-fees">@lang('Registration Fees')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="registration-fees" value={{$registrationfees->user_fees}} readonly>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Confirm Password')*</label>
                                    <input type="password" class="form-control form--control" name="password_confirmation" required>
                                </div>
                                <div class="col-lg-12 form-group" >
                                     <input type="hidden" class="form-control form--control" name="agent_id" value=<?php echo $agent_id ?>>


                                    <p style="color:#fff">Disclaimer :- Dear Viewer on this platform you can pay your utility bills up to Rs 15000/- and can Encash in your Bank account up to Rs 5000/- by
                                    redemption of Radigone points. To credit your Radigone points in your profile we will share information with our sponsors with your consent every time you
                                    watch advertisement at this platform in case any details found false or manipulated your profile will get blocked and all radigone points will be forfeited</p>
                                </div>

                               <div class="col-lg-12 form-group google-captcha">
                                    @php echo recaptcha() @endphp
                                </div>
                                <div class="col-lg-12">
                                    @include($activeTemplate.'partials.custom-captcha')
                                </div>
                                <div class="col-lg-12 form-group text-center mt-2">
                                    <button type="submit" class="submit-btn">@lang('Register Now')</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php  }else{  ?>

                    <p>The time stop plsese wait</p>


                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        "use strict";

        @if($country_code)
            var t = $(`option[data-code={{ $country_code }}]`).attr('selected','');
        @endif

        $('select[name=country_code]').on('change',function(){
            $('input[name=country]').val($('select[name=country_code] :selected').data('country'));
        }).change();

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
