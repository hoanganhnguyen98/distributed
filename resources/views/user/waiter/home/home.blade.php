@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('custom_css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/waiter-home.css') }}">
@endsection

@section('content')
<div class="card">
    @if(Session::has('success'))
        @include('layouts.toast.success')
    @endif

    <div class="card-header text-uppercase text-primary font-weight-bold">
        <div class="row">
            <div class="col-auto mr-auto">    
                <i class="fas fa-fan fa-spin mr-2"></i>{{ $area }}
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn btn-primary">
                    <a  href="{{ route('home') }}"><i class="fas fa-sync-alt fa-spin"></i></a>
                </button>
            </div>
        </div>
    </div>

    <!-- Search table to add bill details -->
    <div class="card-body">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text text-primary">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <input type="text" class="form-control" id="tableInput">
        </div>
    </div>

    <!-- Table list -->
    <div class="card-body table" id="waiterTableList">
        @foreach($tables as $table)
            <div class="row">
                <div class="col-7">
                    <img src="{{ asset('img/table-short.jpg') }}" alt="{{ $table->status }}">
                    <button type="button" class="btn btn-outline-primary font-weight-bold">
                        {{ $table->table_id }}
                    </button>
                </div>

                <button type="button" class="action btn btn-outline-primary font-weight-bold">
                    <a href="add-bill-detail-{{ $table->table_id }}">
                        <i class="fas fa-cart-plus mr-2"></i>{{ trans('messages.home.waiter.order') }}
                    </a>
                </button>
            </div>

            <div class="w-100"></div>
        @endforeach
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('js/waiter-home.js') }}"></script>
<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // set color border for image
        setStatus();

        // init an Pusher object with Pusher app key
        var pusher = new Pusher('6063520d51edaa14b9cf', {
            cluster: 'ap1',
            encrypted: true
        });

        // register a channel created in event
        var channel = pusher.subscribe('channel-display-billing-waiter');

        // bind a function with event
        channel.bind('App\\Events\\DisplayBillingTableInWaiterEvent', changeTableStatus);
    });

    // function to change status of table
    function changeTableStatus (data) {
        var tableList = $( "#waiterTableList .row" );
        for (var i = 0; i < tableList.length; i++) {
            var table = tableList.eq(i);
            var tableId = table.find("button:eq(0)").html();

            if (data.table_id == tableId && data.status == 'prepare') {
                table.find("img:eq(0)").attr("class", "rounded-circle border border-primary");
            } else if (data.table_id == tableId && data.status == 'run') {
                table.find("img:eq(0)").attr("class", "rounded-circle border border-danger");
                table.find("button:eq(1)").css("display", "inline");
            } else if (data.table_id == tableId && data.status == 'ready') {
                table.find("img:eq(0)").attr("class", "rounded-circle border border-success");
            }
        }
    }

    function setStatus () {
        var tableList = $( "#waiterTableList .row" );
        for (var i = 0; i < tableList.length; i++) {
            var table = tableList.eq(i);
            var img = table.find("img:eq(0)");
            var altVal = img.attr("alt");

            if (altVal == 'prepare') {
                img.attr("class", "rounded-circle border border-primary");
            } else if (altVal == 'run') {
                img.attr("class", "rounded-circle border border-danger");
                table.find("button:eq(1)").css("display", "inline");
            } else if (altVal == 'ready') {
                img.attr("class", "rounded-circle border border-success");
            }
        }
    }
</script>
@endsection
