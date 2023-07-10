@php
	$social_icon_elements = getContent('social_icon.element',false);
@endphp
<footer class="footer-section pt-80 section--bg bg-overlay-black bg_img" data-background="{{asset($activeTemplateTrue.'images/banner.svg')}}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 text-center">
                <div class="footer-widget widget-menu">
                    <div class="footer-logo mb-20">
                        <a href="{{route('home')}}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('logo')"></a>
                    </div>
                    <p>{{__(@$footer_content->data_values->description)}}</p>

                    @if (count($social_icon_elements)>0)
                        <div class="social-area">
                            <ul class="footer-social">
                                @foreach ($social_icon_elements as $item)
                                    <li><a href="{{@$item->data_values->url}}">@php echo @$item->data_values->social_icon @endphp</a></li>
                                @endforeach
                            </ul>
                        </div>

                    @endif
                </div>
            </div>
        </div>
        <div class="footer-bottom-area">
            <div class="copyright-area d-flex flex-wrap align-items-center justify-content-between mb-10-none">
                <div class="copyright mb-10">
                    <p>{{__(@$footer_content->data_values->copyright)}}</p>
                </div>
                <ul class="copyright-list mb-10">
                    <li><a href="{{route('home')}}">@lang('Home')</a></li>
                    @foreach($pages as $k => $data)
                        <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                    @endforeach
                    <li><a href="{{route('contact')}}">@lang('Contact')</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
