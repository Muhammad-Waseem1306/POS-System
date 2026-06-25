@extends('backend.master')

@section('title', 'Edit Category')

@section('content')
<x-form-page
    :action="route('backend.admin.categories.update', $category->id)"
    method="PUT"
    :cancel-url="route('backend.admin.categories.index')"
    submit-label="Update Category"
    enctype="multipart/form-data"
>
    <x-form-section title="Category Information">
        <x-form-field label="Name" name="name" required>
            <input type="text" class="form-control" id="name" placeholder="Enter category name" name="name"
                value="{{ old('name', $category->name) }}" required>
        </x-form-field>
        <x-form-field label="Image" name="thumbnailInput">
            <x-form-image-upload
                name="category_image"
                placeholder="Upload category image"
                :preview-src="asset('storage/' . $category->image)"
            />
        </x-form-field>
        <x-form-field label="Description" name="description" col="md-12">
            <textarea class="form-control" id="description" placeholder="Enter description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
        </x-form-field>
        <x-form-switch :checked="$category->status == 1" />
    </x-form-section>
</x-form-page>
@endsection

@push('script')
<script src="{{ asset('js/image-field.js') }}"></script>
@endpush
