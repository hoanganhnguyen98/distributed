@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.create.bill.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        <div class="row">
            <div class="col-auto mr-auto">    
                {{ trans('messages.create.bill.header') }}
            </div>
            <div class="col-auto">
                <a href="cancel-create-bill-{{ $table_id }}" class="text-danger">
                    <i class="fas fa-times mr-2"></i>{{ trans('messages.create.bill.cancel') }}
                </a>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if($errors->any())
            @include('layouts.toast.errors')
        @endif
        
        <form method="POST" action="{{ route('create-bill') }}">
            @csrf

            <!-- Table number -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.bill.table') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control" value="{{ $table_id }}" name="table_id" readonly>
                </div>
            </div>

            <!-- Customer name -->
            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.bill.name') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" name="name" required>

                    @error('name')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.bill.address') }}
                </label>

                <div class="col-md-2">
                    <input type="text" class="form-control @error('street') is-invalid @enderror" value="{{ old('street') }}" name="street" placeholder="{{ trans('messages.create.bill.street') }}">

                    @error('street')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control @error('district') is-invalid @enderror" value="{{ old('district') }}" name="district" placeholder="{{ trans('messages.create.bill.district') }}">

                    @error('district')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" name="city" placeholder="{{ trans('messages.create.bill.city') }}" required>

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

                <div class="col-md-6">
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" name="phone" required>

                    @error('phone')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-3 col-form-label text-md-right">
                    {{ trans('messages.create.bill.email') }}
                </label>

                <div class="col-md-6">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" name="email" required>

                    @error('email')
                        <span class="invalid-feedback" role="alert"></span>
                    @enderror
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-3">
                    <button type="button" class="btn btn-primary font-weight-bold" data-toggle="modal" data-target="#createNewBillModal">
                        {{ trans('messages.create.bill.button') }}
                    </button>
                    <!-- Create new bill modal -->
                    <div class="modal fade" id="createNewBillModal" tabindex="-1" role="dialog" aria-labelledby="createNewBillModalTitle" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-uppercase text-primary" id="createNewBillModalTitle">
                                    {{ trans('messages.create.bill.create_modal') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                                    {{ trans('messages.create.bill.cancel') }}
                                </button>
                                <button type="submit" class="btn btn-primary font-weight-bold">
                                    {{ trans('messages.create.bill.button') }}
                                </button>
                            </div>
                        </div>
                        </div>
                    </div><!-- End modal -->
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
