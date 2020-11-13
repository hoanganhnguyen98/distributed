<!-- Navigation -->
<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
    <div class="media" href="#page-top">
        <img src="{{ asset('img/logo.jpg')}}" class="align-self-center mr-3 img-thumbnail" height="80" width="80">
        <div class="media-body">
            <h3 class="mt-3 text-white">{{ trans('home.ninja') }}</h3>
        </div>
    </div>

    <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><i class="fas fa-bars"></i></button>

    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item mx-0 mx-lg-1">
                <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#intro">
                    {{ trans('home.introduce.header') }}
                </a>
            </li>

            <li class="nav-item mx-0 mx-lg-1">
                <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#menu">
                    {{ trans('home.menu.header') }}
                </a>
            </li>

            <li class="nav-item mx-0 mx-lg-1">
                <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="#branch">
                    {{ trans('home.branch.header') }}
                </a>
            </li>

            <li class="nav-item mx-0 mx-lg-1 dropdown">
                <a id="switchLang" class="nav-link py-3 px-0 px-lg-5 rounded dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-language fa-lg mr-2"></i>EN-VN
                </a>

                <div class="dropdown-menu" aria-labelledby="switchLang">
                    <a class="dropdown-item" href="lang/en"><img src="{{asset('img/en.png')}}" width="30px" height="20x"> English</a>
                    <a class="dropdown-item" href="lang/vn"><img src="{{asset('img/vn.png')}}" width="30px" height="20x"> Tiếng Việt</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
