@extends('backend.master')

@section('title', 'Overdue Installments')

@section('content')
<x-table-panel title="Overdue Installments" icon="fas fa-exclamation-circle" accent="danger">
    <x-slot:tools>
        <a href="{{ route('backend.admin.installment-dashboard.index') }}" class="btn btn-modern btn-modern--ghost btn-modern--sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </x-slot:tools>

    <table class="table table-modern table-striped w-100" id="overdueTable">
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
                <th>Days Overdue</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</x-table-panel>
@endsection

@push('scripts')
<script>
$(function() {
    initModernDataTable('#overdueTable', {
        processing: true,
        serverSide: true,
        ajax: '{{ route("backend.admin.installment-dashboard.overdue") }}',
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'customer' },
            { data: 'customer_phone' },
            { data: 'plan_id' },
            { data: 'installment_no' },
            { data: 'due_date' },
            { data: 'amount' },
            { data: 'remaining' },
            { data: 'days_overdue' },
            { data: 'action', orderable: false },
        ],
        order: [[5, 'asc']],
    });
});
</script>
@endpush
