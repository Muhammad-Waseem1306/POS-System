@extends('backend.master')

@section('title', 'Stock Movements')
@section('page-class', 'page-modern--no-page-title')

@section('content')
<div class="page-split page-split--wide-right stock-movements-page">
    <x-form-panel title="Manual Adjustment" icon="fas fa-sliders-h" variant="primary" class="stock-adjustment-panel">
        <form action="{{ route('backend.admin.stock-movements.adjust') }}" method="POST" class="form-modern stock-adjustment-form">
            @csrf

            <x-form-field label="Product" name="product_id" required col="12">
                <select name="product_id" id="product_id" class="form-control select2" required>
                    <option value="">Select product</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </x-form-field>

            <div class="stock-adjustment-form__hint" role="note">
                <i class="fas fa-info-circle" aria-hidden="true"></i>
                <span>Use a <strong>positive</strong> value to add stock or a <strong>negative</strong> value to remove stock.</span>
            </div>

            <x-form-field label="Adjustment Quantity" name="adjustment" required col="12">
                <input type="number" name="adjustment" id="adjustment" class="form-control"
                    placeholder="e.g. 10 or -5" required>
            </x-form-field>

            <x-form-field label="Reason" name="reason" required col="12">
                <select name="reason" id="reason" class="form-control" required>
                    <option value="">Select reason</option>
                    <option value="Physical count correction">Physical count correction</option>
                    <option value="Damaged goods">Damaged goods</option>
                    <option value="Theft/loss">Theft / Loss</option>
                    <option value="Returned from customer">Returned from customer</option>
                    <option value="Write-off">Write-off</option>
                    <option value="Other">Other</option>
                </select>
            </x-form-field>

            <x-form-field label="Notes" name="notes" col="12">
                <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Optional notes"></textarea>
            </x-form-field>

            <div class="form-panel__footer">
                <button type="submit" class="btn btn-modern btn-modern--primary">
                    <i class="fas fa-check" aria-hidden="true"></i>
                    Apply Adjustment
                </button>
            </div>
        </form>
    </x-form-panel>

    <x-table-panel title="Stock Movement History" icon="fas fa-history" accent="default">
        <div class="table-panel__filters">
            <div class="table-panel__filters-grid">
                <div class="table-panel__filter">
                    <label class="table-panel__filter-label" for="filterProduct">Product</label>
                    <select class="form-control form-control-sm select2" id="filterProduct">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="table-panel__filter">
                    <label class="table-panel__filter-label" for="filterType">Type</label>
                    <select class="form-control form-control-sm" id="filterType">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="table-panel__filter">
                    <label class="table-panel__filter-label" for="filterFrom">From</label>
                    <input type="date" class="form-control form-control-sm" id="filterFrom">
                </div>
                <div class="table-panel__filter">
                    <label class="table-panel__filter-label" for="filterTo">To</label>
                    <input type="date" class="form-control form-control-sm" id="filterTo">
                </div>
            </div>
        </div>

        <table class="table table-modern table-striped w-100" id="movementsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Before</th>
                    <th>Change</th>
                    <th>After</th>
                    <th>Reason</th>
                    <th>By</th>
                    <th>Date</th>
                </tr>
            </thead>
        </table>
    </x-table-panel>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    var table = initModernDataTable('#movementsTable', {
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
        order: [[8, 'desc']],
    });

    $('#filterProduct, #filterType, #filterFrom, #filterTo').on('change', function() {
        table.draw();
    });
});
</script>
@endpush
