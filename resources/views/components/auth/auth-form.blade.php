{{-- Auth Form Component --}}
@props([
    'action' => '',
    'method' => 'POST',
    'fields' => [], // ['name' => 'email', 'type' => 'email', 'label' => 'Email', 'placeholder' => '']
    'submitText' => 'Submit',
    'type' => 'client'
])

<form method="{{ $method }}" action="{{ $action }}" class="auth-form">
    @csrf
    @if($method === 'PUT' || $method === 'PATCH' || $method === 'DELETE')
        @method($method)
    @endif

    @foreach($fields as $field)
        <div class="form-group">
            <label for="{{ $field['name'] }}">
                {{ $field['label'] }}
            </label>
            <input
                id="{{ $field['name'] }}"
                name="{{ $field['name'] }}"
                type="{{ $field['type'] ?? 'text' }}"
                @if(isset($field['autocomplete'])) autocomplete="{{ $field['autocomplete'] }}" @endif
                @if(isset($field['required']) && $field['required']) required @endif
                @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif
                value="{{ old($field['name']) ?? $field['value'] ?? '' }}"
                class="{{ $type === 'admin' ? 'bg-slate-900 border-slate-600 text-white placeholder-slate-500' : '' }}"
            />
        </div>
    @endforeach

    <button type="submit" class="auth-submit-btn {{ $type === 'admin' ? 'auth-gradient-admin' : 'auth-gradient-client' }}">
        {{ $submitText }}
    </button>
</form>
