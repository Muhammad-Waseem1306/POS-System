@extends('backend.master')

@section('title', 'Installment Details #' . $plan->id)

@section('content')
@php
    $sym = currency() ? currency()->symbol : '$';
    $fmt = fn($n) => $sym . ' ' . number_format((float)$n, 2);
@endphp

<div class="content-card p-4">
    <div class="page-header mb-4">
        <div class="page-header__info">
            <h2 class="page-header__title">Installment Plan #{{ $plan->id }}</h2>
            <p class="page-header__subtitle">{{ $plan->customer->name ?? 'N/A' }} · {{ ucfirst($plan->status) }}</p>
        </div>
        <div class="page-header__actions">
            <button class="btn btn-modern btn-modern--ghost" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </button>
        </div>
    </div>

    <div class="detail-grid mb-4">
        <div class="detail-item">
            <span class="detail-item__label">Customer</span>
            <span class="detail-item__value">{{ $plan->customer->name ?? 'N/A' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Guarantor</span>
            <span class="detail-item__value">{{ $plan->guarantor->name ?? 'N/A' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Cash Price</span>
            <span class="detail-item__value">{{ $fmt($plan->cash_price) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Installment Price</span>
            <span class="detail-item__value">{{ $fmt($plan->installment_price) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Total Amount</span>
            <span class="detail-item__value">{{ $fmt($plan->total_amount) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Down Payment</span>
            <span class="detail-item__value">{{ $fmt($plan->down_payment) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Financed Amount</span>
            <span class="detail-item__value">{{ $fmt($plan->financed_amount) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Installment Months</span>
            <span class="detail-item__value">{{ $plan->installment_months }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Monthly Installment</span>
            <span class="detail-item__value">{{ $fmt($plan->monthly_installment) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Start Date</span>
            <span class="detail-item__value">{{ optional($plan->start_date)->format('d M, Y') }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">End Date</span>
            <span class="detail-item__value">{{ optional($plan->end_date)->format('d M, Y') }}</span>
        </div>
    </div>

    <x-table-panel title="Schedule" icon="fas fa-calendar-alt" class="mb-4">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Due Date</th>
                        <th>Amount</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plan->schedules as $schedule)
                    <tr>
                        <td>{{ $schedule->installment_number }}</td>
                        <td>{{ $schedule->due_date->format('d M, Y') }}</td>
                        <td>{{ $fmt($schedule->amount) }}</td>
                        <td>{{ $fmt($schedule->paid_amount) }}</td>
                        <td>{{ $fmt($schedule->remaining_amount) }}</td>
                        <td>{{ ucfirst($schedule->status) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-table-panel>

    <x-table-panel title="Payment Allocations" icon="fas fa-exchange-alt">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Schedule</th>
                        <th>Amount</th>
                        <th>Paid At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plan->paymentAllocations as $allocation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $allocation->installmentSchedule->installment_number ?? '-' }}</td>
                        <td>{{ $fmt($allocation->amount) }}</td>
                        <td>{{ optional($allocation->allocated_at)->format('d M, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-table-panel>
</div>
@endsection
