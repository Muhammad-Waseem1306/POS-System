@props(['flush' => false, 'table' => false])

@php
    $bodyClass = 'content-card__body';
    if ($flush) {
        $bodyClass .= ' content-card__body--flush';
    }
    if ($table) {
        $bodyClass .= ' content-card__body--table';
    }
@endphp

<div {{ $attributes->merge(['class' => 'content-card']) }}>
    @isset($header)
        <div class="page-header content-card__header">
            {{ $header }}
        </div>
    @endisset
    <div class="{{ $bodyClass }}">
        {{ $slot }}
    </div>
</div>
