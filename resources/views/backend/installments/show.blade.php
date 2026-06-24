@extends('backend.master')

@section('title', 'Installment Details #' . $plan->id)

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-4">
      <div>
        <h4>Installment Plan #{{ $plan->id }}</h4>
        <p><strong>Customer:</strong> {{ $plan->customer->name ?? 'N/A' }}</p>
        <p><strong>Guarantor:</strong> {{ $plan->guarantor->name ?? 'N/A' }}</p>
      </div>
      <button class="btn btn-primary" onclick="window.print()">Print</button>
    </div>

    <div class="row mb-4">
      <div class="col-md-6">
        <table class="table table-bordered">
          <tr><th>Cash Price</th><td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->cash_price, 2) }}</td></tr>
          <tr><th>Installment Price</th><td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->installment_price, 2) }}</td></tr>
          <tr><th>Total Amount</th><td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->total_amount, 2) }}</td></tr>
          <tr><th>Down Payment</th><td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->down_payment, 2) }}</td></tr>
          <tr><th>Financed Amount</th><td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->financed_amount, 2) }}</td></tr>
        </table>
      </div>
      <div class="col-md-6">
        <table class="table table-bordered">
          <tr><th>Installment Months</th><td>{{ $plan->installment_months }}</td></tr>
          <tr><th>Monthly Installment</th><td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->monthly_installment, 2) }}</td></tr>
          <tr><th>Start Date</th><td>{{ optional($plan->start_date)->format('d M, Y') }}</td></tr>
          <tr><th>End Date</th><td>{{ optional($plan->end_date)->format('d M, Y') }}</td></tr>
          <tr><th>Status</th><td>{{ ucfirst($plan->status) }}</td></tr>
        </table>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5>Schedule</h5>
            <table class="table table-bordered">
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
                  <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$schedule->amount, 2) }}</td>
                  <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$schedule->paid_amount, 2) }}</td>
                  <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$schedule->remaining_amount, 2) }}</td>
                  <td>{{ ucfirst($schedule->status) }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5>Payment Allocations</h5>
            <table class="table table-bordered">
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
                  <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$allocation->amount, 2) }}</td>
                  <td>{{ optional($allocation->allocated_at)->format('d M, Y H:i') }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
