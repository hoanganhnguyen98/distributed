@extends('layouts.app')

@section('htmlheader_title')
    {{-- {{ trans('messages.login.title') }} --}}
    DSD 08
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-uppercase text-primary font-weight-bold">
                    {{-- {{ trans('messages.login.header') }} --}}
                    BÀI TẬP LỚN MÔN HỌC
                </div>

                {{-- <div class="card-body"> --}}
                    {{-- @if(Session::has('success'))
                        @include('layouts.toast.success')
                    @endif

                    @if($errors->any())
                        @include('layouts.toast.errors')
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.login.email') }}
                            </label>

                            <div class="col-md-6">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" required>

                                @error('email')
                                    <span class="invalid-feedback" role="alert"></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.login.password') }}
                            </label>

                            <div class="col-md-6">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>

                                @error('password')
                                    <span class="invalid-feedback" role="alert"></span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <a class="btn btn-link" href="/password/reset">
                                    <i class="fas fa-question-circle mr-1"></i>{{ trans('messages.login.forget') }}
                                </a>
                            </div>
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary font-weight-bold">
                                    {{ trans('messages.login.button') }}
                                </button>
                            </div>
                        </div>
                    </form> --}}
                {{-- </div> --}}
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
