@extends('backend.master')

@section('title', 'Cash Register')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="row">
            {{-- Today's Register --}}
            <div class="col-md-5">
                @if(!$today)
                {{-- Open Register --}}
                <div class="card card-success">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-cash-register"></i> Open Today's Register</h3></div>
                    <div class="card-body">
                        <form action="{{ route('backend.admin.cash-register.open') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Opening Cash Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ currency()->symbol ?? '$' }}</span>
                                    </div>
                                    <input type="number" name="opening_cash" step="0.01" min="0"
                                           class="form-control" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Opening Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-lock-open"></i> Open Cash Register
                            </button>
                        </form>
                    </div>
                </div>
                @elseif($today->status === 'open')
                {{-- Close Register --}}
                <div class="card card-warning">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-cash-register"></i> Close Today's Register</h3></div>
                    <div class="card-body">
                        <p><strong>Opening Cash:</strong> {{ currency()->symbol ?? '$' }}{{ number_format($today->opening_cash, 2) }}</p>
                        <p><strong>Opened At:</strong> {{ $today->opened_at?->format('H:i A') }}</p>
                        <p><strong>Opened By:</strong> {{ $today->openedBy->name ?? '-' }}</p>
                        <hr>
                        <form action="{{ route('backend.admin.cash-register.close') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Closing Cash (Physical Count) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{ currency()->symbol ?? '$' }}</span>
                                    </div>
                                    <input type="number" name="closing_cash" step="0.01" min="0"
                                           class="form-control" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Closing Notes</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-lock"></i> Close Cash Register
                            </button>
                        </form>
                    </div>
                </div>
                @else
                {{-- Already Closed --}}
                <div class="card card-secondary">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Today's Register — Closed</h3>
                        @can('Admin')
                        <a href="{{ route('backend.admin.cash-register.edit', $today->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i> Edit Entry
                        </a>
                        @endcan
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr><th>Opening Cash</th><td>{{ number_format($today->opening_cash, 2) }}</td></tr>
                            <tr><th>Expected Cash</th><td>{{ number_format($today->expected_cash, 2) }}</td></tr>
                            <tr><th>Closing Cash</th><td>{{ number_format($today->closing_cash, 2) }}</td></tr>
                            <tr>
                                <th>Variance</th>
                                <td class="{{ $today->variance_color }}">
                                    {{ $today->variance >= 0 ? '+' : '' }}{{ number_format($today->variance, 2) }}
                                    @if($today->variance == 0)
                                        <span class="badge bg-success">Balanced</span>
                                    @elseif($today->variance > 0)
                                        <span class="badge bg-info">Surplus</span>
                                    @else
                                        <span class="badge bg-danger">Deficit</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            {{-- Register History --}}
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-history"></i> Register History</h3></div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="registerTable" style="min-width:700px">
                            <thead>
                                <tr>
                                    <th>#</th><th>Date</th><th>Opening</th><th>Expected</th>
                                    <th>Closing</th><th>Variance</th><th>Status</th><th>Opened By</th><th>Action</th>
                                </tr>
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
    $('#registerTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("backend.admin.cash-register.index") }}',
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'date' },
            { data: 'opening_cash' },
            { data: 'expected_cash' },
            { data: 'closing_cash' },
            { data: 'variance', orderable: false },
            { data: 'status', orderable: false },
            { data: 'opened_by' },
            { data: 'action', orderable: false },
        ],
        order: [[1, 'desc']],
    });
});
</script>
@endpush
