@extends('surveyor.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card">
                <form action="{{route('surveyor.survey.question.update',$question->id)}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="col-md-4 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Select Question Type')</label>
                                            <select name="type" class="form-control" required>
                                                <option value="1">@lang('Radio')</option>
                                                <option value="2">@lang('Checkbox')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 for-custom-input">
                                        <div class="form-group">
                                            <label>@lang('Custom Input')</label>
                                            <select name="custom_input" class="form-control" required>
                                                <option value="0">@lang('No')</option>
                                                <option value="1">@lang('Yes')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4" id="custom-input-type">

                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Question')</label>
                                    <textarea class="form-control" name="question" placeholder="@lang('Enter Your Question')" required>{{$question->question}}</textarea>
                                    <input type="hidden" value="{{$s_id}}" name="survey_id" required>
                                </div>

                                <div id="custom-input-question">

                                </div>

                                <div class="payment-method-item p-2">
                                    <div class="payment-method-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card border--primary">
                                                    <h5 class="card-header bg--primary  text-white">@lang('All Options')
                                                        <button type="button" class="btn btn-sm btn-outline-light float-right addUserData"><i class="la la-fw la-plus"></i>@lang('Add New')
                                                        </button>
                                                    </h5>
                                                    <div class="card-body addedField">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <div class="input-group">
                                                                        <div class="col-md-12">
                                                                            @if($question->options)
                                                                                @foreach ($question->options as $item)
                                                                                    <input class="form-control mb-2" type="text" placeholder="@lang('Enter option')" value="{{$item}}" readonly>
                                                                                @endforeach
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    <a href="{{route('surveyor.survey.question.all',$s_id)}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush

@push('script')
    <script>
        'use strict';

        (function ($) {
            $('select[name="type"]').val('{{$question->type}}');

            $('select[name="custom_input"]').val('{{$question->custom_input}}');

            var check_custom_input = $('select[name="custom_input"]').val();

            var custom_input_div = `<div class="form-group">
                                        <label>@lang('Custom Input Question')</label>
                                        <input type="text" name="custom_question" value="{{$question->custom_question}}" class="form-control" placeholder="@lang('Enter your custom input question')" required>
                                    </div>`;

            var custom_input_type_div = `<div class="form-group">
                                            <label>@lang('Custom Input Type')</label>
                                            <select name="custom_input_type" class="form-control" required>
                                                <option value="0">@lang('Not Required')</option>
                                                <option value="1">@lang('Required')</option>
                                            </select>
                                        </div>`;

            if (check_custom_input == 1) {

                $('#custom-input-question').html(custom_input_div);
                $('#custom-input-type').html(custom_input_type_div);
                $('select[name="custom_input_type"]').val('{{$question->custom_input_type}}');

            }
            if (check_custom_input == 0) {
                $('.for-custom-input').removeClass('col-md-4').addClass('col-md-6');
                $('#custom-input-question').html('');
                $('#custom-input-type').html('');
            }

            $('select[name="custom_input"]').on('change',function () {

                if ($('select[name="custom_input"]').val() == 1) {

                    $('#custom-input-question').html(custom_input_div);
                    $('#custom-input-type').html(custom_input_type_div);
                    $('select[name="custom_input_type"]').val('{{$question->custom_input_type}}');
                    $('.for-custom-input').removeClass('col-md-6').addClass('col-md-4');
                }
                if ($('select[name="custom_input"]').val() == 0) {

                    $('#custom-input-question').html('');
                    $('#custom-input-type').html('');
                    $('.for-custom-input').removeClass('col-md-4').addClass('col-md-6');
                }
            });

            $('.addUserData').on('click', function () {
                var html = `
                <div class="row user-data">
                    <div class="col-md-11">
                        <div class="form-group">
                            <div class="input-group mb-md-0">
                                <div class="col-md-12">
                                    <input name="options[]" class="form-control" type="text" placeholder="@lang('Enter option')" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group mt-1">
                                <button class="btn btn--danger btn-lg removeBtn w-100 mt-28 text-center" type="button">
                                    <i class="fa fa-times"></i>
                                </button>
                        <div class="form-group">
                    </div>
                </div>`;
                $('.addedField').append(html)
            });
            $(document).on('click', '.removeBtn', function () {
                $(this).closest('.user-data').remove();
            });
        })(jQuery);
    </script>
@endpush
