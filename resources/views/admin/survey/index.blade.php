@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('Sr. No.')</th>
                                    <th scope="col">@lang('Category')</th>
                                    <th scope="col">@lang('Image')</th>
                                    <th scope="col">@lang('Survey Name')</th>
                                    <th scope="col">@lang('Surveyor')</th>
                                    <th scope="col">@lang('Advt Video')</th>
                                    <th scope="col">@lang('Pass Type')</th>
                                    <th scope="col">@lang('Status')</th>
                                    <th scope="col">@lang('Review')</th>
                                    <th scope="col">@lang('Total Questions')</th>
                                    <th scope="col">@lang('Total Views')</th>
                                    <th scope="col">@lang('Pending Views')</th>
                                    <th scope="col">@lang('Posted At')</th>
                                    <th scope="col">@lang('Set Question')</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse($surveys as $item)
                                    @php
                                        $total_views = $item->total_views;
                                        $views = !is_null($item->users) ? count($item->users) : 0;
                                        $res_views = $total_views - $views;
                                    @endphp
                                    <tr>
                                        <td data-label="@lang('SL')">{{$loop->index+1}}</td>
                                        <td data-label="@lang('Category')">{{__($item->category->name)}}</td>
                                        <td data-label="@lang('Image')">
                                            <div class="user">
                                                <div class="thumb">
                                                 
                                                    <img src="{{ url($item->image)}}" alt="@lang('image')">
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="@lang('Survey Name')">{{__($item->p_name)}}</td>
                                        <td data-label="@lang('Surveyor')">
                                            <a href="{{ route('admin.surveyors.detail', $item->surveyor->id) }}">{{ $item->surveyor->username }}</a>
                                        </td>
                                        <td data-label="@lang('video')"><button class="badge badge--primary" data-toggle="modal" data-target="#viewVideoModal{{$item->id}}">Show</button></td>
                                        <td data-label="@lang('Pass Type')">{{__($item->adtype->name)}}</td>
                                        <td data-label="@lang('Status')">
                                            @if ($item->status == 0)
                                                <span class="text--small badge font-weight-normal badge--success">@lang('Approved')</span>
                                            @elseif($item->status == 1)
                                                <span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                            @elseif($item->status == 3)
                                                <span class="text--small badge font-weight-normal badge--danger">@lang('Rejected')</span>
                                                 <p>{{$item->reject_answer}}</p>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Details')"><button class="badge badge--primary" data-toggle="modal" data-target="#viewDetailsModal{{$item->id}}">Details</button></td>
                                        <td data-label="@lang('Total Questions')">{{ $item->questions->count() }}</td>
                                        <td data-label="@lang('Total Views')">{{ $total_views }}</td>
                                        <td data-label="@lang('Pending Views')">{{ $res_views }}</td>
                                        <td data-label="@lang('Posted At')">{{ showDateTime($item->created_at) }}</td>
                                        <td data-label="@lang('Action')">

                                            <a href="{{route('admin.manage.survey.question.all',$item->id)}}" class="icon-btn mr-1" data-toggle="tooltip" title="@lang('Questions')" data-original-title="@lang('Questions')">
                                                <i class="fas fa-question-circle text--shadow"></i>
                                            </a>

                                            @if ($item->status == 0)
                                                <a href="javascript:void(0)" class="icon-btn btn--danger rejectBtn" data-toggle="tooltip" title="@lang('Suspend')" data-original-title="@lang('Suspend')" data-url="{{route('admin.manage.survey.reject',$item->id)}}">
                                                    <i class="las la-ban text--shadow"></i>
                                                </a>
                                            @endif

                                            @if ($item->status == 1)
                                                <a href="javascript:void(0)" class="icon-btn btn--success mr-1 approveBtn" data-toggle="tooltip" title="@lang('Approve')" data-original-title="@lang('Approve')" data-url="{{route('admin.manage.survey.approve',$item->id)}}">
                                                    <i class="fas fa-check-circle text--shadow"></i>
                                                </a>

                                                <a href="javascript:void(0)" class="icon-btn btn--danger rejectBtn" data-toggle="tooltip" title="@lang('Reject')" data-original-title="@lang('Reject')" data-url="{{route('admin.manage.survey.reject',$item->id)}}">
                                                    <i class="las la-ban text--shadow"></i>
                                                </a>
                                            @endif
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
                    {{ $surveys->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>

    @foreach($surveys as $survey)
        <div class="modal fade" id="viewVideoModal{{$survey->id}}" tabindex="-1" aria-labelledby="viewVideoModalLabel{{$survey->id}}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Video</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <video src="{{$survey->video_url}}" poster="{{getImage(imagePath()['survey']['path'].'/'.$survey->image,imagePath()['survey']['size'])}}" style="width: 100%" controls></video>
                    </div>
{{--                    <div class="modal-footer">--}}
{{--                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                        <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>

        @php
            $total_views = $survey->total_views;
            $views = !is_null($survey->users) ? count($survey->users) : 0;
            $res_views = $total_views - $views;
        @endphp
        <div class="modal fade bd-example-modal-lg" id="viewDetailsModal{{$survey->id}}" tabindex="-1" role="dialog" aria-labelledby="viewDetailsModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Advertise Details</h5>
                        <button type="button" onclick="removeStartText({{$survey->id}})" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container mb-30">
                            <div class="card">
                                <div class="card-header">
                                    {{__($survey->p_name)}}
                                    <span class="float-right">Paid Views Remaining: <span class="text-green">{{$res_views}}</span></span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <video src="{{$survey->video_url}}" poster="{{ getImage(imagePath()['survey']['path'].'/'. $survey->image,imagePath()['survey']['size']) }}" style="width: 100%" controls></video>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-row">
                                                <div class="mr-auto p-2 font-weight-bold">Company/Sponsor Name:</div>
                                                <div class="p-2 font-weight-bold">Campaign ID:</div>
                                            </div>
                                            <div class="d-flex flex-row">
                                                <div class="mr-auto p-2">{{$survey->surveyor->firstname.' '.$survey->surveyor->lastname}}</div>
                                                <div class="p-2">{{$survey->id}}</div>
                                            </div>
                                            <div class="d-flex flex-row">
                                                <div class="mr-auto p-2 font-weight-bold">Product Name:</div>
                                                <div class="p-2 font-weight-bold">Radigone Points:</div>
                                            </div>
                                            <div class="d-flex flex-row">
                                                <div class="mr-auto p-2">{{__($survey->p_name)}}</div>
                                                <div class="p-2">{{__($survey->per_user)}}</div>
                                            </div>
                                            <div class="d-flex flex-row">
                                                <div class="mr-auto p-2 font-weight-bold">Data shared with sponsor (As per pass chosen by Sponsor)</div>
                                                <div class="p-2 font-weight-bold">To opt-out {{!is_null($survey->surveyor->opt_out_msg) ? '('.$survey->surveyor->opt_out_msg.')' : '(Not Available)'}}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="d-flex flex-row">
{{--                                                <div class="p-2 font-weight-bold">Sponsor's Rights</div>--}}
                                                <div class="p-2 font-weight-bold">Post Paid</div>
                                                <div class="p-2 font-weight-bold">Discount Coupons</div>
                                                <div class="p-2 font-weight-bold">Legal Abide</div>
                                            </div>
                                        </div>
                                        @if($survey->online_purchase == 1)
                                            <div class="col-md-12">
                                                <div class="d-flex flex-row">
                                                    <div class="p-2 font-weight-bold">Purchase Link:</div>
                                                    <div class="p-2 font-weight-bold"><a href="{{$survey->purchas_url}}" target="_blank">{{$survey->purchas_url}}</a></div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-12">
                                            <div class="d-flex flex-row">
                                                <div class="mr-auto p-2 font-weight-bold">Price:</div>
                                                <div class="p-2 font-weight-bold">Discount Price:</div>
                                            </div>
                                            <div class="d-flex flex-row">
                                                <div class="mr-auto p-2">{{__($survey->p_mrp)}} INR</div>
                                                <div class="p-2">{{__($survey->discount)}} INR</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="d-flex flex-row">
                                                <div class="p-2"><a href="javascript:void(0)" class="btn btn-success text-white text-center">@lang('Continue Watching')</a></div>
                                                <div class="p-2"><a href="javascript:void(0)" class="btn btn-primary text-white text-center">@lang('Watch Later')</a></div>
                                                <div class="p-2"><a href="javascript:void(0)" class="btn btn-warning text-white text-center">@lang('Skip')</a></div>
                                                <div class="p-2"><a href="javascript:void(0)" class="btn btn-danger text-white text-center">@lang('Block Company/Sponsor')</a></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{--            <div class="modal-footer">--}}
                    {{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
                    {{--                <button type="button" class="btn btn-primary">Save changes</button>--}}
                    {{--            </div>--}}
                </div>
            </div>
        </div>
    @endforeach

    {{-- Approve MODAL --}}
    <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('Approve Survey')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <form method="post" action="">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted">@lang('Are you sure you want to Approve?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success approveButton">@lang('Approve')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('Remove Survey')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <form method="post" action="">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted">@lang('Are you sure you want to Reject?')</p>


                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Reject Answer</label>
                            <textarea name="reject_answer" class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
                          </div>
                    </div>




                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger deleteButton">@lang('Reject')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <form action="{{ route('admin.manage.survey.search', $scope ?? str_replace('admin.manage.survey.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Survey category name')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
@endpush

@push('script')
    <script>
        (function($){
            "use strict";

            $('.rejectBtn').on('click', function () {
                var modal = $('#rejectModal');
                var url = $(this).data('url');

                modal.find('form').attr('action', url);
                modal.modal('show');
            });

            $('.approveBtn').on('click', function () {
                var modal = $('#approveModal');
                var url = $(this).data('url');

                modal.find('form').attr('action', url);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
