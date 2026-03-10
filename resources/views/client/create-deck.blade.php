@extends('layouts.client-app', ['title' => 'Tạo Deck'])

@section('content')
<section class="client-page-shell create-deck-shell">
    <div class="client-page-hero create-deck-hero">
        <div>
            <span class="client-page-kicker">Khởi tạo nội dung</span>
            <h1>Tạo một deck mới với cấu trúc rõ ràng ngay từ đầu.</h1>
            <p>Đặt chủ đề, xác định mức chia sẻ và nhập những flashcard đầu tiên để bộ thẻ của bạn sẵn sàng cho các phiên ôn lặp lại.</p>
        </div>
        <div class="client-page-actions">
            <a href="{{ route('client.dashboard') }}" class="dashboard-btn dashboard-btn-secondary">Quay lại tổng quan</a>
        </div>
    </div>

    <div class="client-page-highlights">
        <div class="client-page-highlight">
            <strong>1</strong>
            <span>xác định mục tiêu học và phạm vi deck</span>
        </div>
        <div class="client-page-highlight">
            <strong>2</strong>
            <span>gắn category, tag và mức chia sẻ phù hợp</span>
        </div>
        <div class="client-page-highlight">
            <strong>3</strong>
            <span>thêm flashcard đầu tiên để có thể ôn ngay</span>
        </div>
    </div>

    <form action="{{ route('client.decks.store') }}" method="POST" class="dashboard-card create-deck-form-panel">
        @csrf
        <input type="hidden" name="is_active" value="1">

        <div class="dashboard-card-header">
            <div>
                <span class="dashboard-card-kicker">Thông tin deck</span>
                <h2>Thiết lập bộ thẻ</h2>
            </div>
        </div>

        <div class="client-form-grid two-columns">
            <div class="client-form-group">
                <label for="title">Tên deck</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" class="client-form-input" placeholder="Ví dụ: Từ vựng tiếng Tây Ban Nha" required>
                @error('title') <span class="client-form-error">{{ $message }}</span> @enderror
            </div>

            <div class="client-form-group">
                <label for="category">Danh mục</label>
                <select id="category" name="category" class="client-form-input client-form-select">
                    <option value="">Chọn danh mục</option>
                    <option value="Ngôn ngữ" {{ old('category') === 'Ngôn ngữ' ? 'selected' : '' }}>🌐 Ngôn ngữ</option>
                    <option value="Tiếng Anh" {{ old('category') === 'Tiếng Anh' ? 'selected' : '' }}>🇬🇧 Tiếng Anh</option>
                    <option value="Tiếng Trung" {{ old('category') === 'Tiếng Trung' ? 'selected' : '' }}>🇨🇳 Tiếng Trung</option>
                    <option value="Tiếng Nhật" {{ old('category') === 'Tiếng Nhật' ? 'selected' : '' }}>🇯🇵 Tiếng Nhật</option>
                    <option value="Tiếng Hàn" {{ old('category') === 'Tiếng Hàn' ? 'selected' : '' }}>🇰🇷 Tiếng Hàn</option>
                    <option value="Toán học" {{ old('category') === 'Toán học' ? 'selected' : '' }}>📐 Toán học</option>
                    <option value="Khoa học" {{ old('category') === 'Khoa học' ? 'selected' : '' }}>🔬 Khoa học</option>
                    <option value="Vật lý" {{ old('category') === 'Vật lý' ? 'selected' : '' }}>⚛️ Vật lý</option>
                    <option value="Hóa học" {{ old('category') === 'Hóa học' ? 'selected' : '' }}>🧪 Hóa học</option>
                    <option value="Sinh học" {{ old('category') === 'Sinh học' ? 'selected' : '' }}>🧬 Sinh học</option>
                    <option value="Lịch sử" {{ old('category') === 'Lịch sử' ? 'selected' : '' }}>📜 Lịch sử</option>
                    <option value="Địa lý" {{ old('category') === 'Địa lý' ? 'selected' : '' }}>🌍 Địa lý</option>
                    <option value="Văn học" {{ old('category') === 'Văn học' ? 'selected' : '' }}>📚 Văn học</option>
                    <option value="Triết học" {{ old('category') === 'Triết học' ? 'selected' : '' }}>💭 Triết học</option>
                    <option value="Kinh tế" {{ old('category') === 'Kinh tế' ? 'selected' : '' }}>💰 Kinh tế</option>
                    <option value="Tin học" {{ old('category') === 'Tin học' ? 'selected' : '' }}>💻 Tin học</option>
                    <option value="Lập trình" {{ old('category') === 'Lập trình' ? 'selected' : '' }}>👨‍💻 Lập trình</option>
                    <option value="Âm nhạc" {{ old('category') === 'Âm nhạc' ? 'selected' : '' }}>🎵 Âm nhạc</option>
                    <option value="Mỹ thuật" {{ old('category') === 'Mỹ thuật' ? 'selected' : '' }}>🎨 Mỹ thuật</option>
                    <option value="Thể thao" {{ old('category') === 'Thể thao' ? 'selected' : '' }}>⚽ Thể thao</option>
                    <option value="Y học" {{ old('category') === 'Y học' ? 'selected' : '' }}>🏥 Y học</option>
                    <option value="Luật" {{ old('category') === 'Luật' ? 'selected' : '' }}>⚖️ Luật</option>
                    <option value="Khác" {{ old('category') === 'Khác' ? 'selected' : '' }}>📁 Khác</option>
                </select>
                @error('category') <span class="client-form-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="client-form-group">
            <label for="description">Mô tả ngắn</label>
            <textarea id="description" name="description" rows="4" class="client-form-input" placeholder="Deck này dùng để học gì, phạm vi đến đâu và nên ôn theo nhịp nào?">{{ old('description') }}</textarea>
            @error('description') <span class="client-form-error">{{ $message }}</span> @enderror
        </div>

        <div class="client-form-grid two-columns">
            <div class="client-form-group">
                <label for="visibility">Mức chia sẻ</label>
                <select id="visibility" name="visibility" class="client-form-input client-form-select">
                    <option value="private" {{ old('visibility', 'private') === 'private' ? 'selected' : '' }}>Riêng tư, chỉ bạn nhìn thấy</option>
                    <option value="public" {{ old('visibility') === 'public' ? 'selected' : '' }}>Công khai, có thể chia sẻ với cộng đồng</option>
                </select>
                @error('visibility') <span class="client-form-error">{{ $message }}</span> @enderror
            </div>

            <div class="client-form-group">
                <label for="tags">Nhãn nhận diện</label>
                <input type="text" id="tags" name="tags" value="{{ old('tags') }}" class="client-form-input" placeholder="Ví dụ: spanish, beginner, travel">
                <span class="client-form-note">Phân tách nhiều tag bằng dấu phẩy để dễ lọc và tìm lại.</span>
                @error('tags') <span class="client-form-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="create-deck-divider"></div>

        <div class="dashboard-card-header">
            <div>
                <span class="dashboard-card-kicker">Bộ thẻ khởi đầu</span>
                <h2>Thêm flashcard đầu tiên</h2>
            </div>
        </div>
        <p class="create-deck-section-note">Hãy nhập ít nhất một thẻ. Mỗi thẻ nên ngắn, rõ ý và đủ cụ thể để việc tự nhớ lại thật dứt khoát.</p>

        <x-client.dynamic-card-input :minCards="1" :maxCards="50" />

        <div class="client-form-actions create-deck-actions">
            <a href="{{ route('client.dashboard') }}" class="dashboard-btn dashboard-btn-secondary">Hủy</a>
            <button type="submit" class="dashboard-btn dashboard-btn-primary">Tạo deck</button>
        </div>
    </form>
</section>
@endsection
