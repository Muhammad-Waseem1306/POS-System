@extends('backend.master')

@section('title', 'Customers')

@section('content')
<x-table-panel title="Customers" icon="fas fa-users" accent="default">
    @can('customer_create')
    <x-slot:tools>
        <x-add-new-button :href="route('backend.admin.customers.create')" />
    </x-slot:tools>
    @endcan
    <table id="datatables" class="table table-modern table-hover w-100">
        <thead>
            <tr>
                <th data-orderable="false">#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Created</th>
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
        ajax: { url: "{{ route('backend.admin.customers.index') }}" },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'address', name: 'address' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action' },
        ],
    });
});
</script>
@endpush
