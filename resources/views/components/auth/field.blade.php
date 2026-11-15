@props(['name', 'label', 'type' => 'text', 'hint' => null, 'default' => null, 'fieldId' => null, 'errorBag' => 'default', 'required' => true])
@php($fieldId ??= $name)

<div class="auth-field">
    <label for="{{ $fieldId }}">{{ $label }}</label>
    @if ($type === 'password')
        <div class="auth-password">
    @endif
    <input id="{{ $fieldId }}" name="{{ $name }}" type="{{ $type }}"
           @if ($type !== 'password') value="{{ old($name, $default) }}" @endif
           @error($name, $errorBag) aria-invalid="true" aria-describedby="{{ $fieldId }}-error" @enderror
           @if ($hint && !$errors->getBag($errorBag)->has($name)) aria-describedby="{{ $fieldId }}-hint" @endif
           {{ $attributes->class(['auth-input']) }} @if ($required) required @endif>
    @if ($type === 'password')
            <button type="button" class="auth-password-toggle" aria-controls="{{ $fieldId }}"
                    aria-label="{{ __('auth.show_password') }}" aria-pressed="false" hidden>
                <i class="fas fa-eye" aria-hidden="true"></i>
                <i class="fas fa-eye-slash" aria-hidden="true"></i>
            </button>
        </div>
    @endif
    @error($name, $errorBag)
        <p id="{{ $fieldId }}-error" class="auth-field-error" role="alert">{{ $message }}</p>
    @enderror
    @if ($hint && !$errors->getBag($errorBag)->has($name))
        <p id="{{ $fieldId }}-hint" class="auth-field-hint">{{ $hint }}</p>
    @endif
</div>
