@extends('backend.master')

@section('title', 'Upcoming Installments')

@section('content')
<x-table-panel title="Upcoming Installments" icon="fas fa-calendar-alt" accent="info">
    <x-slot:tools>
        <div class="table-panel__toolbar">
            <select id="daysFilter" class="form-control form-control-sm table-panel__select">
                <option value="7" {{ request('days', 7) == 7 ? 'selected' : '' }}>Next 7 Days</option>
                <option value="14" {{ request('days') == 14 ? 'selected' : '' }}>Next 14 Days</option>
                <option value="30" {{ request('days') == 30 ? 'selected' : '' }}>Next 30 Days</option>
            </select>
            <a href="{{ route('backend.admin.installment-dashboard.index') }}" class="btn btn-modern btn-modern--ghost btn-modern--sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </x-slot:tools>

    <table class="table table-modern table-striped w-100" id="upcomingTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Plan</th>
                <th>Inst#</th>
                <th>Due Date</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</x-table-panel>
@endsection

@push('scripts')
<script>
var table;
$(function() {
    table = initModernDataTable('#upcomingTable', {
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("backend.admin.installment-dashboard.upcoming") }}',
            data: function(d) { d.days = $('#daysFilter').val(); }
        },
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'customer' },
            { data: 'customer_phone' },
            { data: 'plan_id' },
            { data: 'installment_no' },
            { data: 'due_date' },
            { data: 'amount' },
            { data: 'action', orderable: false },
        ],
        order: [[5, 'asc']],
    });
    $('#daysFilter').on('change', function() { table.draw(); });
});
</script>
@endpush
