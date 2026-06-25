@extends('backend.master')

@section('title', 'Collection')

@section('content')
<x-form-page
    :action="route('backend.admin.due.collection', $order->id)"
    :cancel-url="route('backend.admin.orders.show', $order->id)"
    submit-label="Submit Collection"
>
    <x-form-section title="Order Summary">
        <x-form-field label="Customer" col="md-3">
            <p class="form-field__value">{{ $order->customer->name }}</p>
        </x-form-field>
        <x-form-field label="Order" col="md-3">
            <p class="form-field__value">#{{ $order->id }}</p>
        </x-form-field>
        <x-form-field label="Total" col="md-3">
            <p class="form-field__value">{{ $order->total }}</p>
        </x-form-field>
        <x-form-field label="Due" col="md-3">
            <p class="form-field__value">{{ $order->due }}</p>
        </x-form-field>
        <x-form-field label="Collection Amount" name="amount" required col="md-6">
            <input type="number" class="form-control" id="amount" placeholder="Enter amount"
                value="{{ $order->due }}" name="amount" required min="1" max="{{ $order->due }}">
        </x-form-field>
    </x-form-section>

    @if($order->installmentPlan && $order->installmentPlan->schedules->count())
    <x-form-section title="Installment Schedule">
        <div class="col-12">
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
                            <td>{{ number_format((float)$schedule->amount, 2, '.', ',') }}</td>
                            <td>{{ number_format((float)$schedule->paid_amount, 2, '.', ',') }}</td>
                            <td>{{ number_format((float)$schedule->remaining_amount, 2, '.', ',') }}</td>
                            <td>{{ ucfirst($schedule->status) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-form-section>
    @endif
</x-form-page>
@endsection
