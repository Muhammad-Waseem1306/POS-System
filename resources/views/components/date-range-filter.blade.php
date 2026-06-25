@props([
    'id' => 'daterange-btn',
    'label' => 'Filter by date',
])

<button type="button" {{ $attributes->merge(['class' => 'date-range-filter', 'id' => $id]) }}>
    <span class="date-range-filter__icon" aria-hidden="true">
        <i class="far fa-calendar-alt"></i>
    </span>
    <span class="date-range-filter__text">
        <span class="date-range-filter__label">{{ $label }}</span>
        <span class="date-range-filter__value"></span>
    </span>
    <span class="date-range-filter__caret" aria-hidden="true"></span>
</button>
