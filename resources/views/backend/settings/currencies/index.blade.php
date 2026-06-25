@extends('backend.master')

@section('title', 'Currency')

@section('content')
<x-table-panel title="Currency" icon="fas fa-coins" accent="default">
    @can('currency_create')
    <x-slot:tools>
        <x-add-new-button :href="route('backend.admin.currencies.create')" />
    </x-slot:tools>
    @endcan
    <table id="datatables" class="table table-modern table-hover w-100">
        <thead>
            <tr>
                <th data-orderable="false">#</th>
                <th>Name</th>
                <th>Code</th>
                <th>Symbol</th>
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
        ajax: { url: "{{ route('backend.admin.currencies.index') }}" },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'name', name: 'name' },
            { data: 'code', name: 'code' },
            { data: 'symbol', name: 'symbol' },
            { data: 'action', name: 'action' },
        ],
    });
});
</script>
@endpush
