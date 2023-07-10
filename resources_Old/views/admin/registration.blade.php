@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
      

        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                   
                   <!--{{ route('admin.password.update') }}-->
                    <form action="{{ route('admin.registration.store') }}" method="POST">
                        @csrf

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">@lang('Agents Fees')</label>
                            <div class="col-lg-9">

                                <input class="form-control" type="number" placeholder="@lang('Agents Fees')" value="{{$getall->agent_fees}}" name="agent_fees">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">@lang('Surveyor Fees')</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="number" placeholder="@lang('Surveyor Fees')" name="surveyor_fees" value="{{$getall->surveyor_fees}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">@lang('User Fees')</label>
                            <div class="col-lg-9">
                                <input class="form-control" type="number" placeholder="@lang('User Fees')" name="user_fees" value="{{$getall->user_fees}}">
                            </div>
                        </div>

                        


                        <div class="form-group row">

                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                            <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




@push('style')
    <style>
        .list-left{
            width: 40%;
        }
        .list-right{
            width: calc(100% - 40%);
        }
    </style>
@endpush
