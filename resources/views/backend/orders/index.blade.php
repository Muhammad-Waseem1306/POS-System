@extends('backend.master')

@section('title', 'Sale')

@section('content')
<x-table-panel title="Sale" icon="fas fa-tags" accent="default">
    <table id="datatables" class="table table-modern table-hover w-100">
        <thead>
            <tr>
                <th data-orderable="false">#</th>
                <th>SaleId</th>
                <th>Customer</th>
                <th>Item</th>
                <th>Sub Total {{ currency() ? currency()->symbol : '$' }}</th>
                <th>Discount {{ currency() ? currency()->symbol : '$' }}</th>
                <th>Total {{ currency() ? currency()->symbol : '$' }}</th>
                <th>Paid {{ currency() ? currency()->symbol : '$' }}</th>
                <th>Due {{ currency() ? currency()->symbol : '$' }}</th>
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
        order: [[1, 'desc']],
        ajax: { url: "{{ route('backend.admin.orders.index') }}" },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'saleId', name: 'saleId' },
            { data: 'customer', name: 'customer' },
            { data: 'item', name: 'item' },
            { data: 'sub_total', name: 'sub_total' },
            { data: 'discount', name: 'discount' },
            { data: 'total', name: 'total' },
            { data: 'paid', name: 'paid' },
            { data: 'due', name: 'due' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action' },
        ],
    });
});
</script>
@endpush
