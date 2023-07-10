@extends($activeTemplate.'layouts.master')

@section('content')

    <div class="user-profile-area mt-30">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-5 col-md-12 col-sm-12 mb-30">
                <div class="panel panel-default">
                    <div class="panel-heading d-flex flex-wrap align-items-center justify-content-between">
                        <div class="panel-title"><i class="las la-user"></i> @lang('Withdraw Details')</div>
                        <div class="panel-options">
                            <a href="#" data-rel="collapse"><i class="las la-chevron-circle-down"></i></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="panel-body-inner">
                            <div class="withdraw-log-area">
                                <ul class="withdraw-log-list">
                                    <li>@lang('Current Balance') : <span>{{ getAmount(auth()->user()->balance)}}  {{ __($general->cur_text) }}</span></li>
                                    <li>@lang('Requested Amount') : <span>{{getAmount($withdraw->amount)  }} {{__($general->cur_text)}}</span></li>
                                    <li>@lang('Withdrawal Charge') : <span class="text--danger">{{getAmount($withdraw->charge) }} {{__($general->cur_text)}}</span></li>
                                    <li>@lang('After Bank Charge (MDR)') : <span>{{getAmount($withdraw->after_charge) }} {{__($general->cur_text)}}</span></li>
                                    <li>@lang('Conversion Rate') :</li>
                                    <li>1 {{__($general->cur_text)}} = <span>{{getAmount($withdraw->rate)  }} {{__($withdraw->currency)}}</span></li>
                                    <li>@lang('You Will Get') : <span class="text--primary">{{getAmount($withdraw->final_amount) }} {{__($withdraw->currency)}}</span></li>
                                </ul>
                                <form class="withdraw-form mt-20">
                                    <div class="form-group">
                                        <label>@lang('Balance Will Be') :</label>
                                        <div class="input-group-append">
                                            <input type="text" name="amount" class="form-control" value="{{getAmount($withdraw->user->balance - ($withdraw->amount))}}" required readonly/>
                                            <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-7 col-md-12 col-sm-12 mb-30">
                <div class="panel panel-default">
                    <div class="panel-heading d-flex flex-wrap align-items-center justify-content-between">
                        <div class="panel-title"><i class="las la-user"></i> @lang('Withdraw Form')</div>
                        <div class="panel-options-form">
                            <a href="#" data-rel="collapse"><i class="las la-chevron-circle-down"></i></a>
                        </div>
                    </div>
                    <div class="panel-form-area">
                        <form class="panel-form" action="{{route('user.withdraw.submit')}}" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-12 form-group text-center">
                                    @php
                                        echo $withdraw->method->description;
                                    @endphp
                                </div>
                                @if($withdraw->method->user_data)
                                    @foreach($withdraw->method->user_data as $k => $v)
                                        @if($v->type == "text")
                                            <div class="col-lg-12 form-group">
                                                <label>{{__($v->field_level)}} @if($v->validation == 'required')*@endif</label>
                                                <input type="text" name="{{$k}}" class="form-control" @if($v->validation == "required") required @endif>
                                                @if ($errors->has($k))
                                                    <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        @elseif($v->type == "textarea")

                                            <div class="col-lg-12 form-group">
                                                <label>{{__($v->field_level)}} @if($v->validation == 'required')*@endif</label>
                                                <textarea type="text" name="{{$k}}" class="form-control" placeholder="{{__($v->field_level)}}" rows="3" @if($v->validation == "required") required @endif>{{old($k)}}</textarea>
                                                @if ($errors->has($k))
                                                    <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        @elseif($v->type == "file")
                                            <div class="col-lg-12 form-group">
                                                <label>{{__($v->field_level)}} @if($v->validation == 'required')*@endif</label>
                                                <div class="screen-thumb-area">
                                                    <div class="screen-thumb-preview">
                                                        <div class="screen-image-preview bg_img" data-background="{{ getImage('/','800x800')}}">
                                                        </div>
                                                    </div>
                                                    <div class="screenshot-edit mt-20">
                                                        <input type="file" name="{{$k}}" id="screenshot" class="screen-upload" accept="image/*" @if($v->validation == "required") required @endif>

                                                        @if ($errors->has($k))
                                                            <span class="text-danger">{{ __($errors->first($k)) }}</span>
                                                        @endif
                                                        <label for="screenshot" class="screenshot">
                                                            @LANG('Select') {{__($v->field_level)}}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="submit-btn">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        $('.screenshot').on('click',function () {
                upload();
            });
            function upload() {
                $(".screen-upload").on('change',function () {
                    readURL(this);
                });
            }
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader(); reader.onload = function (e) {
                        var preview = $(input).parents('.screen-thumb-area').find('.screen-image-preview');
                        $(preview).css('background-image', 'url(' + e.target.result + ')');
                        $(preview).hide();
                        $(preview).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
    </script>
@endpush

