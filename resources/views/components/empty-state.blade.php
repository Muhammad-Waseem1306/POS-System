@props([
    'icon' => 'fas fa-inbox',
    'title' => 'No records found',
    'message' => null,
])

<div {{ $attributes->merge(['class' => 'empty-state']) }}>
    <div class="empty-state__icon" aria-hidden="true"><i class="{{ $icon }}"></i></div>
    <h4 class="empty-state__title">{{ $title }}</h4>
    @if ($message)
        <p class="empty-state__message">{{ $message }}</p>
    @endif
    @isset($action)
        <div class="empty-state__action">{{ $action }}</div>
    @endisset
</div>
