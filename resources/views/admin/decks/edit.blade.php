@extends('layouts.admin', [
    'title' => 'Sửa: ' . $deck->title,
    'sidebar' => true,
])

@section('content')

{{-- Back + heading --}}
<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.decks.show', $deck) }}" class="text-slate-400 hover:text-white transition-colors" title="Quay lại chi tiết">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-white">Sửa bộ thẻ</h1>
        <p class="text-slate-400 mt-0.5 text-sm">{{ $deck->title }}</p>
    </div>
</div>

{{-- Edit form --}}
<div class="admin-card mb-8 w-full" style="max-width: 680px">
    <form method="POST" action="{{ route('admin.decks.update', $deck) }}" novalidate>
        @csrf
        @method('PUT')

        {{-- Title --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="title">
                Tiêu đề <span class="text-red-400">*</span>
            </label>
            <input id="title" name="title" type="text"
                value="{{ old('title', $deck->title) }}"
                class="admin-form-input {{ $errors->has('title') ? 'has-error' : '' }}"
                placeholder="Nhập tiêu đề bộ thẻ"
                required autofocus>
            @error('title')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="description">Mô tả</label>
            <textarea id="description" name="description" rows="3"
                class="admin-form-input {{ $errors->has('description') ? 'has-error' : '' }}"
                placeholder="Mô tả ngắn về bộ thẻ (không bắt buộc)">{{ old('description', $deck->description) }}</textarea>
            @error('description')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Owner --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="user_id">Chủ sở hữu</label>
            <select id="user_id" name="user_id"
                class="admin-form-input {{ $errors->has('user_id') ? 'has-error' : '' }}">
                <option value="">— Không có chủ sở hữu —</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        {{ old('user_id', $deck->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->role }})
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Visibility --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="visibility">
                Chế độ hiển thị <span class="text-red-400">*</span>
            </label>
            <select id="visibility" name="visibility"
                class="admin-form-input {{ $errors->has('visibility') ? 'has-error' : '' }}" required>
                <option value="public"
                    {{ old('visibility', $deck->visibility) === 'public' ? 'selected' : '' }}>
                    Công khai
                </option>
                <option value="private"
                    {{ old('visibility', $deck->visibility) === 'private' ? 'selected' : '' }}>
                    Riêng tư
                </option>
            </select>
            @error('visibility')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Category --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="category">Danh mục</label>
            <input id="category" name="category" type="text"
                value="{{ old('category', $deck->category) }}"
                class="admin-form-input {{ $errors->has('category') ? 'has-error' : '' }}"
                placeholder="Ví dụ: Ngôn ngữ, Toán học...">
            @error('category')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tags --}}
        <div class="admin-form-group">
            <label class="admin-form-label" for="tags">Thẻ tag</label>
            <input id="tags" name="tags" type="text"
                value="{{ old('tags', $deck->tags ? implode(', ', $deck->tags) : '') }}"
                class="admin-form-input {{ $errors->has('tags') ? 'has-error' : '' }}"
                placeholder="Cách nhau bởi dấu phẩy: tiếng anh, từ vựng, cơ bản">
            @error('tags')
                <p class="admin-form-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- is_active --}}
        <div class="admin-form-group">
            <label class="admin-form-label">Trạng thái kích hoạt</label>
            <div class="flex items-center gap-2 mt-1">
                <input type="hidden" name="is_active" value="0">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $deck->is_active ? '1' : '0') === '1' ? 'checked' : '' }}
                        class="w-4 h-4 rounded accent-indigo-500">
                    <span class="text-slate-300 text-sm">Bộ thẻ đang được kích hoạt</span>
                </label>
            </div>
        </div>

        {{-- Buttons --}}
        <div class="flex items-center flex-wrap gap-3 pt-2">
            <button type="submit" class="btn-primary">Lưu thay đổi</button>
            <a href="{{ route('admin.decks.show', $deck) }}" class="btn-secondary">Hủy</a>
        </div>

    </form>
</div>

{{-- Flashcard management --}}
@include('admin.decks._flashcards', ['deck' => $deck])

@endsection
