<section class="page-section mr-2 ml-2" id="menu">
    <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
        {{ trans('home.menu.header') }}
    </h2>

    <div class="divider-custom">
        <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <i class="fas fa-fan fa-spin"></i>
            </div>
        <div class="divider-custom-line"></div>
    </div>

    <div class="container">
        <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-uppercase font-weight-bold" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">
                    {{ trans('home.menu.all') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase font-weight-bold" id="types-tab" data-toggle="tab" href="#types" role="tab" aria-controls="types" aria-selected="true">
                    {{ trans('home.menu.types') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase font-weight-bold" id="sources-tab" data-toggle="tab" href="#sources" role="tab" aria-controls="sources" aria-selected="false">
                    {{ trans('home.menu.sources') }}
                </a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                <div class="input-group mb-3 mt-3 container">
                    <div class="input-group-prepend">
                        <div class="input-group-text text-primary">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <input type="text" class="form-control border-primary" placeholder="{{ trans('home.menu.suggest') }}" id="foodInput">
                </div>
                @include('home.menu.all')
            </div>
            <div class="tab-pane fade" id="types" role="tabpanel" aria-labelledby="types-tab">
                @include('home.menu.menu-types')
            </div>
            <div class="tab-pane fade" id="sources" role="tabpanel" aria-labelledby="sources-tab">
                @include('home.menu.menu-sources')
            </div>
        </div>
    </div>
</section>
