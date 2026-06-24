@extends('backend.master')
@section('title', 'Overdue Installments')
@section('content')
<section class="content"><div class="container-fluid">
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-exclamation-circle"></i> Overdue Installments</h3>
            <div class="card-tools">
                <a href="{{ route('backend.admin.installment-dashboard.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped" id="overdueTable" style="min-width:800px">
                <thead>
                    <tr><th>#</th><th>Customer</th><th>Phone</th><th>Plan</th><th>Inst#</th><th>Due Date</th><th>Amount</th><th>Remaining</th><th>Days Overdue</th><th>Action</th></tr>
                </thead>
            </table>
            </div>
        </div>
    </div>
</div></section>
@endsection
@push('scripts')
<script>
$(function() {
    $('#overdueTable').DataTable({
        processing: true, serverSide: true,
        ajax: '{{ route("backend.admin.installment-dashboard.overdue") }}',
        columns: [
            { data: 'DT_RowIndex' }, { data: 'customer' }, { data: 'customer_phone' },
            { data: 'plan_id' }, { data: 'installment_no' }, { data: 'due_date' },
            { data: 'amount' }, { data: 'remaining' }, { data: 'days_overdue' },
            { data: 'action', orderable: false },
        ],
        order: [[5, 'asc']],
    });
});
</script>
@endpush
