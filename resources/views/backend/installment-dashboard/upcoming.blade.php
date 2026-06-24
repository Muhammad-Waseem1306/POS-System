@extends('backend.master')
@section('title', 'Upcoming Installments')
@section('content')
<section class="content"><div class="container-fluid">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Upcoming Installments</h3>
            <div class="card-tools d-flex align-items-center">
                <select id="daysFilter" class="form-control form-control-sm mr-2" style="width:auto">
                    <option value="7">Next 7 Days</option>
                    <option value="14">Next 14 Days</option>
                    <option value="30" {{ request('days',7)==30?'selected':'' }}>Next 30 Days</option>
                </select>
                <a href="{{ route('backend.admin.installment-dashboard.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered table-striped" id="upcomingTable" style="min-width:700px">
                <thead>
                    <tr><th>#</th><th>Customer</th><th>Phone</th><th>Plan</th><th>Inst#</th><th>Due Date</th><th>Amount</th><th>Action</th></tr>
                </thead>
            </table>
            </div>
        </div>
    </div>
</div></section>
@endsection
@push('scripts')
<script>
var table;
$(function() {
    table = $('#upcomingTable').DataTable({
        processing: true, serverSide: true,
        ajax: {
            url: '{{ route("backend.admin.installment-dashboard.upcoming") }}',
            data: function(d) { d.days = $('#daysFilter').val(); }
        },
        columns: [
            { data: 'DT_RowIndex' }, { data: 'customer' }, { data: 'customer_phone' },
            { data: 'plan_id' }, { data: 'installment_no' }, { data: 'due_date' },
            { data: 'amount' }, { data: 'action', orderable: false },
        ],
        order: [[5, 'asc']],
    });
    $('#daysFilter').on('change', function() { table.draw(); });
});
</script>
@endpush
