@php
    $counter_elements = getContent('counter.element',false);
@endphp
@if (count($counter_elements )>0)
    <section class="statistics-section pt-80">
        <div class="container">
            <div class="statistics-area section--bg bg-overlay-black bg_img" data-background="{{asset($activeTemplateTrue.'images/banner.svg')}}">
                <div class="row justify-content-center mb-50-none">
                    @foreach($counter_elements as $item)
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 mb-30">
                            <div class="statistics-item text-center">
                                <div class="statistics-content">
                                    <div class="odo-area">
                                        <h3 class="odo-title odometer" data-odometer-final="{{__(@$item->data_values->counter_digit )}}">0</h3>
                                    </div>
                                </div>
                                <p>{{__(@$item->data_values->sub_title )}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
