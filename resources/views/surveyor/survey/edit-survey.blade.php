@extends('surveyor.layouts.app')

@section('panel')
    @php
        if(isset($rejected)) {
            $route = route('surveyor.survey.update_reject_ad', $survey->id);
        }else {
            $route = route('surveyor.survey.update_before_submit', $survey->id);
        }
    @endphp
    <div class="row">


        <div class="col-lg-12">
            <div class="card">
                <form action="{{$route}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="totl_amount" id="totl_amount" value="{{getAmount($surveyor->balance)}}">
                    <input type="hidden" name="ischeckrepublish" value="ischeckrepublish">
                    <input type="hidden" name="new_id" value="{{$survey->id}}" >
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="col-md-2 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Category')</label>
                                            <input type="text" class="form-control" value="{{$category->name}}" readonly>
                                            <input type="hidden" name="category_id" id="cat_name" value="{{$category->id}}" data-catprice={{$category->price}}>
{{--                                            <select id="cat_name" name="category_id" class="form-control" required>--}}
{{--                                                <option value="">Select Category</option>--}}
{{--                                                @foreach($categories as $cat)--}}
{{--                                                    <option data-catprice={{$cat->price}} value="{{$cat->id}}" {{$survey->category_id == $cat->id ? 'SELECTED' : ''}}>{{$cat->name}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Subcategory')</label>
                                            <input type="text" id="ct_name" class="form-control" value="{{$surveyor->business_subcat}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Product/Service Name')</label>
                                            <!--<select name="p_name" class="form-control" required>-->
                                            <!--    <option value="Yoga Products">@lang('Yoga Products')</option>-->
                                            <!--    <option value="Accessories">@lang('Accessories')</option>-->
                                            <!--    <option value="Fashion">@lang('Fashion')</option>-->
                                            <!--    <option value="Personal Care">@lang('Personal Care')</option>-->
                                            <!--</select>-->
                                            <input type="text" class="form-control" name="p_name" placeholder="@lang('Product/Service Name')" value="{{$survey->p_name}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Product/Service Specifications')</label>
                                            <!--<select name="p_specifications" class="form-control" required>-->
                                            <!--    <option value="You can use these products for doing exercises at home.">@lang('You can use these products for doing exercises at home.')</option>-->
                                            <!--    <option value="Combo of Beauty care products">@lang('Combo of Beauty care products')</option>-->
                                            <!--    <option value="you can have various small parts of any machine">@lang('you can have various small parts of any machine')</option>-->

                                            <!--</select>-->
                                            <input type="text" class="form-control" name="p_specifications" placeholder="@lang('Product/Service Specifications')" value="{{$survey->p_specification}}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Product MRP/Service Charge')</label>
                                            <input type="number" class="form-control" name="p_mrp" placeholder="@lang('Enter Product MRP/Service Charge')" value="{{$survey->p_mrp}}" required>
                                        </div>
                                    </div>
                                    <!--<div class="col-md-5 for-custom-input">-->
                                    <!--    <div class="form-group">-->
                                    <!--        <label>@lang('Discount Offer')</label>-->
                                    <!--        <input type="number" class="form-control" name="d_offer" placeholder="@lang('Enter Discount Offer')" required>-->
                                    <!--        <div class="input-group-prepend">-->
                                    <!--         <div class="input-group-text">%</div>-->
                                    <!--       </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->

                                    <div class="col-md-6 ">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">@lang('Sale Price') </label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="number" class="form-control" name="d_offer" placeholder="@lang('Enter Sale Offer')" value="{{$survey->discount}}" required>
                                                <!--<div class="input-group-prepend">-->
                                                <!--  <div class="input-group-text">%</div>-->
                                                <!--</div>-->
                                            </div>
                                        </div>
                                    </div>


                                </div>


                               <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-4 for-custom-input">
                                            <div class="form-group">
                                                <label>@lang('Advt . Package Paas')</label>
                                                <select name="type_of_ad" id="type_of_ad" class="form-control type_of_ad" required>
                                                    <option value="">@lang('Select type')</option>
                                                    <!--<option value="silver">@lang('Silver')</option>-->
                                                    <!--<option value="gold">@lang('Gold')</option>-->
                                                    <!--<option value="diamond">@lang('Diamond')</option>-->

                                                    @foreach($adtype as $cat)
                                                        <option value="{{$cat->id}}" class="passes" data-adprice={{$cat->price}} {{surveySession('type_of_ad') == $cat->id ? 'selected' : ''}}>{{$cat->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label>@lang('Per View data required')</label>
                                            <div class="form-group">
                                                <div class="r_sliver" id="r_silver" style="display:none">
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="name" checked>@lang(' Name')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="contact" checked>@lang(' Contact')<br>
                                                   <input type="checkbox" id="r_email" name="r_data[]" value="email" checked>@lang(' Email')<br>
                                                </div>
                                                <div class="r_gold" id="r_gold" style="display:none">
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="name" checked>@lang(' Name')<br>
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="gender" checked>@lang(' Gender')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="age" checked>@lang(' Age')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="marital-status" checked>@lang(' Marital Status')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="mobile" checked>@lang(' Mobile Number')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="whatsapp" checked>@lang(' Whatsapp Number')<br>
                                                    <input type="checkbox" id="r_email" name="r_data[]" value="email" checked>@lang(' Email')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="birthday" checked>@lang(' Birthday')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="anniversary" checked>@lang(' Anniversary')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="city" checked>@lang(' City')<br>
                                                </div>
                                                <div class="r_diamond" id="r_diamond" style="display:none">
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="name" checked>@lang(' Name')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="mobile" checked>@lang(' Mobile Number')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="whatsapp" checked>@lang(' Whatsapp Number')<br>
                                                    <input type="checkbox" id="r_email" name="r_data[]" value="email" checked>@lang(' Email')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="age" checked>@lang(' Age')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="anniversary" checked>@lang(' Anniversary')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="city" checked>@lang(' City')<br>
                                                </div>
                                                <div class="r_platinum" id="r_platinum" style="display:none">
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="name" checked>@lang(' Name')<br>
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="gender" checked>@lang(' Gender')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="age" checked>@lang(' Age')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="marital-status" checked>@lang(' Marital Status')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="mobile" checked>@lang(' Mobile Number')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="whatsapp" checked>@lang(' Whatsapp Number')<br>
                                                    <input type="checkbox" id="r_email" name="r_data[]" value="email" checked>@lang(' Email')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="birthday" checked>@lang(' Birthday')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="anniversary" checked>@lang(' Anniversary')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="profession" checked>@lang(' Profession')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="annual-income" checked>@lang(' Annual Income')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="city" checked>@lang(' City')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="country" checked>@lang(' Country')<br>
                                                     <input type="checkbox" id="r_current" name="r_data[]" value="state" checked>@lang(' State')<br>
                                                      <input type="checkbox" id="r_current" name="r_data[]" value="pincode" checked>@lang(' Pincode')<br>
                                                </div>



                                            </div>

                                        </div>

                                        <div class="col-md-4 for-custom-input_target">
                                            <div class="form-group">
                                                <input type="hidden" name="audience_cat"  id="input_json">
                                                <label>@lang('Target market')</label>
                                                <select name="" id="type_of_ad_target" class="form-control type_of_ad" required>
                                                    <option value="General" {{surveySession('audience') == 'General' ? 'selected' : ''}}>@lang('General')</option>
                                                    <option value="name" >@lang(' Name')</option>
                                                    <option value="gender" >@lang(' Gender')</option>
                                                    <option  value="age" checked>@lang(' Age')</option>
                                                    <option value="marital-status" checked>@lang(' Marital Status')</option>
                                                    <option value="mobile" checked>@lang(' Mobile Number')</option>
                                                    <option  value="whatsapp" checked>@lang(' Whatsapp Number')</option>
                                                    <option value="email" checked>@lang(' Email')</option>
                                                    <option  value="birthday" checked>@lang(' Birthday')</option>
                                                    <option  value="anniversary" checked>@lang(' Anniversary')</option>
                                                    <option  value="profession" checked>@lang(' Profession')</option>
                                                    <option value="annual-income" checked>@lang(' Annual Income')</option>
                                                    <option  value="city" checked>@lang(' City')</option>
                                                    <option  value="country" checked>@lang(' Country')</option>
                                                    <option  value="state" checked>@lang('State')</option>
                                                    <option  value="pincode" checked>@lang(' Pincode')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 for-custom-input">
                                            <div class="form-group" id="tm_sub_category_div">
                                                <label>@lang('targrt market option')</label>
                                                <div class="form-group" id="tm_sub_category_div_n"></div>
                                                <a class="btn btn--primary mb-3" id="add-btn" href="#">Add Target Market</a>
                                                <table border="1px solid" class="ms-2" id="example-table">
                                                    <thead>
                                                        <tr>
                                                            <td> Name</td>
                                                            <td> Value</td>
                                                            <td> Remove</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablebody">
                                                      
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">


                                    <div class="form-row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Survey Image')</label>
                                                <div class="image-upload">
                                                    <div class="thumb">
                                                        <div class="avatar-preview">
                                                            <div class="profilePicPreview" style="background-image: url({{ getImage(imagePath()['survey']['path'].'/'. $survey->image,imagePath()['survey']['size']) }})">
                                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>

                                                        <div class="avatar-edit">
                                                            <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                            <label for="profilePicUpload1" class="bg--success"> @lang('Image')</label>
                                                            <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg, jpg, png')</b>.
                                                                @lang('Image Will be resized to'): <b>{{imagePath()['survey']['size']}}</b> px.

                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 for-custom-input d-none">
                                            <div class="form-group">
                                                <label>@lang('Types of Ad')</label>
                                                <select name="type_of_ad" id="type_of_ad" class="form-control type_of_ad" required>
                                                    <option value="">@lang('Select type')</option>
                                                    <!--<option value="silver">@lang('Silver')</option>-->
                                                    <!--<option value="gold">@lang('Gold')</option>-->
                                                    <!--<option value="diamond">@lang('Diamond')</option>-->

                                                    @foreach($adtype as $cat)
                                                        <option value="{{$cat->id}}" {{$cat->id == $survey->ad_type ? 'SELECTED' : ''}} data-adprice={{$cat->price}}>{{$cat->name}}</option>
                                                    @endforeach


                                                </select>

                                            </div>

                                            <label>@lang('Per View data required')</label>
                                            <div class="form-group">
                                                <div class="r_sliver" style="display:none">
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="name" checked>@lang(' Name')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="Contact" checked>@lang(' Contact')<br>
                                                    <input type="checkbox" id="r_email" name="r_data[]" value="Email" checked>@lang(' Email')<br>
                                                </div>
                                                <div class="r_gold" style="display:none">
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="name" checked>@lang(' Name')<br>
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="gender" checked>@lang(' Gender')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="age" checked>@lang(' Age')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="marital-status" checked>@lang(' Marital Status')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="mobile" checked>@lang(' Mobile Number')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="whatsapp" checked>@lang(' Whatsapp Number')<br>
                                                    <input type="checkbox" id="r_email" name="r_data[]" value="email" checked>@lang(' Email')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="birthday" checked>@lang(' Birthday')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="anniversary" checked>@lang(' Anniversary')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="city" checked>@lang(' City')<br>
                                                </div>
                                                <div class="r_diamond" style="display:none">
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="name" checked>@lang(' Name')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="mobile" checked>@lang(' Mobile Number')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="whatsapp" checked>@lang(' Whatsapp Number')<br>
                                                    <input type="checkbox" id="r_email" name="r_data[]" value="email" checked>@lang(' Email')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="age" checked>@lang(' Age')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="anniversary" checked>@lang(' Anniversary')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="city" checked>@lang(' City')<br>
                                                </div>
                                                <div class="r_platinum" style="display:none">
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="name" checked>@lang(' Name')<br>
                                                    <input type="checkbox" id="r_name" name="r_data[]" value="gender" checked>@lang(' Gender')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="age" checked>@lang(' Age')<br>
                                                    <input type="checkbox" id="r_age" name="r_data[]" value="marital-status" checked>@lang(' Marital Status')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="mobile" checked>@lang(' Mobile Number')<br>
                                                    <input type="checkbox" id="r_contact" name="r_data[]" value="whatsapp" checked>@lang(' Whatsapp Number')<br>
                                                    <input type="checkbox" id="r_email" name="r_data[]" value="email" checked>@lang(' Email')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="birthday" checked>@lang(' Birthday')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="anniversary" checked>@lang(' Anniversary')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="profession" checked>@lang(' Profession')<br>
                                                    <input type="checkbox" id="r_gender" name="r_data[]" value="annual-income" checked>@lang(' Annual Income')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="city" checked>@lang(' City')<br>
                                                    <input type="checkbox" id="r_current" name="r_data[]" value="country" checked>@lang(' Country')<br>
                                                </div>



                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Type of Offer')</label>
                                            <select name="t_offer" class="form-control" required>
                                                <option value="">@lang('Select Type of Offer')</option>
                                                @foreach($offerType as $cat)
                                                    <option value="{{$cat->id}}" {{$cat->id == $survey->offer_type ? 'SELECTED' : ''}}>{{$cat->name}}</option>
                                                @endforeach

                                                <!--<option value="0">@lang('1')</option>-->
                                                <!--<option value="1">@lang('2')</option>-->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Number of Views(min 100 to 1000000)')</label>
                                            <input type="number" id="nviews" class="form-control" min="100" max="1000000" onInput="showCurrentValue(event)" name="n_views" placeholder="@lang('Enter Number of Views(100 to 1000000)')" value="{{$survey->total_views}}" required readonly>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 audience for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Publish in General or To Target Market')</label>
                                            <select name="audience" class="form-control" required>
                                                <option value="General" {{$survey->publish == 'General' ? 'SELECTED' : ''}}>@lang('General')</option>
                                                <option value="Target Market" {{$survey->publish == 'Target Market' ? 'SELECTED' : ''}}>@lang('Target Market')</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>


                                <div class="form-row">
                                    {{--                                    <div class="col-md-6 for-custom-input">--}}
                                    {{--                                        <div class="form-group">--}}
                                    {{--                                            <label>@lang('Number of Slides/Pictures')</label>--}}
                                    {{--                                            <select name="slides"  class="form-control slides"  id="slidesval" required>--}}
                                    {{--                                               <option value="">@lang('Select')</option>--}}
                                    {{--                                                <option value="1">@lang('1')</option>--}}
                                    {{--                                                <option value="3">@lang('3')</option>--}}
                                    {{--                                                <option value="5">@lang('5')</option>--}}
                                    {{--                                            </select>--}}
                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Per slide/Picture time in seconds(Minimum total advertisement time is 10 seconds)')</label>
                                            <input type="text" class="form-control" id="slides_time" name="slides_time" value="{{$survey->slides_time}}" readonly>
{{--                                            <select name="slides_time" class="form-control" id="slides_time" required>--}}
{{--                                                <option value="">Per slide/Picture time in seconds</option>--}}
{{--                                                @foreach($sliderPrice as $cat)--}}
{{--                                                    <option data-slprice={{$cat->price}} value="{{$cat->second}}" {{$cat->second == $survey->slides_time ? 'SELECTED' : ''}}>{{$cat->second}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="audience_cat">
                                        <div class="form-group">
                                            <label>@lang('Schedule Ad (or Leave Empty for Quick Start)')</label>
                                            <div class="input-group" data-provide="datepicker">
                                                <input name="date" type="text"  data-language="en" class="datepicker-here form-control bg-white text--black" data-position='bottom left' placeholder="@lang('Schedule Ad (or Leave Empty for Quick Start)')" autocomplete="off" value="{{ @$dateSearch }}" >

                                                <div class="input-group-addon">
                                                    <span class="glyphicon glyphicon-th"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Run with repeated viewers')</label>
                                            <select name="repeated" class="form-control" required>
                                                <option value="0" {{$survey->repeated_viewers == 0 ? 'SELECTED' : ''}}>@lang('no')</option>
                                                <option value="1" {{$survey->repeated_viewers == 1 ? 'SELECTED' : ''}}>@lang('yes')</option>
                                            </select>
                                        </div>
                                    </div>
                                    {{--                                    <div class="col-md-6 for-custom-input">--}}
                                    {{--                                        <div class="form-group">--}}
                                    {{--                                            <label>@lang('Select Duration of Adve rtisement(In Seconds)')</label>--}}
                                    {{--                                            <input type="text" name="ad_duration"  id="ad_duration" class="form-control" required />--}}

                                    {{--                                        </div>--}}
                                    {{--                                    </div>--}}
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Online Purchase Required')</label>
                                            <select id="o_purchase" name="o_purchase" class="form-control opurchase" required>
                                                <option value="0" {{ $survey->online_purchase == 0 ? 'SELECTED' : '' }}>@lang('no')</option>
                                                <option value="1" {{ $survey->online_purchase == 1 ? 'SELECTED' : '' }}>@lang('yes')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 for-custom-input url_fild" id="url_fild" style="display:none">
                                        <div class="form-group">
                                            <label>@lang('Online Purchas Url')</label>
                                            <input type="text" id="opurl" class="form-control"  name="opurl" value="{{$survey->purchas_url}}">
                                        </div>
                                    </div>

                                    <div class="col-md-3 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Total Price')</label>
                                            <input type="number" id="total" class="form-control"  name="total" value="{{$survey->total_without_gst}}"   readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-3 for-custom-input">
                                        <div class="form-group ">
                                            <label class="form-control-label font-weight-bold">GST ( {{$gst}} %)</label>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="number" id="gst" class="form-control"  name="totalpricegst" value={{$gst}}   readonly>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">%</div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-3 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Pay Price')</label>
                                            <input type="number" id="totalprice" class="form-control"  name="totalprice" value="{{$survey->totalprice}}"   readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Per User Price')</label>
                                            <input type="number" id="peruserprice" class="form-control"  name="peruserprice" value="{{!is_null(surveySession('peruserprice')) ? surveySession('peruserprice') : 0}}"   readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Choose Template or Upload Template')</label>
                                            <div id="img"></div>
                                            <!--<select name="template" class="form-control" required>-->

                                            <!--    <option value="0">@lang('Upload Template')</option>-->
                                            <!--    <option value="1">@lang('Choose Template')</option>-->
                                            <!--</select>-->
                                        </div>
                                    </div>

                                </div>
{{--                                <div class="form-row" id="full-image-input">--}}
{{--                                    <div class="col-lg-12">--}}
{{--                                        <input type="file" id="upload-to-server" accept="image/x-png,image/gif,image/jpeg,image/png">--}}
{{--                                        <small class="mt-2 text-facebook">Supported files: <b>jpeg, jpg, png</b>. Image Will be resized to: <b>360x190</b> px.</small>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="form-row" id="show-image">--}}

{{--                                </div>--}}
{{--                                <div class="form-row" id="show-slide">--}}

{{--                                </div>--}}
                                <input type="hidden" name="video_url" id="video-url" value="{{$survey->video_url}}">

                            </div>
{{--                            <button class="btn btn--primary m-3" id="create-slideshow">@lang('Create Video') <span id="spin-here"></span></button>--}}
                            <div class='col-lg-6 m-3'><video controls><source src='{{$survey->video_url}}' type='video/mp4'></video></div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block" id="ad-submit-btn">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <a href="{{route('surveyor.survey.all')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush

@push('script')

    <script type="text/javascript">
        let apiURL = "https://app2.radigone.com:3001/";
        @if(env('APP_DEMO'))
            apiURL = "http://localhost:3000/";
        @endif
        showLocalImage();
        function showLocalImage()
        {
            let localImages = JSON.parse(localStorage.getItem("images"));
            if (localImages && localImages.length) {
                if(localImages.length>=4) {
                    $('#full-image-input').addClass('d-none');
                }else{
                    $('#full-image-input').removeClass('d-none');
                }
                localImages.forEach((data) => {
                    $('#show-image').html($('#show-image').html()
                        + `
                            <div class='col-lg-2 m-3'>
                                <img src='${apiURL}${data.path}' alt=''>
                                <div class="btn btn-danger btn-block mt-2" onclick="removePath()">Remove</div>
                            </div>
                        `
                    );
                });
            }
        }
        function removePath() {
            let images = JSON.parse(localStorage.getItem("images"));
            images.splice(0, 1);
            localStorage.setItem('images', JSON.stringify(images));
            let new_images = images;
            console.log(new_images);
            $('#show-image').html('');
            showLocalImage();
        }

        $('#upload-to-server').on('change', async function() {
            await uploadImage();
            // await createSlideshow();
        });

        function uploadImage()
        {
            let property = document.getElementById('upload-to-server').files[0];
            let formData = new FormData();
            formData.append('image', property)
            // let formData = {
            //     image: new FormData($('#upload-to-server')[0]),
            //     _token: $('meta[name="csrf-token"]').attr('content'),
            // };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: apiURL + 'api/image-upload',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: async function(data) {
                    let images = [];
                    const myArrayFromLocalStorage = localStorage.getItem('images')
                    if (myArrayFromLocalStorage && myArrayFromLocalStorage.length) {
                        images = JSON.parse(localStorage.getItem("images"));

                    }

                    images.push({
                        "path": "uploads/resize/"+data.image
                    });
                    await localStorage.setItem("images", JSON.stringify(images));
                    $('#show-image').append(`
                        <div class='col-lg-2 m-3'>
                            <img src='${apiURL}uploads/resize/${data.image}' alt=''>
                            <div class="btn btn-danger btn-block mt-2" onclick="removePath()">Remove</div>
                        </div>
                    `);
                    // console.log(images.length);
                    if(images.length>=4) {
                        $('#full-image-input').addClass('d-none');
                    }
                    // await showLocalImage();


                    // if(data != 0) {
                    //     location.reload();
                    // }else {
                    //     $('#initModal').modal('hide');
                    //     $('#fitInstallModal').modal('show');
                    // }
                },
                error: function(data) {
                    //showMessage(data);
                }
            });
        }

        $('#create-slideshow').on('click', function (e) {
            e.preventDefault();
            createSlideshow();
        });
        function createSlideshow()
        {
            $('#spin-here').html("<i class='fas fa-circle-notch fa-spin text-white'></i>");
            let imageValue = JSON.parse(localStorage.getItem("images"));
            let loopTime = $('#slides_time').val();
            let formData = new FormData();
            formData.append("images", imageValue);
            $.ajax({
                type: 'POST',
                url: apiURL +'api/slideshow-make',
                data: JSON.stringify({
                    "images" : imageValue,
                    "loop" : loopTime
                }),
                // dataType: 'json',
                processData: false,
                contentType: 'application/json',
                success: function(data) {
                    console.log(data);
                    localStorage.removeItem('images');
                    $('#upload-to-server').addClass('d-none')
                    $('#show-image').html("");
                    $('#show-slide').append("<div class='col-lg-6 m-3'><video controls><source src='"+ apiURL + data.video+"' type='video/mp4'></video></div>");
                    $('#video-url').val(apiURL + data.video);
                    $('#create-slideshow').addClass('d-none')
                    $('#ad-submit-btn').removeAttr('disabled')
                },
                error: function(data) {
                    //showMessage(data);
                }
            });
        }
    </script>
    <script>
        'use strict';
        function showCurrentValue(event)
        {
            {{--const value = event.target.value;--}}

            {{--var catprice =  $( "#cat_name option:selected" ).attr('data-catprice');--}}

            {{--var type_of_ad =  $( "#type_of_ad option:selected" ).attr('data-adprice');--}}

            {{--if(catprice ==undefined){--}}
            {{--    var caprice = 1;--}}
            {{--}else{--}}
            {{--    var caprice = catprice;--}}
            {{--}--}}
            {{--if(type_of_ad ==undefined){--}}
            {{--    var typeprice = 1;--}}
            {{--}else{--}}
            {{--    var typeprice = type_of_ad;--}}
            {{--}--}}

            {{--var total = typeprice*caprice*value;--}}
            {{--var gst_value = typeprice*caprice*value * <?php  echo $gst ?>/100;--}}
            {{--$("#total").val(total);--}}
            {{--$("#totalprice").val(total+gst_value);--}}
            priceCost();

        }
        (function ($) {
            var check_custom_input = $('select[name="audience"]').val();
            var custom_input_div = `<div class="form-group">
                                    <label>@lang('Custom Input Question')</label>
                                    <input type="text" name="custom_question" class="form-control" placeholder="@lang('Enter your custom input question')" required>
                                </div>`;

            var custom_input_type_div = `<div class="form-group">
                                        <label>@lang('Category for Target Market')</label>
                                        <select name="audience_cat" class="form-control" required>
                                            @include('partials.business_category')
            </select>
        </div>`;


            $('select[name="audience"]').on('change',function () {

                if ($('select[name="audience"]').val() == "Target Market") {
                    $('#audience_cat').html(custom_input_type_div);
                    $('.audience').removeClass('col-md-12').addClass('col-md-6');
                }
                if ($('select[name="audience"]').val() == "General") {
                    $('#audience_cat').html('');
                    $('.audience').removeClass('col-md-6').addClass('col-md-12');
                }
            });
        })(jQuery);
        var smallDiv = '<div class="smallDiv"><input type="file" name="fileToUpload[]" id="fileToUpload" accept="image/x-png,image/gif,image/jpeg,image/png"><small class="mt-2 text-facebook">Supported files: <b>jpeg, jpg, png</b>. Image Will be resized to: <b>360x190</b> px.</small></div>'
        function addDiv() {
            $('#img').append(smallDiv);
        }
        $(document).ready(function(){

            $('#cat_name').change(function(){
                {{--var catprice =  $(this).find(':selected').attr('data-catprice');--}}
                {{--var type_of_ad =  $( "#type_of_ad option:selected" ).attr('data-adprice');--}}

                {{--if(type_of_ad ==undefined){--}}
                {{--    var typeprice = 1;--}}
                {{--}else{--}}
                {{--    var typeprice = type_of_ad;--}}
                {{--}--}}
                {{--var nviews = $("#nviews").val();--}}
                {{--if(nviews !=''){--}}
                {{--    var newvalue = nviews--}}
                {{--}else{--}}
                {{--    var newvalue = 1;--}}
                {{--}--}}


                {{--var total = typeprice*catprice*newvalue;--}}
                {{--var gst_value = typeprice*catprice*newvalue * <?php  echo $gst ?>/100;--}}
                {{--$("#total").val(total);--}}
                {{--$("#totalprice").val(total+gst_value);--}}
                priceCost();



            });


            $('#type_of_ad').change(function(){
                {{--var type_of_ad =  $(this).find(':selected').attr('data-adprice');--}}
                {{--var catprice =  $( "#cat_name option:selected" ).attr('data-catprice');--}}

                {{--if(catprice ==undefined){--}}
                {{--    var cprice = 1;--}}
                {{--}else{--}}
                {{--    var cprice = catprice;--}}
                {{--}--}}
                {{--var nviews = $("#nviews").val();--}}
                {{--if(nviews !=''){--}}
                {{--    var newvalue = nviews--}}
                {{--}else{--}}
                {{--    var newvalue = 1;--}}
                {{--}--}}


                {{--var total = type_of_ad*cprice*newvalue;--}}
                {{--var gst_value = type_of_ad*cprice*newvalue * <?php  echo $gst ?>/100;--}}
                {{--$("#total").val(total);--}}
                {{--$("#totalprice").val(total+gst_value);--}}
                priceCost();



            });





            $("#slides_time").change(function(){

                {{--var slides_time =  $(this).find(':selected').attr('data-slprice');--}}


                {{--var nviews = $("#nviews").val();--}}
                {{--if(nviews !=''){--}}
                {{--    var newvalue = nviews--}}
                {{--}else{--}}
                {{--    var newvalue = 1;--}}
                {{--}--}}

                {{--var catprice =  $( "#cat_name option:selected" ).attr('data-catprice');--}}

                {{--if(catprice ==undefined){--}}
                {{--    var cprice = 1;--}}
                {{--}else{--}}
                {{--    var cprice = catprice;--}}
                {{--}--}}

                {{--var type_of_ad =  $( "#type_of_ad option:selected" ).attr('data-adprice');--}}

                {{--if(type_of_ad ==undefined){--}}
                {{--    var typeprice = 1;--}}
                {{--}else{--}}
                {{--    var typeprice = type_of_ad;--}}
                {{--}--}}


                {{--var total = type_of_ad*cprice*newvalue*slides_time;--}}
                {{--var gst_value = type_of_ad*cprice*newvalue*slides_time* <?php  echo $gst ?>/100;--}}
                {{--$("#total").val(total);--}}
                {{--$("#totalprice").val(total+gst_value);--}}
                priceCost();

                //  var slidesval = $("#slidesval option:selected").val();
                //  var second = slides_time * slidesval;
                //  $("#ad_duration").val(second);

            });
            $("select.slides").change(function(){
                var selectedCountry = $(this).children("option:selected").val();

                var slides_time = $("#slides_time option:selected").val();
                var second = selectedCountry * slides_time;
                $("#ad_duration").val(second);




                $('.smallDiv').remove();
                for (i = 1; i <= selectedCountry; i++){
                    addDiv();
                }
            });
            $("select.type_of_ad").change(function(){

                var value = $( "#type_of_ad option:selected" ).val();

                if(value =='2'){
                    $(".r_sliver").show();
                    $(".r_gold").hide();
                    $(".r_diamond").hide();
                    $(".r_platinum").hide();
                }else if(value =='3'){
                    $(".r_sliver").hide();
                    $(".r_gold").show();
                    $(".r_diamond").hide();
                    $(".r_platinum").hide();
                }else if(value =='1'){
                    $(".r_sliver").hide();
                    $(".r_gold").hide();
                    $(".r_diamond").show();
                    $(".r_platinum").hide();
                }else if(value =='4'){
                    $(".r_sliver").hide();
                    $(".r_gold").hide();
                    $(".r_diamond").hide();
                    $(".r_platinum").show();
                }
            })


            $("select.opurchase").change(function(){
                var value = $( "#o_purchase option:selected" ).val();

                if(value == 1){
                    $("#url_fild").css("display", "block");
                }else{
                    $("#url_fild").css("display", "none");
                }
            })





        });

        function priceCost()
        {
            console.log('OK')
            let catprice =  parseFloat($( "#cat_name" ).attr('data-catprice'));
            let type_of_ad =  parseFloat($( "#type_of_ad option:selected" ).attr('data-adprice'));
            let nviews = parseFloat($("#nviews").val());
            let slides_time =  parseFloat($("#slides_time").val());
            let user_percentage = parseFloat($('#userPercentage').attr('data-val'));
            let gst = parseFloat($('#gst').val());
            let total = (catprice + type_of_ad + slides_time) * nviews;
            let total_gst = (total * gst) / 100;
            let total_price_with_gst = total + total_gst;
            let price_per_user = total / nviews;
            $('#total').val(total);
            $("#totalprice").val(total_price_with_gst);
            $('#peruserprice').val(price_per_user);
            console.log(slides_time)
        }
        priceCost();
    </script>

    <script>

        // $(document ).ready(function() {
        //   $('#datepicker').datepicker();
        // });
    </script>
    @push('script')
        <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
        <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
        <script>
            "use strict";
            (function($){
                // var date = new Date();
                // var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                var date = new Date();
                date.setDate(date.getDate() + 3);

                $('.datepicker-here').datepicker({
                    dateFormat: 'dd-mm-yyyy',
                    todayHighlight:'TRUE',
                    minDate: date,
                    autoclose: true
                });
                $('#survey-form').submit(function() {
         let totl_amount = $('#totl_amount').val();
        
         let totl_pric = $('#totalprice').val();
       
         let short = totl_pric-totl_amount;
         
         if(totl_amount< totl_pric){
             var c = confirm(`You are amount ${short} short Click OK to payment?`);
         }
         if(c == false){
             
              window.location.href = 'https://radigone.com/surveyor/survey/all';
              return false;
         }else{
              return c; 
         }
         //you can just return c because it will be true or false
    });

            })(jQuery)
            $(document).ready(function(){
         $('#tm_sub_category_div').hide();
         $(document).on('change','#type_of_ad_target',function(){
                let target = $('#type_of_ad_target').val();
                //var target = $('#input_json').val();
                console.log(target);
                
                
                
             if(target == 'gender'){
                 $('#tm_sub_category_div').show();
                 $('#tm_sub_category_div_n').html(`<select name="target_market_value" id="targetmarketvalue" class="form-control"><option value="male">male</option><option value="female">female</option><select>`)
             }else if(target == 'pincode'){
                 
                  $('#tm_sub_category_div').show();
                  $('#tm_sub_category_div_n').html(`<input type="text" value="" id="targetmarketvalue" name="target_market_value" class="form-control">`);
             }else if(target == 'marital-status'){
                  $('#tm_sub_category_div').show();
                  $('#tm_sub_category_div_n').html(`<select name="target_market_value" id="targetmarketvalue" class="form-control"><option value="single">single</option><option value="married">married</option><select>`)
             }
             else if(target == 'annual-income'){
                 $('#tm_sub_category_div').show();
                  $('#tm_sub_category_div_n').html(`<select class="form-select" id="targetmarketvalue" name="target_market_value" aria-label="Default select example" style="width: 100%;height: 43px;">
                                      <option selected="">Selected Annual Income</option>
                                      <option value="up-to-rs-100000">Up to Rs 100000</option>
                                      <option value="rs-100001-rs-300000">Rs 100001-Rs 300000</option>
                                      <option value="rs-300001-500000">Rs 300001-500000</option>
                                      <option value="rs-500001-750000">Rs 500001-750000</option>
                                      <option value="rs-750001-1000000">Rs 750001-1000000</option>
                                      <option value="rs-1000000">Rs 1000000</option>
                                      <option selected="" value="above">Above</option>

                                    </select>`)
             }else if(target == 'profession'){
                  $('#tm_sub_category_div').show();
                  $('#tm_sub_category_div_n').html(`<select class="form-select" id="targetmarketvalue" name="target_market_value" aria-label="Default select example" style="width: 100%;height: 43px;">
                                      <option selected="">Selected</option>
                                      <option value="service-govt" selected="">Service-Govt</option>
                                      <option value="job-private">Job-Private</option>
                                      <option value="self-employed">Self-employed</option>
                                      <option value="professional-doctor-engineer-charted">Professional-Doctor-Engineer-Charted Accountant-IT Professional</option>
                                      <option value="retired-personal">Retired Personal</option>
                                      <option value="home-maker">Home Maker</option>
                                      <option value="student">Student</option>
                                      <option value="other">Any other</option>
                                    </select>`)
             }else if(target == 'state'){
                 $('#tm_sub_category_div').show();
                 $('#tm_sub_category_div_n').html(`<input type="text" value="" id="targetmarketvalue" name="target_market_value" class="form-control">`);
             }else if(target == 'country'){
                 $('#tm_sub_category_div').show();
                 $('#tm_sub_category_div_n').html(`<input type="text" value="" id="targetmarketvalue" name="target_market_value" class="form-control">`);
             }else if(target == 'city'){
                 $('#tm_sub_category_div').show();
                 $('#tm_sub_category_div_n').html(`<input type="text" value="" id="targetmarketvalue" name="target_market_value" class="form-control">`);
             }else if(target =='age'){
                 $('#tm_sub_category_div').show();
                  $('#tm_sub_category_div_n').html(`<select class="form-select" id="targetmarketvalue" name="target_market_value" aria-label="Default select example" style="width: 100%;height: 43px;">
                                      <option selected="">Selected</option>
                                      <option value="01-17" selected="">Bellow 18</option>
                                       <option value="18-100" selected="">18+</option>
                                       
                                    </select>`)
             }else if(target == 'birthday'){
                  $('#tm_sub_category_div').show();
                 $('#tm_sub_category_div_n').html(`<input type="date" id="targetmarketvalue" value="" name="target_market_value" class="form-control">`);
             }
             else if(target == 'anniversary'){
                  $('#tm_sub_category_div').show();
                 $('#tm_sub_category_div_n').html(`<input type="date" id="targetmarketvalue" value="" name="target_market_value" class="form-control">`);
                 
             }else{
                 $('#tm_sub_category_div').show();
                 $('#tm_sub_category_div_n').html("");
             }
               
         }) ;
              var markedDates = [];
              var markedDate = {};
               markedDate['target_market'] = [];
       $(document).on('click','#add-btn',function(){
     
         
         var name =  $('#type_of_ad_target').val();
         var value =$('#targetmarketvalue').val();
             let r_silver = [];
                                        $('#r_silver input').each(function() {
                                             r_silver.push($(this).attr('value'));
                                        });
                                        let r_gold = [];
                                        $('#r_gold input').each(function() {
                                             r_gold.push($(this).attr('value'));
                                        });
                                        let r_diamond = [];
                                        $('#r_diamond input').each(function() {
                                             r_diamond.push($(this).attr('value'));
                                        });
                                        let r_platinum = [];
                                        $('#r_platinum input').each(function() {
                                             r_platinum.push($(this).attr('value'));
                                        });
  
  if(name == 'name' || name=='email' || name =='mobile' || name == 'whatsapp'){
       var valueToCheck = name;
        var foundObj = markedDates.some(obj=>obj.name === valueToCheck);
        if(foundObj == true){
            alert(`${valueToCheck} is already present`);
        }else{
             $('#tablebody').append(`<tr><td id="valuename"> ${name}</td><td> ${value}</td><td ><a class="btn btn-danger btn-sm mb-2 remove"  href="javascript:void(0)">remove</a></td></tr>`);
             markedDates.push({name:name,value:value});
                var valuename = JSON.stringify(markedDates);
                
                $('#input_json').val(valuename);
                  $('#input_json').val(valuename);
                                        
                                    
                                       
                                         let pass_array = Array.from(Object.values(markedDates), breed => breed.name)
                                         // console.log('passarray',pass_array);
                                        // console.log('r_silver',r_silver);
                                         
                                    if(pass_array.every( ai => r_silver.includes(ai) )) {
                                        
                                        $('#type_of_ad').find('option[value="2"]').prop('selected', true);
                                        $(".r_sliver").show();
                                        $(".r_gold").hide();
                                        $(".r_diamond").hide();
                                        $(".r_platinum").hide();
                                    }else if( pass_array.every( ai => r_diamond.includes(ai) )){
                                    
                                          $('#type_of_ad').find('option[value="1"]').prop('selected', true);
                                          $(".r_sliver").hide();
                                          $(".r_gold").hide();
                                          $(".r_diamond").show();
                                          $(".r_platinum").hide();
                                    }else if(pass_array.every( ai => r_gold.includes(ai) )){
                                     
                                        $('#type_of_ad').find('option[value="3"]').prop('selected', true);
                                        $(".r_sliver").hide();
                                        $(".r_gold").show();
                                        $(".r_diamond").hide();
                                        $(".r_platinum").hide();
                                    }else if(pass_array.every( ai => r_platinum.includes(ai) )){
                                        
                                         $('#type_of_ad').find('option[value="4"]').prop('selected', true);
                                         $(".r_sliver").hide();
                                         $(".r_gold").hide();
                                         $(".r_diamond").hide();
                                         $(".r_platinum").show();
                                    }else {
                                        console.log("is NOT in array");
                                    }
        }
  }else{
           if(value == ""){
               alert(`${name} cant be empty`);
           }else{
                              var valueToCheck = name;
                                var foundObj = markedDates.some(obj=>obj.name === valueToCheck);
                                if(foundObj == true){
                                    alert(`${valueToCheck} is already present`);
                                }else{
                                     $('#tablebody').append(`<tr><td id="valuename"> ${name}</td><td> ${value}</td><td ><a class="btn btn-danger btn-sm mb-2 remove"  href="javascript:void(0)">remove</a></td></tr>`);
                                     markedDates.push({name:name,value:value});
                                        var valuename = JSON.stringify(markedDates);
                         
                                       $('#input_json').val(valuename);
                                        
                                       
                                       
                                         let pass_array = Array.from(Object.values(markedDates), breed => breed.name)
                                          console.log('passarray',pass_array);
                                        console.log('r_gold',r_gold);
                                        console.log('console', pass_array.every( ai => r_gold.includes(ai) ))
                                   if(pass_array.every( ai => r_silver.includes(ai) )) {
                                      
                                        $('#type_of_ad').find('option[value="2"]').prop('selected', true);
                                        $(".r_sliver").show();
                                        $(".r_gold").hide();
                                        $(".r_diamond").hide();
                                        $(".r_platinum").hide();
                                    }else if( pass_array.every( ai => r_diamond.includes(ai) )){
                                     
                                          $('#type_of_ad').find('option[value="1"]').prop('selected', true);
                                          $(".r_sliver").hide();
                                          $(".r_gold").hide();
                                          $(".r_diamond").show();
                                          $(".r_platinum").hide();
                                    }else if(pass_array.every( ai => r_gold.includes(ai) )){
                                        
                                        $('#type_of_ad').find('option[value="3"]').prop('selected', true);
                                        $(".r_sliver").hide();
                                        $(".r_gold").show();
                                        $(".r_diamond").hide();
                                        $(".r_platinum").hide();
                                    }else if(pass_array.every( ai => r_platinum.includes(ai) )){
                                        
                                         $('#type_of_ad').find('option[value="4"]').prop('selected', true);
                                         $(".r_sliver").hide();
                                         $(".r_gold").hide();
                                         $(".r_diamond").hide();
                                         $(".r_platinum").show();
                                    }else {
                                        console.log("is NOT in array");
                                    }
                                }           
           }
  }

 
           
     
       });

    
   
           $(document).on('click','.remove',function(){
               //console.log(markedDates);
               var tr = $(this).closest('tr');
               var newData = tr.find('td:eq(0)').text();
              
               
               
                var index = -1;
                for(var i = 0; i <  markedDates.length; i++) {
                    //console.log('markedDates[i].name', markedDates[i].name);
                    if( markedDates[i].name.trim() == newData.trim()) {
                        index = i;
                     
                         markedDates.splice(index,1);
                        break;
                    }
                }
               
               console.log(markedDates);
               var valuename = JSON.stringify(markedDates);
 
           $('#input_json').val(valuename);
           var input_value = $('#input_json').val();
           if(markedDates.length == 0){
         
               $('#input_json').val("");
           }
               tr.remove();
               let r_silver = [];
                                        $('#r_silver input').each(function() {
                                             r_silver.push($(this).attr('value'));
                                        });
                                        let r_gold = [];
                                        $('#r_gold input').each(function() {
                                             r_gold.push($(this).attr('value'));
                                        });
                                        let r_diamond = [];
                                        $('#r_diamond input').each(function() {
                                             r_diamond.push($(this).attr('value'));
                                        });
                                        let r_platinum = [];
                                        $('#r_platinum input').each(function() {
                                             r_platinum.push($(this).attr('value'));
                                        });
                 let pass_array = Array.from(Object.values(markedDates), breed => breed.name)
                                          console.log('passarray',pass_array);
                                        console.log('r_gold',r_gold);
                                        console.log('console', pass_array.every( ai => r_gold.includes(ai) ))
                                   if(pass_array.every( ai => r_silver.includes(ai) )) {
                                     
                                        $('#type_of_ad').find('option[value="2"]').prop('selected', true);
                                        $(".r_sliver").show();
                                        $(".r_gold").hide();
                                        $(".r_diamond").hide();
                                        $(".r_platinum").hide();
                                    }else if( pass_array.every( ai => r_diamond.includes(ai) )){
                                       
                                          $('#type_of_ad').find('option[value="1"]').prop('selected', true);
                                          $(".r_sliver").hide();
                                          $(".r_gold").hide();
                                          $(".r_diamond").show();
                                          $(".r_platinum").hide();
                                    }else if(pass_array.every( ai => r_gold.includes(ai) )){
                                        
                                        $('#type_of_ad').find('option[value="3"]').prop('selected', true);
                                        $(".r_sliver").hide();
                                        $(".r_gold").show();
                                        $(".r_diamond").hide();
                                        $(".r_platinum").hide();
                                    }else if(pass_array.every( ai => r_platinum.includes(ai) )){
                                 
                                         $('#type_of_ad').find('option[value="4"]').prop('selected', true);
                                         $(".r_sliver").hide();
                                         $(".r_gold").hide();
                                         $(".r_diamond").hide();
                                         $(".r_platinum").show();
                                    }else {
                                        console.log("is NOT in array");
                                    }
           });
    });
        </script>
    @endpush
@endpush
