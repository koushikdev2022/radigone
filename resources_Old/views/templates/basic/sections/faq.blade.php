@php
	$faq_content = getContent('faq.content',true);
	$faq_elements = getContent('faq.element',false);
@endphp

<section class="faq-section ptb-80">
    <div class="container">
        <div class="row justify-content-center align-items-center mb-30-none">
            <div class="col-xl-6 mb-30">
                <div class="faq-wrapper">
                    <h2 class="main-title">{{__(@$faq_content->data_values->heading)}}</h2>
                    @foreach ($faq_elements as $key=> $item)
                        <div class="faq-item @if($key==0) active open @endif">
                            <h3 class="faq-title"><span class="title">{{__(@$item->data_values->question)}}</span><span
                                    class="right-icon"></span></h3>
                            <div class="faq-content">
                                <p>{{__(@$item->data_values->answer)}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-xl-6 mb-30">
                <div class="faq-thumb">
                    <img src="{{ getImage('assets/images/frontend/faq/'. @$faq_content->data_values->image,'700x700') }}" alt="@lang('faq-image')">
                </div>
            </div>
        </div>
    </div>
</section>
