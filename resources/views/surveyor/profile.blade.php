@extends('surveyor.layouts.app')

@section('panel')

    <form action="{{ route('surveyor.profile.update') }}" method="POST" enctype="multipart/form-data">
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
                                            <div class="profilePicPreview" style="background-image: url({{ getImage(imagePath()['profile']['surveyor']['path'].'/'. $surveyor->image,imagePath()['profile']['surveyor']['size']) }})">
                                                <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                            <label for="profilePicUpload1" class="bg--success">@lang('Upload Profile Image')</label>
                                            <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg, jpg')</b>. @lang('Image will be resized into') {{imagePath()['profile']['surveyor']['size']}} @lang('px')</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                    <div class="card-body">
                        <h5 class="mb-20 text-muted">@lang('Sponsor information')</h5>
                        <ul class="list-group">

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Username')
                                <span class="font-weight-bold">{{$surveyor->username}}</span>
                            </li>


                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Status')
                                @switch($surveyor->status)
                                    @case(1)
                                        <span class="badge badge-pill bg--success">@lang('Active')</span>
                                        @break
                                    @case(0)
                                        <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                                        @break
                                @endswitch
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Balance')
                                <span class="font-weight-bold">{{getAmount($surveyor->balance)}}  {{__($general->cur_text)}}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Postpaid')
                                @switch($surveyor->post_arrangement)
                                    @case(\App\Surveyor::PA_ACTIVE)
                                        <span class="badge badge-pill bg--success">@lang('Active')</span>
                                        @break
                                    @case(\App\Surveyor::PA_INACTIVE)
                                        <span class="badge badge-pill bg--danger">@lang('Deactive')</span>
                                        @break
                                    @case(\App\Surveyor::PA_IN_REVIEW)
                                        <span class="badge badge-pill bg--warning">@lang('In Review')</span>
                                        @break
                                @endswitch
                            </li>

                            @if($surveyor->post_arrangement == \App\Surveyor::PA_ACTIVE && $surveyor->post_arrangement_doc != null)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    @lang('Agreement Doc')
                                    <span class="font-weight-bold"><a href="{{asset('assets/admin/docs/'.$surveyor->post_arrangement_doc)}}">{{$surveyor->post_arrangement_doc}}</a></span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                    <div class="card-body">
                        <h5 class="mb-20 text-muted">@lang('Views information')</h5>
                        <ul class="list-group">

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Total Views')
                                <span class="font-weight-bold">{{$surveyor->bought_views}}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Used Views')
                                <span class="font-weight-bold">{{$surveyor->total_views}}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Available Views')
                                <span class="font-weight-bold">{{$surveyor->bought_views - $surveyor->total_views}}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-7 col-md-7 mb-30">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-50 border-bottom pb-2">{{$surveyor->fullname}} @lang('User Information')</h5>

                        <div class="row">
                            <div class="col-md-12">
                                @php
                                    if($c >= 75) {
                                        $p_class = 'bg-success';
                                    }elseif($c >= 50){
                                        $p_class = 'bg-info';
                                    }elseif($c >= 25){
                                        $p_class = 'bg-warning';
                                    }else {
                                        $p_class = 'bg-danger';
                                    }
                                @endphp
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped {{$p_class}}" role="progressbar" style="width: {{$c}}%" aria-valuenow="{{$c}}" aria-valuemin="0" aria-valuemax="100">{{$c}}%</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="firstname" value="{{$surveyor->firstname}}" {{!is_null($surveyor->firstname) ? 'readonly' : null}}>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Last Name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="lastname" value="{{$surveyor->lastname}}" {{!is_null($surveyor->lastname) ? 'readonly' : null}}>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" value="{{$surveyor->email}}" readonly required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile" value="{{$surveyor->mobile}}" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Business Category')</label>
                                    @if(!is_null($surveyor->business_cat))
                                        @foreach($categories as $cat)
                                            @if($surveyor->business_cat == $cat->id )
                                                <input type="text" class="form-control" value="{{$cat->name}}" readonly>
                                                <input type="hidden" name="business_cat" value="{{$cat->id}}">
                                            @endif
                                        @endforeach
                                    @else
                                        <select name="business_cat" id="business-cat" class="form-control">
                                            <option value="">---SELECT CATEGORY---</option>
                                            @foreach($categories as $cat)
                                                <option value="{{$cat->id}}" data-subcategories="{{$cat->subcategories}}" {{$surveyor->business_cat == $cat->id ? 'selected' : ''}}>{{$cat->name}}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-6" id="bsc-snipp">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Business Sub-category') <a href="javascript:void(0)" class="btn btn--primary btn-sm addBtn">Create Subcategory</a></label>
{{--                                    @if(!is_null($surveyor->business_cat))--}}
{{--                                        <input type="text" class="form-control" name="business_subcat" value="{{$surveyor->business_subcat}}" readonly>--}}
{{--                                    @else--}}
{{--                                        <select name="business_subcat" id="business-subcat" class="form-control">--}}
{{--                                            <option value="">---SELECT SUBCATEGORY---</option>--}}
{{--                                            <option value="{{$surveyor->business_subcat}}" selected>{{$surveyor->business_subcat}}</option>--}}
{{--                                        </select>--}}
{{--                                    @endif--}}
                                    <select name="business_subcat" id="business-subcat" class="form-control">
                                        <option value="">---SELECT SUBCATEGORY---</option>
                                        @if(count($subcategories) != 0)
                                            @foreach($subcategories as $subcat)
                                                <option value="{{$subcat}}" {{$subcat == $surveyor->business_subcat ? "selected" : ""}}>{{$subcat}}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-6 d-none" id="new-cat-snipp">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Business Sub-category')</label>
                                    <input type="text" class="form-control" name="new_sub_category" placeholder="Mention you subcategory">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Profiling Services')</label>
                                    <input class="form-control" type="text" name="profiling_service" value="{{$surveyor->profiling_service}}" {{!is_null($surveyor->profiling_service) ? 'readonly' : null}}>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Pan')</label>
                                    <input class="form-control" type="text" name="profiling_service" value="{{$surveyor->pan}}" {{!is_null($surveyor->profiling_service) ? 'readonly' : null}}>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Opt Out Message')</label>
                                    <input class="form-control" type="text" name="opt_out_msg" value="{{$surveyor->opt_out_msg}}" {{!is_null($surveyor->opt_out_msg) ? 'readonly' : null}}>
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="isfirm" id="isfirm" {{$surveyor->isfirm == 1 ? 'checked' : ''}}>
                            <label class="form-check-label">Is it firm?</label>
                        </div>

                        <div class="row {{$surveyor->isfirm != 0 ? 'd-none' : ''}}" id="indvPanel">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Pan Card Number')</label>
                                    <input class="form-control pan" type="text" name="pan" value="{{$surveyor->pan}}" {{!is_null($surveyor->pan) ? 'readonly' : null}}>
                                    <small class="text-danger" id="pan-card-err"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row {{$surveyor->isfirm == 0 ? 'd-none' : ''}}" id="firmPanel">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Firm Name')</label>
                                    <input class="form-control" type="text" name="firm_name" value="{{$surveyor->firm_name}}" {{!is_null($surveyor->firm_name) ? 'readonly' : null}}>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Firm Type')</label>
                                    <input class="form-control" type="text" name="firm_type" value="{{$surveyor->firm_type}}" {{!is_null($surveyor->firm_type) ? 'readonly' : null}}>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Designation')</label>
                                    <input class="form-control" type="text" name="designation" value="{{$surveyor->designation}}" {{!is_null($surveyor->designation) ? 'readonly' : null}}>
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                    <input class="form-control" type="text" name="address" value="{{@$surveyor->address->address}}" {{!is_null($surveyor->address->address) ? 'readonly' : null}}>
                                    <small class="form-text text-muted"><i class="las la-info-circle"></i> @lang('House number, street address')
                                    </small>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" type="text" name="city" value="{{@$surveyor->address->city}}" {{!is_null($surveyor->address->city) ? 'readonly' : null}}>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State') </label>
                                    <input class="form-control" type="text" name="state" value="{{@$surveyor->address->state}}" {{!is_null($surveyor->address->state) ? 'readonly' : null}}>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Pin Code') </label>
                                    <input class="form-control" type="text" name="zip" value="{{@$surveyor->address->zip}}" {{!is_null($surveyor->address->zip) ? 'readonly' : null}}>
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

    <div id="addModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Add New Subcategory')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('surveyor.profile.category_request') }}" method="POST">
                    @csrf
                    <input type="hidden" name="category_id" value="{{$surveyor->business_cat}}">
                    <input type="hidden" name="surveyor_id" value="{{$surveyor->id}}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Subcategory Name')</label>
                            <input type="text" class="form-control" placeholder="@lang('Enter Name')" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{route('surveyor.password')}}" class="btn btn-sm btn--primary box--shadow1 text--small" ><i class="fa fa-key"></i>@lang('Password Setting')</a>
@endpush

@push('script')
    <script>
        $('.addBtn').on('click', function () {
            var modal = $('#addModal');
            modal.modal('show');
        });
        $('#isfirm').on('click', function () {
            if ($(this).is(':checked')) {
                $('#firmPanel').removeClass('d-none');
                $('#indvPanel').addClass('d-none');
            }else {
                $('#firmPanel').addClass('d-none');
                $('#indvPanel').removeClass('d-none');
            }
        });

        $('#business-cat').on('change', function() {
            if($(this).val() == 14) {
                $('#bsc-snipp').addClass('d-none');
                $('#new-cat-snipp').removeClass('d-none');
            }else{
                $('#bsc-snipp').removeClass('d-none');
                $('#new-cat-snipp').addClass('d-none');
            }
            let subcategories = $( "#business-cat option:selected" ).attr('data-subcategories');
            if(subcategories != null) {
                subcategories = subcategories.split(',')
                let options = '';
                $.each(subcategories, function (i, value) {
                    console.log(value)
                    options += `<option value="${value}">${value}</option>`;
                });
                $('#business-subcat').html(`
                 <option value="">---ADD SUBCATEGORY---</option>
                  ${options}
                `);
            }
        });

        $('.pan').on('change', function() {
            var inputvalues = $(this).val();
            var regex = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
            if(!regex.test(inputvalues)){
                $(".pan").val("");
                $('#pan-card-err').text('Invalid pan number!');
                return regex.test(inputvalues);
            }else {
                $('#pan-card-err').text('');
            }
        });
    </script>
@endpush
