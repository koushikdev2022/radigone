@extends($activeTemplate .'layouts.frontend')
@section('content')
@include($activeTemplate.'partials.breadcrumb')

    <section class="account-section ptb-80">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-6">
                    <div class="account-form-area bg-overlay-black section--bg">
                        <div class="account-logo-area text-center">
                            <div class="account-logo">
                                <a href="{{route('home')}}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('logo')"></a>
                            </div>
                        </div>
                        <div class="account-header text-center">
                            <h2 class="title">@lang('Please Verify Your Mobile to Get Access')</h2>
                            <h3 class="sub-title">@lang('Your Mobile Number') : {{auth()->user()->mobile}}</h3>
                            <div id="recaptcha-container"></div>
                            <input type="hidden" id="number" value="{{Auth::user()->mobile}}">
                        </div>
                        <form class="account-form" method="POST" action="#">
                            @csrf
                            <div class="row ml-b-20">
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Enter Verification Code')*</label>
                                    <input type="text" class="form-control form--control" name="sms_verified_code[]" required>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <div class="checkbox-wrapper d-flex flex-wrap align-items-center">
                                        <div class="checkbox-item">
                                            <label><a href="{{route('user.send_verify_code')}}?type=phone">@lang('Resend code')</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 form-group text-center">
                                    <button type="button" class="submit-btn" id="verifPhNum">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                            url: '/user/verify-sms',
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
    @endpush
@endsection
