@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.pay.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">  
        {{ trans('messages.pay.header') }}
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Show bill information -->
            <div class="col">
                @include('user.receptionist.bill.pay-bill.bill-info')
            </div>
            <!-- Show all bill detail -->
            <div class="col">
                 @include('user.receptionist.bill.pay-bill.bill-detail')
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">

</script>
@endsection
