@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.list.item.title') }}
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.list.item.header') }}
    </div>

    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">{{ trans('messages.list.item.name') }}</th>
                    <th scope="col">{{ trans('messages.list.item.address') }}</th>
                    <th scope="col">{{ trans('messages.list.item.phone') }}</th>
                    <th scope="col">{{ trans('messages.list.item.role') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
                    <td>áda</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
