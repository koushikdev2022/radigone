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
                                <th scope="col">@lang('Sl')</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Contact')</th>
                                <th scope="col">@lang('Email')</th>
                                <th scope="col">@lang('Set Question')</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($answers as $item)
                                <tr>
                                    <td data-label="@lang('SL')">{{$loop->index+1}}</td>
                                    <td data-label="@lang('Name')">{{$item->user->firstname}} {{$item->user->lastname}}</td>
                                    <td data-label="@lang('Contact')">{{__($item->user->mobile)}}</td>
                                    <td data-label="@lang('Email')">{{__($item->user->email)}}</td>
                                    <td data-label="@lang('Action')">
                                        <a href="javascript:void(0)" class="icon-btn" title="@lang('Report')" data-toggle="modal" data-target="#see-details{{$item->id}}">
                                            <i class="fa fa-eye text--shadow"></i> Details
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
                    {{ $answers->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>

    @foreach($answers as $ans)
        <div class="modal fade" id="see-details{{$ans->id}}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Profile Details')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex mt-2">
                            <div>@lang('Question:')</div>
                            <div class="ml-auto">{{__($ans->question->question)}}</div>
                        </div>
                        <div class="d-flex mt-2">
                            <div>@lang('Answer:')</div>
                            @if($ans->question->options)
                                @php
                                    $answer = $ans->answer;
                                @endphp
                                <div class="ml-auto">{{$answer[0]}}</div>
                            @else
                                <div class="ml-auto">{{$ans->custom_answer}}</div>
                            @endif
                        </div>
                        <div class="d-flex mt-2">
                            <div>@lang('Name:')</div>
                            <div class="ml-auto">{{$item->user->firstname}} {{$item->user->lastname}}</div>
                        </div>
                        <div class="d-flex mt-2">
                            <div>@lang('Contact:')</div>
                            <div class="ml-auto">{{$item->user->mobile}}</div>
                        </div>
                        <div class="d-flex mt-2">
                            <div>@lang('Email:')</div>
                            <div class="ml-auto">{{$item->user->email}}</div>
                        </div>
                        @if($item->survey->ad_type == 3)
                            <div class="d-flex mt-2">
                                <div>@lang('Age:')</div>
                                <div class="ml-auto">{{$item->user->age}}</div>
                            </div>
                        @endif
                        @if($item->survey->ad_type == 1)
                            <div class="d-flex mt-2">
                                <div>@lang('Age:')</div>
                                <div class="ml-auto">{{$item->user->age}}</div>
                            </div>
                            <div class="d-flex mt-2">
                                <div>@lang('Permanent Location:')</div>
                                <div class="ml-auto">{{$item->user->address->address}}, {{$item->user->address->city}}, {{$item->user->address->state}}, {{$item->user->address->country}}</div>
                            </div>
                            <div class="d-flex mt-2">
                                <div>@lang('Current Location:')</div>
                                <div class="ml-auto">{{$item->user->address->address}}, {{$item->user->address->city}}, {{$item->user->address->state}}, {{$item->user->address->country}}</div>
                            </div>
                            <div class="d-flex mt-2">
                                <div>@lang('Gender:')</div>
                                <div class="ml-auto">{{$item->user->gander}}</div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--primary" data-dismiss="modal">@lang('Close')</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
