<!-- Navigation -->
<nav class="navbar navbar-expand-lg bg-light text-uppercase">
    <div class="container">
        <img src="{{asset('img/logo.jpg')}}" height="50" width="50" alt="Small logo">

        <ul class="navbar-nav ml-auto">
            <li class="nav-item mx-0 mx-lg-1">
                <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="{{ route('home') }}">
                <i class="fas fa-home mr-2"></i>{{ trans('messages.header.home') }}</a>
            </li>

            <li class="nav-item mx-0 mx-lg-1">
                <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#about">
                <i class="fas fa-utensils mr-2"></i>{{ trans('messages.header.about') }}</a>
            </li>

            <li class="nav-item mx-0 mx-lg-1">
                <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="/register">
                <i class="fas fa-user-plus mr-2"></i>{{ trans('messages.header.register') }}</a>
            </li>

            @if(Auth::check())
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="/logout">
                    <i class="fas fa-sign-in-alt mr-2"></i>{{ trans('messages.header.logout') }}</a>
                </li>
            @else
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="/login">
                    <i class="fas fa-sign-in-alt mr-2"></i>{{ trans('messages.header.login') }}</a>
                </li>
            @endif

            <li class="nav-item mx-0 mx-lg-1 dropdown">
                <a id="switchLang" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-language mr-2"></i>{{ trans('messages.header.language') }}
                </a>

                <div class="dropdown-menu" aria-labelledby="switchLang">
                    <a class="dropdown-item" href="lang/en"><img src="{{asset('img/en.png')}}" width="30px" height="20x"> English</a>
                    <a class="dropdown-item" href="lang/vn"><img src="{{asset('img/vn.png')}}" width="30px" height="20x"> Tiếng Việt</a>
                </div>
            </li>
        </ul> 
    </div>
</nav>

@if(Auth::check())
<nav class="navbar navbar-expand-lg bg-light text-uppercase">
    <div class="container">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item mx-0 mx-lg-1">
                <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="/register">
                <i class="fas fa-user-plus mr-2"></i>{{ trans('messages.header.register') }}</a>
            </li>
        </ul>
    </div>
</nav>
@endif
