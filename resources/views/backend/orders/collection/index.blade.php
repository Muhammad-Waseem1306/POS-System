@extends('backend.master')

@section('title', 'Transactions Sale #'.$order->id)

@section('content')
<x-table-panel title="Collection Transactions" icon="fas fa-receipt" accent="info">
    <div class="table-responsive">
        <table id="datatables" class="table table-modern table-hover mb-0">
            <thead>
                <tr>
                    <th data-orderable="false">#</th>
                    <th>TransactionId</th>
                    <th>Amount {{ currency() ? currency()->symbol : '$' }}</th>
                    <th>Paid By</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->transactions as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>#{{ $transaction->id }}</td>
                    <td>{{ number_format((float)$transaction->amount, 2, '.', ',') }}</td>
                    <td>{{ $transaction->paid_by }}</td>
                    <td>{{ $transaction->created_at->format('M-d Y, h:i A') }}</td>
                    <td>
                        <a class="btn btn-modern btn-modern--primary btn-modern--sm" href="{{ route('backend.admin.collectionInvoice', $transaction->id) }}">
                            <i class="fas fa-file-invoice"></i> Invoice
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No transaction found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-table-panel>
@endsection

@push('script')
<script>
$(function() {
    initModernDataTable('#datatables', {
        ordering: true,
        order: [[4, 'desc']],
        pageLength: 10,
    });
});
</script>
@endpush
