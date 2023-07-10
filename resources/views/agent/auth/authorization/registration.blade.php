@extends('surveyor.layouts.master')

@section('content')
    <form action="{{route('agent.payment.registration')}}" method="POST" class="cmn-form mt-30">
        @csrf
        <input type="hidden" name="razorpay_key" id="razorpay-key" value="{{$api_key}}">
        <input type="hidden" name="razorpay_payment_id" id="razorpay-payment-id">
        <input type="hidden" name="price" id="price" value="{{$reg_fee}}">
        <div class="page-wrapper default-version">
            <div class="form-area bg_img" data-background="{{asset('assets/surveyor/images/1.jpg')}}">
                <div class="form-wrapper">
                    <h4 class="logo-text mb-15"><strong>@lang('Please Pay') <b class="text--primary">{{getAmount($reg_fee)}} INR</b></strong></h4>
                    <p class="logo-text mb-15"><strong>@lang('Registration Fee')</strong></p>
                    <p class="logo-text mb-15"><strong>@lang('Current Time') : {{\Carbon\Carbon::now()}}</strong></p>

                    {{--                    <div class="form-group">--}}
                    {{--                        <div id="phoneInput">--}}

                    {{--                            <div class="field-wrapper">--}}
                    {{--                                <div class=" phone">--}}
                    {{--                                    <input type="text" name="code[]" class="letter"--}}
                    {{--                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">--}}
                    {{--                                    <input type="text" name="code[]" class="letter"--}}
                    {{--                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">--}}
                    {{--                                    <input type="text" name="code[]" class="letter"--}}
                    {{--                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">--}}
                    {{--                                    <input type="text" name="code[]" class="letter"--}}
                    {{--                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">--}}
                    {{--                                    <input type="text" name="code[]" class="letter"--}}
                    {{--                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">--}}
                    {{--                                    <input type="text" name="code[]" class="letter"--}}
                    {{--                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    <div class="form-group">
                        {{--                        <script src="https://checkout.razorpay.com/v1/checkout.js"--}}
                        {{--                                data-key="{{$api_key}}"--}}
                        {{--                                data-amount="{{$reg_fee * 100}}"--}}
                        {{--                                data-buttontext="Pay {{$reg_fee}} INR"--}}
                        {{--                                data-name="GeekyAnts official"--}}
                        {{--                                data-description="Razorpay payment"--}}
                        {{--                                data-image="/images/logo-icon.png"--}}
                        {{--                                data-prefill.name="ABC"--}}
                        {{--                                data-prefill.email="abc@gmail.com"--}}
                        {{--                                data-theme.color="#ff7529">--}}
                        {{--                        </script>--}}
                        {{--                        <input type="hidden" name="razorpay_payment_id" value="{{$razorpay_payment_id}}">--}}
                        {{--                        <script src="{{$data->checkout_js}}"--}}
                        {{--                                @foreach($data->val as $key=>$value)--}}
                        {{--                                data-{{$key}}="{{$value}}"--}}
                        {{--                            @endforeach >--}}
                        {{--                        </script>--}}
                        {{--                        <input type="hidden" custom="{{$data->custom}}" name="hidden">--}}
                        <button type="button" class="submit-btn mt-25 b-radius--capsule buy_now">@lang('Verify Code') <i
                                class="las la-sign-in-alt"></i></button>
                    </div>


                </div>
            </div><!-- login-area end -->
        </div>

        <div class="modal fade common-modal" id="show-razor-thanks" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('Razorpay Confirmation')}}</h5>
                    </div>
                    <div class="modal-body">
                        {{__('Your payment is authorized. For capturing your order click')}} <b>{{__('Save')}}</b>
                        <div class="modal-btn-wrap text-end">
                            <button type="submit" class="btn btn-success">{{__('Save')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade common-modal" id="show-required-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('Oops! Something went wrong!')}}</h5>
                    </div>
                    <div class="modal-body">
                        <div id="errText"></div>
                        <div class="modal-btn-wrap text-end">
                            <button type="button" class="btn btn-success" data-dismiss="modal" aria-label="Close">{{__('Close')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('script-lib')
    <script src="{{asset('assets/surveyor/js/jquery.inputLettering.js')}}"></script>
@endpush
@push('style')
    <style>
        #phoneInput .field-wrapper {
            position: relative;
            text-align: center;
        }
        #phoneInput .form-group {
            min-width: 300px;
            width: 50%;
            margin: 4em auto;
            display: flex;
            border: 1px solid rgba(96, 100, 104, 0.3);
        }
        #phoneInput .letter {
            height: 50px;
            border-radius: 0;
            text-align: center;
            max-width: calc((100% / 10) - 1px);
            flex-grow: 1;
            flex-shrink: 1;
            flex-basis: calc(100% / 10);
            outline-style: none;
            padding: 5px 0;
            font-size: 18px;
            font-weight: bold;
            color: red;
            border: 1px solid #0e0d35;
        }
        #phoneInput .letter + .letter {
        }
        @media (max-width: 480px) {
            #phoneInput .field-wrapper {
                width: 100%;
            }
            #phoneInput .letter {
                font-size: 16px;
                padding: 2px 0;
                height: 35px;
            }
        }

    </style>
@endpush

@push('script')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $('body').on('click', '.buy_now', function(e){
            let amount = $('#price').val();

            if(amount == 0 || amount == null || amount == '') {
                console.log('Invalid amount please check your booking!');
                $('#errText').text('Invalid amount please check your booking!');
                $('#show-required-modal').modal('show');
                return
            }
            let razorpay_key = $('#razorpay-key').val();
            var options = {
                "key": razorpay_key,
                "amount": (amount*100), // 2000 paise = INR 20
                "name": "Radigone",
                "description": "Payment",
                "handler": function (response){
                    $('#razorpay-payment-id').val(response.razorpay_payment_id);
                    $('#show-razor-thanks').modal('show');

                },
                "theme": {
                    "color": "#303874"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        });

    </script>
    <script>
        'use strict';

        $(document).ready(function () {
            $('input[type="submit"]').addClass("btn-custom2 btn btn-lg btn--primary mt-4");
        })

        // (function ($) {
        //     $('#phoneInput').letteringInput({
        //         inputClass: 'letter',
        //         onLetterKeyup: function ($item, event) {
        //         },
        //         onSet: function ($el, event, value) {
        //         }
        //     });
        // })(jQuery);
    </script>
@endpush
