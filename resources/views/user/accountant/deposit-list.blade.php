@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('custom_css')
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <div class="row">
            <div class="col-auto mr-auto">    
                <i class="fas fa-fan fa-spin mr-2"></i>Quyet toan list
            </div>

            <div class="col-auto">
                <input type="text" id="userIDInput" class="form-control">
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table">
            <thead>
                <tr class="text-primary">
                    <th scope="col">User ID</th>
                    <th scope="col">VND</th>
                    <th scope="col">USD</th>
                    <th scope="col">Get time</th>
                    <th scope="col">Last updated time</th>
                </tr>
            </thead>
            <tbody id="todayDeposit">
                @foreach($deposits as $deposit)
                <tr class="text-primary font-weight-bold">
                    <td>{{ $deposit->user_id }}</td>
                    <td>{{ $deposit->vnd }}</td>
                    <td>{{ $deposit->usd }}</td>
                    <td>{{ $deposit->created_at }}</td>
                    <td>{{ $deposit->updated_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('js/deposit-list.js') }}"></script>
@endsection
