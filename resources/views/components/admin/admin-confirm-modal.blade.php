{{-- Admin Confirm Modal Component --}}
<div data-admin-confirm id="confirm-modal" class="admin-confirm-modal">
    <div class="modal-content">
        <h3 class="text-lg font-semibold text-slate-900 mb-2">Confirm Action</h3>
        <p data-confirm-message class="text-slate-600 mb-6">Are you sure you want to proceed?</p>

        <div class="flex justify-end gap-3">
            <button type="button" data-confirm-cancel class="btn-admin-cancel">
                Cancel
            </button>
            <button type="button" data-confirm-ok class="btn-admin-delete">
                Confirm
            </button>
        </div>
    </div>
</div>
