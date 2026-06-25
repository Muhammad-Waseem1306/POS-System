@extends('backend.master')

@section('title', 'Backup Management')

@section('content')
@php
    $statusColor = match($health['status']) { 'healthy' => 'success', 'warning' => 'warning', 'critical' => 'danger', default => 'info' };
    $bytes = $health['totalSize'];
    $sizeLabel = $bytes >= 1073741824 ? number_format($bytes / 1073741824, 2) . ' GB'
        : ($bytes >= 1048576 ? number_format($bytes / 1048576, 2) . ' MB'
        : number_format($bytes / 1024, 2) . ' KB');
@endphp

<div class="alert alert-{{ $statusColor }} mb-4">
    <h5 class="mb-1"><i class="fas fa-shield-alt"></i> Backup Health: {{ ucfirst($health['status']) }}</h5>
    @if($health['lastSuccess'])
        Last successful backup: <strong>{{ $health['lastSuccess']->created_at->diffForHumans() }}</strong>
        ({{ $health['lastSuccess']->filename }})
    @else
        <strong>No backups have been created yet!</strong>
    @endif
    @foreach($health['alerts'] as $alert)
        <br><i class="fas fa-exclamation-triangle"></i> {{ $alert }}
    @endforeach
</div>

<div class="stat-cards-row">
    <div class="dashboard-stat-card">
        <div class="dashboard-stat-card__icon dashboard-stat-card__icon--emerald">
            <i class="fas fa-database"></i>
        </div>
        <div class="dashboard-stat-card__body">
            <span class="dashboard-stat-card__label">Total Backup Size</span>
            <span class="dashboard-stat-card__value">{{ $sizeLabel }}</span>
        </div>
    </div>
    <div class="dashboard-stat-card">
        <div class="dashboard-stat-card__icon dashboard-stat-card__icon--{{ $health['failedToday'] > 0 ? 'amber' : 'blue' }}">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="dashboard-stat-card__body">
            <span class="dashboard-stat-card__label">Failed Today</span>
            <span class="dashboard-stat-card__value">{{ $health['failedToday'] }}</span>
        </div>
    </div>
    <div class="dashboard-stat-card">
        <div class="dashboard-stat-card__icon dashboard-stat-card__icon--violet">
            <i class="fas fa-clock"></i>
        </div>
        <div class="dashboard-stat-card__body">
            <span class="dashboard-stat-card__label">Hours Since Last Backup</span>
            <span class="dashboard-stat-card__value">{{ $health['hoursSinceLastBackup'] ?? 'N/A' }}</span>
        </div>
    </div>
    <div class="dashboard-stat-card">
        <div class="dashboard-stat-card__icon dashboard-stat-card__icon--amber">
            <i class="fas fa-list"></i>
        </div>
        <div class="dashboard-stat-card__body">
            <span class="dashboard-stat-card__label">Total Backups</span>
            <span class="dashboard-stat-card__value">{{ $backups->total() }}</span>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0">
        <x-form-panel title="Run Backup Now" icon="fas fa-play-circle" variant="primary">
            <form action="{{ route('backend.admin.backup.run') }}" method="POST" class="form-modern">
                @csrf
                <x-form-field label="Backup Type" name="type" col="12">
                    <select name="type" class="form-control">
                        <option value="manual">Manual</option>
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </x-form-field>
                <div class="form-panel__footer">
                    <button type="submit" class="btn btn-modern btn-modern--primary">
                        <i class="fas fa-database"></i> Run Backup
                    </button>
                </div>
            </form>
        </x-form-panel>
    </div>
    <div class="col-md-8">
        <x-table-panel title="Automatic Backup Schedule" icon="fas fa-calendar-alt" accent="info">
            <table class="table table-modern table-sm mb-0">
                <thead>
                    <tr><th>Type</th><th>Frequency</th><th>Time</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <tr><td>Hourly</td><td>Every Hour</td><td>:00</td><td><span class="badge bg-success">Active</span></td></tr>
                    <tr><td>Daily</td><td>Every Day</td><td>02:00 AM</td><td><span class="badge bg-success">Active</span></td></tr>
                    <tr><td>Weekly</td><td>Every Sunday</td><td>03:00 AM</td><td><span class="badge bg-success">Active</span></td></tr>
                    <tr><td>Monthly</td><td>1st of Month</td><td>04:00 AM</td><td><span class="badge bg-success">Active</span></td></tr>
                </tbody>
            </table>
            <p class="text-muted small px-3 pb-3 mb-0">
                <i class="fas fa-info-circle"></i>
                Requires Laravel Scheduler running: <code>php artisan schedule:run</code> (via cron every minute)
            </p>
        </x-table-panel>
    </div>
</div>

<x-table-panel title="Backup History" icon="fas fa-history">
    <div class="table-responsive">
        <table class="table table-modern table-striped mb-0" style="min-width:750px">
            <thead>
                <tr>
                    <th>#</th><th>Filename</th><th>Type</th><th>Size</th>
                    <th>Status</th><th>Created</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><small>{{ $backup->filename }}</small></td>
                    <td><span class="badge bg-secondary">{{ ucfirst($backup->type) }}</span></td>
                    <td>{{ $backup->formatted_size }}</td>
                    <td>{!! $backup->status_badge !!}</td>
                    <td>{{ $backup->created_at->format('d M Y H:i') }}</td>
                    <td>
                        <div class="table-actions-inline">
                        @if($backup->status === 'success')
                        <a href="{{ route('backend.admin.backup.download', $backup->id) }}"
                           class="table-actions-btn table-actions-btn--primary"
                           title="Download" aria-label="Download">
                            <i class="fas fa-download"></i>
                        </a>
                        <button class="table-actions-btn table-actions-btn--warning"
                                type="button"
                                data-toggle="modal"
                                data-target="#restoreModal{{ $backup->id }}"
                                title="Restore" aria-label="Restore">
                            <i class="fas fa-undo"></i>
                        </button>
                        @endif
                        <form class="table-actions__form-inline" action="{{ route('backend.admin.backup.delete', $backup->id) }}"
                              method="POST"
                              data-confirm="Delete this backup permanently?"
                              data-confirm-title="Delete backup"
                              data-confirm-ok="Delete"
                              data-confirm-variant="danger">
                            @csrf @method('DELETE')
                            <button type="submit" class="table-actions-btn table-actions-btn--danger"
                                    title="Delete" aria-label="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        </div>
                    </td>
                </tr>
                @if($backup->status === 'success')
                <div class="modal fade" id="restoreModal{{ $backup->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning"></i> Confirm Restore</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    <strong>WARNING:</strong> This will overwrite ALL current data with the backup from
                                    <strong>{{ $backup->created_at->format('d M Y H:i') }}</strong>.
                                    This action cannot be undone.
                                </div>
                                <p>Backup: <strong>{{ $backup->filename }}</strong></p>
                                <p>Size: <strong>{{ $backup->formatted_size }}</strong></p>
                                <form action="{{ route('backend.admin.backup.restore', $backup->id) }}" method="POST" class="form-modern">
                                    @csrf
                                    <x-form-field label="Type RESTORE to confirm" name="confirm" col="12">
                                        <input type="text" name="confirm" class="form-control" placeholder="RESTORE" required>
                                    </x-form-field>
                                    <button type="submit" class="btn btn-modern btn-modern--danger w-100">
                                        <i class="fas fa-undo"></i> Restore Database
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($backup->status === 'failed')
                <tr>
                    <td colspan="7">
                        <small class="text-danger"><i class="fas fa-exclamation-circle"></i> Error: {{ $backup->error_message }}</small>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="7" class="text-center py-4">No backups found. Run your first backup now.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($backups->hasPages())
    <div class="datatable-footer">
        {{ $backups->links() }}
    </div>
    @endif
</x-table-panel>
@endsection
