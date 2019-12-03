<ul class="nav nav-tabs justify-content-center" id="typeTab" role="tablist">
    @foreach($types as $type)
        <li class="nav-item">
            <a class="nav-link font-weight-bold" id="types-tab" data-toggle="tab" href="#types{{ $type->id }}" role="tab" aria-controls="types" aria-selected="false">
                {{ trans('messages.type.'.$type->name) }}
            </a>
        </li>
    @endforeach
</ul>

<div class="tab-content" id="typeTabContent">
    @foreach($types as $type)
    <div class="tab-pane fade" id="types{{ $type->id }}" role="tabpanel" aria-labelledby="types-tab">
        <div class="row">
        @foreach($foods as $food)
            @if($food->type == $type->name)
            <div class="col-3">
                <div class="row mt-2">
                    <!-- Image -->
                    <div class="col">
                        <img src="{{ $food->image }}" class="img-thumbnail">
                    </div>

                    <!-- Description -->
                    <div class="col">
                        <div class="row">
                            <p class="text-danger text-monospace font-weight-bold">{{ $food->name }}</p>
                        </div>

                        <div class="w-100"></div>

                        <div class="row">
                            <p >{{ $food->material }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
        </div>
    </div>
    @endforeach
</div>
