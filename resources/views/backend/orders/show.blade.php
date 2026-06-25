@extends('backend.master')

@section('title', 'Order Details #'.$order->id)

@section('content')
@php
    $sym = currency() ? currency()->symbol : '$';
    $fmt = fn($n) => $sym . ' ' . number_format((float)$n, 2, '.', ',');
@endphp

<div class="content-card p-4">
    <div class="page-header mb-4">
        <div class="page-header__info">
            <h2 class="page-header__title">Order #{{ $order->id }}</h2>
            <p class="page-header__subtitle">{{ $order->created_at->format('d M, Y') }} · {{ ucfirst($order->sale_type ?? 'cash') }} sale</p>
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
            <span class="detail-item__value">{{ $order->customer->name ?? 'N/A' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Phone</span>
            <span class="detail-item__value">{{ $order->customer->phone ?? 'N/A' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Status</span>
            <span class="detail-item__value">{!! $order->status ? '<span class="badge bg-primary">Paid</span>' : '<span class="badge bg-danger">Due</span>' !!}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Subtotal</span>
            <span class="detail-item__value">{{ $fmt($order->sub_total) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Discount</span>
            <span class="detail-item__value">{{ $fmt($order->discount) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Total</span>
            <span class="detail-item__value">{{ $fmt($order->total) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Paid</span>
            <span class="detail-item__value">{{ $fmt($order->paid) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-item__label">Due</span>
            <span class="detail-item__value">{{ $fmt($order->due) }}</span>
        </div>
    </div>

    @if ($order->installmentPlan)
    <x-table-panel title="Payment Info" icon="fas fa-credit-card" class="mb-4">
        <div class="p-3">
            @if($order->installmentPlan->guarantor)
            <p class="mb-2"><strong>Guarantor:</strong> {{ $order->installmentPlan->guarantor->name ?? 'N/A' }} — {{ $order->installmentPlan->guarantor->phone ?? 'N/A' }} — {{ $order->installmentPlan->guarantor->cnic ?? 'N/A' }}</p>
            @endif
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-item__label">Down Payment</span>
                    <span class="detail-item__value">{{ $fmt($order->installmentPlan->down_payment) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">Financed Amount</span>
                    <span class="detail-item__value">{{ $fmt($order->installmentPlan->financed_amount) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">Installment Months</span>
                    <span class="detail-item__value">{{ $order->installmentPlan->installment_months }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">Monthly Installment</span>
                    <span class="detail-item__value">{{ $fmt($order->installmentPlan->monthly_installment) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">Plan Status</span>
                    <span class="detail-item__value">{{ ucfirst($order->installmentPlan->status) }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">Start Date</span>
                    <span class="detail-item__value">{{ optional($order->installmentPlan->start_date)->format('d M, Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-item__label">End Date</span>
                    <span class="detail-item__value">{{ optional($order->installmentPlan->end_date)->format('d M, Y') }}</span>
                </div>
            </div>
        </div>
    </x-table-panel>
    @else
    <div class="alert alert-info mb-4">This order is a cash sale and has no installment plan.</div>
    @endif

    <x-table-panel title="Products" icon="fas fa-box" class="mb-4">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name ?? 'N/A' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $fmt($item->discounted_price) }}</td>
                        <td>{{ $fmt($item->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-table-panel>

    @if ($order->installmentPlan)
    <x-table-panel title="Installment Schedule" icon="fas fa-calendar-alt" class="mb-4">
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
                    @foreach($order->installmentPlan->schedules as $schedule)
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

    <x-table-panel title="Allocations" icon="fas fa-exchange-alt">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Schedule</th>
                        <th>Payment</th>
                        <th>Amount</th>
                        <th>Allocated At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->installmentPlan->paymentAllocations as $allocation)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $allocation->installmentSchedule->installment_number ?? '-' }}</td>
                        <td>{{ $allocation->transaction->paid_by ?? '-' }}</td>
                        <td>{{ $fmt($allocation->amount) }}</td>
                        <td>{{ optional($allocation->allocated_at)->format('d M, Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-table-panel>
    @endif
</div>
@endsection
