@extends('backend.master')
@section('title', 'License Management')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                @php
                    $status = $license->expiry_status ?? 'lifetime';
                    $statusColor = match($status) {
                        'expired' => 'danger', 'critical' => 'danger',
                        'warning' => 'warning', 'active' => 'success', default => 'info'
                    };
                @endphp

                @if($license->id)
                <div class="alert alert-{{ $statusColor }}">
                    @if($status === 'lifetime')
                        <i class="fas fa-infinity"></i> <strong>Lifetime License</strong> — No expiry date set.
                    @elseif($status === 'expired')
                        <i class="fas fa-times-circle"></i> <strong>License Expired</strong> — Expired on {{ $license->license_expires_at->format('d M Y') }}
                    @elseif($status === 'critical')
                        <i class="fas fa-exclamation-triangle"></i> <strong>License Expiring Soon</strong> — {{ $license->days_until_expiry }} day(s) remaining!
                    @elseif($status === 'warning')
                        <i class="fas fa-exclamation-circle"></i> <strong>License Expires</strong> in {{ $license->days_until_expiry }} days ({{ $license->license_expires_at->format('d M Y') }})
                    @else
                        <i class="fas fa-check-circle"></i> <strong>License Active</strong> — Valid until {{ $license->license_expires_at?->format('d M Y') ?? 'Lifetime' }}
                    @endif
                </div>
                @endif

                <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-key"></i> Store & License Information</h3></div>
                    <div class="card-body">
                        <form action="{{ route('backend.admin.license.update') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Store Name <span class="text-danger">*</span></label>
                                <input type="text" name="store_name" class="form-control"
                                       value="{{ old('store_name', $license->store_name ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Store Address</label>
                                <textarea name="store_address" class="form-control" rows="2">{{ old('store_address', $license->store_address ?? '') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Store Phone</label>
                                        <input type="text" name="store_phone" class="form-control"
                                               value="{{ old('store_phone', $license->store_phone ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Store Email</label>
                                        <input type="email" name="store_email" class="form-control"
                                               value="{{ old('store_email', $license->store_email ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <label>License Key</label>
                                <input type="text" name="license_key" class="form-control"
                                       value="{{ old('license_key', $license->license_key ?? '') }}"
                                       placeholder="XXXX-XXXX-XXXX-XXXX">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>License Type</label>
                                        <select name="license_type" class="form-control">
                                            @foreach(['standard', 'professional', 'enterprise'] as $lt)
                                            <option value="{{ $lt }}" {{ ($license->license_type ?? 'standard') === $lt ? 'selected' : '' }}>
                                                {{ ucfirst($lt) }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Expiry Date <small class="text-muted">(leave blank for lifetime)</small></label>
                                        <input type="date" name="license_expires_at" class="form-control"
                                               value="{{ old('license_expires_at', $license->license_expires_at?->format('Y-m-d') ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Save License Information
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @if($license->id)
            <div class="col-md-6">
                <div class="card card-info card-outline">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-info-circle"></i> Current License Details</h3></div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><th>Store Name</th><td>{{ $license->store_name }}</td></tr>
                            <tr><th>License Type</th><td><span class="badge bg-primary">{{ ucfirst($license->license_type) }}</span></td></tr>
                            <tr><th>Status</th><td><span class="badge bg-{{ $statusColor }}">{{ ucfirst($status) }}</span></td></tr>
                            <tr><th>Expiry</th><td>{{ $license->license_expires_at?->format('d M Y') ?? 'Lifetime' }}</td></tr>
                            @if($license->license_key)
                            <tr><th>License Key</th><td><code>{{ $license->license_key }}</code></td></tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
