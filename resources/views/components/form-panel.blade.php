@props([
    'title',
    'icon' => null,
    'variant' => 'primary',
])

<div {{ $attributes->merge(['class' => 'content-card form-panel form-panel--' . $variant]) }}>
    <div class="form-panel__header">
        @if ($icon)
            <span class="form-panel__icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
        @endif
        <h3 class="form-panel__title">{{ $title }}</h3>
        @isset($tools)
            <div class="form-panel__tools ml-auto">{{ $tools }}</div>
        @endisset
    </div>
    <div class="form-panel__body">
        {{ $slot }}
    </div>
</div>
