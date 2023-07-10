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
                                    <th scope="col">@lang('Username')</th>
                                    <th scope="col">@lang('IP')</th>
                                    <th scope="col">@lang('Location')</th>
                                    <th scope="col">@lang('Browser')</th>
                                    <th scope="col">@lang('OS')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($login_logs as $log)
                                    <tr>
                                        <td data-label="@lang('Date')">{{diffForHumans($log->created_at) }}</td>
                                        @if($log->user_id)
                                            <td data-label="@lang('Username')"><a href="{{ route('admin.users.detail', $log->user_id)}}"> {{ ($log->user) ? $log->user->username : '' }}</a></td>
                                        @elseif($log->surveyor_id)
                                            <td data-label="@lang('Username')"><a href="{{ route('admin.surveyors.detail', $log->surveyor_id)}}"> {{ ($log->surveyor) ? $log->surveyor->username : '' }}</a></td>
                                        @endif
                                        <td data-label="@lang('IP')">
                                            @if($log->user_id)
                                                <a href="{{route('admin.report.login.ipHistory',[$log->user_ip])}}"> {{ $log->user_ip }} </a>
                                            @elseif($log->surveyor_id)
                                                <a href="{{route('admin.report.surveyor.login.ipHistory',[$log->surveyor_ip])}}"> {{ $log->surveyor_ip }} </a>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Location')">{{ $log->location }}</td>
                                        <td data-label="@lang('Browser')">{{ __($log->browser) }}</td>
                                        <td data-label="@lang('OS')">{{ __($log->os) }}</td>
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
                    {{ paginateLinks($login_logs) }}
                </div>
            </div><!-- card end -->
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    @if(request()->routeIs('admin.report.login.history'))
        <form action="{{ route('admin.report.login.history') }}" method="GET" class="form-inline float-sm-right bg--white">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('Search here')" value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    @elseif(request()->routeIs('admin.report.surveyor.login.history'))
        <form action="{{ route('admin.report.surveyor.login.history') }}" method="GET" class="form-inline float-sm-right bg--white">
            <div class="input-group has_append">
                <input type="text" name="search" class="form-control" placeholder="@lang('Search here')" value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    @endif
@endpush
