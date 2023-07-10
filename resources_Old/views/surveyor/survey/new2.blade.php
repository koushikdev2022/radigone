@extends('surveyor.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <form action="{{route('surveyor.survey.business_cardstore')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                               
                             <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Title')</label>
                                            <input type="text" class="form-control" name="p_mrp" placeholder="@lang('Enter Product MRP/Service Charge')" required>
                                        </div>
                                    </div>
                                                                        
                                </div>

                                <div class="form-group">
                                    
                                
                                    <div class="form-row"> 
                                        
                                         <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Business Card Image')</label>
                                                <div class="image-upload">
                                                    <div class="thumb">
                                                        <div class="avatar-preview">
                                                            <div class="profilePicPreview" style="background-image: url({{ getImage('/',imagePath()['survey']['size']) }})">
                                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
            
                                                        <div class="avatar-edit">
                                                            <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg" required>
                                                            <label for="profilePicUpload1" class="bg--success"> @lang('Image')</label>
                                                            <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg, jpg, png')</b>.
                                                            @lang('Image Will be resized to'): <b>{{imagePath()['survey']['size']}}</b> px.
            
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                </div>

                                

                                
                                
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Select number of slides')</label>
                                            <select name="ad_duration" class="form-control" required>
                                                <option value="1">@lang('1')</option>
                                                <option value="2">@lang('2')</option>
                                                <option value="3">@lang('3')</option>
                                                <option value="4">@lang('4')</option>
                                                <option value="5">@lang('5')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                               
                            </div>
                           
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <a href="{{route('surveyor.survey.business_cardall')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush

@push('script')
<script>
    'use strict';

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

</script>
@endpush
