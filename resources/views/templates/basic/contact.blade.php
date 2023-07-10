@extends($activeTemplate.'layouts.frontend')

@php
    $contact_us_content = getContent('contact_us.content',true);
    $contact_us_element = getContent('contact_us.element',false);
@endphp

@section('content')
@include($activeTemplate.'partials.breadcrumb')

<section class="contact-section pt-80">
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            <div class="col-lg-6 mb-30">
                <div class="contact-info-item-area mb-40-none">
                    <div class="contact-info-header mb-30">
                        <h3 class="header-title">{{__(@$contact_us_content->data_values->heading)}}</h3>
                        <p>{{__(@$contact_us_content->data_values->description)}}</p>
                    </div>

                    @foreach ($contact_us_element as $item)
                        <div class="contact-info-item d-flex flex-wrap align-items-center mb-40">
                            <div class="contact-info-icon">
                                @php echo @$item->data_values->icon @endphp
                            </div>
                            <div class="contact-info-content">
                                <h3 class="title">{{__(@$item->data_values->title)}}</h3>
                                <p>{{__(@$item->data_values->description)}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6 mb-30">
                <div class="contact-form-area">
                    <h3 class="title">{{__(@$contact_us_content->data_values->contact_form_heading)}}</h3>
                    <form class="contact-form" method="post" action="">
                        @csrf
                        <div class="row justify-content-center mb-10-none">
                            <div class="col-lg-12 form-group">
                                <input type="text" name="name" class="form-control" placeholder="@lang('Your Name')*" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-lg-12 form-group">
                                <input type="email" name="email" class="form-control" placeholder="@lang('Your Email')*" value="{{old('email')}}" required>
                            </div>
                            <div class="col-lg-12 form-group">
                                <input type="text" name="subject" class="form-control" placeholder="@lang('Your Subject')*" value="{{old('subject')}}" required>
                            </div>
                            <div class="col-lg-12 form-group">
                                <textarea class="form-control" name="message" placeholder="@lang('Your Message')*">{{old('message')}}</textarea>
                            </div>
                            <div class="col-lg-12 form-group">
                                <button type="submit" class="submit-btn">@lang('Send Message')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="map-section ptb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="maps"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <!-- main -->
    <script src="http://maps.google.com/maps/api/js?key={{@$contact_us_content->data_values->google_map_key}}"></script>
    <script src="{{asset($activeTemplateTrue.'js/map.js')}}"></script>
    <script>

    (function ($) {
        'use strict';
        var mapOptions = {
        center: new google.maps.LatLng({{@$contact_us_content->data_values->latitude}}, {{@$contact_us_content->data_values->longitude}}),
        zoom: 12,
        scrollwheel: true,
        backgroundColor: 'transparent',
        mapTypeControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
        var map = new google.maps.Map(document.getElementsByClassName("maps")[0],
            mapOptions);
        var myLatlng = new google.maps.LatLng({{@$contact_us_content->data_values->latitude}}, {{@$contact_us_content->data_values->longitude}});
        var focusplace = {lat: {{@$contact_us_content->data_values->latitude}} , lng: {{@$contact_us_content->data_values->longitude}} };
        var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
        })
    })(jQuery);
    </script>
@endpush



