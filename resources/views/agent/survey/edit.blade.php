@extends('surveyor.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <form action="{{route('surveyor.survey.update',$survey->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Survey Image')</label>
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage(imagePath()['survey']['path'].'/'. $survey->image,imagePath()['survey']['size'])}})">
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

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>@lang('Select Category')</label>
                                    <select name="category_id" class="form-control" required>
                                        @foreach ($categories as $item)
                                            <option value="{{$item->id}}">{{__($item->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Survey Name')</label>
                                    <input type="text"class="form-control" placeholder="@lang('Enter Name')" name="name" value="{{$survey->name}}" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Age Limit')</label>
                                            <select name="age_limit" class="form-control" required>
                                                <option value="0">@lang('No')</option>
                                                <option value="1">@lang('Yes')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Country Limit')</label>
                                            <select name="country_limit" class="form-control" required>
                                                <option value="0">@lang('No')</option>
                                                <option value="1">@lang('Yes')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 age-limit-div">

                                    </div>

                                    <div class="col-md-12 country-limit-div">

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
    <a href="{{route('surveyor.survey.all')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush


@push('script')
    <script>
        'use strict';

        (function ($) {
            var survey = @json($survey);
            console.log(survey);
            $('select[name="category_id"]').val('{{$survey->category->id}}');
            $('select[name="age_limit"]').val('{{$survey->age_limit}}');
            $('select[name="country_limit"]').val('{{$survey->country_limit}}');

            var check_age_limit = $('select[name="age_limit"]').val();
            var check_country_limit = $('select[name="country_limit"]').val();

            var age_limit_div = `<div class="row">
                                    <div class="form-group col-md-6">
                                        <label>@lang('Minimum Age')</label>
                                        <input type="number" class="form-control" @if($survey->age_limit && ($survey->start_age && $survey->end_age)) value="{{$survey->start_age}}" @endif placeholder="@lang('Enter minimum age')" name="start_age" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>@lang('Maximum Age')</label>
                                        <input type="number" class="form-control" @if($survey->age_limit && ($survey->start_age && $survey->end_age)) value="{{$survey->end_age}}" @endif placeholder="@lang('Enter maximum age')" name="end_age" required>
                                    </div>
                                </div>`;

            var country_limit_div = `<div class="row">
                                        <div class="form-group col-md-12">
                                            <label>@lang('Select Countries')</label>
                                            <select class="select2-multi-select countriesArray form-control" name="country[]" multiple="multiple" required>
                                                    @include('partials.country')
                                            </select>
                                        </div>
                                    </div>`;

            if (check_age_limit == 1) {
                $('.age-limit-div').html(age_limit_div);
            }

            if (check_country_limit == 1) {

                $('.country-limit-div').html(country_limit_div);
                triggerSelect2();
            }

            $('select[name="age_limit"]').on('change',function () {

                var age_val = $('select[name="age_limit"]').val();

                if (age_val == 1) {

                    $('.age-limit-div').html(age_limit_div);

                }

                if (age_val == 0) {

                    $('.age-limit-div').html('');
                }
            });

            $('select[name="country_limit"]').on('change',function () {

                var country_val = $('select[name="country_limit"]').val();

                if (country_val == 1) {

                    $('.country-limit-div').html(country_limit_div);

                    if(survey.country_limit == 1 && survey.country) {

                        var countries = [];
                        countries = @json($survey->country);

                        if (countries.length > 0) {
                            $(".countriesArray").val(countries);
                        }
                    }
                    triggerSelect2();

                }
                if (country_val == 0) {

                    $('.country-limit-div').html('');
                }

            });

            function triggerSelect2(){

                $(document).find('.select2-multi-select').select2({
                    dropdownParent: $(document).find('.card')
                });
            }

            if(survey.country_limit == 1 && survey.country) {

                var countries = [];
                countries = @json($survey->country);

                if (countries.length > 0) {
                    $(".countriesArray").val(countries);
                }
            }
            triggerSelect2();

        })(jQuery);
    </script>
@endpush
