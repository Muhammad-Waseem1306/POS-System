@props([
    'name',
    'id' => null,
    'value' => null,
    'placeholder' => '0.00',
    'required' => false,
    'min' => '0',
    'step' => '0.01',
    'symbol' => null,
])

@php
    $inputId = $id ?? $name;
    $currencySymbol = $symbol ?? (currency()->symbol ?? '$');
@endphp

<div {{ $attributes->merge(['class' => 'currency-input']) }}>
    <span class="currency-input__prefix" aria-hidden="true">{{ $currencySymbol }}</span>
    <input
        type="number"
        name="{{ $name }}"
        id="{{ $inputId }}"
        value="{{ $value }}"
        step="{{ $step }}"
        min="{{ $min }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        class="currency-input__field form-control"
    >
</div>
