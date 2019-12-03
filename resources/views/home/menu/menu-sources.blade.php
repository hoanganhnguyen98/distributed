<ul class="nav nav-tabs justify-content-center" id="sourceTab" role="tablist">
    @foreach($sources as $source)
        <li class="nav-item">
            <a class="nav-link font-weight-bold" id="sources-tab" data-toggle="tab" href="#sources{{ $source->id }}" role="tab" aria-controls="sources" aria-selected="false">
                {{ $source->name }}
            </a>
        </li>
    @endforeach
</ul>

<div class="tab-content" id="sourceTabContent">
    @foreach($sources as $source)
    <div class="tab-pane fade" id="sources{{ $source->id }}" role="tabpanel" aria-labelledby="sources-tab">
        <div class="row">
        @foreach($foods as $food)
            @if($food->source == $source->name)
            <div class="col-3">
                <div class="row mt-2">
                    <!-- Image -->
                    <div class="col">
                        <img src="{{ $food->image }}" class="img-thumbnail">
                    </div>

                    <!-- Description -->
                    <div class="col">
                        <p class="text-danger text-monospace font-weight-bold">{{ $food->name }}</p>
                        <p>{{ trans('messages.type.'.$food->type) }}</p>
                        <p>{{ $food->material }}</p>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
        </div>
    </div>
    @endforeach
</div>
