@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.404.title') }}
@endsection

@section('content')
<div class="card">
	<div class="card-header text-uppercase text-primary font-weight-bold">
		{{ trans('messages.404.header') }}
	</div>

	<div class="card-body text-danger">
		<h4>{{ trans('messages.404.content') }}</h4>
	</div>
</div>
@endsection
