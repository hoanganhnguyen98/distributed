@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.title.home') }}
@endsection

@section('custom_css')
<style type="text/css">
    .confirmButton {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <div class="row">
            <div class="col-auto mr-auto">    
                <i class="fas fa-fan fa-spin mr-2"></i>Quyet toan
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
                    <th scope="col" class="text-center">Balance sheet</th>
                </tr>
            </thead>
            <tbody id="todayDeposit">
                @foreach($deposits as $deposit)
                <tr class="text-primary">
                    <th scope="col">{{ $deposit->user_id }}</th>
                    <th>
                        <form method="POST" action="#">
                            @csrf

                            <input type="hidden" name="user_id" value="{{ $deposit->user_id }}">

                            <!-- Form -->
                            <div class="form-group row">
                                <label class="col-1 col-form-label text-md-right">
                                    VND
                                </label>

                                <div class="col-4">
                                    <input type="text" name="vnd" class="form-control" required>
                                </div>

                                <label class="col-1 col-form-label text-md-right">
                                    USD
                                </label>

                                <div class="col-4">
                                    <input type="text" name="usd" class="form-control" required>
                                </div>

                                <div class="col-2">
                                    <button type="submit" class="confirmButton btn btn-primary">
                                        Confirm
                                    </button>
                                </div>
                            </div>
                        </form>
                    </th>
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
