@extends('backend.master')

@section('title', 'Sales by Day')

@section('content')
<x-report-card title="Sales by Day" icon="fas fa-calendar-day">
    <x-slot:filters>
        <x-report-date-filter
            :start-date="$startDate->format('Y-m-d')"
            :end-date="$endDate->format('Y-m-d')"
            start-id="sales_by_day_start"
            end-id="sales_by_day_end"
        />
    </x-slot:filters>

    <x-slot:stats>
        <div class="col-md-3">
            <div class="report-kpi">
                <span class="report-kpi__icon report-kpi__icon--blue"><i class="fas fa-shopping-cart"></i></span>
                <div>
                    <span class="report-kpi__label">Total Orders</span>
                    <span class="report-kpi__value">{{ $sales->sum('orders') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="report-kpi">
                <span class="report-kpi__icon report-kpi__icon--green"><i class="fas fa-dollar-sign"></i></span>
                <div>
                    <span class="report-kpi__label">Total Revenue</span>
                    <span class="report-kpi__value">{{ number_format($sales->sum('total'), 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="report-kpi">
                <span class="report-kpi__icon report-kpi__icon--violet"><i class="fas fa-check"></i></span>
                <div>
                    <span class="report-kpi__label">Total Paid</span>
                    <span class="report-kpi__value">{{ number_format($sales->sum('paid'), 2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="report-kpi">
                <span class="report-kpi__icon report-kpi__icon--amber"><i class="fas fa-clock"></i></span>
                <div>
                    <span class="report-kpi__label">Total Due</span>
                    <span class="report-kpi__value">{{ number_format($sales->sum('due'), 2) }}</span>
                </div>
            </div>
        </div>
    </x-slot:stats>

    <table class="table table-modern table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Orders</th>
                <th>Revenue</th>
                <th>Paid</th>
                <th>Due</th>
            </tr>
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
            <tr>
                <td colspan="5" class="p-0 border-0">
                    <x-empty-state
                        icon="fas fa-chart-line"
                        title="No sales in this period"
                        message="Try adjusting the date range and filter again."
                    />
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-report-card>
@endsection
