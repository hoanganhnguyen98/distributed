@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.list.account.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.sidebar.profile.detail') }}
    </div>

    <div class="card-body row">
        <!-- Account image -->
        <div class="col-3">
            <img src="{{ $user->image }}" class="img-thumbnail">
        </div>

        <!-- Account information -->
        <div class="col-9">
            <!-- Full name -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.name') }}
                </label>

                <div class="col-md-8">
                    <input class="form-control" value="{{ $user->name }}" readonly>
                </div>
            </div>

            <!-- Address -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.address') }}
                </label>

                <div class="col-md-8">
                    <input class="form-control" value="{{ $user->address }}" readonly>
                </div>
            </div>

            <!-- Phone -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.phone') }}
                </label>

                <div class="col-md-8">
                    <input class="form-control" value="{{ $user->phone }}"readonly>
                </div>
            </div>

            <!-- Area -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.area') }}
                </label>

                <div class="col-md-8">
                    <input class="form-control text-uppercase" value="{{ $user->area }}"readonly>
                </div>
            </div>

            <!-- Account -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.account.email') }}
                </label>

                <div class="col-md-8">
                    <input class="form-control" value="{{ $user->email }}" readonly>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    document.getElementById('profileSidebar').classList.add('show');
</script>
@endsection
