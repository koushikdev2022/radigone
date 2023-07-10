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
                                <th scope="col">@lang('Sl')</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Email')</th>
                                <th scope="col">@lang('Username')</th>
                                <th scope="col">@lang('Role')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($data as $item)
                                <tr>
                                    <td data-label="@lang('SL')">{{$loop->index+1}}</td>
                                    <td data-label="@lang('Name')">{{__($item->name)}}</td>
                                    <td data-label="@lang('Email')">{{__($item->email)}}</td>
                                    <td data-label="@lang('Username')">{{__($item->username)}}</td>
                                    <td data-label="@lang('Role')">
                                        @if(!empty($item->getRoleNames()))
                                            @foreach($item->getRoleNames() as $v)
                                                <label class="badge badge-success">{{ $v }}</label>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">

                                        <a href="{{route('admin.admins.edit', $item->id)}}" class="icon-btn mr-1" data-toggle="tooltip" title="@lang('Questions')" data-original-title="@lang('Questions')">
                                            <i class="fas fa-edit text--shadow"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="icon-btn btn--danger rejectBtn" data-toggle="tooltip" title="@lang('Reject')" data-original-title="@lang('Reject')" data-url="{{route('admin.roles.delete', $item->id)}}">
                                            <i class="las la-ban text--shadow"></i>
                                        </a>
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
                    {{ $data->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>

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
                    <h4 class="modal-title" id="myModalLabel">@lang('Delete Role')</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <form method="post" action="">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted">@lang('Are you sure you want to Delete?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger deleteButton">@lang('Delete')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <div class="btn--search--area">
        <div class="item">
            <a href="{{route('admin.admins.create')}}" class="btn btn-lg btn--primary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
        </div>
        <div class="item">
            @if(request()->routeIs('admin.admins.all'))
                <form action="{{ route('admin.manage.survey.search', $scope ?? str_replace('admin.manage.survey.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
                    <div class="input-group has_append">
                        <input type="text" name="search" class="form-control" placeholder="@lang('Survey role name')" value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            @else
                <form action="{{ route('admin.manage.survey.search', $scope ?? str_replace('admin.manage.survey.', '', request()->route()->getName())) }}" method="GET" class="form-inline float-sm-right bg--white">
                    <div class="input-group has_append">
                        <input type="text" name="search" class="form-control" placeholder="@lang('Survey role name')" value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

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
