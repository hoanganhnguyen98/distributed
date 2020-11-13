@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.list.account.title') }}
@endsection

@section('custom_css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/account-detail.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <div class="row">
            <div class="col-auto mr-auto">
                <a href="{{ route('account-list') }}">
                    <i class="fas fa-chevron-circle-left mr-2"></i>
                    {{ trans('messages.list.account.button.back') }}
                </a>
            </div>
            <div class="col-auto">
                <button type="button" id="changeImage" class="btn btn-outline-success font-weight-bold">
                    {{ trans('messages.list.account.button.change_image') }}
                </button>
                <button type="button" id="cancelChangeButton" class="btn btn-outline-secondary font-weight-bold">
                    {{ trans('messages.list.account.button.cancel') }}
                </button>
                <button type="button" id="editInformation" class="btn btn-outline-primary font-weight-bold">
                    {{ trans('messages.list.account.button.edit') }}
                </button>
                <button type="button" id="cancelEditButton" class="btn btn-outline-secondary font-weight-bold">
                    {{ trans('messages.list.account.button.cancel') }}
                </button>
                <button type="button" id="deleteAccount" class="btn btn-outline-danger font-weight-bold" data-toggle="modal" data-target="#deleteAccountModal">
                    {{ trans('messages.list.account.button.delete') }}
                </button>
                @include('user.admin.account.account-detail.account-delete-modal')
            </div>
        </div>
    </div>

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

    <div class="card-body row">
        <!-- Change account image -->
        <div class="col-3">
            @include('user.admin.account.account-detail.account-image')
        </div>

        <!-- Account information -->
        <div class="col-9">
            @include('user.admin.account.account-detail.account-information')
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('js/account-detail.js') }}"></script>
@endsection
