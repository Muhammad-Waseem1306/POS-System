@extends('backend.master')

@section('title', 'Edit Brand')

@section('content')
<x-form-page
    :action="route('backend.admin.brands.update', $brand->id)"
    method="PUT"
    :cancel-url="route('backend.admin.brands.index')"
    submit-label="Update Brand"
    enctype="multipart/form-data"
>
    <x-form-section title="Brand Information">
        <x-form-field label="Name" name="name" required>
            <input type="text" class="form-control" id="name" placeholder="Enter brand name" name="name"
                value="{{ old('name', $brand->name) }}" required>
        </x-form-field>
        <x-form-field label="Image" name="thumbnailInput">
            <x-form-image-upload
                name="brand_image"
                placeholder="Upload brand image"
                :preview-src="asset('storage/' . $brand->image)"
            />
        </x-form-field>
        <x-form-field label="Description" name="description" col="md-12">
            <textarea class="form-control" id="description" placeholder="Enter description" name="description" rows="4">{{ old('description', $brand->description) }}</textarea>
        </x-form-field>
        <x-form-switch :checked="$brand->status == 1" />
    </x-form-section>
</x-form-page>
@endsection

@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
