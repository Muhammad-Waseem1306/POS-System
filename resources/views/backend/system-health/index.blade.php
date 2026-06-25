@extends('backend.master')

@section('title', 'System Health')

@section('content')
@php
    $overallColor = match($overallStatus) { 'healthy' => 'success', 'warning' => 'warning', 'critical' => 'danger', default => 'info' };
    $overallIcon  = match($overallStatus) { 'healthy' => 'fa-check-circle', 'warning' => 'fa-exclamation-triangle', 'critical' => 'fa-times-circle', default => 'fa-info-circle' };
@endphp

<div class="alert alert-{{ $overallColor }} mb-4">
    <h4 class="mb-1"><i class="fas {{ $overallIcon }}"></i> Overall System Status: {{ ucfirst($overallStatus) }}</h4>
    <p class="mb-0">Last checked: {{ now()->format('d M Y H:i:s') }}</p>
</div>

<div class="health-check-grid">
    @foreach($checks as $checkName => $check)
    @php
        $status = $check['status'] ?? 'ok';
        $cardClass = match($status) { 'ok' => 'ok', 'warning' => 'warning', 'critical' => 'critical', default => 'ok' };
        $badgeColor = match($status) { 'ok' => 'success', 'warning' => 'warning', 'critical' => 'danger', default => 'info' };
        $icon  = match($checkName) {
            'database'      => 'fa-database',
            'storage'       => 'fa-hdd',
            'folders'       => 'fa-folder-open',
            'configuration' => 'fa-cog',
            'backup'        => 'fa-shield-alt',
            'queue'         => 'fa-stream',
            default         => 'fa-check'
        };
    @endphp
    <div class="health-check-card health-check-card--{{ $cardClass }}">
        <div class="health-check-card__header">
            <h3 class="health-check-card__title">
                <i class="fas {{ $icon }}"></i>
                {{ ucfirst(str_replace('_', ' ', $checkName)) }}
            </h3>
            <span class="badge bg-{{ $badgeColor }}">{{ strtoupper($status) }}</span>
        </div>
        <div class="health-check-card__body">
            <p class="mb-0">{{ $check['message'] ?? '' }}</p>
            @if(isset($check['recovery']))
            <div class="alert alert-warning p-2 mt-3 mb-0">
                <small><i class="fas fa-wrench"></i> <strong>Recovery:</strong> {{ $check['recovery'] }}</small>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<x-table-panel title="Recovery Commands" icon="fas fa-terminal" accent="info">
    <table class="table table-modern table-sm mb-0">
        <thead><tr><th>Issue</th><th>Command</th></tr></thead>
        <tbody>
            <tr><td>Clear all caches</td><td><code>php artisan optimize:clear</code></td></tr>
            <tr><td>Create storage symlink</td><td><code>php artisan storage:link</code></td></tr>
            <tr><td>Run migrations</td><td><code>php artisan migrate</code></td></tr>
            <tr><td>Run backup now</td><td><code>php artisan backup:run</code></td></tr>
            <tr><td>Check overdue installments</td><td><code>php artisan installments:check-overdue</code></td></tr>
            <tr><td>Check low stock</td><td><code>php artisan stock:check-low</code></td></tr>
            <tr><td>Retry failed jobs</td><td><code>php artisan queue:retry all</code></td></tr>
            <tr><td>Start scheduler (dev)</td><td><code>php artisan schedule:work</code></td></tr>
        </tbody>
    </table>
</x-table-panel>
@endsection

@push('scripts')
<script>
setTimeout(function() { location.reload(); }, 60000);
</script>
@endpush
