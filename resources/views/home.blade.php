@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <i class="fas fa-fan fa-spin mr-2"></i>ADMIN
    </div>

    <div class="card-body">
        HOMEPAGE
    </div>
</div>
@endsection
