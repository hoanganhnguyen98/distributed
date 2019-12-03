<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="3"></li>
        <li data-target="#carouselExampleCaptions" data-slide-to="4"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ asset('img/slide/img01.jpg') }}" class="d-block w-100" height="600px" width="1000px">
            <div class="carousel-caption d-none d-md-block btn btn-outline-primary">
                <h5>{{ trans('home.slide.h1') }}</h5>
                <p>{{ trans('home.slide.p1') }}</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('img/slide/img02.jpg') }}" class="d-block w-100" height="600px" width="1000px">
            <div class="carousel-caption d-none d-md-block btn btn-outline-primary">
                <h5>{{ trans('home.slide.h2') }}</h5>
                <p>{{ trans('home.slide.p2') }}</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('img/slide/img03.jpg') }}" class="d-block w-100" height="600px" width="1000px">
            <div class="carousel-caption d-none d-md-block btn btn-outline-primary">
                <h5>{{ trans('home.slide.h3') }}</h5>
                <p>{{ trans('home.slide.p3') }}</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('img/slide/img04.jpg') }}" class="d-block w-100" height="600px" width="1000px">
            <div class="carousel-caption d-none d-md-block btn btn-outline-primary">
                <h5>{{ trans('home.slide.h4') }}</h5>
                <p>{{ trans('home.slide.p4') }}</p>
            </div>
        </div>
        <div class="carousel-item">
            <img src="{{ asset('img/slide/img05.jpg') }}" class="d-block w-100" height="600px" width="1000px">
            <div class="carousel-caption d-none d-md-block btn btn-outline-primary">
                <h5>{{ trans('home.slide.h5') }}</h5>
                <p>{{ trans('home.slide.p5') }}</p>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
