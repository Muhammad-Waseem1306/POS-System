@extends('backend.master')

@section('title', 'Categories')

@section('content')
<x-table-panel title="Categories" icon="fas fa-layer-group" accent="default">
    @can('category_create')
    <x-slot:tools>
        <x-add-new-button :href="route('backend.admin.categories.create')" />
    </x-slot:tools>
    @endcan
    <table id="datatables" class="table table-modern table-hover w-100">
        <thead>
            <tr>
                <th data-orderable="false">#</th>
                <th></th>
                <th>Name</th>
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
        order: [[1, 'asc']],
        ajax: { url: "{{ route('backend.admin.categories.index') }}" },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'image', name: 'image' },
            { data: 'name', name: 'name' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ],
    });
});
</script>
@endpush
