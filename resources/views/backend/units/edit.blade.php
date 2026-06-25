@extends('backend.master')

@section('title', 'Edit Unit')

@section('content')
<x-form-page
    :action="route('backend.admin.units.update', $unit->id)"
    method="PUT"
    :cancel-url="route('backend.admin.units.index')"
    submit-label="Update Unit"
    enctype="multipart/form-data"
>
    <x-form-section title="Unit Details">
        <x-form-field label="Title" name="title" required>
            <input type="text" class="form-control" id="title" placeholder="Enter unit title" name="title"
                value="{{ old('title', $unit->title) }}" required>
        </x-form-field>
        <x-form-field label="Short Name" name="short_name" required>
            <input type="text" class="form-control" id="short_name" placeholder="e.g. kg, pc" name="short_name"
                value="{{ old('short_name', $unit->short_name) }}" required>
        </x-form-field>
    </x-form-section>
</x-form-page>
@endsection
