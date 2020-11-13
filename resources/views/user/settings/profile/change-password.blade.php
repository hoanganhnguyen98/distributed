@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.change_password.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.change_password.header') }}
    </div>

    <div class="card-body">
        @if(Session::has('success'))
            @include('layouts.toast.success')
        @endif

        @if($errors->any())
            @include('layouts.toast.errors')
        @endif
        
        <form method="POST" action="{{ route('change-password') }}">
            @csrf

            <!-- Email -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.change_password.email') }}
                </label>

                <div class="col-md-6">
                    <input type="email" class="form-control" value="{{ $email }}" name="email" readonly>
                </div>
            </div>

            <!-- Current password -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.change_password.old_password') }}
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
                    {{ trans('messages.change_password.new_password') }}
                </label>

                <div class="col-md-6">
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>
                    <small class="text-primary font-weight-bold">
                        {{ trans('messages.suggest_password') }}
                    </small>

                    @error('new_password')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <!-- Comfirm new_password -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.change_password.repassword') }}
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
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        {{ trans('messages.change_password.button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    document.getElementById('profileSidebar').classList.add('show');
</script>
@endsection
