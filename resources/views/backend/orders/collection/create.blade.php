@extends('backend.master')

@section('title', 'Collection')

@section('content')
<div class="card">
  <div class="card-body">
    <form action="{{ route('backend.admin.due.collection',$order->id) }}" method="post" class="accountForm">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="mb-3 col-md-3">
            <label for="title" class="form-label">
              Name
            </label>
            <p>{{$order->customer->name}}</p>
          </div>
          <div class="mb-3 col-md-3">
            <label for="title" class="form-label">
              Order
            </label>
            <p># {{$order->id}}</p>
          </div>
          <div class="mb-3 col-md-3">
            <label for="title" class="form-label">
              Total
            </label>
            <p>{{$order->total}}</p>
          </div>
          <div class="mb-3 col-md-3">
            <label for="title" class="form-label">
              Due
            </label>
            <p>{{$order->due}}</p>
          </div>
          <div class="mb-3 col-md-6">
            <label for="title" class="form-label">
              Collection Amount <span class="text-danger">*</span>
            </label>
            <input type="number" class="form-control" placeholder="Enter amount" value="{{$order->due}}" name="amount" required min="1" max="{{$order->due}}">
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <button type="submit" class="btn bg-gradient-primary">Submit</button>
          </div>
        </div>
        @if($order->installmentPlan && $order->installmentPlan->schedules->count())
        <div class="row mt-4">
          <div class="col-12">
            <h5>Installment Schedule</h5>
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
                    <td>{{ number_format((float)$schedule->amount,2,'.',',') }}</td>
                    <td>{{ number_format((float)$schedule->paid_amount,2,'.',',') }}</td>
                    <td>{{ number_format((float)$schedule->remaining_amount,2,'.',',') }}</td>
                    <td>{{ ucfirst($schedule->status) }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @endif
      </div>
    </form>
  </div>
</div>
@endsection
@push('script')
<script>
</script>
@endpush