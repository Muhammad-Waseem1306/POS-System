@props([
    'name',
    'id' => null,
    'accept' => null,
    'required' => false,
    'currentName' => null,
    'currentUrl' => null,
])

@php
    $inputId = $id ?? 'form_file_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $name) . '_' . substr(uniqid(), -6);
    $displayName = $currentName ?: 'No file chosen';
@endphp

<div class="form-file {{ $currentName ? 'form-file--has-file' : '' }}" data-form-file>
    <div class="form-file__picker">
        <input
            type="file"
            name="{{ $name }}"
            id="{{ $inputId }}"
            class="form-file__input"
            @if($accept) accept="{{ $accept }}" @endif
            @if($required) required @endif
        >
        <label for="{{ $inputId }}" class="form-file__label">
            <span class="form-file__icon" aria-hidden="true"><i class="fas fa-paperclip"></i></span>
            <span class="form-file__text">Choose file</span>
            <span class="form-file__name" data-file-name data-default-name="{{ $currentName ? e($currentName) : 'No file chosen' }}">{{ $displayName }}</span>
        </label>
    </div>
    @if ($currentUrl)
        <a href="{{ $currentUrl }}" target="_blank" rel="noopener" class="form-file__current-link">View uploaded file</a>
    @endif
</div>
