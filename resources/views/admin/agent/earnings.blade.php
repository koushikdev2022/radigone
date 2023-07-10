@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Date')</th>
                                <th scope="col">@lang('Name')</th>

                                <th scope="col">@lang('Amount')</th>
                                <th scope="col">@lang('Agent')</th>
                                <th scope="col">@lang('Surveyor')</th>
                                <th scope="col">@lang('Income')</th>
                                <th scope="col">@lang('Detail')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($surveys as $trx)
                                <tr>
                                    <td data-label="@lang('Date')">{{ showDateTime($trx->created_at) }}</td>
                                    <td data-label="@lang('Name')" class="font-weight-bold">{{ $trx->p_name }}</td>


                                    <td data-label="@lang('Amount')" class="budget">
                                        <strong> {{getAmount($trx->total_without_gst)}} {{__($general->cur_text)}}</strong>
                                    </td>
                                    <td data-label="@lang('Agent')" class="budget">{{ $trx->surveyor->agent->fullname }} </td>
                                    <td data-label="@lang('Surveyor')" class="budget">{{ $trx->surveyor->fullname }} </td>
                                    <td data-label="@lang('Incomee')">
                                        @if($trx->status != 0)
                                            <strong>0 {{__($general->cur_text)}}</strong>
                                        @else
                                            <strong class="text-success">{{getAmount(($trx->total_without_gst*1)/100)}} {{__($general->cur_text)}}</strong>
                                        @endif

                                    </td>
                                    <td data-label="@lang('Detail')">
                                        @if($trx->status == 1)
                                            <span>Survey is pending!</span>
                                        @elseif($trx->status == 3)
                                            <span>Survey is rejected!</span>
                                        @elseif($trx->status == 0)
                                            <span>Survey is approved!</span>
                                        @endif
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
                    {{ $surveys->links('surveyor.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')
    @if(request()->routeIs('surveyor.transactions'))
        <form action="{{ route('surveyor.transactions.search') }}" method="GET" class="form-inline float-sm-right bg--white">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('TRX')" value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    @elseif(request()->routeIs('surveyor.transactions.search'))
        <form action="{{ route('surveyor.transactions.search') }}" method="GET" class="form-inline float-sm-right bg--white">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('TRX')" value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    @endif

@endpush


