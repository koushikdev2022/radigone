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
.center {
    margin: auto;
    width: 60%;
    padding: 20px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}

.hideform {
    display: none;
}
</style>
<div class="center hideform">
    <button id="close" style="float: right;">X</button>
    <form action="/action_page.php">
        First name:<br>
        <input type="text" name="firstname" value="Mickey">
        <br>
        Last name:<br>
        <input type="text" name="lastname" value="Mouse">
        <br><br>
        <input type="submit" value="Submit">
    </form>
    <button id="show">Show form</button>
</div>

<div class="page-wrapper default-version">
    <div class="form-area bg_img bg_fixed" data-background="{{asset('assets/surveyor/images/1.jpg')}}">
        <div class="container p-0">
            <div class="row justify-content-center no-gutters">
                <div class="col-lg-10">
                    <div class="form-wrapper w-100">
                        <h4 class="logo-text mb-15" data-url="{{url('/usernamecheck')}}" id="usrnamechk">@lang('Welcome to') <strong>{{$general->sitename}}</strong></h4>
                        <p>{{$page_title}}</p>

                         <?php  if($val=='1'){ ?>
                        <div class="card-header py-3" >
                            <ul id="tabs">
                                <li>
                                    <a id="tab1" class="m-0 font-weight-bold" >Company Sponsor</a>
                                </li>
                                <li>
                                    <a id="tab2" class="m-0 font-weight-bold">Individual Sponsor</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body containerr" id="tab1C">
                            <form action="{{ route('surveyor.regStatuscomp') }}" method="POST" class="cmn-form mt-30 form-row" onsubmit="return submitUserForm();" enctype="multipart/form-data" >
                                @csrf
                                <div class="form-group col-md-6">
                                    <label for="firm_name">@lang('Firm Name')</label>
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
                                        <option value="LLP">LLP</option>
                                         <option value="OPC">OPC</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="gstin">@lang('GST Number')</label>
                                    <input type="text" class="form-control b-radius--capsule gstin" name="gstin">
                                    <small class="text-danger" id="gstin-card-err"></small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="designation">@lang('Designation')</label>
                                    <select class="form-control b-radius--capsule" name="designation">
                                        <option value="Proprietor">Proprietor</option>
                                        <option value="Director">Director</option>
                                        <option value="Authorized person">Authorized person</option>
                                        
                                        
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="firstname">@lang('First Name')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0 border-0">
                                                <select name="title" >
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
                                    <input type="hidden" name="agent_id" class="form-control b-radius--capsule" id="agent_id" value="{{ $agent_id }}" >

                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">@lang('Username')</label>
                                    <input type="text" name="username" class="form-control b-radius--capsule" id="username" value="{{ old('username') }}" required>
                                    <i class="las la-user input-icon"></i>
                                    <h6 id="results-implement"></h6>
                                </div>
                                <div class="form-group col-md-6 country-code" id="mobile" data-url="{{url('smobile')}}">
                                    <label for="mobile">@lang('Your Phone Number')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0 border-0">
                                                <select name="country_code" id="postalcode">
                                                    @include('partials.country_code')
                                                </select>
                                            </span>
                                        </div>
                                        <input type="text" name="mobile" id="mob" class="form-control b-radius--capsule" onkeypress="return /[0-9]/i.test(event.key)" maxlength="10" placeholder="@lang('Your Phone Number')">
                                    </div>
                                    <h6 id="mobilecheck"></h6>
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
                                    <input type="text" class="form-control b-radius--capsule" name="registration-fees" value="<?php echo $registrationfees->surveyor_fees ?>" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="profileservices">@lang('Choose Profile Services')</label>
                                    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">
                                        <input type="checkbox" value="Contact details of viewers" name="profileservices[]"> @lang('Contact details of viewers')
                                    </label>
                                    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">
                                        <input type="checkbox" value="Discount Coupons to boost sale" name="profileservices[]"> @lang('Discount Coupons to boost sale')
                                    </label>
                                    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">
                                        <input type="checkbox" value="Radigone points deposit time -7/10/15/30 Days" name="profileservices[]"> @lang('Radigone points deposit time -7/10/15/30 Days')
                                    </label>
                                </div>
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
                                <div class="form-group col-md-6" id="newurl" data-url="{{url('downloadagreement')}}">
                                    <label for="post_arrangement">@lang('PostPaid Arrangement Required')</label>
                                    <select class="form-control b-radius--capsule" name="post_arrangement" id="post_arrangement">
                                        <option value="0">No</option>
                                        <option value="2">Yes</option>
                                    </select>
                                     <div class="" id="agrm">
                                        <input type="checkbox" name="aggriment" value="" id="newcheckboxm"><label for="vehicle1">Download Agreement</label><br>
                                         </div>
                                    <input type="hidden" name="post_arrangement_mode" value="0" placeholder="Download Agreement">
                                   
                                </div>
{{--                                <div class="form-group col-md-6">--}}
{{--                                    <label for="post_arrangement_mode">@lang('Post Arrangement Mode')</label>--}}
{{--                                    <select class="form-control b-radius--capsule" name="post_arrangement_mode" id="post_arrangement_mode">--}}
{{--                                        <option value="0">Disabled</option>--}}
{{--                                        <option value="31">Enabled</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
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
                                    <label for="zip">@lang('Pin Code')</label>
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
                                    <input type="text" name="email" id="emailnamenew" class="form-control b-radius--capsule" id="email" required>
                                    <i class="lar la-envelope input-icon"></i>
                                    <h6 id="emailchecknew"></h6>
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
                                    <h6 style="font-size:10px;">1 Numeric 1 Capital letter 1 Special Chrecter Minium length 6 digits</h6>
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
                                    <a href="{{route('surveyor.login')}}" class="text-muted text--small"><i class="las la-lock"></i>@lang('Already Have an account ?')</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-body containerr" id="tab2C">
                            <form action="{{ route('surveyor.regStatus') }}" method="POST" class="cmn-form mt-30 form-row" onsubmit="return submitUserForm();"  enctype="multipart/form-data">
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
                                    <input type="hidden" name="agent_id" class="form-control b-radius--capsule" id="agent_id" value="{{ $agent_id }}" >
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="username">@lang('Username')</label>
                                    <input type="text" name="username" class="form-control b-radius--capsule" id="username" value="{{ old('username') }}" required>
                                    <i class="las la-user input-icon"></i>
                                     <h6 id="results-implementtt"></h6>
                                </div>
                                <div class="form-group col-md-6 country-code">
                                    <label for="mobile">@lang('Your Phone Number')</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text p-0 border-0">
                                                <select name="country_code" id="postal">
                                                    @include('partials.country_code')
                                                </select>
                                            </span>
                                        </div>
                                        <input type="text" name="mobile" id="mobi" class="form-control b-radius--capsule" onkeypress="return /[0-9]/i.test(event.key)" maxlength="10" placeholder="@lang('Your Phone Number')">
                                    </div>
                                    <h6 id="mobilechecknew"></h6>
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
                                    <input type="text" class="form-control b-radius--capsule" name="registration-fees" value="<?php echo $registrationfees->surveyor_fees ?>" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="profileservices">@lang('Choose Profile Services')</label>
                                    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">
                                        <input type="checkbox" value="Contact details of viewers" name="profileservices[]"> @lang('Contact details of viewers')
                                    </label>
                                    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">
                                        <input type="checkbox" value="Discount Coupons to boost sale" name="profileservices[]"> @lang('Discount Coupons to boost sale')
                                    </label>
                                    <label class="form-control b-radius--capsule" style="border: none;cursor: pointer;margin-bottom: 0px;">
                                        <input type="checkbox" value="Radigone points deposit time -7/10/15/30 Days" name="profileservices[]"> @lang('Radigone points deposit time -7/10/15/30 Days')
                                    </label>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="multilogins1">@lang('Multiple Login Required')</label>
                                        <select class="form-control b-radius--capsule" name="multilogins" id="multilogins1">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="numlogins1">@lang('No. of Logins')</label>
                                        <select class="form-control b-radius--capsule" name="numlogins" id="numlogins1">
                                            <option value="0">--Please Select Option--</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                </div>
                                <div class="form-group col-md-6" id="newurlm" data-url="{{url('downloadagreement')}}">
                                    <label for="post_arrangement1">@lang('PostPaid Arrangement Required')</label>
                                        <select class="form-control b-radius--capsule" name="post_arrangement" id="post_arrangement1">
                                            <option value="0">No</option>
                                            <option value="2">Yes</option>
                                        </select>
                                        <div class="" id="agr">
                                        <input type="checkbox" name="aggriment" value="" id="newcheckbox"><label for="vehicle1">Download Agreement</label><br>
                                         </div>
                                    <input type="hidden" name="post_arrangement_mode" value="0">
                                </div>
{{--                                <div class="form-group col-md-6">--}}
{{--                                    <label for="post_arrangement_mode1">@lang('Post Arrangement Mode')</label>--}}
{{--                                        <select class="form-control b-radius--capsule" name="post_arrangement_mode" id="post_arrangement_mode1">--}}
{{--                                            <option value="0">Disabled</option>--}}
{{--                                            <option value="31">Enabled</option>--}}
{{--                                        </select>--}}
{{--                                </div>--}}
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
                                    <label for="zip">@lang('Pin Code')</label>
                                    <input type="text" class="form-control b-radius--capsule" name="zip">
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
                                    <label for="country">@lang('Country')</label>
                                    <select class="form-control b-radius--capsule" name="country">
                                            @include('partials.country')
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="address_proof">@lang('Address Proof')</label>
                                    <input type="file" class="form-control b-radius--capsule" name="address_proof">
                                    <i class="las la-user input-icon"></i>
                                </div>
                                <div class="form-group col-md-6" id="emaildata" data-url="{{url('email')}}">
                                    <label for="email">@lang('Email')</label>
                                    <input type="text" name="email" id="emailname" class="form-control b-radius--capsule"  required>
                                    <i class="lar la-envelope input-icon"></i>
                                    <h6 id="emailcheck"></h6>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="password">@lang('Password')</label>
                                    <input type="password" name="password" class="form-control b-radius--capsule" id="password" placeholder="@lang('Enter your password')" required>
                                    <i class="las la-lock input-icon"></i>
                                    <h6 style="font-size:10px;">1 Numeric 1 Capital letter 1 Special Chrecter Minium length 6 digits<h/6>
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
                                    <a href="{{route('surveyor.login')}}" class="text-muted text--small"><i class="las la-lock"></i>@lang('Already Have an account ?')</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            $('#numlogins1').attr("disabled","true");
            $('#multilogins1').on('change',function(){
                var check = $('#multilogins1').val();
                if (check == '1'){
                    $('#numlogins1').removeAttr("disabled");
                }
                else{
                    $('#numlogins1').attr("disabled","true");
                }
            });
            $('#post_arrangement_mode').attr("disabled","true");
            $('#post_arrangement').on('change',function(){
                var checkP = $('#post_arrangement').val();
                if (checkP == '2'){
                    $('#post_arrangement_mode').removeAttr("disabled");
                }
                else{
                    $('#post_arrangement_mode').attr("disabled","true");
                }
            });
            $('#post_arrangement_mode1').attr("disabled","true");
            $('#post_arrangement1').on('change',function(){
                var checkP = $('#post_arrangement1').val();
                if (checkP == '2'){
                    $('#post_arrangement_mode1').removeAttr("disabled");
                }
                else{
                    $('#post_arrangement_mode1').attr("disabled","true");
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

    $('.pan').on('change', function() {
        var inputvalues = $(this).val();
        var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        if(!regex.test(inputvalues)){
            $(".pan").val("");
            $('#pan-card-err').text('Invalid pan number!');
            return regex.test(inputvalues);
        }else {
            $('#pan-card-err').text('');
        }
    });
    
    $(document).on('keyup input','#username',function(){
       
        let url = $("#usrnamechk").attr('data-url');
        let username = $("#username").val();
        let lengthuser = username.length
        if(lengthuser > 5){
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
           type:"POST",
           url:url,
           data:{
               username:username
           },
           success: function(data){
                   console.log(data);
                 if(data == 0){
                     $("#results-implementtt").html(`<a href="javascript:void(0)" style="color:green;">Your can use this username</a>`)
                     $("#results-implement").html(`<a href="javascript:void(0)" style="color:green;">Your can use this username</a>`)
                 }else{
                     $("#results-implementtt").html(`<a href="javascript:void(0)" style="color:red;">This username already taken</a>`) 
                     $("#results-implement").html(`<a href="javascript:void(0)" style="color:red;">This username already taken</a>`) 
                 }
                  
            }
        });
        }else if(lengthuser == 0){
               $("#results-implementtt").html("") 
              $("#results-implement").html("") 
        }else{
              $("#results-implementtt").html(`<a href="javascript:void(0)" style="color:red;">This username must be 6 character</a>`) 
              $("#results-implement").html(`<a href="javascript:void(0)" style="color:red;">This username must be 6 character</a>`) 
        }
    });
    $(document).on('keyup input','#mob',function(){
        let url = $('#mobile').attr('data-url');
        let mobile = $('#mob').val();
        let postal = $('#postalcode').val();
        let mb = mobile.length;
        if(mb == 10){
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
            type:"POST",
            url:url,
            data:{
                mobile:mobile,
                postal:postal
            },
            success:function(data){
                if(data == 0){
                    
                     $("#mobilecheck").html(`<a href="javascript:void(0)" style="color:green;">Your mobile no is valid</a>`)
                 }else{
                    
                     $("#mobilecheck").html(`<a href="javascript:void(0)" style="color:red;">This mobile no already register</a>`) 
                 }
            }
        });
        }else if(mb == 10){
            $("#mobilecheck").html("") 
        }else{
            $("#mobilecheck").html(`<a href="javascript:void(0)" style="color:red;">This mobile no must be 10 digit</a>`) 
        }
    });
     $(document).on('keyup input','#mobi',function(){
        let url = $('#mobile').attr('data-url');
        let mobile = $('#mobi').val();
        let postal = $('#postal').val();
        let mb = mobile.length;
        if(mb == 10){
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
            type:"POST",
            url:url,
            data:{
                mobile:mobile,
                postal:postal
            },
            success:function(data){
                if(data == 0){
                    
                     $("#mobilechecknew").html(`<a href="javascript:void(0)" style="color:green;">Your mobile no is valid</a>`)
                 }else{
                    
                     $("#mobilechecknew").html(`<a href="javascript:void(0)" style="color:red;">This mobile no already register</a>`) 
                 }
            }
        });
        }else if (mb == 0){
             $("#mobilechecknew").html("") 
        }else{
            $("#mobilechecknew").html(`<a href="javascript:void(0)" style="color:red;">This mobile no must be 10 digit</a>`) 
        }
    });
     $(document).on('keyup input','#emailname',function(){
         
        let url = $('#emaildata').attr('data-url');
         let emailname = $('#emailname').val();
         if(isEmail(emailname)){
            
        
       
               $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            $.ajax({
                type:"POST",
                url:url,
                data:{
                    email:emailname ,
                   
                },
                success:function(data){
                    if(data == 0){
                        
                         $("#emailcheck").html(`<a href="javascript:void(0)" style="color:green;">Your email is valid</a>`)
                     }else{
                        
                         $("#emailcheck").html(`<a href="javascript:void(0)" style="color:red;">This email already register</a>`) 
                     }
                }
            });
         }else if(emailname.length == 0){
             $("#emailcheck").html("")
         }else{
             $("#emailcheck").html(`<a href="javascript:void(0)" style="color:red;">Your email is invalid</a>`)
         }
    });
     $(document).on('keyup input','#emailnamenew',function(){
        let url = $('#emaildata').attr('data-url');
         let emailname = $('#emailnamenew').val();
          
       if(isEmail(emailname)){
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
            type:"POST",
            url:url,
            data:{
                email:emailname ,
               
            },
            success:function(data){
                if(data == 0){
                    
                     $("#emailchecknew").html(`<a href="javascript:void(0)" style="color:green;">Your email is valid</a>`)
                 }else{
                    
                     $("#emailchecknew").html(`<a href="javascript:void(0)" style="color:red;">This email already register</a>`) 
                 }
            }
        });
       }else if(emailname == 0){
           $("#emailchecknew").html("") 
       }else{
           $("#emailchecknew").html(`<a href="javascript:void(0)" style="color:red;">This email is invalid</a>`) 
       }
    });
     $(document).on('change','#postalcode',function(){
        let url = $('#mobile').attr('data-url');
        let mobile = $('#mob').val();
        let postal = $('#postalcode').val();
        let mb = mobile.length;
        if(mb == 10){
           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $.ajax({
            type:"POST",
            url:url,
            data:{
                mobile:mobile,
                postal:postal
            },
            success:function(data){
                if(data == 0){
                    
                     $("#mobilecheck").html(`<a href="javascript:void(0)" style="color:green;">Your mobile no is valid</a>`)
                 }else{
                    
                     $("#mobilecheck").html(`<a href="javascript:void(0)" style="color:red;">This mobile no already register</a>`) 
                 }
            }
        });
        }else if(mb == 0){
            $("#mobilecheck").html("") 
        }else{
            $("#mobilecheck").html(`<a href="javascript:void(0)" style="color:red;">This mobile no must be 10 digit</a>`) 
        }
    });
    $(document).ready(function(){
         $('#newcheckbox').hide();
         $('#agr').hide();
             $('#newcheckboxm').hide();
         $('#agrm').hide();
       
        $(document).on('change','#post_arrangement1',function(){
               let newvalue = $('#post_arrangement1').val();
              if(newvalue == 2){
                    //  $('#newcheckboxm').show();
                    //  $('#agrm').show();
                    
                      Swal.fire({
                              title: 'Download Agriment? if not dont check and click ok',
                              input: 'checkbox',
                              id:'newcheckboxm2',
                              inputPlaceholder: 'Yes Download Agriment'
                            }).then((result) => {
                              if (result.isConfirmed) {
                                if (result.value) {
                                     let url = $('#newurlm').attr('data-url');
                                  Swal.fire({icon: 'success', text: 'Downloaded!'});
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });
                                      $.ajax({
                                          type:"post",
                                          url:url,
                                          success:function(data){
                                              let urllink = JSON.parse(data)
                                              let fileName = 'postpaidagreement.pdf';
                                                   var blob = new Blob([data], { type: "application/octetstream" });
                    
                                
                                                        var isIE = false || !!document.documentMode;
                                                        if (isIE) {
                                                            window.navigator.msSaveBlob(blob, fileName);
                                                        } else {
                                                            var url = window.URL || window.webkitURL;
                                                            link = url.createObjectURL(blob);
                                                            var a = $("<a />");
                                                            a.attr("download", fileName);
                                                            a.attr("href", urllink);
                                                            $("body").append(a);
                                                            a[0].click();
                                                            $("body").remove(a);
                                                        }
                                          }
                                      });
                                   
                                } else {
                                  Swal.fire({icon: 'error', text: "cancel download:("});
                                   $('#post_arrangement1').find('option[value="0"]').prop('selected', true);
                                }
                              } else {
                                console.log(`modal was dismissed by ${result.dismiss}`)
                              }
                            })
                      }else{
                   $('#newcheckboxm').hide();
                   $('#agrm').hide();
              }
        });
         $(document).on('click','#newcheckbox',function(){
             if($('#newcheckbox').is(':checked')){
                  let url = $('#newurl').attr('data-url');
                 
                   $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });
                  $.ajax({
                      type:"post",
                      url:url,
                      success:function(data){
                          let urllink = JSON.parse(data)
                          let fileName = 'postpaidagreement.pdf';
                               var blob = new Blob([data], { type: "application/octetstream" });

            
                                    var isIE = false || !!document.documentMode;
                                    if (isIE) {
                                        window.navigator.msSaveBlob(blob, fileName);
                                    } else {
                                        var url = window.URL || window.webkitURL;
                                        link = url.createObjectURL(blob);
                                        var a = $("<a />");
                                        a.attr("download", fileName);
                                        a.attr("href", urllink);
                                        $("body").append(a);
                                        a[0].click();
                                        $("body").remove(a);
                                    }
                      }
                  });
             }
         });
           $(document).on('change','#post_arrangement',function(){
              let newvalue = $('#post_arrangement').val();
             
              if(newvalue == 2){
                    //  $('#newcheckboxm').show();
                    //  $('#agrm').show();
                    
                      Swal.fire({
                              title: 'Download Agriment? if not dont check and click ok',
                              input: 'checkbox',
                              id:'newcheckboxm2',
                              inputPlaceholder: 'Yes Download Agriment'
                            }).then((result) => {
                              if (result.isConfirmed) {
                                if (result.value) {
                                     let url = $('#newurlm').attr('data-url');
                                  Swal.fire({icon: 'success', text: 'Downloaded!'});
                                    $.ajaxSetup({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        }
                                    });
                                      $.ajax({
                                          type:"post",
                                          url:url,
                                          success:function(data){
                                              let urllink = JSON.parse(data)
                                              let fileName = 'postpaidagreement.pdf';
                                                   var blob = new Blob([data], { type: "application/octetstream" });
                    
                                
                                                        var isIE = false || !!document.documentMode;
                                                        if (isIE) {
                                                            window.navigator.msSaveBlob(blob, fileName);
                                                        } else {
                                                            var url = window.URL || window.webkitURL;
                                                            link = url.createObjectURL(blob);
                                                            var a = $("<a />");
                                                            a.attr("download", fileName);
                                                            a.attr("href", urllink);
                                                            $("body").append(a);
                                                            a[0].click();
                                                            $("body").remove(a);
                                                        }
                                          }
                                      });
                                   
                                } else {
                                  Swal.fire({icon: 'error', text: "cancel download:("});
                                   $('#post_arrangement').find('option[value="0"]').prop('selected', true);
                                }
                              } else {
                                console.log(`modal was dismissed by ${result.dismiss}`)
                              }
                            })
                      }else{
                   $('#newcheckboxm').hide();
                   $('#agrm').hide();
              }
        });
         $(document).on('click','#newcheckboxm',function(){
             if($('#newcheckboxm').is(':checked')){
                  let url = $('#newurlm').attr('data-url');
                 
                   $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });
                  $.ajax({
                      type:"post",
                      url:url,
                      success:function(data){
                          let urllink = JSON.parse(data)
                          let fileName = 'postpaidagreement.pdf';
                               var blob = new Blob([data], { type: "application/octetstream" });

            
                                    var isIE = false || !!document.documentMode;
                                    if (isIE) {
                                        window.navigator.msSaveBlob(blob, fileName);
                                    } else {
                                        var url = window.URL || window.webkitURL;
                                        link = url.createObjectURL(blob);
                                        var a = $("<a />");
                                        a.attr("download", fileName);
                                        a.attr("href", urllink);
                                        $("body").append(a);
                                        a[0].click();
                                        $("body").remove(a);
                                    }
                      }
                  });
             }
         });
    });
    function isEmail(email) {
          var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          return regex.test(email);
    }
</script>
@endpush
