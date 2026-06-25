@extends('backend.master')

@section('title', 'Sale Report')

@section('content')
<x-report-card title="Sale Report" icon="fas fa-file-invoice-dollar">
    <x-slot:filters>
        <x-date-range-filter id="daterange-btn" />
    </x-slot:filters>

    <div class="table-responsive">
        <table id="datatables" class="table table-modern table-hover mb-0">
            <thead>
                <tr>
                    <th data-orderable="false">#</th>
                    <th>SaleId</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Sub Total {{ currency() ? currency()->symbol : '$' }}</th>
                    <th>Discount {{ currency() ? currency()->symbol : '$' }}</th>
                    <th>Total {{ currency() ? currency()->symbol : '$' }}</th>
                    <th>Paid {{ currency() ? currency()->symbol : '$' }}</th>
                    <th>Due {{ currency() ? currency()->symbol : '$' }}</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->customer->name ?? '-' }}</td>
                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                    <td>{{ $order->total_item }}</td>
                    <td>{{ number_format($order->sub_total, 2, '.', ',') }}</td>
                    <td>{{ number_format($order->discount, 2, '.', ',') }}</td>
                    <td>{{ number_format($order->total, 2, '.', ',') }}</td>
                    <td>{{ number_format($order->paid, 2, '.', ',') }}</td>
                    <td>{{ number_format($order->due, 2, '.', ',') }}</td>
                    <td>{{ $order->status ? 'Paid' : 'Due' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="no-print mt-3 text-end">
        <button type="button" onclick="window.print()" class="btn btn-modern btn-modern--primary">
            <i class="fas fa-print"></i> Print
        </button>
    </div>
</x-report-card>
@endsection

@push('script')
<script>
  function setDateRangeFilterLabel($btn, start, end) {
    $btn.find('.date-range-filter__value').text(
      start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY')
    );
    $btn.addClass('date-range-filter--has-value');
  }

  $(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const startDate = urlParams.get('start_date') || moment().subtract(29, 'days').format('YYYY-MM-DD');
    const endDate = urlParams.get('end_date') || moment().format('YYYY-MM-DD');
    const $btn = $('#daterange-btn');
    const startMoment = moment(startDate, 'YYYY-MM-DD');
    const endMoment = moment(endDate, 'YYYY-MM-DD');

    $btn.daterangepicker({
        opens: 'left',
        ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: startMoment,
        endDate: endMoment
      },
      function(start, end) {
        setDateRangeFilterLabel($btn, start, end);
        window.location.href = '{{ route("backend.admin.sale.report") }}?start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD');
      }
    );

    setDateRangeFilterLabel($btn, startMoment, endMoment);

    initModernDataTable('#datatables', {
        ordering: true,
        order: [[3, 'desc']],
        pageLength: 25,
        columnDefs: [{ orderable: false, targets: 0 }],
        language: {
            emptyTable: 'No sales found for the selected date range.',
        },
    });
  });
</script>
@endpush
