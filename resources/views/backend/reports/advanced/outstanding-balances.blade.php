@extends('backend.master')
@section('title', 'Outstanding Balances')
@section('content')
<x-report-card title="Outstanding Balances" icon="fas fa-exclamation-circle">
    <div class="alert alert-warning mb-3 mx-0" style="border-radius:10px;">
        <strong>Total Outstanding:</strong> {{ currency()->symbol ?? '' }}{{ number_format($totalOutstanding, 2) }}
    </div>

    <table class="table table-modern table-striped">
        <thead>
            <tr><th>#</th><th>Customer</th><th>Plan ID</th><th>Start Date</th><th>End Date</th><th>Total Amount</th><th>Remaining</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
            @forelse($plans as $plan)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $plan->customer->name ?? '-' }}</td>
                <td>#{{ $plan->id }}</td>
                <td>{{ $plan->start_date }}</td>
                <td>{{ $plan->end_date }}</td>
                <td>{{ number_format($plan->total_amount, 2) }}</td>
                <td class="text-danger"><strong>{{ number_format($plan->total_remaining ?? 0, 2) }}</strong></td>
                <td><span class="badge bg-{{ $plan->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($plan->status) }}</span></td>
                <td>
                    <a href="{{ route('backend.admin.installments.show', $plan->id) }}" class="table-actions-btn table-actions-btn--primary" title="View" aria-label="View">
                        <i class="fas fa-eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="p-0 border-0"><x-empty-state title="No outstanding balances" icon="fas fa-check-circle" /></td></tr>
            @endforelse
        </tbody>
    </table>
    @if($plans->hasPages())
    <div class="datatable-footer">{{ $plans->links() }}</div>
    @endif
</x-report-card>
@endsection
