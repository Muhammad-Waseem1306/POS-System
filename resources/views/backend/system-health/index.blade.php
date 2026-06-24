@extends('backend.master')

@section('title', 'System Health')

@section('content')
<section class="content">
    <div class="container-fluid">

        @php
            $overallColor = match($overallStatus) { 'healthy' => 'success', 'warning' => 'warning', 'critical' => 'danger', default => 'info' };
            $overallIcon  = match($overallStatus) { 'healthy' => 'fa-check-circle', 'warning' => 'fa-exclamation-triangle', 'critical' => 'fa-times-circle', default => 'fa-info-circle' };
        @endphp

        <div class="alert alert-{{ $overallColor }}">
            <h4><i class="fas {{ $overallIcon }}"></i> Overall System Status: {{ ucfirst($overallStatus) }}</h4>
            <p class="mb-0">Last checked: {{ now()->format('d M Y H:i:s') }}</p>
        </div>

        <div class="row">
            @foreach($checks as $checkName => $check)
            @php
                $color = match($check['status'] ?? 'ok') { 'ok' => 'success', 'warning' => 'warning', 'critical' => 'danger', default => 'info' };
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
            <div class="col-md-6">
                <div class="card card-{{ $color }} card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas {{ $icon }}"></i>
                            {{ ucfirst(str_replace('_', ' ', $checkName)) }}
                        </h3>
                        <div class="card-tools">
                            <span class="badge bg-{{ $color }}">{{ strtoupper($check['status'] ?? 'ok') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{ $check['message'] ?? '' }}</p>
                        @if(isset($check['recovery']))
                            <div class="alert alert-warning p-2 mb-0">
                                <small><i class="fas fa-wrench"></i> <strong>Recovery:</strong> {{ $check['recovery'] }}</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="card card-info card-outline">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-terminal"></i> Recovery Commands</h3></div>
            <div class="card-body">
                <table class="table table-sm">
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
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
// Auto-refresh health status every 60 seconds
setTimeout(function() { location.reload(); }, 60000);
</script>
@endpush
