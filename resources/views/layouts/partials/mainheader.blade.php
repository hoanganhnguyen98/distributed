<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        @if(Auth::check())
        <button type="button" id="sidebarCollapse" class="btn btn-secondary">
            <i class="fas fa-align-left"></i>
        </button>
        @else
        <div class="media">
            <a href="/">
                <img src="{{ asset('img/logo.jpg') }}" class="mr-3" width="80" height="80">
            </a>

            <div class="media-body">
                <h3 class="mt-2 text-uppercase text-secondary font-weight-bold">
                    {{-- <a href="/">{{ trans('messages.header.title') }}</a> --}}
                    DSD08
                </h3>
                <h6 class="text-secondary font-weight-bold">
                    {{-- {{ trans('messages.header.subtitle') }} --}}
                    DISTRIBUTED SOFTWARE DEVELOPMENT
                </h6>
            </div>
        </div>
        @endif

        <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-align-justify"></i>
        </button>

        <div class="collapse navbar-collapse text-uppercase" id="navbarSupportedContent">
            {{-- <ul class="navbar-nav ml-auto">
                @if(Auth::check())
                    <li class="nav-item mx-0 mx-lg-1">
                        <a href="#" class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-in-alt fa-lg mr-2"></i>{{ trans('messages.header.logout') }}
                        </a>
                        <!-- Logout modal -->
                        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-primary" id="logoutModalLabel">
                                            {{ trans('messages.header.logout-modal') }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                                            {{ trans('messages.header.logout-cancel') }}
                                        </button>
                                        <button type="button" class="btn btn-primary font-weight-bold">
                                            <a href="/logout" >{{ trans('messages.header.logout') }}</a>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @else
                    <li class="nav-item mx-0 mx-lg-1">
                        <a class="nav-link py-3 px-0 px-lg-3 rounded js-scroll-trigger" href="/login">
                            <i class="fas fa-sign-in-alt fa-lg mr-2"></i>{{ trans('messages.header.login') }}
                        </a>
                    </li>
                @endif

                <li class="nav-item mx-0 mx-lg-1 dropdown">
                    <a id="switchLang" class="nav-link py-3 px-0 px-lg-5 rounded js-scroll-trigger dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-language fa-lg mr-2"></i>{{ trans('messages.header.language') }}
                    </a>

                    <div class="dropdown-menu" aria-labelledby="switchLang">
                        <a class="dropdown-item" href="lang/en"><img src="{{asset('img/en.png')}}" width="30px" height="20x"> English</a>
                        <a class="dropdown-item" href="lang/vn"><img src="{{asset('img/vn.png')}}" width="30px" height="20x"> Tiếng Việt</a>
                    </div>
                </li>
            </ul> --}}
        </div>
    </div>
</nav>
