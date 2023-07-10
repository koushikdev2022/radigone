@php
	$service_content = getContent('service.content',true);
	$service_elements = getContent('service.element',false);
@endphp
@if (count($service_elements)>0)
    <section class="service-section ptb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 text-center">
                    <div class="section-header">
                        <h2 class="section-title">{{__(@$service_content->data_values->heading)}}</h2>
                        <span class="title-border"></span>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mb-30-none">
                @foreach(@$service_elements as $item)
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 mb-30">
                    <div class="service-item text-center">
                        <div class="service-icon">
                            <img src="{{ getImage('assets/images/frontend/service/'. @$item->data_values->image,'100x100') }}" alt="@lang('service-image')">
                        </div>
                        <div class="service-content">
                            <h3 class="title">{{__(@$item->data_values->title)}}</h3>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
