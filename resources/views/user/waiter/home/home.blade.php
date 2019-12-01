@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('custom_css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/waiter-home.css') }}">
@endsection

@section('content')
<div class="card">
    @if(Session::has('success'))
        <div class="alert alert-success"><i class="fas fa-check"></i>
            {!! Session::get('success') !!}
        </div>
    @endif

    <div class="card-header text-uppercase text-primary font-weight-bold">
        <div class="row">
            <div class="col-auto mr-auto">    
                <i class="fas fa-fan fa-spin mr-2"></i>{{ $area }}
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn btn-primary">
                    <a  href="{{ route('home') }}"><i class="fas fa-sync-alt fa-spin"></i></a>
                </button>
            </div>
        </div>
    </div>

    <!-- Search table to add bill details -->
    <div class="card-body">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text text-primary">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <input type="text" class="form-control" id="tableInput" onkeyup="searchTable()">
        </div>
    </div>

    <!-- Table list -->
    <div class="card-body table" id="waiterTableList">
        <!-- Table with size 2 -->
        @foreach($table2s as $table2)
            @include('user.waiter.home.table2')
        @endforeach

        <!-- Table with size 4 -->
        @foreach($table4s as $table4)
            @include('user.waiter.home.table4')
        @endforeach

        <!-- Table with size 10 -->
        @foreach($table10s as $table10)
            @include('user.waiter.home.table10')
        @endforeach
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('js/waiter-home.js') }}"></script>
@endsection
