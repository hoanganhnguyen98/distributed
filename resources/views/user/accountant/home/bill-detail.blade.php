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
        <div class="row">
            <div class="col">
                <!-- Customer name -->
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right">
                        {{ trans('messages.create.bill.name') }}
                    </label>

                    <div class="col-md-9">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $bill->customer_name }}" name="name" id="name" required readonly>

                        @error('name')
                            <span class="invalid-feedback" role="alert"></span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right">
                        {{ trans('messages.create.bill.address') }}
                    </label>

                    <div class="col-md-3">
                        <input type="text" class="form-control @error('street') is-invalid @enderror" value="{{ $bill->street }}" name="street" id="street" placeholder="{{ trans('messages.create.bill.street') }}"  readonly>

                        @error('street')
                            <span class="invalid-feedback" role="alert"></span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control @error('district') is-invalid @enderror" value="{{ $bill->district }}" name="district" id="district" placeholder="{{ trans('messages.create.bill.district') }}"  readonly>

                        @error('district')
                            <span class="invalid-feedback" role="alert"></span>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control @error('city') is-invalid @enderror" value="{{ $bill->city }}" name="city" id="city" placeholder="{{ trans('messages.create.bill.city') }}" required readonly>

                        @error('city')
                            <span class="invalid-feedback" role="alert"></span>
                        @enderror
                    </div>
                </div>

                <!-- Customer phone -->
                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right">
                        {{ trans('messages.create.bill.phone') }}
                    </label>

                    <div class="col-md-9">
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ $bill->phone }}" name="phone" id="phone" required readonly>

                        @error('phone')
                            <span class="invalid-feedback" role="alert"></span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-3 col-form-label text-md-right">
                        {{ trans('messages.create.bill.email') }}
                    </label>

                    <div class="col-md-9">
                        <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ $bill->email }}" name="email" id="email" required readonly>

                        @error('email')
                            <span class="invalid-feedback" role="alert"></span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="col">
                <table class="table">
                    <thead>
                        <tr class="text-primary">
                            <th scope="col">{{ trans('messages.pay.food_name') }}</th>
                            <th scope="col" class="text-center">{{ trans('messages.pay.number') }}</th>
                            <th scope="col" colspan="2" class="text-center">VND</th>
                            <th scope="col" colspan="2" class="text-center">USD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill_details as $bill_detail)
                        <tr>
                            <td>{{ $bill_detail['name'] }}</td>
                            <td class="text-center">{{ $bill_detail['number'] }}</td>
                            <td class="text-right">* {{ $bill_detail['vnd_price'] }}</td>
                            <td class="text-right">= {{ $bill_detail['vnd_total'] }}</td>
                            <td class="text-right">* {{ $bill_detail['usd_price'] }}</td>
                            <td class="text-right">= {{ $bill_detail['usd_total'] }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-danger font-weight-bold">{{ trans('messages.pay.total') }}</td>
                            <td class="text-danger font-weight-bold text-right" id="totalVNDPrice">{{ $vndPrice }}</td>
                            <td></td>
                            <td class="text-danger font-weight-bold text-right" id="totalUSDPrice">{{ $usdPrice }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
@endsection
