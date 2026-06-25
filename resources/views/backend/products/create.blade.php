@extends('backend.master')

@section('title', 'Create Product')

@section('content')
<x-form-page
    :action="route('backend.admin.products.store')"
    :cancel-url="route('backend.admin.products.index')"
    submit-label="Create Product"
    enctype="multipart/form-data"
>
    <x-form-section title="Basic Information">
        <x-form-field label="Name" name="name" required>
            <input type="text" class="form-control" id="name" placeholder="Enter product name" name="name"
                value="{{ old('name') }}" required>
        </x-form-field>
        <x-form-field label="SKU" name="sku" required>
            <input type="text" class="form-control" id="sku" placeholder="Enter SKU" name="sku"
                value="{{ old('sku') }}" required>
        </x-form-field>
        <x-form-field label="Brand" name="brand_id" required>
            <select class="form-control select2" name="brand_id" id="brand_id" required>
                <option value="">Select brand</option>
                @foreach ($brands as $item)
                <option value="{{ $item->id }}" {{ old('brand_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
                @endforeach
            </select>
        </x-form-field>
        <x-form-field label="Category" name="category_id" required>
            <select class="form-control select2" name="category_id" id="category_id" required>
                <option value="">Select category</option>
                @foreach ($categories as $item)
                <option value="{{ $item->id }}" {{ old('category_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
                @endforeach
            </select>
        </x-form-field>
        <x-form-field label="Unit" name="unit_id" required>
            <select class="form-control" name="unit_id" id="unit_id" required>
                <option value="">Select unit</option>
                @foreach ($units as $item)
                <option value="{{ $item->id }}" {{ old('unit_id') == $item->id ? 'selected' : '' }}>
                    {{ $item->title . ' (' . $item->short_name . ')' }}
                </option>
                @endforeach
            </select>
        </x-form-field>
        <x-form-field label="Model" name="model">
            <input type="text" class="form-control" id="model" placeholder="Enter model" name="model"
                value="{{ old('model') }}">
        </x-form-field>
    </x-form-section>

    <x-form-section title="Pricing & Inventory">
        <x-form-field label="Price" name="price" required>
            <input type="number" step="0.01" min="0" class="form-control" id="price"
                placeholder="Enter selling price" name="price" value="{{ old('price') }}" required>
        </x-form-field>
        <x-form-field label="Purchase Price" name="purchase_price" required>
            <input type="number" step="0.01" min="0" class="form-control" id="purchase_price"
                placeholder="Enter purchase price" name="purchase_price" value="{{ old('purchase_price') }}" required>
        </x-form-field>
        <x-form-field label="Discount Type" name="discount_type">
            <select class="form-control" name="discount_type" id="discount_type">
                <option value="">Select discount type</option>
                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
            </select>
        </x-form-field>
        <x-form-field label="Discount Amount" name="discount">
            <input type="number" step="0.01" min="0" class="form-control" id="discount"
                placeholder="Enter discount" name="discount" value="{{ old('discount') }}">
        </x-form-field>
        <x-form-field label="Warranty Period (months)" name="warranty_period_months">
            <input type="number" min="0" class="form-control" id="warranty_period_months"
                placeholder="Enter warranty period" name="warranty_period_months" value="{{ old('warranty_period_months') }}">
        </x-form-field>
        <x-form-field label="Expire Date" name="expire_date">
            <input type="date" class="form-control" name="expire_date" id="expire_date" value="{{ old('expire_date') }}">
        </x-form-field>
    </x-form-section>

    <x-form-section title="Media & Description">
        <x-form-field label="Product Image" name="thumbnailInput">
            <x-form-image-upload name="product_image" placeholder="Upload product image" />
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
