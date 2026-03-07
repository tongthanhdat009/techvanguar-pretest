<div class="fixed inset-0 z-[80] hidden bg-slate-950/60 p-4" data-admin-confirm aria-hidden="true">
    <div class="flex min-h-full items-center justify-center">
        <div class="w-full max-w-md border-y border-stone-300 bg-white p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-lg font-semibold text-slate-950" data-confirm-modal-title>Confirm action</p>
                    <p class="mt-2 text-sm text-slate-600" data-confirm-modal-message>Are you sure you want to continue?</p>
                </div>
                <button type="button" class="p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" data-confirm-cancel aria-label="Close confirmation dialog">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" class="secondary-button py-2.5" data-confirm-cancel>
                    Cancel
                </button>
                <button type="button" class="bg-rose-600 px-4 py-2.5 font-semibold text-white transition hover:bg-rose-700" data-confirm-accept data-theme="danger">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>
