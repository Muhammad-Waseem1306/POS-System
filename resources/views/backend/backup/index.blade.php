@extends('backend.master')

@section('title', 'Backup Management')

@section('content')
<section class="content">
    <div class="container-fluid">

        {{-- Health Status Banner --}}
        @php
            $statusColor = match($health['status']) { 'healthy' => 'success', 'warning' => 'warning', 'critical' => 'danger', default => 'info' };
        @endphp
        <div class="alert alert-{{ $statusColor }} alert-dismissible">
            <h5><i class="icon fas fa-shield-alt"></i> Backup Health: {{ ucfirst($health['status']) }}</h5>
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

        <div class="row">
            {{-- Stats Cards --}}
            <div class="col-md-3">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-database"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Backup Size</span>
                        <span class="info-box-number">
                            @php
                                $bytes = $health['totalSize'];
                                echo $bytes >= 1073741824 ? number_format($bytes/1073741824,2).' GB'
                                    : ($bytes >= 1048576 ? number_format($bytes/1048576,2).' MB'
                                    : number_format($bytes/1024,2).' KB');
                            @endphp
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-{{ $health['failedToday'] > 0 ? 'danger' : 'info' }}">
                    <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Failed Today</span>
                        <span class="info-box-number">{{ $health['failedToday'] }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Hours Since Last Backup</span>
                        <span class="info-box-number">{{ $health['hoursSinceLastBackup'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Backups</span>
                        <span class="info-box-number">{{ $backups->total() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Manual Backup Card --}}
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-play-circle"></i> Run Backup Now</h3></div>
                    <div class="card-body">
                        <form action="{{ route('backend.admin.backup.run') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Backup Type</label>
                                <select name="type" class="form-control">
                                    <option value="manual">Manual</option>
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-database"></i> Run Backup
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Scheduled Backup Info --}}
            <div class="col-md-8">
                <div class="card card-info">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-calendar-alt"></i> Automatic Backup Schedule</h3></div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead><tr><th>Type</th><th>Frequency</th><th>Time</th><th>Status</th></tr></thead>
                            <tbody>
                                <tr><td>Hourly</td><td>Every Hour</td><td>:00</td><td><span class="badge bg-success">Active</span></td></tr>
                                <tr><td>Daily</td><td>Every Day</td><td>02:00 AM</td><td><span class="badge bg-success">Active</span></td></tr>
                                <tr><td>Weekly</td><td>Every Sunday</td><td>03:00 AM</td><td><span class="badge bg-success">Active</span></td></tr>
                                <tr><td>Monthly</td><td>1st of Month</td><td>04:00 AM</td><td><span class="badge bg-success">Active</span></td></tr>
                            </tbody>
                        </table>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Requires Laravel Scheduler running: <code>php artisan schedule:run</code> (via cron every minute)
                        </small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Backup History --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history"></i> Backup History</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-striped" style="min-width:750px">
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
                                @if($backup->status === 'success')
                                <a href="{{ route('backend.admin.backup.download', $backup->id) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                        data-target="#restoreModal{{ $backup->id }}">
                                    <i class="fas fa-undo"></i>
                                </button>
                                @endif
                                <form action="{{ route('backend.admin.backup.delete', $backup->id) }}"
                                      method="POST" style="display:inline"
                                      onsubmit="return confirm('Delete this backup?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @if($backup->status === 'success')
                        {{-- Restore Modal --}}
                        <div class="modal fade" id="restoreModal{{ $backup->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Confirm Restore</h5>
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
                                        <form action="{{ route('backend.admin.backup.restore', $backup->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label>Type <strong>RESTORE</strong> to confirm:</label>
                                                <input type="text" name="confirm" class="form-control" placeholder="RESTORE" required>
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-block">
                                                <i class="fas fa-undo"></i> Restore Database
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($backup->status === 'failed')
                        <tr class="bg-light">
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
            </div>
            <div class="card-footer">
                {{ $backups->links() }}
            </div>
        </div>

    </div>
</section>
@endsection
