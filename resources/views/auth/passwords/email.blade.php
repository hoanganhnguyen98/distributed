@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.reset_password.title') }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-uppercase text-primary font-weight-bold">
                    {{ trans('messages.reset_password.header') }}
                </div>

                <div class="card-body">
                    @if(Session::has('success'))
                        @include('layouts.toast.success')
                    @endif

                    @if($errors->any())
                        @include('layouts.toast.errors')
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.reset_password.email_address') }}
                            </label>

                            <div class="col-md-6">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary font-weight-bold">
                                    {{ trans('messages.reset_password.button') }}
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
