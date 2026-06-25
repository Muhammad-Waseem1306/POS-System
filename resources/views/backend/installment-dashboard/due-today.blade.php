@extends('backend.master')

@section('title', 'Due Today')

@section('content')
<x-table-panel title="Installments Due Today — {{ today()->format('d M Y') }}" icon="fas fa-calendar-day" accent="warning">
    <x-slot:tools>
        <a href="{{ route('backend.admin.installment-dashboard.index') }}" class="btn btn-modern btn-modern--ghost btn-modern--sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </x-slot:tools>

    <table class="table table-modern table-striped w-100" id="dueTodayTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Plan</th>
                <th>Inst#</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Remaining</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</x-table-panel>
@endsection

@push('scripts')
<script>
$(function() {
    initModernDataTable('#dueTodayTable', {
        processing: true,
        serverSide: true,
        ajax: '{{ route("backend.admin.installment-dashboard.due-today") }}',
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'customer' },
            { data: 'customer_phone' },
            { data: 'plan_id' },
            { data: 'installment_no' },
            { data: 'due_date' },
            { data: 'amount' },
            { data: 'remaining' },
            { data: 'action', orderable: false },
        ],
    });
});
</script>
@endpush
