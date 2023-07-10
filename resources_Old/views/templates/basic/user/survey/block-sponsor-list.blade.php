@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="survey-area mt-30">
        <div class="panel-card-header section--bg text-white">
            <div class="panel-card-title"><i class="lar la-question-circle"></i> @lang('Block List')</div>
        </div>
        <div class="panel-card-body">
            <div class="row justify-content-center mb-30-none">
                @forelse ($surveyors as $item)
                    @php
                        $total_views = $item->total_views;
                        $views = !is_null($item->users) ? count($item->users) : 0;
                        $res_views = $total_views - $views;
                    @endphp



                    <div class="col-xl-3 col-md-6 col-sm-8 mb-30">
                        <div class="survey-list-item">
                            <div class="survey-list-body">
                                <div class="survey-list-thumb">
                                    <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. $item->surveyor->image,imagePath()['profile']['user']['size']) }}" alt="survey">
                                </div>
                                <div class="survey-list-content">
                                    <div class="survey-list-header d-flex flex-wrap justify-content-between">
                                        <h3 class="title">{{__($item->surveyor->fullname)}}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="survey-list-footer bg--success text-center">
                                <div class="survey-btn">
                                    {{--                                <a href="{{route('user.survey.ad.view',$item->id)}}" class="text-white text-center">@lang('Start Survey')</a>--}}
                                    <a href="{{route('user.unblock_surveyor', $item->surveyor_id)}}" class="text-white text-center" >@lang('Unblock')</a>
                                </div>
                            </div>
                        </div>
                    </div>


                @empty
                    <p class="mb-4">{{__($empty_message)}}</p>
                @endforelse
            </div>
        </div>
        <div class="panel-card-footer-area d-flex flex-wrap align-items-center justify-content-center">
            <div class="panel-card-footer-right">
                <nav>
                    {{$surveyors->links()}}
                </nav>
            </div>
        </div>
    </div>
@endsection
