@extends('backend.master')

@section('title', 'Create Supplier')

@section('content')
<x-form-page
    :action="route('backend.admin.suppliers.store')"
    :cancel-url="route('backend.admin.suppliers.index')"
    submit-label="Create Supplier"
    enctype="multipart/form-data"
>
    <x-form-section title="Supplier Information">
        <x-form-field label="Name" name="name" required>
            <input type="text" class="form-control" id="name" placeholder="Enter supplier name" name="name"
                value="{{ old('name') }}" required>
        </x-form-field>
        <x-form-field label="Phone" name="phone" required>
            <input type="text" class="form-control" id="phone" placeholder="Enter phone number" name="phone"
                value="{{ old('phone') }}" required>
        </x-form-field>
        <x-form-field label="Address" name="address">
            <input type="text" class="form-control" id="address" placeholder="Enter address" name="address"
                value="{{ old('address') }}">
        </x-form-field>
    </x-form-section>
</x-form-page>
@endsection
