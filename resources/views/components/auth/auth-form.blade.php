{{-- Auth Form Component --}}
@props([
    'action' => '',
    'method' => 'POST',
    'fields' => [], // ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'placeholder' => '']
    'submitText' => 'Submit',
    'type' => 'client',
    'submitLoadingText' => 'Đang xử lý...'
])

<form method="{{ $method }}" action="{{ $action }}" class="auth-form {{ $type }}" data-validate>
    @csrf
    @if($method === 'PUT' || $method === 'PATCH' || $method === 'DELETE')
        @method($method)
    @endif

    @foreach($fields as $field)
        <div class="form-group">
            <label for="{{ $field['name'] }}">
                {{ $field['label'] }}
            </label>
            @if(!empty($field['hint']))
                <p class="auth-field-hint {{ $type }}">{{ $field['hint'] }}</p>
            @endif
            <div class="auth-input-wrap {{ ($field['type'] ?? 'text') === 'password' ? 'is-password' : '' }}">
                <input
                    id="{{ $field['name'] }}"
                    name="{{ $field['name'] }}"
                    type="{{ $field['type'] ?? 'text' }}"
                    @if(($field['type'] ?? 'text') === 'email') inputmode="email" @endif
                    @if(isset($field['autocomplete'])) autocomplete="{{ $field['autocomplete'] }}" @endif
                    @if(isset($field['required']) && $field['required']) required @endif
                    @if(!empty($field['autofocus'])) autofocus @endif
                    @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
                    @if(isset($field['required']) && $field['required']) data-validate="required{{ ($field['type'] ?? 'text') === 'email' ? '|email' : '' }}{{ ($field['name'] === 'password' || $field['name'] === 'password_confirmation') ? '|min:8' : '' }}" @endif
                    value="{{ old($field['name']) ?? $field['value'] ?? '' }}"
                    aria-invalid="{{ $errors->has($field['name']) ? 'true' : 'false' }}"
                    class="{{ $type === 'admin' ? 'bg-slate-900 border-slate-600 text-white placeholder-slate-500' : '' }}"
                />

                @if(($field['type'] ?? 'text') === 'password')
                    <button type="button" class="auth-password-toggle" data-password-toggle aria-label="Hiện hoặc ẩn mật khẩu">
                        <svg data-eye-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg data-eye-off-icon class="hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.477 10.489A3 3 0 0013.5 13.5m2.559-1.938a9.741 9.741 0 001.904-2.38c.199-.357.199-.789 0-1.146C16.574 5.061 14.453 3.75 12 3.75c-1.113 0-2.156.27-3.073.75m-2.784 2.038A9.773 9.773 0 004.03 8.036c-.2.357-.2.79 0 1.147C5.422 12.938 8.01 15.75 12 15.75c.764 0 1.5-.103 2.198-.296" />
                        </svg>
                    </button>
                @endif
            </div>
            @if($errors->has($field['name']))
                <p class="auth-server-error">{{ $errors->first($field['name']) }}</p>
            @endif
        </div>
    @endforeach

    <button type="submit" class="auth-submit-btn {{ $type === 'admin' ? 'auth-gradient-admin' : 'auth-gradient-client' }}" data-loading-text="{{ $submitLoadingText }}">
        {{ $submitText }}
    </button>
</form>
