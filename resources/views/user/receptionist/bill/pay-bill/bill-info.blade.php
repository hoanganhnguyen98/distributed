<form method="POST" action="{{ route('edit-bill') }}">
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

    <div class="form-group row mb-0">
        <div class="col-md-9 offset-md-3">
            <button type="submit" id="editBillButton" class="btn btn-primary font-weight-bold">
                {{ trans('messages.pay.edit') }}
            </button>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-5 offset-md-3">
        <input type="text" class="form-control border-primary" id="enterTotalPrice">
        <small class="text-primary font-weight-bold" id="totalPriceSuggest">
            <i class="fas fa-chevron-up mr-2"></i>{{ trans('messages.pay.suggest') }}
        </small>
    </div>
    <div class="col-md-1">
        <i class="fas fa-check text-success" id="checkTrue"></i>
        <i class="fas fa-times text-danger" id="checkFalse"></i>
    </div>
    <!-- Pay with VND -->
    <div class="col-md-3">
        <button type="button" id="payVNDButton" class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#payVNDBillModal">
            {{ trans('messages.pay.pay_vnd') }}
        </button>
        <!-- Pay bill modal -->
        <div class="modal fade" id="payVNDBillModal" tabindex="-1" role="dialog" aria-labelledby="payVNDBillModalTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase text-primary" id="payVNDBillModalTitle">
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
                            <a href="pay-{{ $bill->table_id }}/vnd">
                                {{ trans('messages.pay.pay_vnd') }}
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div><!-- End modal -->

        <!-- Pay with USD -->
        <button type="button" id="payUSDButton" class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#payUSDBillModal">
            {{ trans('messages.pay.pay_usd') }}
        </button>
        <!-- Pay bill modal -->
        <div class="modal fade" id="payUSDBillModal" tabindex="-1" role="dialog" aria-labelledby="payUSDBillModalTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase text-primary" id="payUSDBillModalTitle">
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
                            <a href="pay-{{ $bill->table_id }}/usd">
                                {{ trans('messages.pay.pay_usd') }}
                            </a>
                        </button>
                    </div>
                </div>
            </div>
        </div><!-- End modal -->
    </div>
</div>
