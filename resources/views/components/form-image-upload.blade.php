@props([
    'name',
    'inputId' => 'thumbnailInput',
    'previewId' => 'thumbnailPreview',
    'containerId' => 'imageUploadContainer',
    'previewContainerId' => 'thumbPreviewContainer',
    'placeholder' => 'Upload image',
    'blankImage' => null,
    'previewSrc' => null,
])

@php
    $blankImage = $blankImage ?? asset('assets/images/no-image.png');
    $hasPreview = filled($previewSrc);
@endphp

<div class="form-image-upload image-upload-container" id="{{ $containerId }}">
    <input
        type="file"
        class="form-image-upload__input"
        name="{{ $name }}"
        id="{{ $inputId }}"
        accept="image/*"
        style="display: none;"
    >
    <div class="thumb-preview form-image-upload__preview" id="{{ $previewContainerId }}">
        <img
            src="{{ $hasPreview ? $previewSrc : $blankImage }}"
            alt="Image preview"
            class="img-thumbnail form-image-upload__img {{ $hasPreview ? '' : 'd-none' }}"
            id="{{ $previewId }}"
            @if($hasPreview) onerror="this.onerror=null; this.src='{{ $blankImage }}'" @endif
        >
        <div class="upload-text form-image-upload__placeholder {{ $hasPreview ? 'd-none' : '' }}">
            <i class="fas fa-cloud-upload-alt" aria-hidden="true"></i>
            <span>{{ $placeholder }}</span>
        </div>
    </div>
</div>
