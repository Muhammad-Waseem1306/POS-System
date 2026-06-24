@extends('backend.master')

@section('title', 'Audit Log Detail')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-clipboard-check"></i> Audit Log #{{ $log->id }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('backend.admin.audit-logs.index') }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr><th width="200">Action</th><td>{!! $log->action_badge !!}</td></tr>
                            <tr><th>Module</th><td>{{ ucfirst($log->module ?? '-') }}</td></tr>
                            <tr><th>Record ID</th><td>{{ $log->record_id ?? '-' }}</td></tr>
                            <tr><th>User</th><td>{{ $log->user_name ?? 'System' }}</td></tr>
                            <tr><th>Description</th><td>{{ $log->description ?? '-' }}</td></tr>
                            <tr><th>IP Address</th><td>{{ $log->ip_address ?? '-' }}</td></tr>
                            <tr><th>Browser/Device</th><td><small>{{ $log->user_agent ?? '-' }}</small></td></tr>
                            <tr><th>URL</th><td><small>{{ $log->url ?? '-' }}</small></td></tr>
                            <tr><th>Method</th><td>{{ $log->method ?? '-' }}</td></tr>
                            <tr><th>Timestamp</th><td>{{ $log->created_at->format('d M Y H:i:s') }}</td></tr>
                        </table>

                        @if($log->old_values)
                        <div class="mt-3">
                            <h6>Before Change</h6>
                            <pre class="bg-light p-3 rounded">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                        @endif

                        @if($log->new_values)
                        <div class="mt-3">
                            <h6>After Change</h6>
                            <pre class="bg-light p-3 rounded">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
