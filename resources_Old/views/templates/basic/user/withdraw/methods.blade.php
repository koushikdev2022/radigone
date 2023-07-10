@extends($activeTemplate.'layouts.master')

@section('content')

    <div class="deposit-area mt-30">
        <div class="panel-card-header section--bg text-white">
            <div class="panel-card-title"><i class="las la-money-bill"></i> @lang('User Withdraw')</div>
        </div>
        <div class="panel-card-body">
            <div class="row justify-content-center mb-30-none">

                @foreach($withdrawMethod as $data)
                    <div class="col-xl-3 col-md-6 col-sm-8 mb-30">
                        <div class="deposit-item border border--primary">
                            <div class="deposit-item-header bg--primary text-white">
                                <span class="title"><i class="las la-money-bill"></i> {{__($data->name)}}</span>
                            </div>
                            <div class="deposit-item-body">
                                <div class="deposit-thumb">
                                    <img src="{{getImage(imagePath()['withdraw']['method']['path'].'/'. $data->image,imagePath()['withdraw']['method']['size'])}}" alt="{{__($data->name)}}">
                                </div>
                            </div>
                            <div class="deposit-item-footer bg--primary">
                                <div class="deposit-btn">
                                    <a href="javascript:void(0)"
                                    data-id="{{$data->id}}"
                                    data-resource="{{$data}}"
                                    data-min_amount="{{getAmount($data->min_limit)}}"
                                    data-max_amount="{{getAmount($data->max_limit)}}"
                                    data-fix_charge="{{getAmount($data->fixed_charge)}}"
                                    data-percent_charge="{{getAmount($data->percent_charge)}}"
                                    data-base_symbol="{{__($general->cur_text)}}"
                                    data-delay="{{$data->delay}}"
                                    data-toggle="modal" data-target="#depoModal" class="btn btn--primary text-white btn-block btn-icon icon-left deposit"><i class="las la-money-bill"></i> @lang('Withdraw Now')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="modal fade" id="depoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title method-name" id="exampleModalLabel">@lang('Withdraw')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <p class="text--primary depositLimit"></p>
                        <p class="text--primary depositCharge"></p>
                        <p class="text--primary delay"></p>
                    <form method="POST" action="{{route('user.withdraw.money')}}">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="currency"  class="edit-currency form-control" value="">
                            <input type="hidden" name="method_code" class="edit-method-code  form-control" value="">
                        </div>
                        <div class="form-group">
                            <h5>@lang('Enter Deposit Amount')</h5>
                            <div class="input-group-append">
                                <input type="text" id="amount" name="amount" class="form-control"  onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" name="amount" placeholder="0.00" value="{{old('amount')}}" required />
                                <span class="input-group-text">{{__($general->cur_text)}}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--base" style="width:100%;">@lang('Withdraw Preview')</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger btn-rounded text-white" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";
        $(document).ready(function(){
            $('.deposit').on('click', function () {
                var id = $(this).data('id');
                var result = $(this).data('resource');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount');
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');
                var delay = $(this).data('delay');

                var depositLimit = `@lang('Withdraw Limit'): ${minAmount} - ${maxAmount}  {{__($general->cur_text)}}`;
                $('.depositLimit').text(depositLimit);
                var depositCharge = `@lang('Charge'): ${fixCharge} {{__($general->cur_text)}} ${(0 < percentCharge) ? ' + ' + percentCharge + ' %' : ''}`
                $('.depositCharge').text(depositCharge);
                $('.delay').text(`@lang('Processing Time:') ${delay}`);
                $('.method-name').text(`@lang('Withdraw Via') ${result.name}`);
                $('.edit-currency').val(result.currency);
                $('.edit-method-code').val(result.id);
            });
        });
    </script>

@endpush

