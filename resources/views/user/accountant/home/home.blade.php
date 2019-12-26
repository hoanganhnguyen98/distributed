@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <i class="fas fa-fan fa-spin mr-2"></i>{{ $area }}
    </div>

    <div class="card-body">
        {!! $bills->appends(\Request::except('page'))->render() !!}
        <table class="table">
            <thead>
                <tr class="text-primary">
                    <th scope="col">@sortablelink('id', trans('messages.list.bill.id'))</th>
                    <th scope="col">@sortablelink('table_id', trans('messages.list.bill.table'))</th>
                    <th scope="col">@sortablelink('customner_name', trans('messages.list.bill.name'))</th>
                    <th scope="col">@sortablelink('phone', trans('messages.list.bill.phone'))</th>
                    <th scope="col">@sortablelink('created_at', trans('messages.list.bill.booktime'))</th>
                    <th scope="col">@sortablelink('updated_at', trans('messages.list.bill.paytime'))</th>
                </tr>
                <tr>
                    <th>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-filter"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="idInput">
                        </div>
                    </th>
                    <th>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-filter"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="tableInput">
                        </div>
                    </th>
                    <th>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-filter"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="nameInput">
                        </div>
                    </th>
                    <th>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-filter"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="phoneInput">
                        </div>
                    </th>
                    <th>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-filter"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="bookInput">
                        </div>
                    </th>
                    <th>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text text-primary">
                                    <i class="fas fa-filter"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="payInput">
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody id="billTable">
                @foreach($bills as $bill)
                @if($bill->status == 'done')
                <tr class="alert alert-primary" role="alert">
                @elseif($bill->status == 'new')
                <tr>
                @endif
                    <td class="text-primary font-weight-bold">
                        <a href="https://res.cloudinary.com/ninjahh/image/upload/v1577359386/ninja_restaurant/invoices/{{ $bill->id }}.pdf" target="_blank">{{ $bill->id }}</a>
                    </td>
                    <td>{{ $bill->table_id }}</td>
                    <td>{{ $bill->customer_name }}</td>
                    <td>{{ $bill->phone }}</td>
                    <td>{{ $bill->created_at }}</td>
                    @if($bill->status == 'done')
                    <td>{{ $bill->updated_at }}</td>
                    @elseif($bill->status == 'new')
                    <td></td>
                    @endif
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
