@extends('backend.master')

@section('title', 'Edit Cash Register Entry')

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-warning">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title"><i class="fas fa-edit"></i> Edit Register Entry — {{ $cashRegister->register_date->format('d M Y') }}</h3>
                        <a href="{{ route('backend.admin.cash-register.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Admin Edit:</strong> Editing a closed register entry will recalculate the expected cash and variance automatically.
                        </div>

                        <form action="{{ route('backend.admin.cash-register.update', $cashRegister->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Date</label>
                                <input type="text" class="form-control" value="{{ $cashRegister->register_date->format('d M Y') }}" disabled>
                            </div>
                            <div class="form-group">
                                <label>Opening Cash <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ currency()->symbol ?? '$' }}</span>
                                    </div>
                                    <input type="number" name="opening_cash" step="0.01" min="0"
                                           class="form-control" value="{{ old('opening_cash', $cashRegister->opening_cash) }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Closing Cash (Physical Count) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ currency()->symbol ?? '$' }}</span>
                                    </div>
                                    <input type="number" name="closing_cash" step="0.01" min="0"
                                           class="form-control" value="{{ old('closing_cash', $cashRegister->closing_cash) }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $cashRegister->closing_notes) }}</textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Current Closing</span>
                                            <span class="info-box-number text-warning">{{ currency()->symbol ?? '$' }}{{ number_format($cashRegister->closing_cash, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-text text-muted">Current Variance</span>
                                            <span class="info-box-number {{ $cashRegister->variance >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $cashRegister->variance >= 0 ? '+' : '' }}{{ number_format($cashRegister->variance, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-save"></i> Update Register Entry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
