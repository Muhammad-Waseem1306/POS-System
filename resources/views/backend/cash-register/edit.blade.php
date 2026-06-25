@extends('backend.master')

@section('title', 'Edit Cash Register Entry')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <x-form-panel title="Edit Register Entry — {{ $cashRegister->register_date->format('d M Y') }}" icon="fas fa-edit" variant="warning">
            <x-slot:tools>
                <a href="{{ route('backend.admin.cash-register.index') }}" class="btn btn-modern btn-modern--ghost btn-modern--sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </x-slot:tools>

            <div class="alert alert-warning mb-4">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Admin Edit:</strong> Editing a closed register entry will recalculate the expected cash and variance automatically.
            </div>

            <form action="{{ route('backend.admin.cash-register.update', $cashRegister->id) }}" method="POST" class="form-modern">
                @csrf
                <x-form-field label="Date" col="12">
                    <input type="text" class="form-control" value="{{ $cashRegister->register_date->format('d M Y') }}" disabled>
                </x-form-field>
                <x-form-field label="Opening Cash" name="opening_cash" required col="12">
                    <x-currency-input name="opening_cash" :value="old('opening_cash', $cashRegister->opening_cash)" required />
                </x-form-field>
                <x-form-field label="Closing Cash (Physical Count)" name="closing_cash" required col="12">
                    <x-currency-input name="closing_cash" :value="old('closing_cash', $cashRegister->closing_cash)" required />
                </x-form-field>
                <x-form-field label="Notes" name="notes" col="12">
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $cashRegister->closing_notes) }}</textarea>
                </x-form-field>

                <div class="detail-grid mb-4">
                    <div class="detail-item">
                        <span class="detail-item__label">Current Closing</span>
                        <span class="detail-item__value text-warning">{{ currency()->symbol ?? '$' }}{{ number_format($cashRegister->closing_cash, 2) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-item__label">Current Variance</span>
                        <span class="detail-item__value {{ $cashRegister->variance >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $cashRegister->variance >= 0 ? '+' : '' }}{{ number_format($cashRegister->variance, 2) }}
                        </span>
                    </div>
                </div>

                <div class="form-panel__footer">
                    <button type="submit" class="btn btn-modern btn-modern--primary">
                        <i class="fas fa-save"></i> Update Register Entry
                    </button>
                </div>
            </form>
        </x-form-panel>
    </div>
</div>
@endsection
