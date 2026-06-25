@extends('backend.master')
@section('title', 'Sales by Employee')
@section('content')
<x-report-card title="Sales by Employee" icon="fas fa-user-tie">
    <x-slot:filters>
        <x-report-date-filter
            :start-date="$startDate->format('Y-m-d')"
            :end-date="$endDate->format('Y-m-d')"
            start-id="sales_by_employee_start"
            end-id="sales_by_employee_end"
        />
    </x-slot:filters>

    <table class="table table-modern table-striped">
        <thead><tr><th>#</th><th>Employee</th><th>Orders</th><th>Revenue</th><th>Paid</th></tr></thead>
        <tbody>
            @forelse($employees as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->user->name ?? 'Unknown' }}</td>
                <td>{{ $row->orders }}</td>
                <td>{{ number_format($row->total, 2) }}</td>
                <td>{{ number_format($row->paid, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-0 border-0"><x-empty-state title="No data found" message="Try a different date range." /></td></tr>
            @endforelse
        </tbody>
    </table>
</x-report-card>
@endsection
