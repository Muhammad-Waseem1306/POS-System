@extends('backend.master')

@section('title', 'Products')

@section('content')
<x-table-panel title="Products" icon="fas fa-box" accent="default">
    @can('product_create')
    <x-slot:tools>
        <x-add-new-button :href="route('backend.admin.products.create')" />
    </x-slot:tools>
    @endcan
    <table id="datatables" class="table table-modern table-hover w-100">
        <thead>
            <tr>
                <th data-orderable="false">#</th>
                <th></th>
                <th>Name</th>
                <th>Price{{ currency() ? currency()->symbol : '$' }}</th>
                <th>Stock</th>
                <th>Created</th>
                <th>Status</th>
                <th data-orderable="false">Action</th>
            </tr>
        </thead>
    </table>
</x-table-panel>
@endsection

@push('script')
<script>
$(function() {
    initModernDataTable('#datatables', {
        processing: true,
        serverSide: true,
        ordering: true,
        ajax: { url: "{{ route('backend.admin.products.index') }}" },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'image', name: 'image' },
            { data: 'name', name: 'name' },
            { data: 'price', name: 'price' },
            { data: 'quantity', name: 'quantity' },
            { data: 'created_at', name: 'created_at' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ],
    });
});
</script>
@endpush
