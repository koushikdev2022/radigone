
@extends('admin.layouts.app')

@section('panel')

<div class="row">
    @if(count($sub_cat_requests) != 0)
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive--sm table-responsive">
                        <h5 class="mb-2">Subcategory Requests</h5>
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('SL')</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Sponsor')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            @forelse ($sub_cat_requests as $scr)
                                <tr>
                                    <td data-label="@lang('SL')">{{ $loop->index+1 }}</td>
                                    <td data-label="@lang('Name')">{{ $scr->name }}</td>
                                    <td data-label="@lang('Sponsor')">{{ $scr->surveyor->fullname }} ({{ $scr->surveyor->username }})</td>
                                    <td data-label="@lang('Action')">
                                        <a href="{{route('admin.subcategory_request_update', $scr->id)}}" class="icon-btn">Approve</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ $empty_message }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ $categories->links('admin.partials.paginate') }}
                </div>
            </div>
        </div>
    @endif
    <div class="col-lg-12 col-md-12 mb-30">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th scope="col">@lang('SL')</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @forelse ($categories as $item)
                                <tr>
                                    <td data-label="@lang('SL')">{{ $loop->index+1 }}</td>
                                    <td data-label="@lang('Name')">{{ $item->name }}</td>
                                    <td data-label="@lang('Action')">
                                        <a href="#" class="icon-btn" data-route="{{ route('admin.category.update',$item->id) }}" data-resourse="{{$item}}" data-toggle="modal" data-target="#updateBtn{{$item->id}}" ><i class="la la-pencil-alt"></i></a>
                                        @if ($item->status == 0)
                                            <button type="button"
                                                    class="icon-btn btn--success ml-1 activateBtn"
                                                    data-toggle="modal" data-target="#activateModal"
                                                    data-id="{{$item->id}}"
                                                    data-original-title="@lang('Enable')">
                                                <i class="la la-eye"></i>
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="icon-btn btn--danger ml-1 deactivateBtn"
                                                    data-toggle="modal" data-target="#deactivateModal"
                                                    data-id="{{$item->id}}"
                                                    data-original-title="@lang('Disable')">
                                                <i class="la la-eye-slash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ $empty_message }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer py-4">
                {{ $categories->links('admin.partials.paginate') }}
            </div>
        </div>
    </div>
</div>

{{-- Add METHOD MODAL --}}
<div id="addModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> @lang('Add New Category')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.category.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Category Name')</label>
                        <input type="text" class="form-control" placeholder="@lang('Enter Name')" name="name" required>
                    </div>
                     <div class="form-group">
                        <label>@lang('Price')</label>
                        <input type="number" class="form-control" placeholder="@lang('Enter Price')" name="price" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('Subcategories')</label>
                        <textarea class="form-control subcategories" placeholder="@lang('Enter Subcategory')" name="subcategories"></textarea>
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


{{-- Update METHOD MODAL --}}
@foreach($categories as $item)
    <div id="updateBtn{{$item->id}}" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Update Category')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.category.update',$item->id) }}" class="edit-route" method="POST">
                    @csrf

                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Category Name')</label>
                            <input type="text" class="form-control name" placeholder="@lang('Enter Name')" name="name" value="{{$item->name}}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Price')</label>
                            <input type="number" class="form-control price" placeholder="@lang('Enter Price')" name="price" value="{{$item->price}}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Subcategories')</label>
                            <textarea class="form-control subcategories" placeholder="@lang('Enter Subcategory')" name="subcategories">{{$item->subcategories}}</textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Update')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

    {{-- ACTIVATE METHOD MODAL --}}
    <div id="activateModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Category Activation Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.category.activate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to activate this category?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Activate')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DEACTIVATE METHOD MODAL --}}
    <div id="deactivateModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Category Disable Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.category.deactivate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to disable thid category')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Disable')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('breadcrumb-plugins')
        <div class="btn--search--area">
            <div class="item">
                <a href="javascript:void(0)" class="btn btn-lg btn--primary box--shadow1 text--small addBtn"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
            </div>
            <div class="item">
                @if(request()->routeIs('admin.category'))
                    <form action="{{ route('admin.category.search') }}" method="GET" class="form-inline float-sm-right bg--white">
                        <div class="input-group has_append">
                            <input type="text" name="search" class="form-control" placeholder="@lang('Category Name')" value="{{ $search ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                @else
                    <form action="{{ route('admin.category.search') }}" method="GET" class="form-inline float-sm-right bg--white">
                        <div class="input-group has_append">
                            <input type="text" name="search" class="form-control" placeholder="@lang('Category Name')" value="{{ $search ?? '' }}">
                            <div class="input-group-append">
                                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endpush
@endsection

@push('script')
<script>
    'use strict';

    (function ($) {
        $('.subcategories').tagEditor({
            forceLowercase: false,
        });
        $('.addBtn').on('click', function () {
            var modal = $('#addModal');
            modal.modal('show');
        });

        $('.updateBtn').on('click', function () {
            var modal = $('#updateBtn');

            var resourse = $(this).data('resourse');

            var route = $(this).data('route');
            $('.name').val(resourse.name);
            $('.price').val(resourse.price);
            $('.upsubcategories').removeClass('sss'+resourse.id)
            $('.upsubcategories').addClass('sss'+resourse.id)
            $('.sss'+resourse.id).tagEditor({
                initialTags: resourse.subcategories != null ? resourse.subcategories.split(',') : null,
                forceLowercase: false,
            });
            $('.edit-route').attr('action',route);

        });

        $('.activateBtn').on('click', function () {
                var modal = $('#activateModal');
                modal.find('input[name=id]').val($(this).data('id'));
            });

            $('.deactivateBtn').on('click', function () {
                var modal = $('#deactivateModal');
                modal.find('input[name=id]').val($(this).data('id'));
            });
    })(jQuery);
</script>
@endpush
