<div class="dropdown">
    <li class="btn-group dropdown-toggle">
        <a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @if($table10->status == 'ready')
            <img src="{{ asset('img/table-long.jpg') }}" class="rounded-pill border border-success">
            @elseif($table10->status == 'run')
            <img src="{{ asset('img/table-long.jpg') }}" class="rounded-pill border border-danger"> 
            @endif
        </a>
        <div class="dropdown-menu">
            <p class="dropdown-item text-info font-weight-bold">
                <i class="fas fa-map-marker-alt mr-5"></i>{{ $table10->table_id }}
            </p>
            <p class="dropdown-item text-info font-weight-bold">
                <i class="fas fa-chair mr-5"></i>{{ $table10->size }}
            </p>
            
            @if($table10->status == 'ready')
            <p class="dropdown-item text-success font-weight-bold">
                <i class="fas fa-comment-alt mr-5"></i>{{ trans('messages.status.'.$table10->status) }}
            </p>
            @elseif($table10->status == 'run')
            <p class="dropdown-item text-danger font-weight-bold">
                <i class="fas fa-comment-alt mr-5"></i>{{ trans('messages.status.'.$table10->status) }}
            </p>
            @endif
        </div> 
    </li>
</div>
