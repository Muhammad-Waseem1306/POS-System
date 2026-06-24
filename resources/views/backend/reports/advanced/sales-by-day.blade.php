@extends('backend.master')
@section('title', 'Sales by Day')
@section('content')
<section class="content"><div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-day"></i> Sales by Day</h3>
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
            <div class="row mb-3">
                <div class="col-md-3"><div class="info-box bg-primary"><span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span><div class="info-box-content"><span class="info-box-text">Total Orders</span><span class="info-box-number">{{ $sales->sum('orders') }}</span></div></div></div>
                <div class="col-md-3"><div class="info-box bg-success"><span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span><div class="info-box-content"><span class="info-box-text">Total Revenue</span><span class="info-box-number">{{ number_format($sales->sum('total'), 2) }}</span></div></div></div>
                <div class="col-md-3"><div class="info-box bg-info"><span class="info-box-icon"><i class="fas fa-check"></i></span><div class="info-box-content"><span class="info-box-text">Total Paid</span><span class="info-box-number">{{ number_format($sales->sum('paid'), 2) }}</span></div></div></div>
                <div class="col-md-3"><div class="info-box bg-warning"><span class="info-box-icon"><i class="fas fa-clock"></i></span><div class="info-box-content"><span class="info-box-text">Total Due</span><span class="info-box-number">{{ number_format($sales->sum('due'), 2) }}</span></div></div></div>
            </div>
            <table class="table table-bordered table-striped dataTable">
                <thead>
                    <tr><th>Date</th><th>Orders</th><th>Revenue</th><th>Paid</th><th>Due</th></tr>
                </thead>
                <tbody>
                    @forelse($sales as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->date)->format('d M Y (D)') }}</td>
                        <td>{{ $row->orders }}</td>
                        <td>{{ number_format($row->total, 2) }}</td>
                        <td>{{ number_format($row->paid, 2) }}</td>
                        <td>{{ number_format($row->due, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center">No sales in this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div></section>
@endsection
