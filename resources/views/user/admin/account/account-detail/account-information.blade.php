<form method="POST" action="{{ route('account-edit') }}" id="account-edit">
    @csrf

    <input type="hidden" name="user_id" value="{{ $account->user_id }}">
    <!-- Full name -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.create.account.name') }}
        </label>

        <div class="col-md-8">
            <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $account->name }}" name="name" id="name" required readonly>

            @error('name')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
    </div>

    <!-- Address -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.create.account.address') }}
        </label>

        <div class="col-md-8">
            <input type="text" class="form-control @error('address') is-invalid @enderror" value="{{ $account->address }}" name="address" id="address" required readonly>

            @error('address')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
    </div>

    <!-- Phone -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.create.account.phone') }}
        </label>

        <div class="col-md-8">
            <input type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ $account->phone }}" name="phone" id="phone" required readonly>

            @error('phone')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
    </div>

    <!-- Area -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.create.account.area') }}
        </label>

        <div class="col-md-8">
            <select class="btn btn-outline border border-success text-uppercase" name="area" id="area" form="account-edit" disabled>
                <option value="{{ $account->area }}" selected>
                    {{ $account->area }}
                </option>
                <option value="shuriken">
                    Shuriken
                </option>
                <option value="ninjutsu">
                    Ninjutsu
                </option>
                <option value="ninjago">
                    Ninjago
                </option>
            </select>
            <small class="text-success" id="suggestArea">
                <i class="fas fa-long-arrow-alt-left mr-2"></i>
                {{ trans('messages.suggest') }}
            </small>
        </div>
    </div>

    <!-- Role -->
    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.create.account.role') }}
        </label>

        <div class="col-md-8">
            <select class="btn btn-outline border border-success" name="role" id="role" form="account-edit" disabled>
                <option value="{{ $account->role }}" selected>
                    {{ trans('messages.role.'.$account->role) }}
                </option>
                <option value="employee">
                    {{ trans('messages.role.employee') }}
                </option>
                <option value="receptionist">
                    {{ trans('messages.role.receptionist') }}
                </option>
                <option value="waiter">
                    {{ trans('messages.role.waiter') }}
                </option>
                <option value="kitchen_manager">
                    {{ trans('messages.role.kitchen_manager') }}
                </option>
                <option value="accountant">
                    {{ trans('messages.role.accountant') }}
                </option>
                <option value="admin">
                    {{ trans('messages.role.admin') }}
                </option>
            </select>
            <small class="text-success" id="suggestRole">
                <i class="fas fa-long-arrow-alt-left mr-2"></i>
                {{ trans('messages.suggest') }}
            </small>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
            {{ trans('messages.create.account.email') }}
        </label>

        <div class="col-md-8">
            <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ $account->email }}" name="email" required readonly>

            @error('email')
                <span class="invalid-feedback" role="alert"></span>
            @enderror
        </div>
    </div>

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-3">
            <button type="submit" id="editButton" class="btn btn-primary font-weight-bold">
                {{ trans('messages.list.account.button.edit') }}
            </button>
        </div>
    </div>
</form>
