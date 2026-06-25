@props([
    'cancelUrl' => null,
    'submitLabel' => 'Save',
])

<div {{ $attributes->merge(['class' => 'form-actions']) }}>
    @if ($cancelUrl)
        <a href="{{ $cancelUrl }}" class="btn btn-modern btn-modern--ghost">Cancel</a>
    @endif
    <button type="submit" class="btn btn-modern btn-modern--primary">{{ $submitLabel }}</button>
</div>
