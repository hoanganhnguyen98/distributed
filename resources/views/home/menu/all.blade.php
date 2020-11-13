<div class="row" id="allFoodList">
    @foreach($foods as $food)
    <div class="col-3">
        <div class="row mt-2">
            <!-- Image -->
            <div class="col">
                <img src="{{ $food->image }}" class="img-thumbnail">
            </div>

            <!-- Description -->
            <div class="col">
                <p class="text-danger text-monospace font-weight-bold">{{ $food->name }}</p>
                <p>{{ $food->material }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>
