@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.pay.title') }}
@endsection

@section('custom_css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pay-bill.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">  
        <div class="row">
            <div class="col-auto mr-auto">
                {{ trans('messages.pay.header') }}
            </div>
            <div class="col-auto">
                <button type="button" id="editBill" class="btn btn-outline-primary font-weight-bold">
                    {{ trans('messages.pay.edit') }}
                </button>
                <button type="button" id="cancelEditButton" class="btn btn-outline-secondary font-weight-bold">
                    {{ trans('messages.pay.cancel') }}
                </button>
            </div>
        </div>
    </div>

    @if(Session::has('success'))
        @include('layouts.toast.success')
    @endif

    @if($errors->any())
        @include('layouts.toast.errors')
    @endif
    <div class="card-body">
        <div class="row">
            <!-- Show bill information -->
            <div class="col">
                @include('user.receptionist.bill.pay-bill.bill-info')
            </div>
            <!-- Show all bill detail -->
            <div class="col">
                 @include('user.receptionist.bill.pay-bill.bill-detail')
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('js/pay-bill.js') }}"></script>
@endsection
