@extends($activeTemplate.'layouts.frontend')

@section('content')
@include($activeTemplate.'partials.breadcrumb')
<section class="ticket-section padding-top padding-bottom ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card border-primary">
                    <div class="card-header card-header-bg d-flex flex-wrap justify-content-between align-items-center">
                        <h5 class="card-title mt-0">
                            @if($my_ticket->status == 0)
                                <span class="badge ticket-button py-2 px-3">@lang('Open')</span>
                            @elseif($my_ticket->status == 1)
                                <span class="badge ticket-button py-2 px-3">@lang('Answered')</span>
                            @elseif($my_ticket->status == 2)
                                <span class="badge ticket-button py-2 px-3">@lang('Replied')</span>
                            @elseif($my_ticket->status == 3)
                                <span class="badge badge-dark py-2 px-3">@lang('Closed')</span>
                            @endif
                            [@lang('Ticket')#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}
                        </h5>
                        <div class="cart-right d-flex flex-wrap align-items-center">
                            <h5 class="card-title mr-2">@lang('Close Ticket')</h5>
                            <button class="btn close-button" type="button" title="@lang('Close Ticket')"
                                    data-toggle="modal"
                                    data-target="#DelModal"><i class="fa fa-lg fa-times"></i>

                            </button>
                        </div>

                    </div>

                    <div class="card-body">

                        <div class="accordion" id="accordionExample">

                            <div class="card">
                                <div class="card-header bf" id="headingThree">
                                    <h2>
                                        <a class="btn btn-link collapsed float-left"
                                           href="javascript:void(0)" data-toggle="collapse"
                                           data-target="#collapseThree" aria-expanded="true"
                                           aria-controls="collapseThree" style="text-decoration: none">
                                            <i class="fa fa-pencil-alt"></i> @lang('Reply')
                                        </a>


                                        <a class="btn btn-link collapsed float-right accor"
                                           href="javascript:void(0)" data-toggle="collapse"
                                           data-target="#collapseThree" aria-expanded="true"
                                           aria-controls="collapseThree">
                                            <i class="fa fa-plus"></i>
                                        </a>

                                    </h2>
                                </div>
                                <div id="collapseThree" class="collapse show"
                                     aria-labelledby="headingThree"
                                     data-parent="#accordionExample">

                                    <div class="card-body">
                                        @if($my_ticket->status != 4)
                                            <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row justify-content-between">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <textarea name="message" class="form-control form-control-lg" id="inputMessage" placeholder="@lang('Your Reply') ..." rows="4" cols="10"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row justify-content-between">

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

                                                    <div class="col-md-3">
                                                        <button type="submit"
                                                                class="submit-button reply-btn float-right"
                                                                name="replayTicket" value="1">
                                                            <i class="fa fa-reply"></i> @lang('Reply')
                                                        </button>
                                                    </div>

                                                </div>
                                            </form>
                                        @endif

                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12">

                                <div class="card">
                                    <div class="card-body">

                                        @foreach($messages as $message)
                                            @if($message->admin_id == 0)

                                                <div class="row border border-radius-3 my-3 py-3 mx-2">
                                                    <div class="col-md-3 border-right text-right">
                                                        <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                                    </div>

                                                    <div class="col-md-9">
                                                        <p class="text-muted font-weight-bold my-3">
                                                            @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                                        <p>{{$message->message}}</p>
                                                        @if($message->attachments()->count() > 0)
                                                            <div class="mt-2">
                                                                @foreach($message->attachments as $k=> $image)
                                                                    <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                    </div>

                                                </div>

                                            @else

                                                <div class="row border border-radius-3 my-3 py-3 mx-2 admin-reply-section">
                                                    <div class="col-md-9 border-right text-right">
                                                        <p class="text-muted font-weight-bold my-3">
                                                            @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                                        <p>{{$message->message}}</p>

                                                        @if($message->attachments()->count() > 0)
                                                            <div class="mt-2">
                                                                @foreach($message->attachments as $k=> $image)
                                                                    <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h5 class="my-3">{{ $message->admin->name }}</h5>
                                                        <p class="lead text-muted">@lang('Staff')</p>

                                                    </div>

                                                </div>
                                            @endif
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
</section>

<div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Confirmation')!</h5>

                    <button type="button" class="close close-button" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <strong class="text-dark">@lang('Are you sure you want to Close This Support Ticket')?</strong>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn--base" data-dismiss="modal"><i class="fa fa-times"></i>
                        @lang('Close')
                    </button>

                    <button type="submit" class="btn btn--base" name="replayTicket"
                            value="2"><i class="fa fa-check"></i> @lang("Confirm")
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        'use strict';

        (function ($) {
            $(document).ready(function () {
                $('.delete-message').on('click', function (e) {
                    $('.message_id').val($(this).data('id'));
                });
            });

        })(jQuery);

        function extraTicketAttachment() {
            $("#fileUploadsContainer").append(`
            <div class="file-upload-wrapper mt-3" data-text="Select your file!"><input type="file" name="attachments[]"class="file-upload-field"/></div>`)
        }
    </script>
@endpush
