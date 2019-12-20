@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.create.account.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.create.account.header') }}
    </div>

    <div class="card-body">
        @if(Session::has('success'))
            @include('layouts.toast.success')
        @endif

        @if($errors->any())
            @include('layouts.toast.errors')
        @endif
        
        <form method="POST" action="{{ route('create-account') }}" enctype="multipart/form-data" id="account">
            @csrf

            <!-- Full name -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.name') }}
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
                    {{ trans('messages.create.account.image') }}
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

            <!-- Address -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.address') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" name="address" required>

                    @error('address')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <!-- Phone -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.phone') }}
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

            <!-- Area -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.area') }}
                </label>

                <div class="col-md-6">
                    <select class="btn btn-outline border border-success text-uppercase" name="area" form="account">
                        <option value="shuriken">
                            Shuriken
                        </option>
                        <option value="ninjutsu">
                            Ninjutsu
                        </option>
                        <option value="ninjago">
                            Ninjago
                        </option>
                    </select>
                    <small class="text-success">
                        <i class="fas fa-long-arrow-alt-left mr-2"></i>
                        {{ trans('messages.suggest') }}
                    </small>
                </div>
            </div>

            <!-- Role -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.role') }}
                </label>

                <div class="col-md-6">
                    <select class="btn btn-outline border border-success" name="role" form="account">
                        <option value="employee">
                            {{ trans('messages.role.employee') }}
                        </option>
                        <option value="receptionist">
                            {{ trans('messages.role.receptionist') }}
                        </option>
                        <option value="waiter">
                            {{ trans('messages.role.waiter') }}
                        </option>
                        <option value="kitchen_manager">
                            {{ trans('messages.role.kitchen_manager') }}
                        </option>
                        <option value="accountant">
                            {{ trans('messages.role.accountant') }}
                        </option>
                        <option value="admin">
                            {{ trans('messages.role.admin') }}
                        </option>
                    </select>
                    <small class="text-success">
                        <i class="fas fa-long-arrow-alt-left mr-2"></i>
                        {{ trans('messages.suggest') }}
                    </small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.email') }}
                </label>

                <div class="col-md-6">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" required>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-3">
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        {{ trans('messages.create.account.button') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    document.getElementById('accountSidebar').classList.add('show');
</script>
@endsection
