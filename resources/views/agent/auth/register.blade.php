@extends('surveyor.layouts.master')

@section('content')

<style type="text/css">
    #tabs {

   width: 100%;
    height:30px;
   padding-right: 2px;
   margin-top: 30px;


}
a {cursor:pointer;}

#tabs li {
    float:left;
    list-style:none;
    border-top:1px solid #ccc;
    border-left:1px solid #ccc;
    border-right:1px solid #ccc;
    margin-right:5px;
    border-top-left-radius:3px;
    border-top-right-radius:3px;
      outline:none;
}

#tabs li a {

    font-family:Arial, Helvetica, sans-serif;
    font-size: small;
    font-weight: bold;
   padding-top: 5px;
   padding-left: 150px;
   padding-right: 150px;
    padding-bottom: 8px;
    display:block;
    color: #FFF;
    background: #7367f0;
    border-top-left-radius:3px;
    border-top-right-radius:3px;
    text-decoration:none;
    outline:none;

}

#tabs li a.inactive{
    padding-top:5px;
    padding-bottom:8px;
  padding-left: 150px;
  padding-right: 150px;
  color: #7367f0;
   background: #FFF;
   outline:none;

}

#tabs li a:hover, #tabs li a.inactive:hover {
      outline:none;
}
</style>

<div class="page-wrapper default-version">
    <div class="form-area bg_img bg_fixed" data-background="{{asset('assets/surveyor/images/1.jpg')}}">
        <div class="container p-0">
            <div class="row justify-content-center no-gutters">
                <div class="col-lg-10">
                    <div class="form-wrapper w-100">
                        <h4 class="logo-text mb-15">@lang('Welcome to') <strong>Business Correspondent</strong></h4>
                        <p>{{$page_title}}</p>
 <?php  if($val=='1'){ ?>
                        <div class="card-header py-3">
                            <ul id="tabs">
                                <li>
                                    <a id="tab1" class="m-0 font-weight-bold">Company Sponsor</a>
                                </li>
                                <li>
                                    <a id="tab2" class="m-0 font-weight-bold">Individual Sponsor</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body containerr" id="tab1C">
                            <form action="{{ route('agent.regStatus') }}" method="POST" class="cmn-form mt-30 form-row" onsubmit="return submitUserForm();" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group col-md-6">
                                    <label for="firm_name">@lang('Firm Type')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="firm_name">
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="firm_type">@lang('Firm Type')</label>
                                    <select class="form-control b-radius--capsule" name="firm_type">
                                        <option value="Company">Company</option>
                                        <option value="Private limited">Private limited</option>
                                        <option value="Partnership">Partnership</option>
                                        <option value="Proprietorship">Proprietorship</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="gstin">@lang('GSTIN Number')</label>
                                    <input type="text" class="form-control b-radius--capsule gstin" name="gstin">
                                    <small class="text-danger" id="gstin-card-err"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="designation">@lang('Designation')</label>
                                    <select class="form-control b-radius--capsule" name="designation">
                                        <option value="Authorized person">Authorized person</option>
                                        <option value="Proprietor">Proprietor</option>
                                        <option value="Board of Director name">Board of Director name</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="firstname">@lang('First Name')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0 border-0">
                                                <select name="title">
                                                    @include('partials.title')
                                                </select>
                                            </span>
                                        </div>
                                        <input type="text" name="firstname" class="form-control b-radius--capsule" id="firstname" value="{{ old('firstname') }}" required>
                                        <i class="las la-user input-icon"style="top: 4px;right: 0px;"></i>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lastname">@lang('Last Name')</label>
                                    <input type="text" name="lastname" class="form-control b-radius--capsule" id="lastname" value="{{ old('lastname') }}" required>
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">@lang('Username')</label>
                                    <input type="text" name="username" class="form-control b-radius--capsule" id="username" value="{{ old('username') }}" required>
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6 country-code">
                                    <label for="mobile">@lang('Your Phone Number')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0 border-0">
                                                <select name="country_code">
                                                    @include('partials.country_code')
                                                </select>
                                            </span>
                                        </div>
                                        <input type="text" name="mobile" class="form-control b-radius--capsule" maxlength="10" onkeypress="return /[0-9]/i.test(event.key)" placeholder="@lang('Your Phone Number')">
                                    </div>
                                </div>
                                <div class="form-group col-md-6 country-code">
                                    <label for="mobile">@lang('Whatsapp number')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0 border-0">
                                                <select name="country_code">
                                                    @include('partials.country_code')
                                                </select>
                                            </span>
                                        </div>
                                        <input type="text" name="whatsapp" class="form-control b-radius--capsule" maxlength="10" onkeypress="return /[0-9]/i.test(event.key)" placeholder="@lang('Your Whatsapp number')">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="business">@lang('Business Category')</label>
                                        <select class="form-control b-radius--capsule" id="business-cat-com" name="business">
{{--                                            @include('partials.business_category')--}}
                                            <option value="">---SELECT CATEGORY---</option>
                                            @foreach($categories as $cat)
                                                <option value="{{$cat->id}}" data-subcategories="{{$cat->subcategories}}">{{$cat->name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="business">@lang('Business Sub Category')</label>
                                    <select class="form-control b-radius--capsule" id="business-subcat-com" name="subcategory">
                                    </select>
                                </div>
{{--                                <div class="form-group col-md-6">--}}
{{--                                    <label for="subcategory">@lang('Business Sub Category')</label>--}}
{{--                                    <input type="text" name="subcategory" class="form-control b-radius--capsule">--}}
{{--                                    <i class="las la-briefcase input-icon"></i>--}}
{{--                                </div>--}}
                                <div class="form-group col-md-6">
                                    <label for="registration-fees">@lang('Registration Fees')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="registration-fees" value={{$registrationfees->agent_fees}} readonly>
                                </div>
                                <!--<div class="form-group col-md-6">-->
                                <!--    <label for="profileservices">@lang('Choose Profile Services')</label>-->
                                <!--    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">-->
                                <!--        <input type="checkbox" value="Contact details of viewers" name="profileservices[]"> @lang('Contact details of viewers')-->
                                <!--    </label>-->
                                <!--    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">-->
                                <!--        <input type="checkbox" value="Discount Coupons to boost sale" name="profileservices[]"> @lang('Discount Coupons to boost sale')-->
                                <!--    </label>-->
                                <!--    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">-->
                                <!--        <input type="checkbox" value="Radigone points deposit time -7/10/15/30 Days" name="profileservices[]"> @lang('Radigone points deposit time -7/10/15/30 Days')-->
                                <!--    </label>-->
                                <!--</div>-->
                                <div class="form-group col-md-6">
                                    <label for="multilogins">@lang('Multiple Login Required')</label>
                                        <select class="form-control b-radius--capsule" name="multilogins" id="multilogins">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="numlogins">@lang('No. of Logins')</label>
                                        <select class="form-control b-radius--capsule" name="numlogins" id="numlogins">
                                            <option value="1">--Please Select Option--</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address">@lang('Address')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="address">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="city">@lang('City')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="city">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="state">@lang('State')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="state">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="zip">@lang('Zip Code')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="zip">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="country">@lang('Country')</label>
                                    <select class="form-control b-radius--capsule" name="country">
                                            @include('partials.country')
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">@lang('Email')</label>
                                    <input type="text" name="email" class="form-control b-radius--capsule" id="email" required>
                                    <i class="lar la-envelope input-icon"></i>
                                </div>
                                 <div class="form-group col-md-6">
                                    <label for="Pan_card">@lang('Pan Card')</label>
                                    <input type="file" class="form-control b-radius--capsule" name="pan_card">
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address_proof">@lang('Address Proof')</label>
                                    <input type="file" class="form-control b-radius--capsule" name="address_proof">
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password">@lang('Password')</label>
                                    <input type="password" name="password" class="form-control b-radius--capsule" id="password" placeholder="@lang('Enter your password')" required>
                                    <i class="las la-lock input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password-confirm">@lang('Confirm Password')</label>
                                    <input type="password" name="password_confirmation" class="form-control b-radius--capsule" id="password-confirm"
                                        placeholder="@lang('Confirm your password')" required>
                                    <i class="las la-lock input-icon"></i>
                                </div>
                                <div class="form-group col-md-12">

                                    @php echo recaptcha() @endphp

                                </div>
                                <div class="form-group col-md-12">
                                    @include($activeTemplate.'partials.custom-captcha')
                                </div>

                                <div class="form-group col-md-12">
                                    <button type="submit" id="recaptcha" class="submit-btn mt-25 b-radius--capsule">@lang('Register') <i
                                            class="las la-sign-in-alt"></i></button>
                                </div>
                                <div class="form-group mb-0 col-md-12">
                                    <a href="{{route('agent.login')}}" class="text-muted text--small"><i class="las la-lock"></i>@lang('Already Have an account ?')</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-body containerr" id="tab2C">
                            <form action="{{ route('agent.regStatus') }}" method="POST" class="cmn-form mt-30 form-row" onsubmit="return submitUserForm();" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group col-md-6">
                                    <label for="firstname">@lang('First Name')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0 border-0">
                                                <select name="title">
                                                    @include('partials.title')
                                                </select>
                                            </span>
                                        </div>
                                        <input type="text" name="firstname" class="form-control b-radius--capsule" id="firstname" value="{{ old('firstname') }}" required>
                                        <i class="las la-user input-icon"style="top: 4px;right: 0px;"></i>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="lastname">@lang('Last Name')</label>
                                    <input type="text" name="lastname" class="form-control b-radius--capsule" id="lastname" value="{{ old('lastname') }}" required>
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">@lang('Username')</label>
                                    <input type="text" name="username" class="form-control b-radius--capsule" id="username" value="{{ old('username') }}" required>
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6 country-code">
                                    <label for="mobile">@lang('Your Phone Number')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0 border-0">
                                                <select name="country_code">
                                                    @include('partials.country_code')
                                                </select>
                                            </span>
                                        </div>
                                        <input type="text" name="mobile" class="form-control b-radius--capsule" maxlength="10" onkeypress="return /[0-9]/i.test(event.key)" placeholder="@lang('Your Phone Number')">
                                    </div>
                                </div>
                                <div class="form-group col-md-6 country-code">
                                    <label for="mobile">@lang('Whatsapp number')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0 border-0">
                                                <select name="country_code">
                                                    @include('partials.country_code')
                                                </select>
                                            </span>
                                        </div>
                                        <input type="text" name="whatsapp" class="form-control b-radius--capsule" maxlength="10" onkeypress="return /[0-9]/i.test(event.key)" placeholder="@lang('Your Whatsapp number')">
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="business">@lang('Business Category')</label>
                                        <select class="form-control b-radius--capsule" id="business-cat" name="business">
{{--                                            @include('partials.business_category')--}}
                                            <option value="">---SELECT CATEGORY---</option>
                                            @foreach($categories as $cat)
                                                <option value="{{$cat->id}}" data-subcategories="{{$cat->subcategories}}">{{$cat->name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="business">@lang('Business Sub Category')</label>
                                    <select class="form-control b-radius--capsule" id="business-subcat" name="subcategory">
                                    </select>
                                </div>
{{--                                <div class="form-group col-md-6">--}}
{{--                                    <label for="subcategory">@lang('Business Sub Category')</label>--}}
{{--                                    <input type="text" name="subcategory" class="form-control b-radius--capsule">--}}
{{--                                </div>--}}
                                <div class="form-group col-md-6">
                                    <label for="registration-fees">@lang('Registration Fees')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="registration-fees" value={{$registrationfees->agent_fees}} readonly>
                                </div>
                                <!--<div class="form-group col-md-6">-->
                                <!--    <label for="profileservices">@lang('Choose Profile Services')</label>-->
                                <!--    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">-->
                                <!--        <input type="checkbox" value="Contact details of viewers" name="profileservices[]"> @lang('Contact details of viewers')-->
                                <!--    </label>-->
                                <!--    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">-->
                                <!--        <input type="checkbox" value="Discount Coupons to boost sale" name="profileservices[]"> @lang('Discount Coupons to boost sale')-->
                                <!--    </label>-->
                                <!--    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">-->
                                <!--        <input type="checkbox" value="Radigone points deposit time -7/10/15/30 Days" name="profileservices[]"> @lang('Radigone points deposit time -7/10/15/30 Days')-->
                                <!--    </label>-->
                                <!--</div>-->
                                <div class="form-group col-md-6">
                                    <label for="multilogins">@lang('Multiple Login Required')</label>
                                        <select class="form-control b-radius--capsule" name="multilogins" id="multilogins">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="numlogins">@lang('No. of Logins')</label>
                                        <select class="form-control b-radius--capsule" name="numlogins" id="numlogins">
                                            <option value="0">--Please Select Option--</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address">@lang('Address')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="address">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="city">@lang('City')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="city">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="state">@lang('State')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="state">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="zip">@lang('Zip Code')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="zip">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="country">@lang('Country')</label>
                                    <select class="form-control b-radius--capsule" name="country">
                                            @include('partials.country')
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="email">@lang('Email')</label>
                                    <input type="text" name="email" class="form-control b-radius--capsule" id="email" required>
                                    <i class="lar la-envelope input-icon"></i>
                                </div>
                                  <div class="form-group col-md-6">
                                    <label for="pan">@lang('Pan Card Number')</label>
                                    <input type="text" class="form-control b-radius--capsule pan" name="pan">
                                    <small class="text-danger" id="pan-card-err"></small>
                                </div>
                                 <div class="form-group col-md-6">
                                    <label for="Pan_card">@lang('Pan Card')</label>
                                    <input type="file" class="form-control b-radius--capsule" name="pan_card">
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address_proof">@lang('Address Proof')</label>
                                    <input type="file" class="form-control b-radius--capsule" name="address_proof">
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password">@lang('Password')</label>
                                    <input type="password" name="password" class="form-control b-radius--capsule" id="password" placeholder="@lang('Enter your password')" required>
                                    <i class="las la-lock input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password-confirm">@lang('Confirm Password')</label>
                                    <input type="password" name="password_confirmation" class="form-control b-radius--capsule" id="password-confirm"
                                        placeholder="@lang('Confirm your password')" required>
                                    <i class="las la-lock input-icon"></i>
                                </div>
                                <div class="form-group col-md-12">

                                    @php echo recaptcha() @endphp

                                </div>
                                <div class="form-group col-md-12">
                                    @include($activeTemplate.'partials.custom-captcha')
                                </div>

                                <div class="form-group col-md-12">
                                    <button type="submit" id="recaptcha" class="submit-btn mt-25 b-radius--capsule">@lang('Register') <i
                                            class="las la-sign-in-alt"></i></button>
                                </div>
                                <div class="form-group mb-0 col-md-12">
                                    <a href="{{route('agent.login')}}" class="text-muted text--small"><i class="las la-lock"></i>@lang('Already Have an account ?')</a>
                                </div>
                            </form>
                        </div>

                         <?php  }else{  ?>

                    <p>The time stop plsese wait</p>


                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
    <style>
        .input-group .form-control.b-radius--capsule {
            border-radius: 0 999px 999px 0 !important;
        }
        .input-group .input-group-text select {
            padding:   7px 10px;
        }
        .input-group .input-group-prepend .input-group-text,
        .input-group .input-group-prepend select {
            border-radius: 999px 0 0 999px;
        }
    </style>
@endpush

@push('script')
    <script>
        'use strict';

        (function ($) {
            @if($country_code)
                var t = $(`option[data-code={{ $country_code }}]`).attr('selected','');
            @endif
                $('select[name=country_code]').on('change',function(){
                    $('input[name=country]').val($('select[name=country_code] :selected').data('country'));
                }).change();
        })(jQuery);

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
    <script>
        $(document).ready(function() {
            $('#numlogins').attr("disabled","true");
            $('#multilogins').on('change',function(){
                var check = $('#multilogins').val();
                if (check == '1'){
                    $('#numlogins').removeAttr("disabled");
                }
                else{
                    $('#numlogins').attr("disabled","true");
                }
            });
        });
    </script>
    <script>
    // Switcher

    $(document).ready(function() {
        $('#tabs li a:not(:first)').addClass('inactive');
        $('.containerr').hide();
        $('.containerr:first').show();
        $('#tabs li a').click(function(){
            var t = $(this).attr('id');
            if($(this).hasClass('inactive')){ //this is the start of our condition
                $('#tabs li a').addClass('inactive');
                $(this).removeClass('inactive');

                $('.containerr').hide();
                $('#'+ t + 'C').fadeIn('slow');
            }
        });

    });

    $('.gstin').on('change', function() {
        var inputvalues = $(this).val();
        var regex = /^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/;
        if(!regex.test(inputvalues)){
            $(".gstin").val("");
            $('#gstin-card-err').text('Invalid gstin number!');
            return regex.test(inputvalues);
        }else {
            $('#gstin-card-err').text('');
        }
    });

    $('#business-cat').on('change', function() {
        let subcategories = $( "#business-cat option:selected" ).attr('data-subcategories');
        if(subcategories != null) {
            subcategories = subcategories.split(',')
            let options = '';
            $.each(subcategories, function (i, value) {
                console.log(value)
                options += `<option value="${value}">${value}</option>`;
            });
            $('#business-subcat').html(`
                 <option value="">---ADD SUBCATEGORY---</option>
                  ${options}
                `);
        }
    });

    $('#business-cat-com').on('change', function() {
        let subcategories = $( "#business-cat-com option:selected" ).attr('data-subcategories');
        if(subcategories != null) {
            subcategories = subcategories.split(',')
            let options = '';
            $.each(subcategories, function (i, value) {
                console.log(value)
                options += `<option value="${value}">${value}</option>`;
            });
            $('#business-subcat-com').html(`
                 <option value="">---ADD SUBCATEGORY---</option>
                  ${options}
                `);
        }
    });
</script>
@endpush
