@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.list.bill.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.list.bill.header') }}
    </div>

    <div class="card-body">
        {!! $bills->appends(\Request::except('page'))->render() !!}
        <table class="table">
            <thead>
                <tr class="text-primary">
                    <th scope="col">@sortablelink('id', trans('messages.list.bill.id'))</th>
                    <th scope="col">@sortablelink('table_id', trans('messages.list.bill.table'))</th>
                    <th scope="col">@sortablelink('customner_name', trans('messages.list.bill.name'))</th>
                    <th></th>
                </tr>
                <tr>
                    <th>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-filter"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="idInput" onkeyup="searchID()">
                        </div>
                    </th>
                    <th>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-filter"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="tableInput" onkeyup="searchTable()">
                        </div>
                    </th>
                    <th>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-filter"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="nameInput" onkeyup="searchName()">
                        </div>
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="currentBill">
                @foreach($bills as $bill)
                <tr>
                    <td>{{ $bill->id }}</td>
                    <td>{{ $bill->table_id }}</td>
                    <td>{{ $bill->customer_name }}</td>
                    <td>
                        @if($bill->status == 'new')
                        <a href="#" class="badge badge-pill badge-info text-uppercase">
                            {{ trans('messages.list.bill.button.detail') }}
                        </a>
                        @elseif($bill->status == 'done')
                        <a href="#" class="badge badge-pill badge-danger text-uppercase">
                            {{ trans('messages.list.bill.button.detail') }}
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {!! $bills->appends(\Request::except('page'))->render() !!}
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('js/bill-list.js') }}"></script>
@endsection
