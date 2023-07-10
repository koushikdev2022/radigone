@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="survey-area mt-30">
        <div class="panel-card-header section--bg text-white">
            <div class="panel-card-title"><i class="lar la-question-circle"></i> @lang('Answer all the Questions')</div>
        </div>

        <div class="panel-body multi_step_form">
            <div class="mb-2 step-tracker"></div>
            <form id="msform" method="POST" action="{{$demo_route ? route('user.dummy_question_answer') : route('user.survey.questions.answers',$survey->id)}}" onsubmit="return submitUserForm();">
                @csrf
                <ul id="progressbar">
                    @foreach ($survey->questions as $k => $item)
                        <li class="@if($k == 0) active done @else @endif"><span></span></li>
                    @endforeach
                </ul>
                @foreach($survey->questions as $key => $item)
                    <fieldset>
                        <h3 class="title">{{__($item->question)}}</h3>
                        <div class="form-row">
                            <div class="form-group col-lg-12">
                                <div class="radio-wrapper">
                                    @if($item->options)
                                        @foreach ($item->options as $k => $data)
                                            <div class="@if($item->type == 1) radio-item @elseif($item->type == 2) checkbox-item @endif">
                                                @if($item->type == 1)
                                                    <input type="radio" id="answer-{{$key}}-{{$k}}" name="answer[{{$item->id}}][]" value="{{$data}}">
                                                    <label for="answer-{{$key}}-{{$k}}">{{$data}}</label>
                                                @elseif($item->type == 2)
                                                    <input type="checkbox" id="answer-{{$key}}-{{$k}}" name="answer[{{$item->id}}][]" value="{{$data}}">
                                                    <label for="answer-{{$key}}-{{$k}}">{{$data}}</label>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($item->custom_input == 1 && $item->custom_question)
                            <label for="">{{__($item->custom_question)}} @if($item->custom_input_type == 1) <span class="text--danger">*(@lang('answer required'))</span> @endif</label>
                            <input type="text"  class="form-control mb-4" placeholder="@lang('Enter your answer')" name="answer[{{$item->id}}][c]" @if($item->custom_input_type == 1) required @endif>
                        @endif

                        <a href="#0" class="@if($key > 0) previous @endif action-button previous_button text-center">@lang('Back')</a>

                        @if($loop->last)
                            <button type="submit" class="action-button" id="disable-btn">@lang('Finish')</button>
                            <p class="mt-2">@lang('This is the last question. After clicking the finish button your answers will be submitted and you will have no chance to change your answers. If you do not able to submit then check your all previous questions, you may miss any question to answer')</p>
                        @else
                            <button type="button" class="next action-button">@lang('Next')</button>
                        @endif

                    </fieldset>
                @endforeach
                @if(!is_null($survey->purchas_url))
                    <div class="survey-list-footer mt-30">
                        Purchase Link: <a href="{{$survey->purchas_url}}" target="_blank">{{$survey->purchas_url}}</a>
                    </div>
                @endif
        </div>
    </div>

    <div id="t" data-id="{{$t}}"></div>
    <div id="srv-id-r" data-id="{{$survey->id}}"></div>
@endsection

@push('script')
<script src="{{asset($activeTemplateTrue.'js/jquery.easing.min.js')}}"></script>

<script>
    "use strict";

    (function ($) {

        isRedirect();
        function isRedirect() {
            let time = localStorage.getItem('time');
            let t = $('#t').data('id');
            if(time != t) {
                let srv_id_r = $('#srv-id-r').data('id');
                window.location.replace(`/user/survey/${srv_id_r}/ad-view`);
            }
        }
        calculateStepTracker()
        function calculateStepTracker(){
            let totalLength = $('#progressbar li').length;
            let doneStep    = $('#progressbar li.done').length;

            $('.step-tracker').text(`${doneStep}/${totalLength}`)
        }

            //* Form js
        function verificationForm() {
            //jQuery time
            var current_fs, next_fs, previous_fs; //fieldsets
            var left, opacity, scale; //fieldset properties which we will animate
            var animating; //flag to prevent quick multi-click glitches

            $(".next").on('click',function () {
                if (animating) return false;
                animating = true;

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();

                //activate next step on progressbar using the index of next_fs
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active done");



                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function (now, mx) {
                        //as the opacity of current_fs reduces to 0 - stored in "now"
                        //1. scale current_fs down to 80%
                        scale = 1 - (1 - now) * 0.2;
                        //2. bring next_fs from the right(50%)
                        left = (now * 50) + "%";
                        //3. increase opacity of next_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({
                            'transform': 'scale(' + scale + ')',
                            'position': 'absolute'
                        });
                        next_fs.css({
                            'left': left,
                            'opacity': opacity
                        });
                    },
                    duration: 0,
                    complete: function () {
                        current_fs.hide();
                        animating = false;
                    },
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
                calculateStepTracker();
            });

            $(".previous").on('click',function () {
                if (animating) return false;
                animating = true;

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                //de-activate current step on progressbar
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active done");

                //show the previous fieldset
                previous_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function (now, mx) {
                        //as the opacity of current_fs reduces to 0 - stored in "now"
                        //1. scale previous_fs from 80% to 100%
                        scale = 0.8 + (1 - now) * 0.2;
                        //2. take current_fs to the right(50%) - from 0%
                        left = ((1 - now) * 50) + "%";
                        //3. increase opacity of previous_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({
                            'left': left
                        });
                        previous_fs.css({
                            'transform': 'scale(' + scale + ')',
                            'opacity': opacity,
                            'position': 'relative'
                        });
                    },
                    duration: 0,
                    complete: function () {
                        current_fs.hide();
                        animating = false;
                    },
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
                calculateStepTracker();
            });
        };
        /*Function Calls*/
        verificationForm();


    })(jQuery);

    function submitUserForm() {
            $("#disable-btn").addClass('d-none');
            return true;
        }
</script>
@endpush



