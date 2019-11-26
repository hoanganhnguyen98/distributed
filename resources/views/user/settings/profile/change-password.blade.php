@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.change-password.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.change-password.header') }}
    </div>

    <div class="card-body">
        @if(Session::has('success'))
            <div class="alert alert-success"><i class="fas fa-check"></i>
                {!! Session::get('success') !!}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><i class="fa fa-exclamation-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('change-password') }}">
            @csrf

            <!-- Email -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.change-password.email') }}
                </label>

                <div class="col-md-6">
                    <input type="email" class="form-control" value="{{ $email }}" name="email" readonly>
                </div>
            </div>

            <!-- Current password -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.change-password.old_password') }}
                </label>

                <div class="col-md-6">
                    <input type="password" class="form-control @error('old_password') is-invalid @enderror" name="old_password" required>

                    @error('old_password')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <!-- New password -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.change-password.new_password') }}
                </label>

                <div class="col-md-6">
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>

                    @error('new_password')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <!-- Comfirm new_password -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.change-password.repassword') }}
                </label>

                <div class="col-md-6">
                    <input type="password" class="form-control @error('repassword') is-invalid @enderror" name="repassword" required>

                    @error('repassword')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-3">
                    <button type="submit" class="btn btn-primary">
                        {{ trans('messages.change-password.button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
