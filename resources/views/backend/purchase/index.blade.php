@extends('backend.master')

@section('title', 'Purchase')

@section('content')
<x-table-panel title="Purchase" icon="fas fa-shopping-bag" accent="default">
    @can('purchase_create')
    <x-slot:tools>
        <x-add-new-button :href="route('backend.admin.purchase.create')" />
    </x-slot:tools>
    @endcan
    <table id="datatables" class="table table-modern table-hover w-100">
        <thead>
            <tr>
                <th data-orderable="false">#</th>
                <th>Supplier</th>
                <th>ID</th>
                <th>Total {{ currency() ? currency()->symbol : '$' }}</th>
                <th>Date</th>
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
        ajax: { url: "{{ route('backend.admin.purchase.index') }}" },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'supplier', name: 'supplier' },
            { data: 'id', name: 'id' },
            { data: 'total', name: 'total' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action' },
        ],
    });
});
</script>
@endpush
