@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.create.food.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.create.food.header') }}
    </div>

    <div class="card-body">
        @if(Session::has('success'))
            @include('layouts.toast.success')
        @endif

        @if($errors->any())
            @include('layouts.toast.errors')
        @endif
        
        <form method="POST" action="{{ route('create-food') }}" enctype="multipart/form-data" id="food">
            @csrf

            <!-- Name -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.food.name') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" required>

                    @error('name')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <!-- Image -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.food.image') }}
                </label>

                <div class="col-md-6">
                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" accept="image/jpeg, image/jpg, image/png" name="image" required>

                    @error('image')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <!-- Type -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.food.type') }}
                </label>

                <div class="col-md-6">
                    <select class="btn btn-outline border border-success" name="type" form="food">
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
                    {{ trans('messages.create.food.source') }}
                </label>
                <div class="col-md-6">
                    <select class="btn btn-outline border border-success" name="source" form="food">
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
                    {{ trans('messages.create.food.material') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('material') is-invalid @enderror" value="{{ old('material') }}" name="material" required>

                    @error('material')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <!-- Price -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.food.price') }}
                </label>

                <div class="col-md-3">
                    <input type="text" class="form-control @error('vnd_price') is-invalid @enderror" value="{{ old('vnd_price') }}" name="vnd_price" required placeholder="VND">

                    @error('vnd_price')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control @error('usd_price') is-invalid @enderror" value="{{ old('usd_price') }}" name="usd_price" required placeholder="USD">

                    @error('usd_price')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-3">
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        {{ trans('messages.create.food.button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    document.getElementById('foodSidebar').classList.add('show');
</script>
@endsection
