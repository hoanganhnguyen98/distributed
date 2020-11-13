<div class="toast show bg-danger text-white border-danger" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
    <div class="toast-header">
        <strong class="mr-auto text-danger"><i class="fas fa-exclamation-circle fa-2x"></i></strong>
        <button type="button" id="colseToastButton" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body font-weight-bold">
        <ul>
            @foreach ($errors->all() as $error)
                <li><i class="fa fa-exclamation-circle mr-2"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
