<div class="row table2">
    <div class="col-7">
        @if($table2->status == 'ready')
        <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-success"> 
        @elseif($table2->status == 'prepare')
        <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-primary"> 
        @elseif($table2->status == 'run')
        <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-danger"> 
        @endif
        <button type="button" class="btn btn-outline-primary font-weight-bold">{{ $table2->table_id }}</button>
    </div>

    @if($table2->status == 'run')
    <button type="button" class="action btn btn-outline-primary font-weight-bold">
        <a href="add-bill-detail-{{ $table2->table_id }}">
            <i class="fas fa-cart-plus mr-2"></i>{{ trans('messages.home.waiter.order') }}
        </a>
    </button>
    @endif
</div>

<div class="w-100"></div>
