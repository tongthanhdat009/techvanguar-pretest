@extends('layouts.client-app', ['title' => $deck ? "Ôn tập: {$deck->title}" : 'Phiên Ôn Tập'])

@section('content')
@php
    $deck = $deck ?? null;
    $cards = $cards ?? collect();
    $currentIndex = $currentIndex ?? 0;
    $mode = $mode ?? 'flip'; // flip, multiple-choice, typed
    $totalCards = $cards->count();
    $currentCard = $cards->get($currentIndex);
    $isAllDue = $deck === null; // studying all due cards
    $cardPayload = $cards->map(fn ($c) => [
        'id' => $c['flashcard']->id,
        'front' => $c['flashcard']->front_content,
        'back' => $c['flashcard']->back_content,
        'hint' => $c['flashcard']->hint,
        'image_url' => $c['flashcard']->image_url,
        'audio_url' => $c['flashcard']->audio_url,
        'choices' => $c['choices'] ?? [],
    ])->values();
    $backUrl = $deck ? route('client.decks.show', $deck) : route('client.dashboard');
    $restartUrl = $deck
        ? route('client.decks.study', ['deck' => $deck, 'mode' => $mode])
        : route('client.study.all', ['mode' => $mode]);
    $progressPercent = $totalCards > 0 ? (($currentIndex + 1) / $totalCards) * 100 : 0;
@endphp

@if($totalCards === 0)
    <div class="study-empty-state">
        <div class="empty-state-content">
            <div class="empty-icon">📚</div>
            <h2>Hiện chưa có thẻ để ôn tập</h2>
            <p>@if($isAllDue)
                Bạn đã hoàn tất toàn bộ thẻ đến hạn. Hãy quay lại khi có lượt ôn mới.
            @else
                Deck này chưa có flashcard nào. Hãy thêm nội dung trước khi bắt đầu học.
            @endif</p>
            @if($deck)
                <a href="{{ route('client.decks.show', $deck) }}" class="btn btn-primary">
                    Quay lại deck
                </a>
            @else
                <a href="{{ route('client.dashboard') }}" class="btn btn-primary">
                    Quay lại dashboard
                </a>
            @endif
        </div>
    </div>
@else
    <div class="study-room" data-study-room
         data-deck-id="{{ $deck?->id }}"
         data-mode="{{ $mode }}"
         data-current-index="{{ $currentIndex }}"
         data-total-cards="{{ $totalCards }}"
         data-progress-url="{{ route('client.study.progress') }}"
         data-csrf-token="{{ csrf_token() }}"
         data-back-url="{{ $backUrl }}"
         data-restart-url="{{ $restartUrl }}"
         data-cards='@json($cardPayload)'>

        {{-- Study Header --}}
        <div class="study-header">
            <div class="study-back-link">
                @if($deck)
                    <a href="{{ route('client.decks.show', $deck) }}" class="back-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        <span>Quay lại deck</span>
                    </a>
                @else
                    <a href="{{ route('client.dashboard') }}" class="back-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                        <span>Quay lại dashboard</span>
                    </a>
                @endif
            </div>

            <h1 class="study-title">
                @if($deck) {{ $deck->title }} @else Phiên ôn tập đến hạn @endif
            </h1>

            {{-- Study Mode Selector --}}
            <div class="study-mode-selector">
                <button class="mode-btn {{ $mode === 'flip' ? 'active' : '' }}" data-mode="flip">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                    </svg>
                    Lật thẻ
                </button>
                <button class="mode-btn {{ $mode === 'multiple-choice' ? 'active' : '' }}" data-mode="multiple-choice">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75 12 2.25 15.75 6.75M12 2.25v12m-9 8.25h9m-9 0H5.25a2.25 2.25 0 0 1-2.25-2.25V6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v9a2.25 2.25 0 0 1-2.25 2.25h-3.75" />
                    </svg>
                    Trắc nghiệm
                </button>
                <button class="mode-btn {{ $mode === 'typed' ? 'active' : '' }}" data-mode="typed">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    Tự nhập
                </button>
            </div>
        </div>

        <div class="study-session-note">
            <span class="dashboard-card-kicker">Chế độ hiện tại</span>
            <p>
                @if($mode === 'flip')
                    Lật thẻ, tự đánh giá mức độ nhớ và để hệ thống lên lịch ôn tiếp theo.
                @elseif($mode === 'multiple-choice')
                    Chọn đáp án đúng để kiểm tra nhanh trí nhớ nhận diện của bạn.
                @else
                    Tự nhập đáp án để buộc trí nhớ chủ động hoạt động mạnh hơn.
                @endif
            </p>
        </div>

        {{-- Progress Bar --}}
        <div class="study-progress">
            <div class="progress-bar-container">
                <progress class="study-progress-meter" max="100" value="{{ $progressPercent }}"></progress>
            </div>
            <div class="progress-text">
                Thẻ <span class="current-num">{{ $currentIndex + 1 }}</span> / <span class="total-num">{{ $totalCards }}</span>
            </div>
        </div>

        {{-- Study Card Container --}}
        <div class="study-card-container">

            {{-- Flip Card Mode --}}
            @if($mode === 'flip')
                <div class="flip-card" data-flip-card>
                    <div class="flip-card-inner" data-flip-card-inner>
                        {{-- Front --}}
                        <div class="flip-card-front">
                            <div class="card-face card-front">
                                <div class="card-label">Mặt trước</div>
                                <div class="card-content">{{ $currentCard['flashcard']->front_content }}</div>
                                @if($currentCard['flashcard']->image_url)
                                    <div class="card-image">
                                        <img src="{{ $currentCard['flashcard']->image_url }}" alt="Card image">
                                    </div>
                                @endif
                                <div class="card-audio" @if(!$currentCard['flashcard']->audio_url) style="display: none;" @endif>
                                    <audio controls src="{{ $currentCard['flashcard']->audio_url }}"></audio>
                                </div>
                                @if($currentCard['flashcard']->hint)
                                    <div class="card-hint-text">
                                        <span class="hint-label">💡 Gợi ý:</span> {{ $currentCard['flashcard']->hint }}
                                    </div>
                                @endif
                                <div class="card-hint">Nhấn để lật thẻ</div>
                            </div>
                        </div>

                        {{-- Back --}}
                        <div class="flip-card-back">
                            <div class="card-face card-back">
                                <div class="card-label">Mặt sau</div>
                                <div class="card-content">{{ $currentCard['flashcard']->back_content }}</div>
                                <div class="card-audio" @if(!$currentCard['flashcard']->audio_url) style="display: none;" @endif>
                                    <audio controls src="{{ $currentCard['flashcard']->audio_url }}"></audio>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- Multiple Choice Mode --}}
            @elseif($mode === 'multiple-choice')
                @php
                    $choices = $currentCard['choices'] ?? [];
                @endphp
                <div class="multiple-choice-card">
                    <div class="mcq-front">
                        <div class="card-label">Câu hỏi</div>
                        <div class="card-content">{{ $currentCard['flashcard']->front_content }}</div>
                        @if($currentCard['flashcard']->image_url)
                            <div class="card-image">
                                <img src="{{ $currentCard['flashcard']->image_url }}" alt="Card image">
                            </div>
                        @endif
                        <div class="card-audio" @if(!$currentCard['flashcard']->audio_url) style="display: none;" @endif>
                            <audio controls src="{{ $currentCard['flashcard']->audio_url }}"></audio>
                        </div>
                    </div>
                    <div class="mcq-choices" data-mcq-choices>
                        @foreach($choices as $index => $choice)
                            <button class="mcq-choice" data-choice="{{ $index }}">
                                <span class="choice-letter">{{ ['A', 'B', 'C', 'D'][$index] }}</span>
                                <span class="choice-text">{{ $choice }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

            {{-- Typed Mode --}}
            @else
                <div class="typed-card">
                    <div class="typed-front">
                        <div class="card-label">Câu hỏi</div>
                        <div class="card-content">{{ $currentCard['flashcard']->front_content }}</div>
                        @if($currentCard['flashcard']->image_url)
                            <div class="card-image">
                                <img src="{{ $currentCard['flashcard']->image_url }}" alt="Card image">
                            </div>
                        @endif
                        <div class="card-audio" @if(!$currentCard['flashcard']->audio_url) style="display: none;" @endif>
                            <audio controls src="{{ $currentCard['flashcard']->audio_url }}"></audio>
                        </div>
                    </div>
                    <div class="typed-input-section">
                        <textarea class="typed-input"
                                  data-typed-input
                                  placeholder="Nhập đáp án của bạn..."
                                  rows="3"></textarea>
                        <button class="btn btn-primary typed-submit" data-typed-submit>
                            Kiểm tra đáp án
                        </button>
                    </div>
                </div>
            @endif

            {{-- Control Buttons --}}
            @if($mode === 'flip')
                <div class="study-controls" data-study-controls style="display: none;">
                    <div class="control-buttons">
                        <form action="{{ route('client.study.progress') }}" method="POST" class="control-form" data-control-form>
                            @csrf
                            <input type="hidden" name="flashcard_id" value="{{ $currentCard['flashcard']->id }}">
                            @if($deck) <input type="hidden" name="deck_id" value="{{ $deck->id }}"> @endif
                            <input type="hidden" name="study_mode" value="{{ $mode }}">
                            <input type="hidden" name="card_index" value="{{ $currentIndex }}" data-card-index-input>
                            <input type="hidden" name="result" value="again" data-result-input>
    
                            <div class="control-buttons-row">
                                <button type="submit" class="control-btn control-btn-again" data-result="again">
                                    <span class="btn-icon">🔴</span>
                                    <span class="btn-label">Quên</span>
                                    <span class="btn-subtitle">Ôn lại ngay</span>
                                </button>
                                <button type="submit" class="control-btn control-btn-hard" data-result="hard">
                                    <span class="btn-icon">🟠</span>
                                    <span class="btn-label">Khó</span>
                                    <span class="btn-subtitle">Cần thêm nhịp</span>
                                </button>
                                <button type="submit" class="control-btn control-btn-good" data-result="good">
                                    <span class="btn-icon">🔵</span>
                                    <span class="btn-label">Ổn</span>
                                    <span class="btn-subtitle">Giữ nhịp hiện tại</span>
                                </button>
                                <button type="submit" class="control-btn control-btn-easy" data-result="easy">
                                    <span class="btn-icon">🟢</span>
                                    <span class="btn-label">Dễ</span>
                                    <span class="btn-subtitle">Giãn lịch dài hơn</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>


        {{-- Keyboard Shortcuts Hint --}}
        @if($mode === 'flip')
            <div class="keyboard-hints">
                <span class="hint-key"><kbd>Space</kbd> Lật thẻ</span>
                <span class="hint-key"><kbd>1-4</kbd> Chấm mức độ nhớ</span>
            </div>
        @endif

    </div>
@endif

@endsection
