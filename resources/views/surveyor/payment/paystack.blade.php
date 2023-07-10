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
                                    <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST" class="text-center">
                                        <h3>@lang('Please Pay') <b class="text--primary">{{getAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</b></h3>
                                        <h3>@lang('To Get') <b class="text--primary">{{getAmount($deposit->amount)}}  {{__($general->cur_text)}}</b></h3>
                                        <button type="button" class="btn btn-lg btn--primary mt-4" id="btn-confirm">@lang('Pay Now')</button>
                                        <script
                                            src="//js.paystack.co/v1/inline.js"
                                            data-key="{{ $data->key }}"
                                            data-email="{{ $data->email }}"
                                            data-amount="{{$data->amount}}"
                                            data-currency="{{$data->currency}}"
                                            data-ref="{{ $data->ref }}"
                                            data-custom-button="btn-confirm"
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

