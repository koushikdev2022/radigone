@extends('surveyor.layouts.app')

@section('panel')

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center py-3">
                    <div class="col-md-8">
                        <div class="card text-center border-primary">
                            <div class="card-header card-header-bg bg--primary">
                                <h3 class="text-white">@lang('Payment Preview')</h3>
                            </div>
                            <div class="card-body text-center">
                                <h4 class="my-2"> @lang('Please Send Exactly') <span class="text--primary"> {{ $data->amount }}</span> {{__($data->currency)}}</h4>
                                <h5 class="mb-2">@lang('To') <span class="text--primary"> {{ $data->sendto }}</span></h5>
                                <img src="{{$data->img}}" alt="@lang('Image')">
                                <h4 class="text--primary bold my-4">@lang('Scan To Send')</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
