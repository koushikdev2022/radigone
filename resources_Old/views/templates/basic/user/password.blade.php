@extends($activeTemplate.'layouts.master')

@section('content')

    <div class="user-profile-area mt-30">
        <div class="row justify-content-center mb-30-none">
            <div class="col-xl-12 col-md-12 col-sm-12 mb-30">
                <div class="panel panel-default">
                    <div class="panel-heading d-flex flex-wrap align-items-center justify-content-between">
                        <div class="panel-title"><i class="las la-lock"></i> @lang('Password Change Form')</div>
                        <div class="panel-options-form">
                            <a href="#" data-rel="collapse"><i class="las la-chevron-circle-down"></i></a>
                        </div>
                    </div>
                    <div class="panel-form-area">
                        <form class="panel-form" action="" method="post">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Current Password')*</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Password')*</label>
                                    <input type="password" name="password" class="form-control"  required>
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Confirm Password')*</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                                <div class="col-lg-12 form-group text-center">
                                    <button type="submit" class="submit-btn">@lang('Update')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

