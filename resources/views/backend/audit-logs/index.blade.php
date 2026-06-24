@extends('backend.master')

@section('title', 'Audit Logs')

@section('content')
<section class="content">
    <div class="container-fluid">

        {{-- Filters --}}
        <div class="card card-primary card-outline">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-filter"></i> Filters</h3></div>
            <div class="card-body">
                <div class="row" id="auditFilters">
                    <div class="col-md-2">
                        <select class="form-control form-control-sm" id="filterAction">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                            <option value="{{ $action }}">{{ ucfirst(str_replace('_',' ',$action)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control form-control-sm" id="filterModule">
                            <option value="">All Modules</option>
                            @foreach($modules as $module)
                            <option value="{{ $module }}">{{ ucfirst($module) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-control form-control-sm" id="filterUser">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control form-control-sm" id="filterDateFrom" placeholder="From Date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control form-control-sm" id="filterDateTo" placeholder="To Date">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary btn-sm" id="clearFilters"><i class="fas fa-times"></i> Clear</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Audit Trail</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered table-striped" id="auditTable" style="min-width:900px">
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
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
$(function() {
    var table = $('#auditTable').DataTable({
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
                    return '<a href="{{ route("backend.admin.audit-logs.index") }}/' + id + '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
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
