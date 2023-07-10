@extends('agent.layouts.app')

@section('panel')

    <form action="{{ route('agent.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-none-30">
            <div class="col-xl-3 col-lg-5 col-md-5 mb-30">

                <div class="card b-radius--10 overflow-hidden box--shadow1">
                    <div class="card-body p-0">
                        <div class="p-3 bg--white">
                            <div class="form-group">
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview" style="background-image: url()">
                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                            <label for="profilePicUpload1" class="bg--success">@lang('Upload Profile Image')</label>
                                            <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg, jpg')</b>. @lang('Image will be resized into')  @lang('px')</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-50 border-bottom pb-2">{{$agent->fullname}} @lang('User Information')</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="firstname" value="{{$agent->firstname}}" {{!is_null($agent->firstname) ? 'readonly' : null}}>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="lastname" value="{{$agent->lastname}}" {{!is_null($agent->lastname) ? 'readonly' : null}}>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" value="{{$agent->email}}" readonly required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile" value="{{$agent->mobile}}" readonly required>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                    <input class="form-control" type="text" name="address" value="{{@$agent->address->address}}" {{!is_null($agent->address->address) ? 'readonly' : null}}>
                                    <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('House number, street address')
                                    </small>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" type="text" name="city" value="{{@$agent->address->city}}" {{!is_null($agent->address->city) ? 'readonly' : null}}>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State') </label>
                                    <input class="form-control" type="text" name="state" value="{{@$agent->address->state}}" {{!is_null($agent->address->state) ? 'readonly' : null}}>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Pin Code') </label>
                                    <input class="form-control" type="text" name="zip" value="{{@$agent->address->zip}}" {{!is_null($agent->address->zip) ? 'readonly' : null}}>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('breadcrumb-plugins')
    <a href="{{route('agent.password')}}" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="fa fa-key"></i>@lang('Password Setting')</a>
@endpush
