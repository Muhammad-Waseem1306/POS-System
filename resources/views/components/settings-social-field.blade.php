@props([
    'name',
    'label',
    'icon',
    'brand' => 'default',
    'value' => '',
])

<div {{ $attributes->merge(['class' => 'settings-social-field']) }}>
    <label class="settings-social-field__label" for="{{ $name }}">{{ $label }}</label>
    <div class="settings-social-field__control">
        <span class="settings-social-field__icon settings-social-field__icon--{{ $brand }}" aria-hidden="true">
            <i class="{{ $icon }}"></i>
        </span>
        <input
            class="form-control"
            id="{{ $name }}"
            name="{{ $name }}"
            type="url"
            value="{{ $value }}"
            placeholder="https://"
        >
    </div>
</div>
