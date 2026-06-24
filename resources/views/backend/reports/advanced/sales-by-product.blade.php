@extends('backend.master')
@section('title', 'Sales by Product')
@section('content')
<section class="content"><div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-box"></i> Sales by Product</h3>
            <div class="card-tools">
                <form class="form-inline" method="GET">
                    <input type="date" name="start_date" class="form-control form-control-sm mr-1"
                           value="{{ $startDate->format('Y-m-d') }}">
                    <input type="date" name="end_date" class="form-control form-control-sm mr-1"
                           value="{{ $endDate->format('Y-m-d') }}">
                    <button class="btn btn-sm btn-primary">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped dataTable">
                <thead><tr><th>#</th><th>Product</th><th>Qty Sold</th><th>Revenue</th><th>Profit</th></tr></thead>
                <tbody>
                    @forelse($products as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row->product->name ?? 'N/A' }}</td>
                        <td>{{ $row->qty_sold }}</td>
                        <td>{{ number_format($row->revenue, 2) }}</td>
                        <td class="{{ $row->profit >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($row->profit, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">No data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
</div></section>
@endsection
