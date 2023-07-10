@php
	$testimonial_content = getContent('testimonial.content',true);
	$testimonial_elements = getContent('testimonial.element',false);
@endphp

@if (count($testimonial_elements)>0)
    <section class="client-section pt-80">
        <div class="container">
            <div class="row justify-content-center ml-b-30">
                <div class="col-lg-12">
                    <div class="client-area section--bg bg-overlay-black bg_img" data-background="{{asset($activeTemplateTrue.'images/banner.svg')}}">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 text-center">
                                <div class="section-header white">
                                    <h2 class="section-title">{{__(@$testimonial_content->data_values->heading)}}</h2>
                                    <span class="title-border"></span>
                                </div>
                            </div>
                        </div>
                        <div class="client-slider">
                            <div class="swiper-wrapper">
                                @foreach ($testimonial_elements as $item)
                                <div class="swiper-slide">
                                    <div class="client-item d-flex flex-wrap align-items-center">
                                        <div class="client-thumb">
                                            <img src="{{getImage('assets/images/frontend/testimonial/'. @$item->data_values->image,'170x170')}}" alt="@lang('client-image')">
                                        </div>
                                        <div class="client-content">
                                            <p>{{__(@$item->data_values->quote)}}</p>
                                            <div class="client-thumb-content">
                                                <h3 class="title">{{__(@$item->data_values->name)}}</h3>
                                            </div>
                                        </div>
                                        <div class="first-shadow"></div>
                                        <div class="second-shadow"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

