@extends('backend.master')
@section('title', 'Sales by Month — ' . $year)

@section('content')
<x-report-card title="Sales by Month — {{ $year }}" icon="fas fa-calendar-alt">
    <x-slot:filters>
        <form method="GET" class="filter-bar__form form-modern">
            <div class="filter-bar__grid filter-bar__grid--compact">
                <div class="filter-bar__field">
                    <label class="form-label" for="sales_by_month_year">Year</label>
                    <select name="year" id="sales_by_month_year" class="form-control form-control-sm">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <x-filter-actions
                    :clear-url="route('backend.admin.reports.advanced.sales-by-month', ['year' => now()->year])"
                />
            </div>
        </form>
    </x-slot:filters>

    <table class="table table-modern table-striped">
        <thead>
            <tr><th>Month</th><th>Orders</th><th>Revenue</th><th>Paid</th><th>Due</th></tr>
        </thead>
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
            <tr class="font-weight-bold" style="background:#f8fafc;">
                <th>Total</th>
                <th>{{ $months->sum('orders') }}</th>
                <th>{{ number_format($months->sum('total'), 2) }}</th>
                <th>{{ number_format($months->sum('paid'), 2) }}</th>
                <th>{{ number_format($months->sum('due'), 2) }}</th>
            </tr>
        </tfoot>
    </table>
</x-report-card>
@endsection
