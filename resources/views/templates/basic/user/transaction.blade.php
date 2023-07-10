@extends($activeTemplate.'layouts.master')

@section('content')

    <div class="withdraw-area mt-30">
        <div class="panel-card-header section--bg text-white">
            <div class="panel-card-title"><i class="la la-bank"></i> @lang('Transactions Details')</div>
        </div>
        <div class="panel-card-body p-0">
            <div class="row justify-content-center mb-30-none">
                <div class="col-xl-12 col-md-12 col-sm-12 mb-30">
                    <div class="panel-table-area">
                        <div class="panel-table border--base">
                            <div class="panel-card-body p-0 table-responsive">
                                <table class="custom-table">
                                    <thead>
                                        <tr>
                                            <th>@lang('Transaction Date')</th>
                                            <th>@lang('TRNX ID')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Bank Charge')(MDR)</th>
                                            <th>@lang('Post Balance')</th>
                                            <th>@lang('Payment Remarks')</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse($transactions as $trx)
                                            <tr>
                                                <td data-label="@lang('Date')">{{ showDateTime($trx->created_at) }}</td>
                                                <td data-label="@lang('TRX')" class="font-weight-bold">{{ $trx->trx }}</td>
                                                <td data-label="@lang('Amount')" class="budget">
                                                    <strong @if($trx->trx_type == '+') class="text-success" @else class="text-danger" @endif> {{($trx->trx_type == '+') ? '+':'-'}} {{getAmount($trx->amount)}} {{__($general->cur_text)}}</strong>
                                                </td>
                                                <td data-label="@lang('Charge')" class="budget">{{ __(__($general->cur_sym)) }} {{ getAmount($trx->charge) }} </td>
                                                <td data-label="@lang('Post Balance')">{{ getAmount($trx->post_balance) }} {{__($general->cur_text)}}</td>
                                                <td data-label="@lang('Detail')">{{ __($trx->details) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="panel-card-footer-area d-flex flex-wrap align-items-center justify-content-end">
                                <div class="panel-card-footer-right">
                                    <nav>
                                        {{$transactions->links()}}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
