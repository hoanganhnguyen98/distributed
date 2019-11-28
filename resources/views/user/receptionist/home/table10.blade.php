<li data-toggle="collapse" href="#table10-{{ $table10->table_id }}" role="button" aria-expanded="false" aria-controls="table10-{{ $table10->table_id }}">
    @if($table10->status == 'ready')
    <img src="{{ asset('img/table-long.jpg') }}" class="rounded-pill border border-success"> 
    @elseif($table10->status == 'prepare')
    <img src="{{ asset('img/table-long.jpg') }}" class="rounded-pill border border-warning"> 
    @elseif($table10->status == 'run')
    <img src="{{ asset('img/table-long.jpg') }}" class="rounded-pill border border-danger"> 
    @endif
    <div class="collapse" id="table10-{{ $table10->table_id }}">
        <div class="card card-body">
            <p>Size: {{ $table10->size }}</p>
            <p>Status: {{ $table10->status }}</p>
        </div>
    </div> 
</li>