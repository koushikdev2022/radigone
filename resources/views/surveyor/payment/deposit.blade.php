@extends('surveyor.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <div class="row">
                        @foreach($gatewayCurrency as $data)
                            <div class="col col-xl-3 col-lg-3 col-md-3 col-sm-6">
                                <div class="card mb-30 border--primary">
                                    <img src="{{$data->methodImage()}}" class="card-img-top" alt="{{__($data->name)}}">
                                    <div class="card-body">
                                        <h5>{{__($data->name)}}</h5>
                                        <a href="javascript:void(0)" data-id="{{$data->id}}" data-resource="{{$data}}"
                                            data-min_amount="{{getAmount($data->min_amount)}}"
                                            data-max_amount="{{getAmount($data->max_amount)}}"
                                            data-base_symbol="{{$data->baseSymbol()}}"
                                            data-fix_charge="{{getAmount($data->fixed_charge)}}"
                                            data-percent_charge="{{getAmount($data->percent_charge)}}"
                                            class="btn btn--primary btn-block deposit mt-2"
                                            data-toggle="modal" data-target="#exampleModal">@lang('Deposit Now')</a>
                                    </div>
                                </div><!-- card end -->
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <strong class="modal-title method-name" id="exampleModalLabel"></strong>
                    <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <form action="{{route('surveyor.deposit.insert')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <h5 class="text--primary text-center depositLimit"></h5>
                        <h5 class="text--primary text-center depositCharge"></h5>
                        <div class="form-group">
                            <input type="hidden" name="currency" class="edit-currency" value="">
                            <input type="hidden" name="method_code" class="edit-method-code" value="">
                        </div>
                        <div class="form-group">
                            <label>@lang('Enter Amount'):</label>
                            <div class="input-group">
                                <input id="amount" type="text" class="form-control form-control-lg" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')" name="amount" placeholder="0.00" required=""  value="{{old('amount')}}">
                                <div class="input-group-prepend">
                                    <span class="input-group-text currency-addon bg--primary">{{__($general->cur_text)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop



@push('script')
    <script>
        "use strict";
        $(document).ready(function(){
            $('.deposit').on('click', function () {
                var id = $(this).data('id');
                var result = $(this).data('resource');
                var minAmount = $(this).data('min_amount');
                var maxAmount = $(this).data('max_amount');
                var baseSymbol = "{{__($general->cur_text)}}";
                var fixCharge = $(this).data('fix_charge');
                var percentCharge = $(this).data('percent_charge');

                var depositLimit = `@lang('Deposit Limit'): ${minAmount} - ${maxAmount}  ${baseSymbol}`;
                $('.depositLimit').text(depositLimit);
                var depositCharge = `@lang('Charge'): ${fixCharge} ${baseSymbol}  ${(0 < percentCharge) ? ' + ' +percentCharge + ' % ' : ''}`;
                $('.depositCharge').text(depositCharge);
                $('.method-name').text(`@lang('Payment By ') ${result.name}`);
                $('.currency-addon').text(baseSymbol);


                $('.edit-currency').val(result.currency);
                $('.edit-method-code').val(result.method_code);
            });
        });
    </script>
@endpush
