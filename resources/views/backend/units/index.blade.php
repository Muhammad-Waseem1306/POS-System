@extends('backend.master')

@section('title', 'Units')

@section('content')
<x-table-panel title="Units" icon="fas fa-balance-scale" accent="default">
    @can('unit_create')
    <x-slot:tools>
        <x-add-new-button :href="route('backend.admin.units.create')" />
    </x-slot:tools>
    @endcan
    <table id="datatables" class="table table-modern table-hover w-100">
        <thead>
            <tr>
                <th data-orderable="false">#</th>
                <th>Title</th>
                <th>Short Name</th>
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
        ajax: { url: "{{ route('backend.admin.units.index') }}" },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex' },
            { data: 'title', name: 'title' },
            { data: 'short_name', name: 'short_name' },
            { data: 'action', name: 'action' },
        ],
    });
});
</script>
@endpush
