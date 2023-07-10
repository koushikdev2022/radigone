@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="mt-30">
        <div class="row justify-content-center mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header text-white d-flex flex-wrap align-items-center justify-content-between">{{ __($page_title) }}
                        <a href="{{route('ticket') }}" class="btn btn-sm btn--base">
                            @lang('My Support Ticket')
                        </a>
                    </div>

                    <div class="card-body">
                        <form  action="{{route('ticket.store')}}"  method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                            @csrf
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="name">@lang('Name')</label>
                                    <input type="text" name="name" value="{{@$user->firstname . ' '.@$user->lastname}}" class="form-control" placeholder="@lang('Enter Name')" readonly required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="email">@lang('Email address')</label>
                                    <input type="email"  name="email" value="{{@$user->email}}" class="form-control" placeholder="@lang('Enter your Email')" readonly required>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label for="website">@lang('Subject')</label>
                                    <input type="text" name="subject" value="{{old('subject')}}" class="form-control" placeholder="@lang('Subject')" >
                                </div>
                                <div class="form-group col-lg-12">
                                    <label for="inputMessage">@lang('Message')</label>
                                    <textarea name="message" id="inputMessage" rows="6" class="form-control" placeholder="@lang('Enter Your Message')">{{old('message')}}</textarea>
                                </div>
                            </div>

                            <div class="col-md-8 ">
                                <div class="row justify-content-between flex-row-reverse">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <a href="javascript:void(0)"
                                               class="btn submit-button reply-btn btn-round mt-4"
                                               onclick="extraTicketAttachment()">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-9">

                                        <div class="form-group">
                                            <label for="inputAttachments">@lang('Attachments')</label>
                                            <div class="file-upload-wrapper" data-text="@lang('Select your file!')">
                                                <input type="file"
                                                    name="attachments[]"
                                                    class="file-upload-field"/>
                                            </div>
                                            <div id="fileUploadsContainer"></div>
                                            <p class="my-2 ticket-attachments-message text-muted">
                                                @lang('Allowed File Extensions: .png, .jpg, .jpeg, .pdf, .doc, .docx')
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row form-group justify-content-center">
                                <div class="col-md-12">
                                    <button class="btn btn--base" type="submit" id="recaptcha" ><i class="fa fa-paper-plane"></i>&nbsp;@lang('Submit')</button>
                                    <button class=" btn btn--base" type="button" onclick="formReset()">&nbsp;@lang('Cancel')</button>
                                </div>
                            </div>
                        </form>
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
            <div class="file-upload-wrapper mt-3" data-text="Select your file!"><input type="file" name="attachments[]"class="file-upload-field"/></div>`)
        }

        function formReset() {
            window.location.href = "{{url()->current()}}"
        }
    </script>
@endpush
