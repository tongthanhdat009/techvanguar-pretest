@extends('layouts.client-app', ['title' => 'Cộng Đồng'])

@section('content')
    <section class="client-page-shell">
        <div class="client-page-hero client-page-hero-community">
            <div>
                <span class="client-page-kicker">Khám phá nội dung</span>
                <h1>Deck công khai từ cộng đồng để bạn bắt đầu nhanh hơn.</h1>
                <p>Quan sát cách người khác tổ chức kiến thức, tìm các bộ thẻ có sẵn và mở rộng thư viện cá nhân mà không cần dựng mọi thứ từ đầu.</p>
            </div>
            <div class="client-page-actions">
                <a href="{{ route('client.decks.create') }}" class="dashboard-btn dashboard-btn-secondary">Tạo deck để chia sẻ</a>
            </div>
        </div>

        {{-- Search & Filter Section --}}
        <div class="community-search-section">
            <form method="GET" action="{{ route('client.community') }}" class="community-search-form">
                <div class="community-search-main">
                    <div class="search-input-wrapper">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input type="text" name="search" value="{{ $filters['search'] }}"
                               placeholder="Tìm kiếm theo tên hoặc mô tả..."
                               class="community-search-input">
                        @if($filters['search'])
                            <a href="{{ route('client.community') }}" class="search-clear">✕</a>
                        @endif
                    </div>
                </div>

                <div class="community-filters">
                    <div class="filter-group">
                        <label class="filter-label">Danh mục</label>
                        <select name="category" class="filter-select">
                            <option value="">Tất cả</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ $filters['category'] === $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Tags</label>
                        <select name="tag" class="filter-select">
                            <option value="">Tất cả</option>
                            @foreach($allTags as $tag)
                                <option value="{{ $tag }}" {{ $filters['tag'] === $tag ? 'selected' : '' }}>
                                    {{ $tag }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-group">
                        <label class="filter-label">Sắp xếp</label>
                        <select name="sort" class="filter-select">
                            <option value="latest" {{ $filters['sort'] === 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ $filters['sort'] === 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                            <option value="rating" {{ $filters['sort'] === 'rating' ? 'selected' : '' }}>Đánh giá cao</option>
                            <option value="cards" {{ $filters['sort'] === 'cards' ? 'selected' : '' }}>Nhiều thẻ nhất</option>
                            <option value="popular" {{ $filters['sort'] === 'popular' ? 'selected' : '' }}>Phổ biến nhất</option>
                        </select>
                    </div>

                    <button type="submit" class="filter-apply-btn">Áp dụng</button>
                    @if($filters['search'] || $filters['category'] || $filters['tag'] || $filters['sort'] !== 'latest')
                        <a href="{{ route('client.community') }}" class="filter-reset-btn">Xóa bộ lọc</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Active Filters Display --}}
        @if($filters['search'] || $filters['category'] || $filters['tag'])
            <div class="active-filters">
                <span class="active-filters-label">Đang lọc:</span>
                @if($filters['search'])
                    <span class="active-filter-tag">
                        🔍 "{{ $filters['search'] }}"
                        <a href="{{ request()->fullUrlWithQuery(['search' => null, 'page' => null]) }}" class="filter-remove">×</a>
                    </span>
                @endif
                @if($filters['category'])
                    <span class="active-filter-tag">
                        📁 {{ $filters['category'] }}
                        <a href="{{ request()->fullUrlWithQuery(['category' => null, 'page' => null]) }}" class="filter-remove">×</a>
                    </span>
                @endif
                @if($filters['tag'])
                    <span class="active-filter-tag">
                        🏷️ {{ $filters['tag'] }}
                        <a href="{{ request()->fullUrlWithQuery(['tag' => null, 'page' => null]) }}" class="filter-remove">×</a>
                    </span>
                @endif
            </div>
        @endif

        <div class="client-page-highlights">
            <div class="client-page-highlight">
                <strong>{{ $communityDecks->total() }}</strong>
                <span>deck công khai đang hiển thị</span>
            </div>
            <div class="client-page-highlight">
                <strong>{{ number_format($communityDecks->getCollection()->sum('flashcards_count')) }}</strong>
                <span>flashcard trong trang hiện tại</span>
            </div>
            <div class="client-page-highlight">
                <strong>{{ $communityDecks->getCollection()->filter(fn($deck) => !empty($deck->reviews_avg_rating))->count() }}</strong>
                <span>deck đã có đánh giá</span>
            </div>
        </div>

        @if($communityDecks->isEmpty())
            <div class="client-empty-panel">
                <div class="client-empty-icon">🌍</div>
                <h2>Chưa có deck cộng đồng nào.</h2>
                <p>Bạn có thể là người đầu tiên đưa một bộ thẻ tốt ra không gian dùng chung để người khác học cùng.</p>
                <a href="{{ route('client.decks.create') }}" class="dashboard-btn dashboard-btn-primary">Tạo deck public</a>
            </div>
        @else
            <div class="deck-grid client-list-grid">
                @foreach($communityDecks as $deck)
                    <a href="{{ route('client.decks.show', $deck) }}" class="deck-card client-list-card">
                        <div class="deck-card-topline">
                            <span class="deck-card-badge">Deck public</span>
                            @if($deck->reviews_avg_rating)
                                <span class="deck-card-rating">★ {{ number_format($deck->reviews_avg_rating, 1) }}</span>
                            @endif
                        </div>
                        <h3 class="title">{{ $deck->title }}</h3>
                        <p class="deck-card-description">{{ $deck->description ? \Illuminate\Support\Str::limit($deck->description, 120) : 'Deck này đang mở cho cộng đồng nhưng chưa có mô tả chi tiết.' }}</p>
                        <div class="deck-card-meta">
                            <p class="count">{{ $deck->flashcards_count ?? 0 }} thẻ</p>
                            @if($deck->owner)
                                <span class="deck-card-category">{{ $deck->owner->name }}</span>
                            @endif
                        </div>
                        @if($deck->tags && count($deck->tags) > 0)
                            <div class="deck-tags client-list-tags">
                                @foreach(array_slice($deck->tags, 0, 3) as $tag)
                                    <span class="tag">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($communityDecks->hasPages())
                <div class="pagination client-pagination-shell">
                    {{ $communityDecks->appends(request()->query())->links() }}
                </div>
            @endif
        @endif
    </section>
@endsection
