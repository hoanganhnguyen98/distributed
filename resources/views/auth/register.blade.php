@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.register') }}
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ trans('messages.register.header') }}</div>

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
                    
                    <form method="POST" action="{{ route('create') }}" id="role">
                        @csrf

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.register.name') }}
                            </label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ old('name') }}" name="name" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.register.address') }}
                            </label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ old('address') }}" name="address" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.register.phone') }}
                            </label>

                            <div class="col-md-6">
                                <input type="tel" class="form-control" value="{{ old('phone') }}" name="phone" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.register.role') }}
                            </label>

                            <div class="col-md-6">
                                <select class="btn btn-outline" name="role" form="role">
                                    <option value="waiter">
                                        {{ trans('messages.register.waiter') }}
                                    </option>
                                    <option value="kitchen_manager">
                                        {{ trans('messages.register.kitchen_manager') }}
                                    </option>
                                    <option value="admin">
                                        {{ trans('messages.register.admin') }}
                                    </option>
                                </select>
                                <small class="text-success">
                                    <i class="fas fa-long-arrow-alt-left mr-2"></i>click to choose
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.register.email') }}
                            </label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ old('email') }}" name="email" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.register.password') }}
                            </label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">
                                {{ trans('messages.register.repassword') }}
                            </label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="repassword" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('messages.register.button') }}
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

@section('custom_js')
@endsection
