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
                    <button type="button" class="btn btn-outline-primary font-weight-bold" value="{{ $table->table_id }}">
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
@endsection
