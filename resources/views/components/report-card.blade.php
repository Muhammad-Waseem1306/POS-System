@props([
    'title' => null,
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'content-card report-card']) }}>
    @if ($title || isset($filters))
        <div class="report-card__header">
            @if ($title)
                <h3 class="report-card__title">
                    @if ($icon)
                        <span class="report-card__icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
                    @endif
                    {{ $title }}
                </h3>
            @endif
            @isset($filters)
                <div class="report-card__toolbar">{{ $filters }}</div>
            @endisset
        </div>
    @endif
    @isset($stats)
        <div class="report-card__stats row">{{ $stats }}</div>
    @endisset
    <div class="report-card__body">
        {{ $slot }}
    </div>
</div>
