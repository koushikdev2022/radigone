@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="user-profile-area mt-30">
        <form action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-5 col-md-12 col-sm-12 mb-30">
                    <div class="panel panel-default">
                        <div class="panel-heading d-flex flex-wrap align-items-center justify-content-between">
                            <div class="panel-title"><i class="las la-user"></i> @lang('User Details')</div>
                            <div class="panel-options">
                                <a href="#" data-rel="collapse"><i class="las la-chevron-circle-down"></i></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="panel-body-inner">
                                <div class="profile-thumb-area text-center">
                                    <div class="profile-thumb">
                                        <div class="image-preview bg_img" data-background="{{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,imagePath()['profile']['user']['size']) }}"></div>
                                    </div>
                                    <div class="profile-edit">
                                        <input type="file" name="image" id="imageUpload" class="upload" accept=".png, .jpg, .jpeg">
                                        <div class="rank-label">
                                            <label for="imageUpload" class="imgUp bg--primary">
                                                @lang('Upload Image')
                                            </label>
                                        </div>
                                    </div>
                                    <div class="profile-content-area text-center mt-20">
                                        <h3 class="name">{{__($user->fullname)}}</h3>
                                        <h5 class="email">@lang('E-Mail') : {{__($user->email)}}</h5>
                                        <h5 class="phone">@lang('Phone') : {{$user->mobile}}</h5>
                                        <h5 class="address">@lang('Country') : {{$user->address->country}}</h5>
                                        <h5 class="reference">@lang('Balance') : <span class="badge badge--primary text-white">{{getAmount($user->balance)}}</span></h5>
                                        <h5 class="reference">@lang('Status') : <span class="badge badge--primary text-white">@lang('Active')</span></h5>

                                        <a href="#0" class="btn btn--success text-white btn-rounded btn-block btn-icon icon-left mt-20"
                                            data-clipboard-text="bbakaHwKsaMc">
                                            <i class="las la-clipboard-check"></i> @lang('Status : Active')
                                        </a>

                                        <div class="profile-footer-btn mt-10">
                                            <div class="row mb-10-none">
                                                <div class="col-md-6 col-sm-12 mb-10">
                                                    <a href="{{ route('user.change-password') }}"
                                                        class="btn btn--primary  text-white btn-rounded btn-block btn-icon icon-left"><i
                                                            class="las la-lock"></i> @lang('Change Password')</a>
                                                </div>
                                                @if($user->percentage == 100)
                                                    <div class="col-md-6 col-sm-12 mb-10">
                                                        <a href="{{route('user.survey')}}" class="btn btn--primary text-white btn-rounded btn-block btn-icon icon-left"><i class="lar la-question-circle"></i> @lang('Start Viewing Ads')</a>
                                                    </div>
                                                @else
                                                    <div class="col-md-6 col-sm-12 mb-10">
                                                        <a href="javascript:void(0)" class="btn btn--primary text-white btn-rounded btn-block btn-icon icon-left disabled" title="You have to 100% your profile first" disabled><i class="lar la-question-circle"></i> @lang('Start Viewing Ads')</a>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-md-12 col-sm-12 mb-30">
                    <div class="panel panel-default">
                        <div class="panel-heading d-flex flex-wrap align-items-center justify-content-between">
                            <div class="panel-title"><i class="las la-user"></i> @lang('User Form')</div>
                            <div class="panel-options-form">
                                <a href="#" data-rel="collapse"><i class="las la-chevron-circle-down"></i></a>
                            </div>
                        </div>
                        <div class="panel-form-area">
                            <div class="row justify-content-center">
                                <div class="col-lg-6 form-group">
                                    <label>@lang('First Name') <span class="text-danger">*</span></label>
                                    <input type="text" name="firstname" class="form-control" value="{{$user->firstname}}" {{!is_null($user->firstname) ? 'readonly' : null}} required>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input type="text" name="lastname" class="form-control" value="{{$user->lastname}}" {{!is_null($user->lastname) ? 'readonly' : null}} required>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Email Address')</label>
                                    <input type="email" class="form-control" value="{{$user->email}}" readonly>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Mobile Number')</label>
                                    <input type="hidden" id="track" name="country_code">
                                    <input type="tel" class="form-control" value="{{$user->mobile}}" placeholder="@lang('Your Contact Number')" {{!is_null($user->mobile) ? 'readonly' : null}} readonly>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label>@lang('Whatsapp Number')</label>

                                    <input type="tel" name="whatsaap" class="form-control" value="{{$user->whatsaap}}" placeholder="@lang('Your Whatsapp Numbe')" {{!is_null($user->whatsaap) ? 'readonly' : null}}>
                                </div>
                                <div class="col-lg-6 form-group" style="display:none">
                                    <label>@lang('Age')</label>
                                    <input type="text" class="form-control" value="{{$user->age}}" disabled>
                                </div>
                                <div class="col-lg-6 form-group">
                                   <label>Gender</label>
                                    @if(!is_null($user->gander))
                                        <input type="text" name="gander" class="form-control" value="{{$user->gander}}" readonly>
                                    @else
                                        <select class="form-select" name="gander" aria-label="Default select example" style="width: 100%;height: 43px;">
                                            <option selected>Selected</option>
                                            <option <?php echo $user->gander == 'male' ? 'selected' : ''?> value="male">male</option>
                                            <option <?php echo $user->gander == 'female' ? 'selected' : ''?> value="female">female</option>

                                        </select>
                                    @endif


                                </div>
                                <div class="col-lg-6 form-group">
                                   <label>Marital</label>
                                    @if(!is_null($user->marital))
                                        <input type="text" name="marital" class="form-control" value="{{$user->marital}}" readonly>
                                    @else
                                        <select class="form-select marital" name="marital" id="marital" aria-label="Default select example" style="width: 100%;height: 43px;">
                                            <option selected>Selected</option>
                                            <option <?php echo $user->marital == 'married' ? 'selected' : ''?> value="married">married</option>
                                            <option <?php echo $user->marital == 'single' ? 'selected' : ''?> value="single">single</option>

                                        </select>
                                    @endif


                                </div>
                                <div class="col-md-6 marital_gold" style="display:none">
                                    <div class="form-group">
                                        <label>@lang('Anniversary Date')</label>
                                      <div class="input-group" data-provide="datepicker">
                                          <input name="anniversary_date" value="{{$user->anniversary_date}}" type="date"  data-language="en" class="datepicker-here form-control bg-white text--black" data-position='bottom left' placeholder="@lang('Schedule Ad (or Leave Empty for Quick Start)')" autocomplete="off" value="{{ @$dateSearch }}" {{!is_null($user->anniversary_date) && $user->anniversary_date != '' ? 'readonly' : null}}>

                                              <div class="input-group-addon">
                                                  <span class="glyphicon glyphicon-th"></span>
                                              </div>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 form-group" >
                                    <label>@lang('Profession')</label>
                                    <input type="text" class="form-control" name="profession" value="{{$user->profession}}" {{!is_null($user->profession) && $user->profession != '' ? 'readonly' : null}}>
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label>@lang('Address') <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control" placeholder="@lang('Address')" value="{{@$user->address->address}}" required {{!is_null($user->address->address) && $user->address->address != '' ? 'readonly' : null}}>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('State') <span class="text-danger">*</span></label>
                                    <input type="text" name="state" class="form-control" placeholder="@lang('state')" value="{{@$user->address->state}}" required {{!is_null($user->address->state) && $user->address->state != '' ? 'readonly' : null}}>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Zip/Pin Code') <span class="text-danger">*</span></label>
                                    <input type="text" name="zip" class="form-control" placeholder="@lang('Zip Code')" value="{{@$user->address->zip}}" required {{!is_null($user->address->zip) && $user->address->zip != '' ? 'readonly' : null}}>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('City') <span class="text-danger">*</span></label>
                                    <input type="text" name="city" class="form-control" placeholder="@lang('city')" value="{{@$user->address->city}}" required {{!is_null($user->address->city) && $user->address->city != ''  ? 'readonly' : null}}>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>Date of Birth</label>
                                    <input type="date" name="dob" class="form-control" placeholder="Date of Birth" value="{{@$user->dob}}" {{!is_null($user->dob) ? 'readonly' : null}}>
                                </div>
                                <div class="col-lg-6 form-group">
                                   <label>Occupation</label>
                                    <select class="form-select" name="occupation" aria-label="Default select example" style="width: 100%;height: 43px;">
                                      <option selected>Selected</option>
                                      <option value="service-govt" <?php echo $user->occupation == 'service-govt' ? 'selected' : ''?> >Service-Govt</option>
                                      <option  <?php echo $user->occupation == 'job-private' ? 'selected' : ''?> value="job-private">Job-Private</option>
                                      <option <?php echo $user->occupation == 'self-employed' ? 'selected' : ''?> value="self-employed">Self-employed</option>
                                      <option <?php echo $user->occupation == 'professional-doctor-engineer-charted' ? 'selected' : ''?> value="professional-doctor-engineer-charted">Professional-Doctor-Engineer-Charted Accountant-IT Professional</option>
                                      <option <?php echo $user->occupation == 'retired-personal' ? 'selected' : ''?> value="retired-personal">Retired Personal</option>
                                      <option <?php echo $user->occupation == 'home-maker' ? 'selected' : ''?> value="home-maker">Home Maker</option>
                                      <option <?php echo $user->occupation == 'student' ? 'selected' : ''?> value="student">Student</option>
                                      <option <?php echo $user->occupation == 'other' ? 'selected' : ''?> value="other">Any other</option>
                                    </select>

                                </div>
                                <!-- <div class="col-lg-6 form-group">-->
                                <!--    <label>Anniversary Date</label>-->
                                <!--    <input type="date" name="anniversary_date" class="form-control" placeholder="Date of Birth" value="{{@$user->dob}}">-->
                                <!--</div>-->
                                <div class="col-lg-6 form-group">
                                   <label>Annual Income</label>
                                    <select class="form-select" name="annual_income" aria-label="Default select example" style="width: 100%;height: 43px;">
                                      <option selected>Selected Annual Income</option>
                                      <option <?php echo $user->annual_income == 'up-to-rs-100000' ? 'selected' : ''?> value="up-to-rs-100000">Up to Rs 100000</option>
                                      <option <?php echo $user->annual_income == 'rs-100001-rs-300000' ? 'selected' : ''?> value="rs-100001-rs-300000">Rs 100001-Rs 300000</option>
                                      <option <?php echo $user->annual_income == 'rs-300001-500000' ? 'selected' : ''?> value="rs-300001-500000">Rs 300001-500000</option>
                                      <option <?php echo $user->annual_income == 'rs-500001-750000' ? 'selected' : ''?> value="rs-500001-750000">Rs 500001-750000</option>
                                      <option <?php echo $user->annual_income == 'rs-750001-1000000' ? 'selected' : ''?> value="rs-750001-1000000">Rs 750001-1000000</option>
                                      <option <?php echo $user->annual_income == 'rs-1000000' ? 'selected' : ''?> value="rs-1000000">Rs 1000000</option>
                                      <option <?php echo $user->annual_income == 'above' ? 'selected' : ''?> value="above">Above</option>

                                    </select>

                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>PAN Card No.</label>
                                    <input type="text" name="pan" class="form-control pan" placeholder="Enter Pan Card No" value="{{@$user->pan}}" {{!is_null($user->pan) ? 'readonly' : null}}>
                                    <small class="text-danger" id="pan-card-err"></small>
                                <!--<small>Enter PAN to avoid 20% TDS (NSDL API will use here and Name and DOB should fill as per PAN record and freeze the information against verified mobile number)</small>-->
                                </div>

                                <div class="col-lg-6 form-group">
                                    <label>Account number</label>
                                    <input type="text" name="account_number" class="form-control" placeholder="Account number" value="{{@$user->account_number}}" {{!is_null($user->account_number) ? 'readonly' : null}}>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>Re-Entre Account number</label>
                                    <input type="text" name="re_account_number" class="form-control" placeholder="Re-Entre Account number" value="{{@$user->re_account_number}}" {{!is_null($user->re_account_number) ? 'readonly' : null}}>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>Bank IFSC</label>
                                    <input type="text" name="bank_ifsc" class="form-control bank-ifsc" placeholder="Bank IFSC" maxlength="11" title="11 digit bank a/c no." value="{{@$user->bank_ifsc}}" {{!is_null($user->bank_ifsc) ? 'readonly' : null}}>
                                    <small class="text-danger" id="bank-ifsc-err"></small>
                                </div>
                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="submit-btn">@lang('Update')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('script')
  <script>
    "use strict";
    var value = $( "#marital option:selected" ).val();
    if(value =='single'){
             $(".marital_gold").hide();

         }else{

             $(".marital_gold").show();

         }
     $("select.marital").change(function(){

         var value = $( "#marital option:selected" ).val();

         if(value =='single'){
             $(".marital_gold").hide();

         }else{

             $(".marital_gold").show();

         }
    })

    $(".bank-ifsc").on("change", function () {
        var inputvalues = $(this).val();
        var regex = /[A-Z|a-z|0-9]{11}$/;
        if(!regex.test(inputvalues)){
            $(".bank-ifsc").val("");
            // alert("invalid PAN no");
            $('#bank-ifsc-err').text('11 digit alphanumeric is allowed');
            return regex.test(inputvalues);
        }else{
            $('#bank-ifsc-err').text('');
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
  </script>
@endpush

