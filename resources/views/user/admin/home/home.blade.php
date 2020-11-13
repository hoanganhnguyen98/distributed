@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('custom_css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/receptionist-home.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <div class="row">
            <div class="col-auto mr-auto">    
                <i class="fas fa-fan fa-spin mr-2"></i>{{ $area }}
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-outline-success">{{ $ready }}</button>
                <button type="button" class="btn btn-outline-danger">{{ $run }}</button>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row table">
            <div class="col-md-3 table2">
                @foreach($table2s as $table2)
                <ul>
                    @include('user.admin.home.table2')
                </ul>
                @endforeach
            </div>

            <div class="col-md-3 ml-md-auto table4">
                @foreach($table4s as $table4)
                <ul>
                     @include('user.admin.home.table4')
                </ul>
                @endforeach
            </div>

            <div class="col-md-3 ml-md-auto table10">
                @foreach($table10_1s as $table10)
                <ul>
                     @include('user.admin.home.table10')
                </ul>
                @endforeach
            </div>
            
            <div class="col-md-3 ml-md-auto table10">
                @foreach($table10_2s as $table10)
                <ul>
                     @include('user.admin.home.table10')
                </ul>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
