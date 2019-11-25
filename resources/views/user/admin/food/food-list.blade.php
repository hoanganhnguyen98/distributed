@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.list.food.title') }}
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.list.food.header') }}
    </div>

    <div class="card-body">
        {!! $foods->appends(\Request::except('page'))->render() !!}
        <table class="table">
            <thead>
                <tr class="text-primary">
                    <th scope="col">{{ trans('messages.list.food.image') }}</th>
                    <th scope="col">@sortablelink('name',trans('messages.list.food.name'))</th>
                    <th scope="col">@sortablelink('type',trans('messages.list.food.type'))</th>
                    <th scope="col">@sortablelink('source',trans('messages.list.food.source'))</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($foods as $food)
                <tr>
                    <td><img src="{{ $food->image }}" width="100px" height="100px"></td>
                    <td>{{ $food->name }}</td>
                    <td>{{ trans('messages.type.'.$food->type) }}</td>
                    <td>{{ $food->source }}</td>
                    <td class="text-uppercase">
                        <a href="#" class="badge badge-pill badge-info">
                            {{ trans('messages.list.food.button.detail') }}
                        </a>
                        <a href="#" class="badge badge-pill badge-primary">
                            {{ trans('messages.list.food.button.edit') }}
                        </a>
                        <a href="#" class="badge badge-pill badge-danger">
                            {{ trans('messages.list.food.button.delete') }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {!! $foods->appends(\Request::except('page'))->render() !!}
    </div>
</div>
@endsection
