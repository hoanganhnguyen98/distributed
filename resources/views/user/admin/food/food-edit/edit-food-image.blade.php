<div class="card card-body">
    <img src="{{ $food->image }}" class="img-thumbnail">

    <form method="POST" action="{{ route('food-change-image') }}" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="id" value="{{ $food->id }}">
        <div class="form-group row">
            <input type="file" class="form-control-file @error('image') is-invalid @enderror" accept="image/jpeg, image/jpg, image/png" name="image">

            @error('image')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
        <div class="form-group row mb-0">
            <button type="submit" class="btn btn-success font-weight-bold">
                {{ trans('messages.list.account.button.change_image') }}
            </button>
        </div>
    </form>
</div>