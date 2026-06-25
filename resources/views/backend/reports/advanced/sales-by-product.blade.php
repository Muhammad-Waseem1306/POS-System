@extends('backend.master')
@section('title', 'Sales by Product')
@section('content')
<x-report-card title="Sales by Product" icon="fas fa-box">
    <x-slot:filters>
        <x-report-date-filter
            :start-date="$startDate->format('Y-m-d')"
            :end-date="$endDate->format('Y-m-d')"
            start-id="sales_by_product_start"
            end-id="sales_by_product_end"
        />
    </x-slot:filters>

    <table class="table table-modern table-striped">
        <thead><tr><th>#</th><th>Product</th><th>Qty Sold</th><th>Revenue</th><th>Profit</th></tr></thead>
        <tbody>
            @forelse($products as $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $row->product->name ?? 'N/A' }}</td>
                <td>{{ $row->qty_sold }}</td>
                <td>{{ number_format($row->revenue, 2) }}</td>
                <td class="{{ $row->profit >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($row->profit, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="p-0 border-0"><x-empty-state title="No data found" message="Try a different date range." /></td></tr>
            @endforelse
        </tbody>
    </table>
    @if($products->hasPages())
    <div class="datatable-footer">{{ $products->links() }}</div>
    @endif
</x-report-card>
@endsection
