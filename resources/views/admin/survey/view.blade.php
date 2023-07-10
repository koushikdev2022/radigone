@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Question Type')</label>
                                            <input type="text" class="form-control" value="@if($question->type == 1 ) @lang('Radio') @elseif($question->type == 2) @lang('Checkbox') @endif" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Custom Input')</label>
                                            <input type="text" class="form-control" value="@if($question->custom_input == 0 ) @lang('No') @elseif($question->custom_input == 1) @lang('Yes') @endif" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>@lang('Question')</label>
                                    <textarea class="form-control" placeholder="@lang('Enter Your Question')" disabled>{{$question->question}}</textarea>
                                </div>
                                @if($question->options)
                                    <div class="payment-method-item p-2">
                                        <div class="payment-method-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card border--primary">
                                                        <h5 class="card-header bg--primary  text-white">@lang('All Options')
                                                        </h5>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <div class="input-group">
                                                                            <div class="col-md-12">
                                                                                @foreach ($question->options as $item)
                                                                                    <input class="form-control mb-2" type="text" placeholder="@lang('Enter option')" value="{{$item}}" disabled>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="payment-method-item p-2">
                                        <div class="payment-method-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="card border--primary">
                                                        <h5 class="card-header bg--primary  text-white">@lang('No Options Found')
                                                        </h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('breadcrumb-plugins')
    <a href="javascript:window.history.back();" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="las la-angle-double-left"></i>@lang('Go Back')</a>
@endpush
