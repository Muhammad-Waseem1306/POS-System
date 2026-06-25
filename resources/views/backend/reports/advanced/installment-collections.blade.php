@extends('backend.master')
@section('title', 'Installment Collections')
@section('content')
<x-report-card title="Installment Collections" icon="fas fa-hand-holding-usd">
    <x-slot:filters>
        <x-report-date-filter
            :start-date="$startDate->format('Y-m-d')"
            :end-date="$endDate->format('Y-m-d')"
            start-id="installment_collections_start"
            end-id="installment_collections_end"
        />
    </x-slot:filters>

    <div class="alert alert-info mb-3 mx-0" style="border-radius:10px;">
        <strong>Total Collected:</strong> {{ currency()->symbol ?? '' }}{{ number_format($totalCollected, 2) }}
        <span class="ml-3"><strong>Period:</strong> {{ $startDate->format('d M Y') }} – {{ $endDate->format('d M Y') }}</span>
    </div>

    <table class="table table-modern table-striped">
        <thead>
            <tr><th>#</th><th>Date</th><th>Customer</th><th>Order</th><th>Amount</th><th>Method</th><th>Collected By</th></tr>
        </thead>
        <tbody>
            @forelse($collections as $txn)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $txn->paid_at ? \Carbon\Carbon::parse($txn->paid_at)->format('d M Y') : '-' }}</td>
                <td>{{ $txn->order->installmentPlan->customer->name ?? '-' }}</td>
                <td>#{{ $txn->order_id }}</td>
                <td>{{ number_format($txn->amount, 2) }}</td>
                <td>{{ ucfirst($txn->paid_by) }}</td>
                <td>{{ $txn->user->name ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" class="p-0 border-0"><x-empty-state title="No collections found" message="Try adjusting the date range." /></td></tr>
            @endforelse
        </tbody>
    </table>
    @if($collections->hasPages())
    <div class="datatable-footer">{{ $collections->links() }}</div>
    @endif
</x-report-card>
@endsection
