@extends('surveyor.layouts.app')

@section('panel')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card card-deposit ">
                            <div class="card-body">
                                <form action="{{ route('surveyor.deposit.manual.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row border border--primary">
                                        <div class="col-md-12 text-center">
                                            <p class="text-center mt-2">@lang('You have requested') <b class="text--primary">{{ getAmount($data['amount'])  }} {{__($general->cur_text)}}</b> , @lang('Please pay')
                                                <b class="text--primary">{{getAmount($data['final_amo']) .' '.$data['method_currency'] }} </b> @lang('for successful payment')
                                            </p>
                                            <h4 class="text-center mb-4 text--primary">@lang('Please follow the instruction bellow')</h4>

                                            <p class="my-4 text-center">@php echo  $data->gateway->description @endphp</p>

                                        </div>

                                        @if($method->gateway_parameter)

                                            @foreach(json_decode($method->gateway_parameter) as $k => $v)

                                                @if($v->type == "text")
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label><strong>{{__(inputTitle($v->field_level))}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                                            <input type="text" class="form-control" name="{{$k}}" value="{{old($k)}}" placeholder="{{__($v->field_level)}}" @if($v->validation == 'required') required @endif>
                                                        </div>
                                                    </div>
                                                @elseif($v->type == "textarea")
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label><strong>{{__(inputTitle($v->field_level))}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                                                <textarea name="{{$k}}"  class="form-control"  placeholder="{{__($v->field_level)}}" rows="3" @if($v->validation == 'required') required @endif>{{old($k)}}</textarea>

                                                            </div>
                                                        </div>
                                                @elseif($v->type == "file")
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label><strong>{{__($v->field_level)}} @if($v->validation == 'required') <span class="text-danger">*</span>  @endif</strong></label>
                                                            <input type="file" class="form-control" name="{{$k}}" accept="image/*" @if($v->validation == 'required') required @endif>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn--primary btn-block mt-2 text-center">@lang('Pay Now')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
