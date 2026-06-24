@extends('backend.master')
@section('title', 'Sales by Employee')
@section('content')
<section class="content"><div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-tie"></i> Sales by Employee</h3>
            <div class="card-tools">
                <form class="form-inline" method="GET">
                    <input type="date" name="start_date" class="form-control form-control-sm mr-1" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="date" name="end_date" class="form-control form-control-sm mr-1" value="{{ $endDate->format('Y-m-d') }}">
                    <button class="btn btn-sm btn-primary">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
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
                    <tr><td colspan="5" class="text-center">No data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div></section>
@endsection
