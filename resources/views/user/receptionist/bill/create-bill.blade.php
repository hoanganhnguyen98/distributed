@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.create.bill.title') }}
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.create.bill.header') }}
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
        
        <form method="POST" action="{{ route('create-bill') }}">
            @csrf

            <!-- Table number -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.bill.table') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control" value="3" name="table" readonly>
                </div>
            </div>

            <!-- Customer name -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.bill.name') }}
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

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.bill.address') }}
                </label>

                <div class="col-md-2">
                    <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" name="address" placeholder="{{ trans('messages.create.bill.street') }}" required>

                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" name="address" placeholder="{{ trans('messages.create.bill.district') }}" required>

                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" name="address" placeholder="{{ trans('messages.create.bill.city') }}" required>

                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Customer phone -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.bill.phone') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" name="phone" required>

                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.bill.email') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" required>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-3">
                    <button type="submit" class="btn btn-primary">
                        {{ trans('messages.create.bill.button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
