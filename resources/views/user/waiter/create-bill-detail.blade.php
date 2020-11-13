@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.create.bill-detail.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <a href="{{ route('home') }}">
            <i class="fas fa-chevron-circle-left mr-2"></i>{{ trans('messages.create.bill-detail.back') }}
        </a>
    </div>
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.create.bill-detail.header') }}
    </div>
    <div class="card-body">
        <a href="#" id="addOptions" class="text-primary font-weight-bold">
            <i class="fas fa-plus mr-2"></i>{{ trans('messages.create.bill-detail.add_option') }}
        </a>
        <form method="POST" action="{{ route('add-bill-detail') }}" id="billDetail" class="font-weight-bold">
            @csrf

            <div class="form-group row" id="newOption">
                <input type="hidden" name="bill_id" value="{{ $bill_id }}">
                <!-- Food ID -->
                <input class="form-control border border-primary" list="foodList" name="food_id[]" type="text" placeholder="{{ trans('messages.create.bill-detail.food_name') }}" required>
                <!-- Amount of food -->
                <input class="form-control border border-primary" name="amount[]" type="number" placeholder="{{ trans('messages.create.bill-detail.amount') }}" required>

                <!-- Food list to show -->
                <datalist id="foodList">
                    @foreach($foods as $food)
                    <option value="{{ $food->id }}">
                        {{ $food->name }}
                    </option>
                    @endforeach
                </datalist>
            </div>
            <div class="form-group row">
                <button type="submit" class="btn btn-primary">
                    {{ trans('messages.create.bill-detail.button') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('js/create-bill-detail.js') }}"></script>
@endsection
