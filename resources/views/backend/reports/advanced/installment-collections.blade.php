@extends('backend.master')
@section('title', 'Installment Collections')
@section('content')
<section class="content"><div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-hand-holding-usd"></i> Installment Collections</h3>
            <div class="card-tools">
                <form class="form-inline" method="GET">
                    <input type="date" name="start_date" class="form-control form-control-sm mr-1" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="date" name="end_date" class="form-control form-control-sm mr-1" value="{{ $endDate->format('Y-m-d') }}">
                    <button class="btn btn-sm btn-primary">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Total Collected:</strong> {{ currency()->symbol ?? '' }}{{ number_format($totalCollected, 2) }}
                <span class="ml-3"><strong>Period:</strong> {{ $startDate->format('d M Y') }} to {{ $endDate->format('d M Y') }}</span>
            </div>
            <table class="table table-bordered table-striped">
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
                    <tr><td colspan="7" class="text-center">No collections found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $collections->links() }}
        </div>
    </div>
</div></section>
@endsection
