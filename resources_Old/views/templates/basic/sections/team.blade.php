@php
    $team_content = getContent('team.content',true);
	$team_elements = getContent('team.element',false);
@endphp

@if (count($team_elements)>0)
    <section class="team-section pt-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 text-center">
                    <div class="section-header">
                        <h2 class="section-title">{{__(@$team_content->data_values->heading)}}</h2>
                        <span class="title-border"></span>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mb-30-none">
                @foreach ($team_elements as $item)
                    <div class="col-lg-3 col-md-6 col-sm-6 mb-30">
                        <div class="team-item text-center">
                            <div class="team-thumb">
                                <img src="{{getImage('assets/images/frontend/team/'. @$item->data_values->image,'270x330')}}" alt="@lang('team-image')">
                                <ul class="team-social">
                                    <li class="bg-primary"><a href="{{@$item->data_values->facebokk_link}}"><i class="fab fa-facebook-f"></i> @lang('Facebook')</a></li>
                                    <li class="bg--info"><a href="{{@$item->data_values->twitter_link}}"><i class="fab fa-twitter"></i> @lang('Twitter')</a></li>
                                    <li class="bg-danger"><a href="{{@$item->data_values->instagram_link}}"><i class="fab fa-instagram"></i> @lang('Instagram')</a></li>
                                </ul>
                            </div>
                            <div class="team-content">
                                <h3 class="title"><a href="#0">{{__(@$item->data_values->name)}}</a></h3>
                                <span class="sub-title">{{__(@$item->data_values->designation)}}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
