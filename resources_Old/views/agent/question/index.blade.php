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
                                    <th scope="col">@lang('SL')</th>
                                    <th scope="col">@lang('Question')</th>
                                    <th scope="col">@lang('Total Options')</th>
                                    <th scope="col">@lang('Type')</th>
                                    <th scope="col">@lang('Custom Input')</th>
                                    <th scope="col">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($questions as $item)
                                    <tr>
                                        <td data-label="@lang('SL')">{{$loop->index+1}}</td>
                                        <td data-label="@lang('Question')">{{__($item->question)}}</td>
                                        <td data-label="@lang('Total Options')"> {{@count($item->options)}}</td>
                                        <td data-label="@lang('Type')">
                                            @if ($item->type == 1)
                                                @lang('Radio')
                                            @elseif ($item->type == 2)
                                                @lang('Checkbox')
                                            @endif
                                        </td>
                                        <td data-label="@lang('Custom Input')">
                                            @if ($item->custom_input == 0)
                                                @lang('No')
                                            @elseif ($item->custom_input == 1)
                                                @lang('Yes')
                                            @endif
                                        </td>
                                        @if ($survey->status != 3)
                                            <td data-label="@lang('Action')"><a href="{{route('surveyor.survey.question.edit',[$item->id,$survey->id])}}" class="icon-btn"><i class="la la-pencil-alt"></i></a></td>
                                        @elseif($survey->status == 3)
                                            <td data-label="@lang('Action')"><a href="{{route('surveyor.survey.question.view',[$item->id,$survey->id])}}" class="icon-btn"><i class="la la-eye"></i></a></td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{__($empty_message)}}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $questions->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('surveyor.survey.all')}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
    <a href="{{route('surveyor.survey.question.new',$survey->id)}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
@endpush
