@extends('backend.master')

@section('title', 'Installment Dashboard')

@section('content')
<div class="dashboard-modern">
    <div class="stat-cards-row">
        <x-stat-card
            :value="$stats['overdue']"
            label="Overdue Installments"
            :subtitle="(currency()->symbol ?? '') . number_format($stats['overdue_amount'], 2) . ' total'"
            :href="route('backend.admin.installment-dashboard.overdue')"
            icon="fas fa-exclamation-circle"
            variant="rose"
        />
        <x-stat-card
            :value="$stats['due_today']"
            label="Due Today"
            :subtitle="(currency()->symbol ?? '') . number_format($stats['due_today_amount'], 2) . ' total'"
            :href="route('backend.admin.installment-dashboard.due-today')"
            icon="fas fa-calendar-day"
            variant="amber"
        />
        <x-stat-card
            :value="$stats['upcoming_7']"
            label="Due Next 7 Days"
            :href="route('backend.admin.installment-dashboard.upcoming') . '?days=7'"
            icon="fas fa-calendar-week"
            variant="blue"
        />
        <x-stat-card
            :value="$stats['upcoming_30']"
            label="Due Next 30 Days"
            :href="route('backend.admin.installment-dashboard.upcoming') . '?days=30'"
            icon="fas fa-calendar-alt"
            variant="green"
        />
    </div>

    <x-table-panel title="Overdue Installments" icon="fas fa-exclamation-circle" accent="danger">
        <x-slot:tools>
            <a href="{{ route('backend.admin.installment-dashboard.overdue') }}" class="btn btn-modern btn-modern--danger btn-modern--sm">
                View All Overdue
            </a>
        </x-slot:tools>
        <table class="table table-modern table-striped w-100" id="overduePreview">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Plan</th>
                    <th>Inst#</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Days Overdue</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </x-table-panel>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    initModernDataTable('#overduePreview', {
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
            { data: 'days_overdue' },
            { data: 'action', orderable: false },
        ],
        pageLength: 10,
        order: [[5, 'asc']],
    });
});
</script>
@endpush
