@extends('backend.master')

@section('title', 'Installment Plans')
@section('page-class', 'page-modern--no-page-title')

@section('content')
<x-table-panel accent="default">
    <div class="table-responsive">
        <table class="table table-modern table-hover mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Guarantor</th>
                    <th>Total</th>
                    <th>Due</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td>{{ $plan->id }}</td>
                    <td>{{ $plan->sale_id }}</td>
                    <td>{{ $plan->customer->name ?? 'N/A' }}</td>
                    <td>{{ $plan->guarantor->name ?? 'N/A' }}</td>
                    <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->total_amount, 2) }}</td>
                    <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->schedules->sum('remaining_amount'), 2) }}</td>
                    <td>{{ ucfirst($plan->status) }}</td>
                    <td>
                        <a href="{{ route('backend.admin.installments.show', $plan->id) }}" class="table-actions-btn table-actions-btn--primary" title="View" aria-label="View">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">No installment plans found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($plans->hasPages())
    <div class="datatable-footer">
        {{ $plans->links() }}
    </div>
    @endif
</x-table-panel>
@endsection
