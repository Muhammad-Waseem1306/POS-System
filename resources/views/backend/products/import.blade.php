@extends('backend.master')

@section('title', 'Import Product')

@section('content')
<x-form-page
    :action="route('backend.admin.products.import')"
    :cancel-url="route('backend.admin.products.index')"
    submit-label="Import Products"
    enctype="multipart/form-data"
>
    <x-form-section title="Import File" description="Upload a CSV or Excel file to bulk import products.">
        <x-form-field label="Product File" required col="md-8">
            <div class="d-flex flex-wrap gap-2 align-items-stretch">
                <div class="flex-grow-1">
                    <x-form-file name="file" required />
                </div>
                <a href="{{ route('backend.admin.products.import', ['download-demo' => true]) }}"
                   class="btn btn-modern btn-modern--ghost align-self-center">
                    <i class="fas fa-download"></i> Download Demo
                </a>
            </div>
        </x-form-field>
    </x-form-section>
</x-form-page>
@endsection
