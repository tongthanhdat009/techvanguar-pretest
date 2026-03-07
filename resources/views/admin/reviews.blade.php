<x-layouts.admin :title="'Moderate Reviews'" :breadcrumb="[['label' => 'Dashboard', 'url' => route('admin.overview')], ['label' => 'Reviews', 'url' => null]]">
    <section class="space-y-6">
        <div>
            <h1 class="text-3xl font-black text-slate-950">Moderate Reviews</h1>
            <p class="mt-2 text-slate-600">Review and moderate community feedback.</p>
        </div>

        <div class="glass-panel p-6">
            <h2 class="text-lg font-semibold text-slate-900 mb-4">Community Reviews ({{ $reviews->total() }})</h2>
            @if($reviews->isEmpty())
                <x-shared.empty-state message="No reviews found." />
            @else
                <div class="mt-4 space-y-4">
                    @foreach($reviews as $review)
                        <div class="border border-slate-200 rounded-lg p-4 hover:border-slate-300 transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="@if($i <= $review->rating) text-amber-400 @else text-slate-300 @endif">★</span>
                                            @endfor
                                        </div>
                                        <span class="text-sm font-medium text-slate-900">{{ $review->user->name }}</span>
                                        <span class="text-sm text-slate-500">· {{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <h3 class="mt-2 font-semibold text-slate-900">{{ $review->deck->title }}</h3>
                                    @if($review->comment)
                                        <p class="mt-2 text-slate-600">{{ $review->comment }}</p>
                                    @endif
                                </div>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-700 text-sm font-medium"
                                            data-confirm-title="Remove review"
                                            data-confirm-message="Remove this review?"
                                            data-confirm-accept="Remove review">
                                        Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($reviews->hasPages())
                    <div class="mt-6">
                        {{ $reviews->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
</x-layouts.app>
