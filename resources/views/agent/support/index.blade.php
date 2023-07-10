@extends('surveyor.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light">
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
                                    <td data-label="@lang('Subject')"> <a href="{{ route('surveyor.ticket.view', $support->ticket) }}" class="font-weight-bold"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a></td>
                                    <td data-label="@lang('Status')">
                                        @if($support->status == 0)
                                            <span class="badge badge--success">@lang('Open')</span>
                                        @elseif($support->status == 1)
                                            <span class="badge badge--primary">@lang('Answered')</span>
                                        @elseif($support->status == 2)
                                            <span class="badge badge--warning">@lang('Customer Reply')</span>
                                        @elseif($support->status == 3)
                                            <span class="badge badge--dark">@lang('Closed')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                                    <td data-label="@lang('Action')">
                                        <a href="{{ route('surveyor.ticket.view', $support->ticket) }}" class="icon-btn  ml-1" data-toggle="tooltip" title="" data-original-title="@lang('Details')">
                                            <i class="las la-desktop text--shadow"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $supports->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('surveyor.ticket.open')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-plus"></i></i> @lang('Add New') </a>
@endpush
