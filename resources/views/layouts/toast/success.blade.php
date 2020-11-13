<div class="toast show bg-success text-white border-success" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
    <div class="toast-header">
        <strong class="mr-auto text-success"><i class="fas fa-check fa-2x"></i></strong>
        <button type="button" id="colseToastButton" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body font-weight-bold">
        <i class="fas fa-check mr-2"></i>{!! Session::get('success') !!}
    </div>
</div>
