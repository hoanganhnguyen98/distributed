@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('custom_css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/kitchen-manager-home.css') }}">
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <div class="row">
            <div class="col-auto mr-auto">    
                <i class="fas fa-fan fa-spin mr-2"></i>{{ Auth::user()->area }}
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn btn-primary">
                    <a  href="{{ route('home') }}"><i class="fas fa-sync-alt fa-spin"></i></a>
                </button>
            </div>
        </div>
    </div>

    <div class="card-body row">
        <div class="col">
            <table class="table table-bordered">
                <thead class="text-success">
                    <tr>
                        <th colspan="4" scope="col" class="text-center text-uppercase">
                            New Order
                        </th>
                    </tr>
                    <tr>
                        <th scope="col">Food Name</th>
                        <th scope="col" class="text-center">Number</th>
                        <th colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order_news as $order)
                    <tr>
                        <td>{{ $order['food_name'] }}</td>
                        <td class="text-center">{{ $order['number'] }}</td>
                        <td>Click to Prepare</td>
                        <td>Click to Cancel</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col">
            <table class="table table-bordered">
                <thead class="text-danger">
                    <tr>
                        <th colspan="3" scope="col" class="text-center text-uppercase">
                            Preparing order
                        </th>
                    </tr>
                    <tr>
                        <th scope="col">Food Name</th>
                        <th scope="col" class="text-center">Number</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order_prepares as $order)
                    <tr>
                        <td>{{ $order['food_name'] }}</td>
                        <td class="text-center">{{ $order['number'] }}</td>
                        <td>Click to Done</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('custom_js')

@endsection
