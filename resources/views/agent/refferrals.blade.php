@extends('agent.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('Sr.No')</th>
                                <th scope="col">@lang('Sponsor Name')</th>
                                <th scope="col">@lang('Email')</th>
                               
                            </tr>
                            </thead>
                            @forelse($user as $trx)
                                <tr>
                                    <td data-label="@lang('ID')">{{ $trx->id }}</td>
                                    <td data-label="@lang('Username')" class="font-weight-bold">{{ $trx->username }}</td>
                                    <td data-label="@lang('Email')" class="font-weight-bold">{{ $trx->email }}</td>
                                    

                                    
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse
                            
                            <tbody>
                            
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
               
            </div><!-- card end -->
        </div>
    </div>

@endsection




