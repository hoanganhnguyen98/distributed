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
            @include('user.admin.food.food-edit.edit-food-image')
        </div>

        <!-- Account information -->
        <div class="col-9">
            @include('user.admin.food.food-edit.edit-food-info')
        </div>
    </div>
</div>
@endsection
