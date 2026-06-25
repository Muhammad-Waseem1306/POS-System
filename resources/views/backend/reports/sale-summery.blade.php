@extends('backend.master')

@section('title', 'Sale Summary')

@section('content')
@php
    $symbol = currency() ? currency()->symbol : '$';
    $formatMoney = fn ($amount) => $symbol . ' ' . number_format((float) $amount, 2);
@endphp

<x-report-card title="Sale Summary" icon="fas fa-chart-pie">
    <x-slot:filters>
        <x-date-range-filter id="daterange-btn" />
    </x-slot:filters>

    <x-slot:stats>
        <div class="col-sm-6 col-xl-3">
            <div class="report-kpi">
                <span class="report-kpi__icon report-kpi__icon--blue"><i class="fas fa-receipt"></i></span>
                <div>
                    <span class="report-kpi__label">Subtotal</span>
                    <span class="report-kpi__value">{{ $formatMoney($sub_total) }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="report-kpi">
                <span class="report-kpi__icon report-kpi__icon--violet"><i class="fas fa-percent"></i></span>
                <div>
                    <span class="report-kpi__label">Total Discount</span>
                    <span class="report-kpi__value">{{ $formatMoney($discount) }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="report-kpi">
                <span class="report-kpi__icon report-kpi__icon--green"><i class="fas fa-shopping-cart"></i></span>
                <div>
                    <span class="report-kpi__label">Total Sold</span>
                    <span class="report-kpi__value">{{ $formatMoney($total) }}</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="report-kpi">
                <span class="report-kpi__icon report-kpi__icon--amber"><i class="fas fa-clock"></i></span>
                <div>
                    <span class="report-kpi__label">Customer Due</span>
                    <span class="report-kpi__value">{{ $formatMoney($due) }}</span>
                </div>
            </div>
        </div>
    </x-slot:stats>

    <div class="report-summary">
        <div class="report-summary__meta">
            <span class="report-summary__period">
                <i class="far fa-calendar-alt" aria-hidden="true"></i>
                {{ $start_date }} – {{ $end_date }}
            </span>
        </div>

        <dl class="report-summary__list">
            <div class="report-summary__row">
                <dt>Subtotal</dt>
                <dd>{{ $formatMoney($sub_total) }}</dd>
            </div>
            <div class="report-summary__row">
                <dt>Total Discount</dt>
                <dd class="report-summary__value--discount">{{ $formatMoney($discount) }}</dd>
            </div>
            <div class="report-summary__row report-summary__row--highlight">
                <dt>Total Sold</dt>
                <dd>{{ $formatMoney($total) }}</dd>
            </div>
            <div class="report-summary__row">
                <dt>Customer Paid</dt>
                <dd class="report-summary__value--paid">{{ $formatMoney($paid) }}</dd>
            </div>
            <div class="report-summary__row report-summary__row--due{{ (float) $due > 0 ? ' report-summary__row--due-active' : '' }}">
                <dt>Customer Due</dt>
                <dd>{{ $formatMoney($due) }}</dd>
            </div>
        </dl>

        <div class="report-summary__footer no-print">
            <button type="button" onclick="window.print()" class="btn btn-modern btn-modern--primary">
                <i class="fas fa-print"></i> Print Summary
            </button>
        </div>
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
        window.location.href = '{{ route("backend.admin.sale.summery") }}?start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD');
      }
    );

    setDateRangeFilterLabel($btn, startMoment, endMoment);
  });
</script>
@endpush
