@props([
    'title' => null,
    'icon' => null,
    'accent' => null,
])

<div {{ $attributes->merge(['class' => 'content-card table-panel' . ($accent ? ' table-panel--' . $accent : '')]) }}>
    @if ($title || isset($tools))
        <div class="table-panel__header">
            @if ($title)
                <h3 class="table-panel__title">
                    @if ($icon)
                        <span class="table-panel__icon" aria-hidden="true"><i class="{{ $icon }}"></i></span>
                    @endif
                    {{ $title }}
                </h3>
            @endif
            @isset($tools)
                <div class="table-panel__tools">{{ $tools }}</div>
            @endisset
        </div>
    @endif
    <div class="table-panel__body">
        {{ $slot }}
    </div>
</div>
