<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteAccountTitle">
                    {{ trans('messages.list.account.modal_title') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">
                    {{ trans('messages.list.account.button.cancel') }}
                </button>
                <button type="button" class="btn btn-danger font-weight-bold">
                    <a href="account-delete-{{ $account->user_id }}">
                        {{ trans('messages.list.account.button.delete') }}
                    </a>
                </button>
            </div>
        </div>
    </div>
</div>
