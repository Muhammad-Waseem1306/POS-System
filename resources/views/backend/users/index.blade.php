@extends('backend.master')

@section('title', 'User Management')

@section('content')
<x-table-panel title="User Management" icon="fas fa-user-cog" accent="default">
    @can('user_create')
    <x-slot:tools>
        <x-add-new-button :href="route('backend.admin.user.create')" />
    </x-slot:tools>
    @endcan
    <table id="datatables" class="table table-modern table-hover w-100">
        <thead>
            <tr>
                <th data-orderable="false">#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
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
        order: [[1, 'asc']],
        ajax: { url: "{{ route('backend.admin.users') }}" },
        columns: [
            { data: 'thumb', name: 'thumb' },
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles' },
            { data: 'created', name: 'created' },
            { data: 'suspend', name: 'ststus' },
            { data: 'action', name: 'action' },
        ],
    });
});
</script>
@endpush
