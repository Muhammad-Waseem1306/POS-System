@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'page-header']) }}>
    <div class="page-header__info">
        @if ($title)
            <h2 class="page-header__title">{{ $title }}</h2>
        @endif
        @if ($subtitle)
            <p class="page-header__subtitle">{{ $subtitle }}</p>
        @endif
        {{ $slot }}
    </div>
    @isset($actions)
        <div class="page-header__actions">
            {{ $actions }}
        </div>
    @endisset
</div>
