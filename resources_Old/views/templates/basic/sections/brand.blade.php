@php
	$brand_content = getContent('brand.content',true);
	$brand_elements = getContent('brand.element',false);
@endphp

@if (count($brand_elements)>0)
    <section class="brand-section ptb-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7 text-center">
                    <div class="section-header">
                        <h2 class="section-title">{{__(@$brand_content->data_values->heading)}}</span></h2>
                        <span class="title-border"></span>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mb-30-none">
                @foreach ($brand_elements as $item)
                <div class="col-lg-2 col-md-6 col-sm-6 mb-30">
                    <div class="brand-item">
                        <img src="{{getImage('assets/images/frontend/brand/'. @$item->data_values->image,'185x65')}}" alt="@lang('brand-image')">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
