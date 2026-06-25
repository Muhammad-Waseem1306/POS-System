<div class="modal fade modal-modern app-confirm" id="app-confirm-dialog" tabindex="-1" role="dialog"
    aria-labelledby="appConfirmTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-modern__content">
            <div class="modal-modern__header">
                <div class="modal-modern__heading">
                    <span class="modal-modern__icon app-confirm__icon" aria-hidden="true">
                        <i class="fas fa-question"></i>
                    </span>
                    <h5 class="modal-modern__title" id="appConfirmTitle">Confirm</h5>
                </div>
                <button type="button" class="modal-modern__close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-modern__body">
                <p class="app-confirm__message" id="appConfirmMessage"></p>
            </div>
            <div class="modal-modern__footer">
                <button type="button" class="btn btn-modern btn-modern--ghost" data-dismiss="modal" id="appConfirmCancel">
                    Cancel
                </button>
                <button type="button" class="btn btn-modern btn-modern--primary" id="appConfirmOk">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>
