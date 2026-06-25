@extends('backend.master')

@section('title', 'Create Currency')

@section('content')
<x-form-page
    :action="route('backend.admin.currencies.store')"
    :cancel-url="route('backend.admin.currencies.index')"
    submit-label="Create Currency"
    enctype="multipart/form-data"
>
    <x-form-section title="Currency Details">
        <x-form-field label="Name" name="name" required>
            <input type="text" class="form-control" id="name" placeholder="e.g. US Dollar" name="name"
                value="{{ old('name') }}" required>
        </x-form-field>
        <x-form-field label="Code" name="code" required hint="ISO currency code">
            <input type="text" class="form-control" id="code" placeholder="e.g. USD" name="code"
                value="{{ old('code') }}" required>
        </x-form-field>
        <x-form-field label="Symbol" name="symbol" required>
            <input type="text" class="form-control" id="symbol" placeholder="e.g. $" name="symbol"
                value="{{ old('symbol') }}" required>
        </x-form-field>
    </x-form-section>
</x-form-page>
@endsection
