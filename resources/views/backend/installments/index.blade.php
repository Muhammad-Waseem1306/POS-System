@extends('backend.master')

@section('title', 'Installment Plans')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-3">
      <h4>Installment Plans</h4>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Order</th>
            <th>Customer</th>
            <th>Guarantor</th>
            <th>Total</th>
            <th>Due</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($plans as $plan)
          <tr>
            <td>{{ $plan->id }}</td>
            <td>{{ $plan->sale_id }}</td>
            <td>{{ $plan->customer->name ?? 'N/A' }}</td>
            <td>{{ $plan->guarantor->name ?? 'N/A' }}</td>
            <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->total_amount, 2) }}</td>
            <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$plan->schedules->sum('remaining_amount'), 2) }}</td>
            <td>{{ ucfirst($plan->status) }}</td>
            <td><a href="{{ route('backend.admin.installments.show', $plan->id) }}" class="btn btn-sm btn-primary">View</a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="mt-3">
      {{ $plans->links() }}
    </div>
  </div>
</div>
@endsection
