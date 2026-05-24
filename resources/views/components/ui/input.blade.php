@props([
    'type' => 'text',
    'name' => '',
    'label' => null,
    'placeholder' => '',
    'error' => null,
    'icon' => null,
    'value' => '',
    'required' => false,
])

@php
    $inputId = $name ? 'input-' . $name : 'input-' . uniqid();
    $hasIcon = !empty($icon);
    $isPassword = $type === 'password';
@endphp

<div class="field-group flex flex-col gap-1" x-data="{{ $isPassword ? '{ showPw: false }' : '{}' }}">
    @if($label)
        <label for="{{ $inputId }}" class="text-[0.6875rem] font-semibold tracking-[0.08em] uppercase text-[var(--color-text-muted)] font-display">
            {{ $label }}
        </label>
    @endif

    <div class="relative flex items-center">
        @if($hasIcon)
            <span class="absolute left-3.5 text-[var(--color-text-muted)] pointer-events-none transition-colors duration-200">
                {!! $icon !!}
            </span>
        @endif

        <input
            @if($isPassword) :type="showPw ? 'text' : 'password'" @else type="{{ $type }}" @endif
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'w-full ' . ($hasIcon ? 'pl-10' : 'pl-4') . ' ' . ($isPassword ? 'pr-10' : 'pr-4') . ' py-3 bg-[var(--color-bg-sunken)] border border-[var(--color-border)] rounded-[var(--radius-sm)] text-[var(--color-text)] text-[0.875rem] font-sans placeholder:text-[var(--color-text-muted)] outline-none transition-[border-color,background,box-shadow] duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)] backdrop-blur-sm'
            ]) }}
            data-testid="input-{{ $name }}"
        />

        @if($isPassword)
            <button
                type="button"
                class="absolute right-3 text-[var(--color-text-muted)] hover:text-[var(--color-primary)] transition-colors duration-200"
                @click="showPw = !showPw"
                aria-label="Toggle password visibility"
                data-testid="toggle-password-{{ $name }}">
                <svg x-show="!showPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <svg x-show="showPw" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>
                </svg>
            </button>
        @endif
    </div>

    @if($error)
        <p class="text-[0.7rem] text-[var(--accent-rose)] font-medium" data-testid="error-{{ $name }}">{{ $error }}</p>
    @endif
</div>
