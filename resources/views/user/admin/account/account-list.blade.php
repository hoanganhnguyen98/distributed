@extends('layouts.app')

@section('htmlheader_title')
    {{ trans('messages.list.account.title') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header text-uppercase text-primary font-weight-bold">
        {{ trans('messages.list.account.header') }}
    </div>

    @if(Session::has('success'))
        @include('layouts.toast.success')
    @endif

    <div class="card-body">
        <table class="table">
            <thead>
                <tr class="text-primary">
                    <th scope="col">{{ trans('messages.list.account.name') }}</th>
                    <th scope="col">{{ trans('messages.list.account.address') }}</th>
                    <th scope="col">{{ trans('messages.list.account.phone') }}</th>
                    <th scope="col">{{ trans('messages.list.account.role') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                <tr>
                    <td>{{ $account->name }}</td>
                    <td>{{ $account->address }}</td>
                    <td>{{ $account->phone }}</td>
                    <td>{{ trans('messages.role.'.$account->role) }}</td>
                    <td class="text-uppercase">
                        <a href="account-detail-{{ $account->user_id }}" class="badge badge-pill badge-info">
                            {{ trans('messages.list.account.button.detail') }}
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('custom_js')
<script type="text/javascript">
    document.getElementById('accountSidebar').classList.add('show');
</script>
@endsection
