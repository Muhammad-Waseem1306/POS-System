@extends('backend.master')

@section('title', 'Inventory Report')

@section('content')
<x-table-panel title="Inventory Report" icon="fas fa-warehouse" accent="default">
    <table id="datatables" class="table table-modern table-hover w-100">
        <thead>
            <tr>
                <th data-orderable="false">#</th>
                <th>Name</th>
                <th>SKU</th>
                <th>Price</th>
                <th>Stock</th>
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
        order: [[1, 'desc']],
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        ajax: { url: "{{ route('backend.admin.inventory.report') }}" },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false },
            { data: 'name', name: 'name' },
            { data: 'sku', name: 'sku' },
            { data: 'price', name: 'price' },
            { data: 'quantity', name: 'quantity' },
        ],
    });
});
</script>
@endpush
