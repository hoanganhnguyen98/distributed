<div class="dropdown">
    <li class="btn-group dropdown-toggle">
        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @if($table2->status == 'ready')
            <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-success">
            @elseif($table2->status == 'run')
            <img src="{{ asset('img/table-short.jpg') }}" class="rounded-circle border border-danger"> 
            @endif
        </a>
        <div class="dropdown-menu">
            <p class="dropdown-item text-info font-weight-bold">
                <i class="fas fa-map-marker-alt mr-5"></i>{{ $table2->table_id }}
            </p>
            <p class="dropdown-item text-info font-weight-bold">
                <i class="fas fa-chair mr-5"></i>{{ $table2->size }}
            </p>
            
            @if($table2->status == 'ready')
            <p class="dropdown-item text-success font-weight-bold">
                <i class="fas fa-comment-alt mr-5"></i>{{ trans('messages.status.'.$table2->status) }}
            </p>
            @elseif($table2->status == 'run')
            <p class="dropdown-item text-danger font-weight-bold">
                <i class="fas fa-comment-alt mr-5"></i>{{ trans('messages.status.'.$table2->status) }}
            </p>
            @endif
        </div> 
    </li>
</div>
