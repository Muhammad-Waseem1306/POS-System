@extends('backend.master')

@section('title', 'Order Details #'.$order->id)

@section('content')
<div class="card">
  <div class="card-body p-2 p-md-4 pt-0">
    <div class="row mb-4">
      <div class="col-md-8">
        <h4>Order #{{ $order->id }}</h4>
        <p class="mb-1">Customer: {{ $order->customer->name ?? 'N/A' }}</p>
        <p class="mb-1">Phone: {{ $order->customer->phone ?? 'N/A' }}</p>
        <p class="mb-1">Sale Type: {{ ucfirst($order->sale_type ?? 'cash') }}</p>
      </div>
      <div class="col-md-4 text-right">
        <button class="btn btn-primary" onclick="window.print()">Print</button>
      </div>
      <div class="col-12 mt-3 text-right">
        <p class="mb-1">Order Date: {{ $order->created_at->format('d M, Y') }}</p>
        <p class="mb-1">Status: {!! $order->status ? '<span class="badge bg-primary">Paid</span>' : '<span class="badge bg-danger">Due</span>' !!}</p>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Order Summary</h5>
            <p>Subtotal: {{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$order->sub_total,2,'.',',') }}</p>
            <p>Discount: {{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$order->discount,2,'.',',') }}</p>
            <p>Total: {{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$order->total,2,'.',',') }}</p>
            <p>Paid: {{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$order->paid,2,'.',',') }}</p>
            <p>Due: {{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$order->due,2,'.',',') }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Payment Info</h5>
            @if ($order->installmentPlan)
            @if($order->installmentPlan->guarantor)
            <p><strong>Guarantor:</strong> {{ $order->installmentPlan->guarantor->name ?? 'N/A' }} - {{ $order->installmentPlan->guarantor->phone ?? 'N/A' }} - {{ $order->installmentPlan->guarantor->cnic ?? 'N/A' }}</p>
            @endif
            <p>Down Payment: {{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$order->installmentPlan->down_payment,2,'.',',') }}</p>
            <p>Financed Amount: {{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$order->installmentPlan->financed_amount,2,'.',',') }}</p>
            <p>Installment Months: {{ $order->installmentPlan->installment_months }}</p>
            <p>Monthly Installment: {{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$order->installmentPlan->monthly_installment,2,'.',',') }}</p>
            <p>Plan Status: {{ ucfirst($order->installmentPlan->status) }}</p>
            <p>Start Date: {{ optional($order->installmentPlan->start_date)->format('d M, Y') }}</p>
            <p>End Date: {{ optional($order->installmentPlan->end_date)->format('d M, Y') }}</p>
            @else
            <p>This order is a cash sale and has no installment plan.</p>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Products</h5>
            <div class="table-responsive">
              <table class="table table-bordered">
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
                    <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$item->discounted_price,2,'.',',') }}</td>
                    <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$item->total,2,'.',',') }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if ($order->installmentPlan)
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Installment Schedule</h5>
            <div class="table-responsive">
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
                  @foreach($order->installmentPlan->schedules as $schedule)
                  <tr>
                    <td>{{ $schedule->installment_number }}</td>
                    <td>{{ $schedule->due_date->format('d M, Y') }}</td>
                    <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$schedule->amount,2,'.',',') }}</td>
                    <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$schedule->paid_amount,2,'.',',') }}</td>
                    <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$schedule->remaining_amount,2,'.',',') }}</td>
                    <td>{{ ucfirst($schedule->status) }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Allocations</h5>
            <div class="table-responsive">
              <table class="table table-bordered">
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
                    <td>{{ (currency() ? currency()->symbol : '$') . ' ' . number_format((float)$allocation->amount,2,'.',',') }}</td>
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
    @endif
  </div>
</div>
@endsection
