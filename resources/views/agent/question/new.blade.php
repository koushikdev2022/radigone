@extends('surveyor.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <form action="{{route('surveyor.survey.question.store')}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Product/Service Name')</label>
                                            <select name="p_name" class="form-control" required>
                                                <option value="1">@lang('1')</option>
                                                <option value="2">@lang('2')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Product/Service Specifications')</label>
                                            <select name="p_specifications" class="form-control" required>
                                                <option value="0">@lang('1')</option>
                                                <option value="1">@lang('2')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Product MRP/Service Charge')</label>
                                            <input type="text" class="form-control" name="p_mrp" placeholder="@lang('Enter Product MRP/Service Charge')" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Discount Offer')</label>
                                            <input type="text" class="form-control" name="d_offer" placeholder="@lang('Enter Discount Offer')" required>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{$survey->id}}" name="survey_id" required>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Per View data required')</label>
                                
                                    <div class="form-row">    
                                        <div class="col-md-6 for-custom-input">
                                            <div class="form-group">
                                                <input type="checkbox" name="r_data[]" value="Name">@lang(' Name')<br>
                                                <input type="checkbox" name="r_data[]" value="Contact">@lang(' Contact')<br>
                                                <input type="checkbox" name="r_data[]" value="Email">@lang(' Email')<br>
                                                <input type="checkbox" name="r_data[]" value="Age">@lang(' Age')<br>
                                            </div>
                                        </div>
                                        <div class="col-md-6 for-custom-input">
                                            <div class="form-group">
                                                <input type="checkbox" name="r_data[]" value="Permanent Location">@lang(' Permanent Location')<br>
                                                <input type="checkbox" name="r_data[]" value="Current Location">@lang(' Current Location')<br>
                                                <input type="checkbox" name="r_data[]" value="Gender">@lang(' Gender')<br>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Type of Offer')</label>
                                            <select name="t_offer" class="form-control" required>
                                                <option value="0">@lang('1')</option>
                                                <option value="1">@lang('2')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Number of Views(100 to 1000000)')</label>
                                            <input type="text" class="form-control" name="n_views" placeholder="@lang('Enter Number of Views(100 to 1000000)')" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-12 audience for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Publish in General or To Target Market')</label>
                                            <select name="audience" class="form-control" required>
                                                <option value="General">@lang('General')</option>
                                                <option value="Target Market">@lang('Target Market')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="audience_cat">

                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Number of Slides/Pictures')</label>
                                            <select name="slides" class="form-control" required>
                                                <option value="1">@lang('1')</option>
                                                <option value="3">@lang('3')</option>
                                                <option value="5">@lang('5')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Per slide/Picture time in seconds(Minimum total advertisement time is 10 seconds)')</label>
                                            <select name="slides_time" class="form-control" required>
                                                <option value="5">@lang('5')</option>
                                                <option value="7">@lang('7')</option>
                                                <option value="10">@lang('10')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Run with repeated viewers')</label>
                                            <select name="repeated" class="form-control" required>
                                                <option value="0">@lang('no')</option>
                                                <option value="1">@lang('yes')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Select Duration of Advertisement(In Seconds)')</label>
                                            <select name="ad_duration" class="form-control" required>
                                                <option value="10">@lang('10')</option>
                                                <option value="15">@lang('15')</option>
                                                <option value="25">@lang('25')</option>
                                                <option value="30">@lang('30')</option>
                                                <option value="50">@lang('50')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Online Purchase Required')</label>
                                            <select name="o_purchase" class="form-control" required>
                                                <option value="0">@lang('no')</option>
                                                <option value="1">@lang('yes')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Choose Template or Upload Template')</label>
                                            <select name="template" class="form-control" required>
                                                <option value="10">@lang('10')</option>
                                                <option value="15">@lang('15')</option>
                                                <option value="25">@lang('25')</option>
                                                <option value="30">@lang('30')</option>
                                                <option value="50">@lang('50')</option>
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
    <a href="{{route('surveyor.survey.question.all',$survey->id)}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
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

            if ($('select[name="audience"]').val() == 1) {
                $('#audience_cat').html(custom_input_type_div);
                $('.audience').removeClass('col-md-12').addClass('col-md-6');
            }
            if ($('select[name="audience"]').val() == 0) {
                $('#audience_cat').html('');
                $('.audience').removeClass('col-md-6').addClass('col-md-12');
            }
        });
    })(jQuery);

</script>
@endpush
