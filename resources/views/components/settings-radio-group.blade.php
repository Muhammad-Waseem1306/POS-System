@props([
    'name',
    'label' => null,
    'options' => [],
])

<div {{ $attributes->merge(['class' => 'settings-field col-12']) }}>
    @if ($label)
        <span class="settings-field__label">{{ $label }}</span>
    @endif
    <div class="settings-option-group" role="radiogroup" @if($label) aria-label="{{ $label }}" @endif>
        @foreach ($options as $option)
            <label class="settings-option">
                <input
                    type="radio"
                    name="{{ $name }}"
                    value="{{ $option['value'] }}"
                    @checked($option['checked'] ?? false)
                >
                <span class="settings-option__label">{{ $option['label'] }}</span>
            </label>
        @endforeach
    </div>
</div>
