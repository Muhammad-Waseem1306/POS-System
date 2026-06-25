@extends('backend.master')

@section('title', 'Audit Log Detail')

@section('content')
<div class="content-card p-4">
    <div class="page-header mb-4">
        <div class="page-header__info">
            <h2 class="page-header__title">Audit Log #{{ $log->id }}</h2>
            <p class="page-header__subtitle">{{ $log->created_at->format('d M Y H:i:s') }}</p>
        </div>
        <div class="page-header__actions">
            <a href="{{ route('backend.admin.audit-logs.index') }}" class="btn btn-modern btn-modern--ghost">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="detail-grid mb-4">
        <div class="detail-item">
            <span class="detail-item__label">Action</span>
            <span class="detail-item__value">{!! $log->action_badge !!}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Module</span>
            <span class="detail-item__value">{{ ucfirst($log->module ?? '-') }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Record ID</span>
            <span class="detail-item__value">{{ $log->record_id ?? '-' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">User</span>
            <span class="detail-item__value">{{ $log->user_name ?? 'System' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">IP Address</span>
            <span class="detail-item__value">{{ $log->ip_address ?? '-' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Method</span>
            <span class="detail-item__value">{{ $log->method ?? '-' }}</span>
        </div>
        <div class="detail-item col-12">
            <span class="detail-item__label">Description</span>
            <span class="detail-item__value">{{ $log->description ?? '-' }}</span>
        </div>
        <div class="detail-item col-12">
            <span class="detail-item__label">URL</span>
            <span class="detail-item__value"><small>{{ $log->url ?? '-' }}</small></span>
        </div>
        <div class="detail-item col-12">
            <span class="detail-item__label">Browser/Device</span>
            <span class="detail-item__value"><small>{{ $log->user_agent ?? '-' }}</small></span>
        </div>
    </div>

    @if($log->old_values)
    <x-table-panel title="Before Change" icon="fas fa-history" class="mb-4">
        <pre class="mb-0 p-3 bg-light rounded">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
    </x-table-panel>
    @endif

    @if($log->new_values)
    <x-table-panel title="After Change" icon="fas fa-check">
        <pre class="mb-0 p-3 bg-light rounded">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
    </x-table-panel>
    @endif
</div>
@endsection
