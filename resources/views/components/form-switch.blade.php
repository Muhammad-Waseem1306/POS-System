@props([
    'name' => 'status',
    'id' => 'active',
    'label' => 'Active',
    'checked' => true,
    'col' => '12',
])

<div {{ $attributes->merge(['class' => 'form-field form-field--switch col-12 col-' . $col]) }}>
    <div class="form-switch-modern">
        <input type="hidden" name="{{ $name }}" value="0">
        <label class="switch mb-0">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $id }}"
                value="1"
                @checked($checked)
            >
            <span class="slider round"></span>
        </label>
        <label class="form-switch-modern__text mb-0" for="{{ $id }}">{{ $label }}</label>
    </div>
</div>
