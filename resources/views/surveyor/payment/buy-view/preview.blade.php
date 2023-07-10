@extends('surveyor.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card text-center">
                                <div class="card-body payment-item d-flex flex-wrap align-items-center">
                                    <div class="payment-thumb">
                                        <img src="{{ $data->gateway_currency()->methodImage() }}" alt="@lang('Image')" class="w-100" />
                                    </div>
                                    <ul class="payment-content list-group text-center">
                                        <p class="list-group-item border--primary">
                                            @lang('Amount'):
                                            <strong>{{getAmount($data->amount)}} </strong> {{__($general->cur_text)}}
                                        </p>
                                        <p class="list-group-item border--primary">
                                            @lang('Charge'):
                                            <strong>{{getAmount($data->charge)}}</strong> {{__($general->cur_text)}}
                                        </p>
                                        <p class="list-group-item border--primary">
                                            @lang('Payable'): <strong> {{getAmount($data->amount + $data->charge)}}</strong> {{__($general->cur_text)}}
                                        </p>
                                        <p class="list-group-item border--primary">
                                            @lang('Conversion Rate'): <strong>1 {{__($general->cur_text)}} = {{getAmount($data->rate)}}  {{__($data->baseCurrency())}}</strong>
                                        </p>
                                        <p class="list-group-item border--primary">
                                            @lang('In') {{$data->baseCurrency()}}:
                                            <strong>{{getAmount($data->final_amo)}}</strong>
                                        </p>


                                        @if($data->gateway->crypto==1)
                                            <p class="list-group-item border--primary">
                                                @lang('Conversion with')
                                                <b> {{ __($data->method_currency) }}</b> @lang('and final value will Show on next step')
                                            </p>
                                        @endif
                                        @if( 1000 >$data->method_code)
                                            <a href="{{route('surveyor.buy_views.deposit.confirm')}}" class="btn btn--primary  btn-block font-weight-bold mt-3">@lang('Pay Now')</a>
                                        @else
                                            <a href="{{route('surveyor.buy_views.deposit.manual.confirm')}}" class="btn btn--primary btn-block font-weight-bold mt-3">@lang('Pay Now')</a>
                                        @endif
                                    </ul>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('style')

    <style>
        .payment-thumb{
            width: 300px;
            border-radius: 5px;
            overflow: hidden;
        }
        .payment-content{
            width: calc(100% - 300px);
            padding-left: 30px;
        }
        @media(max-width: 767px){
            .payment-item{
                display: block !important;
            }
            .payment-thumb{
                width: 100%;
                border-radius: 5px 5px 0 0;
            }
            .payment-content{
                width: 100%;
                padding-left: 0;
                border-radius: 0 0 5px 5px;
            }
            .card-body{
                padding: 10px;
            }
        }
    </style>

@endpush


