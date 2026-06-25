@props([
    'href' => null,
    'label' => 'Add New',
])

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => 'btn-add-new']) }}>
        <span class="btn-add-new__icon" aria-hidden="true"><i class="fas fa-plus"></i></span>
        <span class="btn-add-new__label">{{ $label }}</span>
    </a>
@else
    <button type="button" {{ $attributes->merge(['class' => 'btn-add-new']) }}>
        <span class="btn-add-new__icon" aria-hidden="true"><i class="fas fa-plus"></i></span>
        <span class="btn-add-new__label">{{ $label }}</span>
    </button>
@endif
