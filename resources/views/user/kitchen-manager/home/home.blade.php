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
                            {{ trans('messages.home.kitchen.new_order') }}
                        </th>
                    </tr>
                    <tr>
                        <th scope="col">{{ trans('messages.home.kitchen.food_name') }}</th>
                        <th scope="col" class="text-center">{{ trans('messages.home.kitchen.number') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order_news as $order)
                    <tr>
                        <td>{{ $order['food_name'] }}</td>
                        <td class="text-center">{{ $order['number'] }}</td>
                        <td class="text-center">
                            <a href="prepare-order-{{ $order['id'] }}" class="text-uppercase badge badge-pill badge-info">
                                {{ trans('messages.home.kitchen.prepare') }}
                            </a>
                        </td>
                        <!-- <td class="text-center">
                            <a href="" class="text-uppercase badge badge-pill badge-danger" data-toggle="modal" data-target="#deleteOrderModal-{{ $order['id'] }}">
                                {{ trans('messages.home.kitchen.delete') }}
                            </a>
                            <div class="modal fade" id="deleteOrderModal-{{ $order['id'] }}" tabindex="-1" role="dialog" aria-labelledby="deleteOrderModal-{{ $order['id'] }}Label" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-uppercase text-danger" id="deleteOrderModal-{{ $order['id'] }}Label">
                                                {{ trans('messages.home.kitchen.delete_modal') }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                                                {{ trans('messages.home.kitchen.cancel') }}
                                            </button>
                                            <button type="button" class="btn btn-danger font-weight-bold">
                                                <a href="delete-order-{{ $order['id'] }}" >
                                                    {{ trans('messages.home.kitchen.delete') }}
                                                </a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td> -->
                    </tr>
                    @endforeach
                    <tr id="addOrder">
                        
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col">
            <table class="table table-bordered">
                <thead class="text-danger">
                    <tr>
                        <th colspan="4" scope="col" class="text-center text-uppercase">
                            {{ trans('messages.home.kitchen.preparing_order') }}
                        </th>
                    </tr>
                    <tr>
                        <th scope="col">{{ trans('messages.home.kitchen.table') }}</th>
                        <th scope="col">{{ trans('messages.home.kitchen.food_name') }}</th>
                        <th scope="col" class="text-center">{{ trans('messages.home.kitchen.number') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order_prepares as $order)
                    <tr>
                        <td>{{ $order['table'] }}</td>
                        <td>{{ $order['food_name'] }}</td>
                        <td class="text-center">{{ $order['number'] }}</td>
                        <td class="text-center">
                            <a href="confirm-order-{{ $order['id'] }}" class="text-uppercase badge badge-pill badge-success">
                                {{ trans('messages.home.kitchen.confirm') }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // init an Pusher object with Pusher app key
        var pusher = new Pusher('6063520d51edaa14b9cf', {
            cluster: 'ap1',
            encrypted: true
        });

        // register a channel created in event
        var channel = pusher.subscribe('channel-display-billdetail-kitchen');

        // bind a function with event
        channel.bind('App\\Events\\DislayBillDetailInKitchenManagerEvent', addOrder);
    });

    // function to change status of table
    function addOrder(data) {
        console.log(data.food_name);
        var tdName = $("<td></td>");
        tdName.html(data.food_name);
        $("#addOrder").append(tdName);

        var tdNumber = $("<td class='text-center'></td>");
        tdNumber.html(data.number);
        $("#addOrder").append(tdNumber);

        var tdId = $("<td class='text-center'><a class='text-uppercase badge badge-pill badge-info'>{{ trans('messages.home.kitchen.prepare') }}</a></td>");
        tdId.find("a:eq(0)").attr("href","prepare-order-"+data.order_id);
        $("#addOrder").append(tdId);
    }
</script>
@endsection
