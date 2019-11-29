<form method="POST" action="{{ route('food-edit-info') }}" id="food">
    @csrf

    <input type="hidden" name="id" value="{{ $food->id }}">
    <!-- Name -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.list.food.name') }}
        </label>

        <div class="col-md-6">
            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $food->name }}" name="name" required>

            @error('name')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
    </div>

    <!-- Type -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.list.food.type') }}
        </label>

        <div class="col-md-6">
            <select class="btn btn-outline border border-success" name="type" form="food">
                <option value="{{ $food->type }}" selected>
                    {{ trans('messages.type.'.$food->type) }}
                </option>
                @foreach($types as $type)
                <option value="{{ $type->name }}">
                    {{ trans('messages.type.'.$type->name) }}
                </option>
                @endforeach
            </select>
            <small class="text-success">
                <i class="fas fa-long-arrow-alt-left mr-2"></i>
                {{ trans('messages.suggest') }}
            </small>
        </div>
    </div>

    <!-- Source -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.list.food.source') }}
        </label>
        <div class="col-md-6">
            <select class="btn btn-outline border border-success" name="source" form="food">
                <option value="{{ $food->source }}" selected>
                    {{ $food->source }}
                </option>
                @foreach($sources as $source)
                <option value="{{ $source->name }}">
                    {{ $source->name }}
                </option>
                @endforeach
            </select>
            <small class="text-success">
                <i class="fas fa-long-arrow-alt-left mr-2"></i>
                {{ trans('messages.suggest') }}
            </small>
        </div>
    </div>

    <!-- Material -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.list.food.material') }}
        </label>

        <div class="col-md-6">
            <input type="text" class="form-control @error('material') is-invalid @enderror" value="{{ $food->material }}" name="material" required>

            @error('material')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
    </div>

    <!-- Price -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.list.food.price') }}
        </label>

        <div class="col-md-3">
            <input type="text" class="form-control @error('vnd_price') is-invalid @enderror" value="{{ $food->vnd_price }}" name="vnd_price" required placeholder="VND">

            @error('vnd_price')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>

        <div class="col-md-3">
            <input type="text" class="form-control @error('usd_price') is-invalid @enderror" value="{{ $food->usd_price }}" name="usd_price" required placeholder="USD">

            @error('usd_price')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-6 offset-md-3">
            <button type="submit" class="btn btn-primary font-weight-bold">
                {{ trans('messages.list.food.button.edit') }}
            </button>
        </div>
    </div>
</form>
