@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.reset_form.title') }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-uppercase text-primary font-weight-bold">
                    {{ trans('messages.reset_form.header') }}
                </div>

                <div class="card-body">
                    @if(Session::has('success'))
                        @include('layouts.toast.success')
                    @endif

                    @if($errors->any())
                        @include('layouts.toast.errors')
                    @endif
                    
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.reset_form.email') }}
                            </label>

                            <div class="col-md-6">
                                <input class="form-control" name="email" value="{{ $email }}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.reset_form.password') }}
                            </label>

                            <div class="col-md-6">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                <small class="text-primary font-weight-bold">
                                    {{ trans('messages.suggest-password') }}
                                </small>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.reset_form.repassword') }}
                            </label>

                            <div class="col-md-6">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="repassword" required>

                                @error('repassword')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary font-weight-bold">
                                    {{ trans('messages.reset_form.button') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
