<li data-toggle="collapse" href="#table4-{{ $table4->table_id }}" role="button" aria-expanded="false" aria-controls="table4-{{ $table4->table_id }}">
    @if($table4->status == 'ready')
    <img src="{{ asset('img/table-short.jpg') }}" class="rounded border border-success"> 
    @elseif($table4->status == 'prepare')
    <img src="{{ asset('img/table-short.jpg') }}" class="rounded border border-warning"> 
    @elseif($table4->status == 'run')
    <img src="{{ asset('img/table-short.jpg') }}" class="rounded border border-danger"> 
    @endif
    <div class="collapse" id="table4-{{ $table4->table_id }}">
        <div class="card card-body">
            <p>Size: {{ $table4->size }}</p>
            <p>Status: {{ $table4->status }}</p>
        </div>
    </div> 
</li>