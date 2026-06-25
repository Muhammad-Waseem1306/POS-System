@extends('backend.master')

@section('title', 'Cash Register')

@section('content')
<div class="page-split page-split--wide-right">
    <div>
        @if(!$today)
        <x-form-panel title="Open Today's Register" icon="fas fa-cash-register" variant="success">
            <form action="{{ route('backend.admin.cash-register.open') }}" method="POST" class="form-modern">
                @csrf
                <x-form-field label="Opening Cash Amount" name="opening_cash" required col="12">
                    <x-currency-input name="opening_cash" required />
                </x-form-field>
                <x-form-field label="Opening Notes" name="notes" col="12">
                    <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Optional notes"></textarea>
                </x-form-field>
                <div class="form-panel__footer">
                    <button type="submit" class="btn btn-modern btn-modern--primary">
                        <i class="fas fa-lock-open" aria-hidden="true"></i> Open Cash Register
                    </button>
                </div>
            </form>
        </x-form-panel>
        @elseif($today->status === 'open')
        <x-form-panel title="Close Today's Register" icon="fas fa-cash-register" variant="warning">
            <div class="detail-grid mb-3">
                <div class="detail-item">
                    <span class="detail-item__label">Opening Cash</span>
                    <span class="detail-item__value">{{ currency()->symbol ?? '$' }}{{ number_format($today->opening_cash, 2) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">Opened At</span>
                    <span class="detail-item__value">{{ $today->opened_at?->format('H:i A') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">Opened By</span>
                    <span class="detail-item__value">{{ $today->openedBy->name ?? '-' }}</span>
                </div>
            </div>
            <form action="{{ route('backend.admin.cash-register.close') }}" method="POST" class="form-modern">
                @csrf
                <x-form-field label="Closing Cash (Physical Count)" name="closing_cash" required col="12">
                    <x-currency-input name="closing_cash" required />
                </x-form-field>
                <x-form-field label="Closing Notes" name="closing_notes" col="12">
                    <textarea name="notes" id="closing_notes" class="form-control" rows="3" placeholder="Optional notes"></textarea>
                </x-form-field>
                <div class="form-panel__footer">
                    <button type="submit" class="btn btn-modern btn-modern--primary">
                        <i class="fas fa-lock" aria-hidden="true"></i> Close Cash Register
                    </button>
                </div>
            </form>
        </x-form-panel>
        @else
        <x-form-panel title="Today's Register — Closed" icon="fas fa-check-circle" variant="muted">
            <x-slot:tools>
                @can('Admin')
                <a href="{{ route('backend.admin.cash-register.edit', $today->id) }}" class="btn btn-modern btn-modern--ghost btn-modern--sm">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan
            </x-slot:tools>
            <table class="table table-modern table-sm">
                <tbody>
                    <tr><th>Opening Cash</th><td>{{ number_format($today->opening_cash, 2) }}</td></tr>
                    <tr><th>Expected Cash</th><td>{{ number_format($today->expected_cash, 2) }}</td></tr>
                    <tr><th>Closing Cash</th><td>{{ number_format($today->closing_cash, 2) }}</td></tr>
                    <tr>
                        <th>Variance</th>
                        <td class="{{ $today->variance_color }}">
                            {{ $today->variance >= 0 ? '+' : '' }}{{ number_format($today->variance, 2) }}
                            @if($today->variance == 0)
                                <span class="badge bg-success ml-1">Balanced</span>
                            @elseif($today->variance > 0)
                                <span class="badge bg-info ml-1">Surplus</span>
                            @else
                                <span class="badge bg-danger ml-1">Deficit</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </x-form-panel>
        @endif
    </div>

    <x-table-panel title="Register History" icon="fas fa-history" accent="default">
        <table class="table table-modern table-striped w-100" id="registerTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Opening</th>
                    <th>Expected</th>
                    <th>Closing</th>
                    <th>Variance</th>
                    <th>Status</th>
                    <th>Opened By</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </x-table-panel>
</div>
@endsection

@push('script')
<script>
$(function() {
    initModernDataTable('#registerTable', {
        processing: true,
        serverSide: true,
        ajax: '{{ route("backend.admin.cash-register.index") }}',
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'date' },
            { data: 'opening_cash' },
            { data: 'expected_cash' },
            { data: 'closing_cash' },
            { data: 'variance', orderable: false },
            { data: 'status', orderable: false },
            { data: 'opened_by' },
            { data: 'action', orderable: false },
        ],
        order: [[1, 'desc']],
    });
});
</script>
@endpush
