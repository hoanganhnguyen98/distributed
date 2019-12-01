<div class="row table4">
    <div class="col-8">
        @if($table4->status == 'ready')
        <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-success"> 
        @elseif($table4->status == 'prepare')
        <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-primary"> 
        @elseif($table4->status == 'run')
        <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-danger"> 
        @endif
        <button type="button" class="btn btn-outline-primary">{{ $table4->table_id }}</button>
    </div>

    <div class="col-4">
        @if($table4->status == 'run')
        <a href="add-bill-detail-{{ $table4->table_id }}" class="btn btn-outline-primary font-weight-bold">
            <i class="fas fa-cart-plus mr-2"></i>{{ trans('messages.home.waiter.order') }}
        </a>
        @endif
    </div>
</div>

<div class="w-100"></div>