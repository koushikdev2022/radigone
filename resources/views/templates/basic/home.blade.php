@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
	$banner_content = getContent('banner.content',true);
@endphp

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="banner-section section--bg bg-overlay-black bg_img" data-background="{{asset($activeTemplateTrue.'images/banner.svg')}}">
    <div class="banner-bg-shape bg_img" data-background="{{asset($activeTemplateTrue.'images/banner-shape.png')}}"></div>
    <div class="container">
        <div class="row justify-content-center align-items-center mb-30-none">
            <div class="col-xl-12 mb-30">
                <div class="banner-content text-center">
                    <h3 class="sub-title text--base">{{__(@$banner_content->data_values->title)}}</h3>
                    <h1 class="title text-white">{{__(@$banner_content->data_values->heading)}}</h1>
                    <p class="text-white">{{__(@$banner_content->data_values->sub_title)}}</p>
                    <div class="banner-btn">
                        <a href="{{@$banner_content->data_values->button_url}}" class="btn--base">{{__(@$banner_content->data_values->button_name)}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

    @if($sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection
