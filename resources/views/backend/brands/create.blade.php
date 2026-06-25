@extends('backend.master')

@section('title', 'Create Brand')

@section('content')
<x-form-page
    :action="route('backend.admin.brands.store')"
    :cancel-url="route('backend.admin.brands.index')"
    submit-label="Create Brand"
    enctype="multipart/form-data"
>
    <x-form-section title="Brand Information">
        <x-form-field label="Name" name="name" required>
            <input type="text" class="form-control" id="name" placeholder="Enter brand name" name="name"
                value="{{ old('name') }}" required>
        </x-form-field>
        <x-form-field label="Image" name="thumbnailInput">
            <x-form-image-upload name="brand_image" placeholder="Upload brand image" />
        </x-form-field>
        <x-form-field label="Description" name="description" col="md-12">
            <textarea class="form-control" id="description" placeholder="Enter description" name="description" rows="4">{{ old('description') }}</textarea>
        </x-form-field>
        <x-form-switch />
    </x-form-section>
</x-form-page>
@endsection

@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
