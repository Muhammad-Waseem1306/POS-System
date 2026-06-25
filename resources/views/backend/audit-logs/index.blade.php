@extends('backend.master')

@section('title', 'Audit Logs')

@section('content')
<div class="filter-bar mb-4">
    <div class="filter-bar__form">
        <div class="filter-bar__grid filter-bar__grid--compact" id="auditFilters">
            <div class="filter-bar__field">
                <label class="form-label" for="filterAction">Action</label>
                <select class="form-control form-control-sm" id="filterAction">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                    <option value="{{ $action }}">{{ ucfirst(str_replace('_',' ',$action)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-bar__field">
                <label class="form-label" for="filterModule">Module</label>
                <select class="form-control form-control-sm" id="filterModule">
                    <option value="">All Modules</option>
                    @foreach($modules as $module)
                    <option value="{{ $module }}">{{ ucfirst($module) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-bar__field">
                <label class="form-label" for="filterUser">User</label>
                <select class="form-control form-control-sm" id="filterUser">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-bar__field">
                <label class="form-label" for="filterDateFrom">From</label>
                <input type="date" class="form-control form-control-sm" id="filterDateFrom">
            </div>
            <div class="filter-bar__field">
                <label class="form-label" for="filterDateTo">To</label>
                <input type="date" class="form-control form-control-sm" id="filterDateTo">
            </div>
            <div class="filter-bar__field d-flex align-items-end">
                <button type="button" id="clearFilters" class="btn btn-modern btn-modern--ghost btn-modern--sm">Clear</button>
            </div>
        </div>
    </div>
</div>

<x-table-panel title="Audit Trail" icon="fas fa-clipboard-list">
    <div class="table-responsive">
        <table class="table table-modern table-striped w-100" id="auditTable" style="min-width:900px">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Module</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Date & Time</th>
                    <th>Details</th>
                </tr>
            </thead>
        </table>
    </div>
</x-table-panel>
@endsection

@push('scripts')
<script>
$(function() {
    var table = initModernDataTable('#auditTable', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("backend.admin.audit-logs.index") }}',
            data: function(d) {
                d.action    = $('#filterAction').val();
                d.module    = $('#filterModule').val();
                d.user_id   = $('#filterUser').val();
                d.date_from = $('#filterDateFrom').val();
                d.date_to   = $('#filterDateTo').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'user_name', name: 'user_name' },
            { data: 'action_badge', name: 'action', orderable: false },
            { data: 'module', name: 'module' },
            { data: 'description', name: 'description', orderable: false },
            { data: 'ip_address', name: 'ip_address' },
            { data: 'created_at', name: 'created_at' },
            {
                data: 'id',
                render: function(id) {
                    return '<a href="{{ route("backend.admin.audit-logs.index") }}/' + id + '" class="table-actions-btn table-actions-btn--primary" title="View details" aria-label="View details"><i class="fas fa-eye"></i></a>';
                },
                orderable: false, searchable: false
            }
        ],
        order: [[6, 'desc']],
        pageLength: 25,
    });

    $('#filterAction, #filterModule, #filterUser, #filterDateFrom, #filterDateTo').on('change', function() {
        table.draw();
    });

    $('#clearFilters').on('click', function() {
        $('#filterAction, #filterModule, #filterUser').val('');
        $('#filterDateFrom, #filterDateTo').val('');
        table.draw();
    });
});
</script>
@endpush
