@props([
    'clearUrl' => null,
    'clearId' => null,
    'clearLabel' => 'Clear',
    'submitLabel' => 'Filter',
    'showFilterIcon' => true,
])

<div {{ $attributes->merge(['class' => 'filter-bar__actions']) }}>
    <button type="submit" class="btn btn-modern btn-modern--primary btn-modern--sm">
        @if ($showFilterIcon)
            <i class="fas fa-filter" aria-hidden="true"></i>
        @endif
        {{ $submitLabel }}
    </button>
    @if ($clearUrl)
        <a href="{{ $clearUrl }}" class="btn btn-modern btn-modern--ghost btn-modern--sm">{{ $clearLabel }}</a>
    @elseif ($clearId)
        <button type="button" id="{{ $clearId }}" class="btn btn-modern btn-modern--ghost btn-modern--sm">{{ $clearLabel }}</button>
    @endif
</div>
