@extends($activeTemplate.'layouts.master')

@section('content')

    <div class="withdraw-area mt-30">
        <div class="panel-card-header section--bg text-white">
            <div class="panel-card-title"><i class="las la-ticket-alt"></i> @lang('Ticket List')</div>
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
                                            <th scope="col">@lang('Subject')</th>
                                            <th scope="col">@lang('Status')</th>
                                            <th scope="col">@lang('Last Reply')</th>
                                            <th scope="col">@lang('Action')</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($supports as $key => $support)
                                            <tr>
                                                <td data-label="@lang('Subject')"> <a href="{{ route('ticket.view', $support->ticket) }}" class="font-weight-bold"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                                                <td data-label="@lang('Status')">
                                                    @if($support->status == 0)
                                                        <span class="badge badge--primary text-white">@lang('Open')</span>
                                                    @elseif($support->status == 1)
                                                        <span class="badge badge--primary text-white">@lang('Answered')</span>
                                                    @elseif($support->status == 2)
                                                        <span class="badge badge--warning text-white">@lang('Customer Reply')</span>
                                                    @elseif($support->status == 3)
                                                        <span class="badge badge--dark text-white">@lang('Closed')</span>
                                                    @endif
                                                </td>
                                                <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                                <td data-label="@lang('Action')">
                                                    <a href="{{ route('ticket.view.user', $support->ticket) }}" class="badge badge--warning text-white">
                                                        <i class="las la-desktop"></i>
                                                    </a>
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
                                        {{$supports->links()}}
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
