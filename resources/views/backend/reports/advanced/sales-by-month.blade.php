@extends('backend.master')
@section('title', 'Sales by Month')
@section('content')
<section class="content"><div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Sales by Month — {{ $year }}</h3>
            <div class="card-tools">
                <form class="form-inline" method="GET">
                    <select name="year" class="form-control form-control-sm mr-1">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button class="btn btn-sm btn-primary">Filter</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead><tr><th>Month</th><th>Orders</th><th>Revenue</th><th>Paid</th><th>Due</th></tr></thead>
                <tbody>
                    @foreach($months as $m)
                    <tr class="{{ $m['orders'] == 0 ? 'text-muted' : '' }}">
                        <td><strong>{{ $m['name'] }}</strong></td>
                        <td>{{ $m['orders'] }}</td>
                        <td>{{ number_format($m['total'], 2) }}</td>
                        <td>{{ number_format($m['paid'], 2) }}</td>
                        <td>{{ number_format($m['due'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-dark">
                        <th>Total</th>
                        <th>{{ $months->sum('orders') }}</th>
                        <th>{{ number_format($months->sum('total'), 2) }}</th>
                        <th>{{ number_format($months->sum('paid'), 2) }}</th>
                        <th>{{ number_format($months->sum('due'), 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div></section>
@endsection
