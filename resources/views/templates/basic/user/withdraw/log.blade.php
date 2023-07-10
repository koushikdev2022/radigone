@extends($activeTemplate.'layouts.master')

@section('content')

    <div class="withdraw-area mt-30">
        <div class="panel-card-header section--bg text-white">
            <div class="panel-card-title"><i class="la la-bank"></i> @lang('Withdraw Details')</div>
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
                                            <th>@lang('Transaction ID')</th>
                                            <th>@lang('Gateway')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Charge')</th>
                                            <th>@lang('After Bank Charge (MDR) in INR')</th>
                                            <th>@lang('Rate') (1 Reward point = 1 Rupee)</th>
                                            <th>@lang('Receivable Amount')(After Bank Charges)</th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Transaction Date')</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @forelse($withdraws as $k=>$data)
                                            <tr>
                                                <td data-label="@lang('Trx')">{{$data->trx}}</td>
                                                <td data-label="@lang('Gateway')">{{ __($data->method->name) }}</td>
                                                <td data-label="@lang('Amount')">
                                                    <strong>{{getAmount($data->amount)}} {{__($general->cur_text)}}</strong>
                                                </td>
                                                <td data-label="@lang('Charge')" class="text--danger">
                                                    {{getAmount($data->charge)}} {{__($general->cur_text)}}
                                                </td>
                                                <td data-label="@lang('After Bank Charge (MDR)')">
                                                    {{getAmount($data->after_charge)}} {{__($general->cur_text)}}
                                                </td>
                                                <td data-label="@lang('Rate')">
                                                    {{getAmount($data->rate)}} {{__($data->currency)}}
                                                </td>
                                                <td data-label="@lang('Receivable Amount')" class="text--success">
                                                    <strong>{{getAmount($data->final_amount)}} {{__($data->currency)}}</strong>
                                                </td>
                                                <td data-label="@lang('Status')">
                                                    @if($data->status == 2)
                                                        <span class="badge badge--warning text-white">@lang('Pending')</span>
                                                    @elseif($data->status == 1)
                                                        <span class="badge badge--success text-white">@lang('Completed')</span>
                                                        <button class="btn-info btn-rounded  badge approveBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                                                    @elseif($data->status == 3)
                                                        <span class="badge badge--danger text-white">@lang('Rejected')</span>
                                                        <button class="btn-info btn-rounded badge approveBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="fa fa-info"></i></button>
                                                    @endif

                                                </td>
                                                <td data-label="@lang('Time')">
                                                    <i class="fa fa-calendar mr-2"></i> {{showDateTime($data->created_at)}}
                                                </td>
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
                                        {{$withdraws->links()}}
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail MODAL --}}
    <div id="detailModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="withdraw-detail"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        "use strict";
        $(function(){
            $('.approveBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');

                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });
        });

    </script>
@endpush
