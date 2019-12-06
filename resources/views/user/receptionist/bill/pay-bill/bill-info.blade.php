@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li><i class="fa fa-exclamation-circle"></i> {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('create-bill') }}">
    @csrf

    <!-- Table number -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.create.bill.table') }}
        </label>

        <div class="col-md-9">
            <input type="text" class="form-control" value="{{ $bill->table_id }}" name="table_id" readonly>
        </div>
    </div>

    <!-- Customer name -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.create.bill.name') }}
        </label>

        <div class="col-md-9">
            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $bill->customer_name }}" name="name" required readonly>

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
            <input type="text" class="form-control @error('street') is-invalid @enderror" value="{{ $bill->street }}" name="street" placeholder="{{ trans('messages.create.bill.street') }}"  readonly>

            @error('street')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control @error('district') is-invalid @enderror" value="{{ $bill->district }}" name="district" placeholder="{{ trans('messages.create.bill.district') }}"  readonly>

            @error('district')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
        <div class="col-md-3">
            <input type="text" class="form-control @error('city') is-invalid @enderror" value="{{ $bill->city }}" name="city" placeholder="{{ trans('messages.create.bill.city') }}" required readonly>

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
            <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ $bill->phone }}" name="phone" required readonly>

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
            <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ $bill->email }}" name="email" required readonly>

            @error('email')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-9 offset-md-3">
            <button type="button" class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#payBillModal">
                {{ trans('messages.pay.pay') }}
            </button>
            <!-- Create new bill modal -->
            <div class="modal fade" id="payBillModal" tabindex="-1" role="dialog" aria-labelledby="payBillModalTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase text-primary" id="payBillModalTitle">
                            {{ trans('messages.pay.pay_modal') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                            {{ trans('messages.pay.cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary font-weight-bold">
                            <a href="">
                                {{ trans('messages.pay.pay') }}
                            </a>
                        </button>
                        <button type="button" class="btn btn-danger font-weight-bold">
                            <a href="export-bill-{{ $bill->table_id }}" target="_blank">
                                {{ trans('messages.pay.pay_bill') }}
                            </a>
                        </button>
                    </div>
                </div>
                </div>
            </div><!-- End modal -->
        </div>
    </div>
</form>
