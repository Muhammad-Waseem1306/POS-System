@extends('backend.master')

@section('title', 'Installment Dashboard')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['overdue'] }}</h3>
                        <p>Overdue Installments</p>
                        <small>{{ currency()->symbol ?? '' }}{{ number_format($stats['overdue_amount'], 2) }} total</small>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
                    <a href="{{ route('backend.admin.installment-dashboard.overdue') }}" class="small-box-footer">
                        View <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['due_today'] }}</h3>
                        <p>Due Today</p>
                        <small>{{ currency()->symbol ?? '' }}{{ number_format($stats['due_today_amount'], 2) }} total</small>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-day"></i></div>
                    <a href="{{ route('backend.admin.installment-dashboard.due-today') }}" class="small-box-footer">
                        View <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['upcoming_7'] }}</h3>
                        <p>Due Next 7 Days</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-week"></i></div>
                    <a href="{{ route('backend.admin.installment-dashboard.upcoming') }}?days=7" class="small-box-footer">
                        View <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>{{ $stats['upcoming_30'] }}</h3>
                        <p>Due Next 30 Days</p>
                    </div>
                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                    <a href="{{ route('backend.admin.installment-dashboard.upcoming') }}?days=30" class="small-box-footer">
                        View <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exclamation-circle text-danger"></i> Overdue Installments</h3>
                        <div class="card-tools">
                            <a href="{{ route('backend.admin.installment-dashboard.overdue') }}" class="btn btn-sm btn-danger">
                                View All Overdue
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-sm table-striped" id="overduePreview" style="min-width:750px">
                            <thead>
                                <tr><th>#</th><th>Customer</th><th>Phone</th><th>Plan</th><th>Inst#</th><th>Due Date</th><th>Amount</th><th>Days Overdue</th><th>Action</th></tr>
                            </thead>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
<script>
$(function() {
    $('#overduePreview').DataTable({
        processing: true, serverSide: true,
        ajax: '{{ route("backend.admin.installment-dashboard.overdue") }}',
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'customer' },
            { data: 'customer_phone' },
            { data: 'plan_id' },
            { data: 'installment_no' },
            { data: 'due_date' },
            { data: 'amount' },
            { data: 'days_overdue' },
            { data: 'action', orderable: false },
        ],
        pageLength: 10,
        order: [[5, 'asc']],
    });
});
</script>
@endpush
