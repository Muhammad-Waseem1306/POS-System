@extends('backend.master')
@section('title', 'Outstanding Balances')
@section('content')
<section class="content"><div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Outstanding Installment Balances</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <strong>Total Outstanding:</strong> {{ currency()->symbol ?? '' }}{{ number_format($totalOutstanding, 2) }}
            </div>
            <table class="table table-bordered table-striped">
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
                            <a href="{{ route('backend.admin.installments.show', $plan->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">No outstanding balances.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $plans->links() }}
        </div>
    </div>
</div></section>
@endsection
