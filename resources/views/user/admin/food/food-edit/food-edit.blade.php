@extends('layouts.app')

@section('htmlheader_title')
    {{ $food->name }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('food-list') }}">
                    <i class="fas fa-chevron-circle-left mr-2"></i>{{ trans('messages.list.food.header') }}
                </a></li>
                <li class="breadcrumb-item active text-danger" aria-current="page">
                    {{ $food->name }}
                </li>
            </ol>
        </nav>
    </div>

    @if(Session::has('success'))
        @include('layouts.toast.success')
    @endif

    @if($errors->any())
        @include('layouts.toast.errors')
    @endif
    <div class="card-body row">
        <!-- Change account image -->
        <div class="col-3">
            @include('user.admin.food.food-edit.edit-food-image')
        </div>

        <!-- Account information -->
        <div class="col-9">
            @include('user.admin.food.food-edit.edit-food-info')
        </div>
    </div>
</div>
@endsection
