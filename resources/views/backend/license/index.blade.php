@extends('backend.master')

@section('title', 'License Management')

@section('content')
@php
    $status = $license->expiry_status ?? 'lifetime';
    $statusColor = match($status) {
        'expired' => 'danger', 'critical' => 'danger',
        'warning' => 'warning', 'active' => 'success', default => 'info'
    };
@endphp

<div class="row">
    <div class="col-lg-7 mb-4 mb-lg-0">
        @if($license->id)
        <div class="alert alert-{{ $statusColor }} mb-4">
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

        <x-form-panel title="Store & License Information" icon="fas fa-key" variant="primary">
            <form action="{{ route('backend.admin.license.update') }}" method="POST" class="form-modern">
                @csrf
                <x-form-field label="Store Name" name="store_name" required col="12">
                    <input type="text" name="store_name" class="form-control"
                           value="{{ old('store_name', $license->store_name ?? '') }}" required>
                </x-form-field>
                <x-form-field label="Store Address" name="store_address" col="12">
                    <textarea name="store_address" class="form-control" rows="2">{{ old('store_address', $license->store_address ?? '') }}</textarea>
                </x-form-field>
                <div class="row">
                    <x-form-field label="Store Phone" name="store_phone" col="md-6">
                        <input type="text" name="store_phone" class="form-control"
                               value="{{ old('store_phone', $license->store_phone ?? '') }}">
                    </x-form-field>
                    <x-form-field label="Store Email" name="store_email" col="md-6">
                        <input type="email" name="store_email" class="form-control"
                               value="{{ old('store_email', $license->store_email ?? '') }}">
                    </x-form-field>
                </div>
                <hr>
                <x-form-field label="License Key" name="license_key" col="12">
                    <input type="text" name="license_key" class="form-control"
                           value="{{ old('license_key', $license->license_key ?? '') }}"
                           placeholder="XXXX-XXXX-XXXX-XXXX">
                </x-form-field>
                <div class="row">
                    <x-form-field label="License Type" name="license_type" col="md-6">
                        <select name="license_type" class="form-control">
                            @foreach(['standard', 'professional', 'enterprise'] as $lt)
                            <option value="{{ $lt }}" {{ ($license->license_type ?? 'standard') === $lt ? 'selected' : '' }}>
                                {{ ucfirst($lt) }}
                            </option>
                            @endforeach
                        </select>
                    </x-form-field>
                    <x-form-field label="Expiry Date" name="license_expires_at" col="md-6">
                        <input type="date" name="license_expires_at" class="form-control"
                               value="{{ old('license_expires_at', $license->license_expires_at?->format('Y-m-d') ?? '') }}">
                        <small class="text-muted">Leave blank for lifetime</small>
                    </x-form-field>
                </div>
                <div class="form-panel__footer">
                    <button type="submit" class="btn btn-modern btn-modern--primary">
                        <i class="fas fa-save"></i> Save License Information
                    </button>
                </div>
            </form>
        </x-form-panel>
    </div>

    @if($license->id)
    <div class="col-lg-5">
        <div class="content-card p-4">
            <h3 class="form-panel__title mb-4">
                <span class="form-panel__icon d-inline-flex mr-2" style="width:2rem;height:2rem;background:rgba(37,99,235,0.1);color:var(--page-primary);border-radius:10px;align-items:center;justify-content:center;">
                    <i class="fas fa-info-circle"></i>
                </span>
                Current License Details
            </h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-item__label">Store Name</span>
                    <span class="detail-item__value">{{ $license->store_name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">License Type</span>
                    <span class="detail-item__value"><span class="badge bg-primary">{{ ucfirst($license->license_type) }}</span></span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">Status</span>
                    <span class="detail-item__value"><span class="badge bg-{{ $statusColor }}">{{ ucfirst($status) }}</span></span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">Expiry</span>
                    <span class="detail-item__value">{{ $license->license_expires_at?->format('d M Y') ?? 'Lifetime' }}</span>
                </div>
                @if($license->license_key)
                <div class="detail-item col-12">
                    <span class="detail-item__label">License Key</span>
                    <span class="detail-item__value"><code>{{ $license->license_key }}</code></span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
