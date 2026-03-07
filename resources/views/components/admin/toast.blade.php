@props(['toasts' => []])

@if (filled($toasts))
    <div class="pointer-events-none fixed right-4 top-4 z-[70] flex w-full max-w-sm flex-col gap-3 sm:right-6 sm:top-6" data-admin-toast-stack>
        @foreach ($toasts as $toast)
            @php
                $isError = ($toast['type'] ?? 'success') === 'error';
            @endphp
            <div
                class="pointer-events-auto flex items-start gap-3 border px-4 py-3 transition duration-200 {{ $isError ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-emerald-200 bg-emerald-50 text-emerald-700' }}"
                data-admin-toast
                data-timeout="{{ $isError ? 6000 : 4000 }}"
                role="status"
            >
                <div class="min-w-0 flex-1 text-sm font-medium">
                    {{ $toast['message'] }}
                </div>
                <button type="button" class="p-1 transition hover:bg-black/5" data-toast-dismiss aria-label="Dismiss notification">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endforeach
    </div>
@else
    <div class="pointer-events-none fixed right-4 top-4 z-[70] flex w-full max-w-sm flex-col gap-3 sm:right-6 sm:top-6" data-admin-toast-stack></div>
@endif
