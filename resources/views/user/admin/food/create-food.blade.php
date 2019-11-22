@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.create.food.title') }}
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.create.food.header') }}
    </div>

    <div class="card-body">
        @if(Session::has('success'))
            <div class="alert alert-success"><i class="fas fa-check"></i>
                {!! Session::get('success') !!}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="list-style-type: none;">
                    @foreach ($errors->all() as $error)
                        <li><i class="fa fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('create-food') }}" enctype="multipart/form-data">
            @csrf

            <!-- Name -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.food.name') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" required>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
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
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.food.type') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('type') is-invalid @enderror" value="{{ old('type') }}" name="type" required>

                    @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.food.source') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('source') is-invalid @enderror" value="{{ old('source') }}" name="source" required>

                    @error('source')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.food.material') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('material') is-invalid @enderror" value="{{ old('material') }}" name="material" required>

                    @error('material')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.food.price') }}
                </label>

                <div class="col-md-3">
                    <input type="text" class="form-control @error('vnd_price') is-invalid @enderror" value="{{ old('vnd_price') }}" name="vnd_price" required placeholder="VND">

                    @error('vnd_price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control @error('usa_price') is-invalid @enderror" value="{{ old('usa_price') }}" name="usa_price" required placeholder="USA">

                    @error('usa_price')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ trans('messages.create.food.button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
