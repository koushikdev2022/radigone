@extends('surveyor.layouts.master')

@section('content')
    <div class="page-wrapper default-version">
        <div class="form-area bg_img" data-background="{{asset('assets/surveyor/images/1.jpg')}}">
            <div class="form-wrapper">
                <h4 class="logo-text mb-15">@lang('Please Verify Your Mobile to Get Access')</h4>
                <h4>@lang('Your Mobile Number'): <strong>{{Auth::guard('agent')->user()->mobile}}</strong></h4>
                <div id="recaptcha-container"></div>
                <input type="hidden" id="number" value="{{Auth::guard('agent')->user()->mobile}}">
                {{--            <input type="hidden" id="number" value="8801714899502">--}}
                <form action="#" method="POST" class="cmn-form mt-30">
                    @csrf
                    <div class="form-group">
                        <div id="phoneInput">

                            <div class="field-wrapper">
                                <div class=" phone">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                    <input type="text" name="sms_verified_code[]" class="letter"
                                           pattern="[0-9]*" inputmode="numeric" maxlength="1">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group d-flex justify-content-between align-items-center">

                        <a href="{{route('surveyor.send_verify_code')}}?type=phone" class="text-muted text--small">@lang('Try to send
                        again')</a>
                    </div>

                    <div class="form-group">
                        <button type="button" id="verifPhNum" class="submit-btn mt-25 b-radius--capsule">@lang('Verify Code') <i
                                class="las la-sign-in-alt"></i></button>
                    </div>
                </form>
            </div>
        </div><!-- login-area end -->
    </div>

{{--    <button id="getcode">Submit</button>--}}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.0.1/firebase.js"></script>
    <script>
        $(document).ready(function() {

            const firebaseConfig = {
                apiKey: "AIzaSyAH7uEXwuDyoudrlV2rR80aAD5gHAYZPq4",
                authDomain: "otpapp-87dc5.firebaseapp.com",
                projectId: "otpapp-87dc5",
                storageBucket: "otpapp-87dc5.appspot.com",
                messagingSenderId: "603217308887",
                appId: "1:603217308887:web:07e105a78f697ee718d492",
                measurementId: "G-PVHWCEK4BK"
            };

            // Initialize Firebase
            firebase.initializeApp(firebaseConfig);

            window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
                'size': 'invisible',
                'callback': function (response) {
                    // reCAPTCHA solved, allow signInWithPhoneNumber.
                    console.log('recaptcha resolved');
                }
            });
            onSignInSubmit();
        });



        function onSignInSubmit() {
            $('#verifPhNum').on('click', function() {
                // Get all input fields with the name "sms_verified_code[]"
                const inputFields = document.getElementsByName("sms_verified_code[]");

                // Concatenate the values of all input fields
                let verificationCode = "";
                for (let i = 0; i < inputFields.length; i++) {
                    verificationCode += inputFields[i].value;
                }
                let phoneNo = '';
                var code = verificationCode;
                // var code = $('#codeToVerify').val();
                console.log(code);
                $(this).attr('disabled', 'disabled');
                $(this).text('Processing..');
                confirmationResult.confirm(code).then(function (result) {
                    $.ajax({
                        type: 'POST',
                        url: '/agent/verify-sms',
                        data: JSON.stringify({
                            '_token'  : '{{csrf_token()}}',
                        }),
                        // dataType: 'json',
                        processData: false,
                        contentType: 'application/json',
                        success: function(data) {
                            window.location.reload();
                        },
                        error: function(data) {
                            console.log(data)
                        }
                    });
                    var user = result.user;
                    console.log(user);
                    console.log('Verified')


                    // ...
                }.bind($(this))).catch(function (error) {

                    // User couldn't sign in (bad verification code?)
                    // ...
                    $(this).removeAttr('disabled');
                    $(this).text('Invalid Code');
                    //         $('#errMsg').html(`
                    //     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    //       <strong>Invalid OTP Code
                    //       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    //         <span aria-hidden="true">&times;</span>
                    //       </button>
                    //     </div>
                    // `);
                    setTimeout(() => {
                        $(this).text('Verify Phone');
                    }, 2000);
                }.bind($(this)));

            });


            $('#getcode').on('click', function () {
                var phoneNo = '+' + $('#number').val();
                $(this).text('Sending...')
                console.log(phoneNo);
                // getCode(phoneNo);
                var appVerifier = window.recaptchaVerifier;
                firebase.auth().signInWithPhoneNumber(phoneNo, appVerifier)
                    .then(function (confirmationResult) {

                        window.confirmationResult=confirmationResult;
                        coderesult=confirmationResult;
                        console.log(coderesult);
                        // $('#otpCard').addClass('d-none');
                        // $('#verifyCard').removeClass('d-none');
                        //         $('#errMsg').html(`
                        //     <div class="alert alert-success alert-dismissible fade show" role="alert">
                        //       <strong>A verification code is send to your phone
                        //       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        //         <span aria-hidden="true">&times;</span>
                        //       </button>
                        //     </div>
                        // `);
                    }).catch(function (error) {
                    //         $('#errMsg').html(`
                    //     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    //       <strong>${error.message}
                    //       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    //         <span aria-hidden="true">&times;</span>
                    //       </button>
                    //     </div>
                    // `);
                    console.log(error.message);

                });
            });

            function getCode() {
                var phoneNo = '+' + $('#number').val();
                $(this).text('Sending...')
                console.log(phoneNo);
                // getCode(phoneNo);
                var appVerifier = window.recaptchaVerifier;
                firebase.auth().signInWithPhoneNumber(phoneNo, appVerifier)
                    .then(function (confirmationResult) {

                        window.confirmationResult=confirmationResult;
                        coderesult=confirmationResult;
                        console.log(coderesult);
                        // $('#otpCard').addClass('d-none');
                        // $('#verifyCard').removeClass('d-none');
                        //         $('#errMsg').html(`
                        //     <div class="alert alert-success alert-dismissible fade show" role="alert">
                        //       <strong>A verification code is send to your phone
                        //       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        //         <span aria-hidden="true">&times;</span>
                        //       </button>
                        //     </div>
                        // `);
                    }).catch(function (error) {
                    //         $('#errMsg').html(`
                    //     <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    //       <strong>${error.message}
                    //       <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    //         <span aria-hidden="true">&times;</span>
                    //       </button>
                    //     </div>
                    // `);
                    console.log(error.message);

                });
            }
            getCode();
        }
    </script>
    <script>
        'use strict';

        (function ($) {
            $('#phoneInput').letteringInput({
                inputClass: 'letter',
                onLetterKeyup: function ($item, event) {
                },
                onSet: function ($el, event, value) {
                }
            });
        })(jQuery);
    </script>
@endpush
