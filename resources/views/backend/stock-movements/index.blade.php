@extends('backend.master')

@section('title', 'Stock Movements')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="row">
            {{-- Stock Adjustment Form --}}
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title"><i class="fas fa-sliders-h"></i> Manual Adjustment</h3></div>
                    <div class="card-body">
                        <form action="{{ route('backend.admin.stock-movements.adjust') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Product <span class="text-danger">*</span></label>
                                <select name="product_id" class="form-control select2" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Adjustment Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="adjustment" class="form-control"
                                       placeholder="+ add stock / - remove stock" required>
                                <small class="text-muted">Use positive (+) to add stock, negative (-) to remove</small>
                            </div>
                            <div class="form-group">
                                <label>Reason <span class="text-danger">*</span></label>
                                <select name="reason" class="form-control" required>
                                    <option value="">Select Reason</option>
                                    <option value="Physical count correction">Physical count correction</option>
                                    <option value="Damaged goods">Damaged goods</option>
                                    <option value="Theft/loss">Theft / Loss</option>
                                    <option value="Returned from customer">Returned from customer</option>
                                    <option value="Write-off">Write-off</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> Apply Adjustment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Movement History --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-history"></i> Stock Movement History</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2 g-2">
                            <div class="col-12 col-md-4">
                                <select class="form-control form-control-sm select2" id="filterProduct">
                                    <option value="">All Products</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <select class="form-control form-control-sm" id="filterType">
                                    <option value="">All Types</option>
                                    @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <input type="date" class="form-control form-control-sm" id="filterFrom">
                            </div>
                            <div class="col-6 col-md-2">
                                <input type="date" class="form-control form-control-sm" id="filterTo">
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="movementsTable" style="min-width:700px">
                            <thead>
                                <tr>
                                    <th>#</th><th>Product</th><th>Type</th><th>Before</th>
                                    <th>Change</th><th>After</th><th>Reason</th><th>By</th><th>Date</th>
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
    var table = $('#movementsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("backend.admin.stock-movements.index") }}',
            data: function(d) {
                d.product_id = $('#filterProduct').val();
                d.type       = $('#filterType').val();
                d.date_from  = $('#filterFrom').val();
                d.date_to    = $('#filterTo').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'product' },
            { data: 'type_badge', orderable: false },
            { data: 'qty_before' },
            { data: 'qty_change' },
            { data: 'qty_after' },
            { data: 'reason' },
            { data: 'user' },
            { data: 'date' },
        ],
        rawColumns: ['type_badge'],
        order: [[8, 'desc']],
    });

    $('#filterProduct, #filterType, #filterFrom, #filterTo').on('change', function() {
        table.draw();
    });
});
</script>
@endpush
