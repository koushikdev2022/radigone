<header class="header-section">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container-fluid custom-container">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo site-title" href="{{route('home')}}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="@lang('site-logo')"></a>
                        <div class="language-select-area d-block d-lg-none ml-auto">
                            <select class="language-select langSel">
                                @foreach($language as $item)
                                    <option value="{{ __($item->code) }}" @if(session('lang') == $item->code) selected  @endif>{{ __($item->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="navbar-toggler ml-auto" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu ml-auto mr-auto">
                                <li><a href="{{route('home')}}">@lang('Introduction')</a></li>
                                @foreach($pages as $k => $data)
                                    <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                                @endforeach
                                <li><a href="{{route('contact')}}">@lang('Contact')</a></li>
                            </ul>
                            <div class="language-select-area d-none d-xl-block">
                                <select class="language-select langSel">
                                    @foreach($language as $item)
                                        <option value="{{ __($item->code) }}" @if(session('lang') == $item->code) selected  @endif>{{ __($item->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="header-action">
                                @auth
                                    <a href="{{route('user.home')}}" class="btn--base">@lang('Dashboard')</a>
                                @else
                                <div class="dropdown">
                                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         Register
                                      </button>
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                         <a href="{{route('user.register')}}" class="btn--base">@lang('User Register')</a>
                                        <a href="/surveyor/register" class="btn--base">@lang('Sponsor Register')</a>
                                        <a href="/agent/register" class="btn--base">@lang('Business Correspondent Register')</a>
                                      </div>
                                </div>
                                <div class="dropdown" style="margin-left: 13px;">
                                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         Login
                                      </button>
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a href="{{route('user.login')}}" class="btn--base">@lang('Login')</a>
                                        <a href="/surveyor" class="btn--base">@lang('Sponsor Login')</a>
                                        <a href="/agent" class="btn--base">@lang('Business Correspondent Login')</a>
                                      </div>
                                </div>


                                @endauth
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
