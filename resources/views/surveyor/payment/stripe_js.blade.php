@extends('surveyor.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-5 px-2">

                    <div class="row justify-content-center">
                        <div class="col-lg-7">
                            <div class="deposit-confirm-card border border--primary rounded-sm">
                                <div class="thumb">
                                    <img src="{{$deposit->gateway_currency()->methodImage()}}" alt="@lang('Image')"/>
                                </div>
                                <div class="content">
                                    <form action="{{$data->url}}" method="{{$data->method}}">
                                        <h3>@lang('Please Pay') <b class="text--primary">{{getAmount($deposit->final_amo)}} {{$deposit->method_currency}}</b></h3>
                                        <h3>@lang('To Get') <b class="text--primary">{{getAmount($deposit->amount)}}  {{__($general->cur_text)}}</b></h3>

                                        <script
                                            src="{{$data->src}}"
                                            class="stripe-button"
                                            @foreach($data->val as $key=> $value)
                                            data-{{$key}}="{{$value}}"
                                            @endforeach
                                        >
                                        </script>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@push('script')
    <script>
        "use strict";
        $(document).ready(function () {
            $('button[type="submit"]').addClass("custom-success btn btn-lg btn--primary mt-4");
        })
    </script>
@endpush
