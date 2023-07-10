@extends('surveyor.layouts.app')

@section('panel')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card border--primary">
                            <div class="card-header">{{ __($page_title) }}
                                <a href="{{route('surveyor.ticket') }}" class="btn btn--primary float-right">
                                    @lang('My Support Ticket')
                                </a>
                            </div>

                            <div class="card-body">
                                <form  action="{{route('surveyor.ticket.store')}}"  method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="name">@lang('Name')</label>
                                            <input type="text" name="name" value="{{@$surveyor->firstname . ' '.@$surveyor->lastname}}" class="form-control" placeholder="@lang('Enter Name')" readonly required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">@lang('Email address')</label>
                                            <input type="email"  name="email" value="{{@$surveyor->email}}" class="form-control" placeholder="@lang('Enter your Email')" readonly required>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="website">@lang('Subject')</label>
                                            <input type="text" name="subject" value="{{old('subject')}}" class="form-control" placeholder="@lang('Subject')" >
                                        </div>
                                        <div class="col-12 form-group">
                                            <label for="inputMessage">@lang('Message')</label>
                                            <textarea name="message" id="inputMessage" rows="6" class="form-control">{{old('message')}}</textarea>
                                        </div>
                                    </div>

                                    <div class="row form-group ">
                                        <div class="col-9 ">
                                            <p class="text-muted">
                                                @lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')
                                            </p>
                                            <div class="file-upload-wrapper" data-text="@lang('Select your file!')">
                                                <input type="file" name="attachments[]"
                                                class="file-upload-field"/>
                                            </div>
                                            <div id="fileUploadsContainer"></div>
                                        </div>
                                        <div class="col-3 mt-4">
                                            <button type="button" class="btn btn--primary" onclick="extraTicketAttachment()"><i class="fa fa-plus"></i></button>
                                        </div>

                                    </div>

                                    <div class="row form-group justify-content-center">
                                        <div class="col-md-12">
                                            <button class="btn btn--primary" type="submit" id="recaptcha" ><i class="fa fa-paper-plane"></i>&nbsp;@lang('Submit')</button>
                                            <button class=" btn btn--danger" type="button" onclick="formReset()">&nbsp;@lang('Cancel')</button>
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


@push('script')
    <script>
        "use strict";
        function extraTicketAttachment() {
            $("#fileUploadsContainer").append(`
            <div class="file-upload-wrapper" data-text="Select your file!"><input type="file" name="attachments[]"  class="file-upload-field"/></div>`)
        }

        function formReset() {
            window.location.href = "{{url()->current()}}"
        }
    </script>
@endpush
