@props([
    'label',
    'name' => null,
    'required' => false,
    'col' => 'md-6',
    'hint' => null,
])

<div {{ $attributes->merge(['class' => 'form-field col-12 col-' . $col]) }}>
    <label class="form-field__label" @if($name) for="{{ $name }}" @endif>
        {{ $label }}
        @if ($required)
            <span class="form-field__required" aria-hidden="true">*</span>
        @endif
    </label>
    <div class="form-field__control">
        {{ $slot }}
    </div>
    @if ($hint)
        <p class="form-field__hint">{{ $hint }}</p>
    @endif
</div>
