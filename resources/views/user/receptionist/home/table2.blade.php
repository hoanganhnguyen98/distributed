<li data-toggle="collapse" href="#table2-{{ $table2->table_id }}" role="button" aria-expanded="false" aria-controls="table2-{{ $table2->table_id }}">
    @if($table2->status == 'ready')
    <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-success"> 
    @elseif($table2->status == 'prepare')
    <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-warning"> 
    @elseif($table2->status == 'run')
    <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-danger"> 
    @endif
    <div class="collapse" id="table2-{{ $table2->table_id }}">
        <div class="card card-body">
            <p>Size: {{ $table2->size }}</p>
            <p>Status: {{ $table2->status }}</p>
        </div>
    </div> 
</li>