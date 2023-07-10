@extends('surveyor.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Sr. No.')</th>
                                    <th scope="col">@lang('Campaign Category')</th>
                                    <th scope="col">@lang('Thmubnail Image')</th>
                                    <th scope="col">@lang('Name')</th>
                                    <th scope="col">@lang('Total Questions')</th>
                                    <th scope="col">@lang('Posting Date')</th>
                                    <th scope="col">@lang('Download Report')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($surveys as $item)
                                    <tr>
                                        <td data-label="@lang('SL')">{{$loop->index+1}}</td>
                                        <td data-label="@lang('Category')">{{__($item->category->name)}}</td>
                                        <td data-label="@lang('User')">
                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="{{ url($item->image)}}" alt="@lang('image')" style="border-radius: unset;">
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Name')">{{__($item->p_name)}}</td>
                                        <td data-label="@lang('Total Questions')">{{ $item->questions->count() }}</td>
                                        <td data-label="@lang('Posted At')">{{ showDateTime($item->created_at) }}</td>
                                        <td data-label="@lang('Action')">
                                        <a href="{{route('surveyor.report.question',$item->id)}}" class="icon-btn" data-toggle="tooltip" title="@lang('Report')" data-original-title="@lang('Questions')">
                                            <i class="las la-chart-bar text--shadow"></i>
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
                    {{ $surveys->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
