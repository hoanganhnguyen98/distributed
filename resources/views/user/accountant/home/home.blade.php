@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <div class="row">
            <div class="col-auto mr-auto">    
                <i class="fas fa-fan fa-spin mr-2"></i>{{ $area }}
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn btn-primary">
                    <a  href="{{ route('home') }}"><i class="fas fa-sync-alt fa-spin"></i></a>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if(Session::has('success'))
            @include('layouts.toast.success')
        @endif

        @if($errors->any())
            @include('layouts.toast.errors')
        @endif
        
        <form method="POST" action="{{ route('create-deposit') }}">
            @csrf

            <!-- VND -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.home.accountant.vnd') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('vnd') is-invalid @enderror" name="vnd" required>

                    @error('vnd')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <!-- USD -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.home.accountant.usd') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('usd') is-invalid @enderror" name="usd" required>

                    @error('usd')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <!-- User ID -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.home.accountant.email') }}
                </label>

                <div class="col-md-6">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" required>

                    @error('email')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.home.accountant.password') }}
                </label>

                <div class="col-md-6">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>

                    @error('password')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-3">
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        {{ trans('messages.home.accountant.confirm') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
