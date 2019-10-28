@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('custom_css')
    <link href="#" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    	<h1>Đây là trang chủ</h1>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
