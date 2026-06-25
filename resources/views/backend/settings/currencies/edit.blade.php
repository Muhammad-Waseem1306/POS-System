@extends('backend.master')

@section('title', 'Edit Currency')

@section('content')
<x-form-page
    :action="route('backend.admin.currencies.update', $currency->id)"
    method="PUT"
    :cancel-url="route('backend.admin.currencies.index')"
    submit-label="Update Currency"
    enctype="multipart/form-data"
>
    <x-form-section title="Currency Details">
        <x-form-field label="Name" name="name" required>
            <input type="text" class="form-control" id="name" placeholder="e.g. US Dollar" name="name"
                value="{{ old('name', $currency->name) }}" required>
        </x-form-field>
        <x-form-field label="Code" name="code" required>
            <input type="text" class="form-control" id="code" placeholder="e.g. USD" name="code"
                value="{{ old('code', $currency->code) }}" required>
        </x-form-field>
        <x-form-field label="Symbol" name="symbol" required>
            <input type="text" class="form-control" id="symbol" placeholder="e.g. $" name="symbol"
                value="{{ old('symbol', $currency->symbol) }}" required>
        </x-form-field>
    </x-form-section>
</x-form-page>
@endsection
