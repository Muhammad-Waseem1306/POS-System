@extends('backend.master')

@section('title', 'Dashboard')
@section('page-class', 'page-modern--no-page-title')

@section('content')
    @can('dashboard_view')
        <div class="dashboard-modern">
            {{-- Currency KPIs --}}
            <div class="row dashboard-row">
                <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-card__icon dashboard-stat-card__icon--blue">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="dashboard-stat-card__body">
                            <span class="dashboard-stat-card__label">Sale Subtotal</span>
                            <span class="dashboard-stat-card__value">{{ currency()->symbol ?? '' }}
                                {{ number_format((float) $sub_total, 2, '.', ',') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-card__icon dashboard-stat-card__icon--violet">
                            <i class="fas fa-percent"></i>
                        </div>
                        <div class="dashboard-stat-card__body">
                            <span class="dashboard-stat-card__label">Sale Discount</span>
                            <span class="dashboard-stat-card__value">{{ currency()->symbol ?? '' }}
                                {{ number_format((float) $discount, 2, '.', ',') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-card__icon dashboard-stat-card__icon--emerald">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="dashboard-stat-card__body">
                            <span class="dashboard-stat-card__label">Total Sales</span>
                            <span class="dashboard-stat-card__value">{{ currency()->symbol ?? '' }}
                                {{ number_format((float) $total, 2, '.', ',') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
                    <div class="dashboard-stat-card">
                        <div class="dashboard-stat-card__icon dashboard-stat-card__icon--amber">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="dashboard-stat-card__body">
                            <span class="dashboard-stat-card__label">Sale Due</span>
                            <span class="dashboard-stat-card__value">{{ currency()->symbol ?? '' }}
                                {{ number_format((float) $due, 2, '.', ',') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Count metrics --}}
            <div class="row dashboard-row">
                <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
                    <a href="{{ route('backend.admin.customers.index') }}" class="dashboard-metric-card">
                        <div class="dashboard-metric-card__top">
                            <span class="dashboard-metric-card__value">{{ $total_customer }}</span>
                            <span class="dashboard-metric-card__icon dashboard-metric-card__icon--blue">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                        <span class="dashboard-metric-card__label">Customers</span>
                        <span class="dashboard-metric-card__link">View all <i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
                    <a href="{{ route('backend.admin.products.index') }}" class="dashboard-metric-card">
                        <div class="dashboard-metric-card__top">
                            <span class="dashboard-metric-card__value">{{ $total_product }}</span>
                            <span class="dashboard-metric-card__icon dashboard-metric-card__icon--green">
                                <i class="fas fa-box"></i>
                            </span>
                        </div>
                        <span class="dashboard-metric-card__label">Products</span>
                        <span class="dashboard-metric-card__link">View all <i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
                    <a href="{{ route('backend.admin.orders.index') }}" class="dashboard-metric-card">
                        <div class="dashboard-metric-card__top">
                            <span class="dashboard-metric-card__value">{{ $total_order }}</span>
                            <span class="dashboard-metric-card__icon dashboard-metric-card__icon--amber">
                                <i class="fas fa-tags"></i>
                            </span>
                        </div>
                        <span class="dashboard-metric-card__label">Sales</span>
                        <span class="dashboard-metric-card__link">View all <i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                <div class="col-12 col-sm-6 col-xl-3 mb-3 mb-xl-0">
                    <a href="{{ route('backend.admin.orders.index') }}" class="dashboard-metric-card">
                        <div class="dashboard-metric-card__top">
                            <span class="dashboard-metric-card__value">{{ $total_sale_item }}</span>
                            <span class="dashboard-metric-card__icon dashboard-metric-card__icon--rose">
                                <i class="fas fa-layer-group"></i>
                            </span>
                        </div>
                        <span class="dashboard-metric-card__label">Sale Items</span>
                        <span class="dashboard-metric-card__link">View all <i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
            </div>

            {{-- Charts --}}
            <div class="row dashboard-row">
                <div class="col-12 col-xl-6 mb-3 mb-xl-0">
                    <div class="dashboard-chart-card">
                        <div class="dashboard-chart-card__header">
                            <div>
                                <h5 class="dashboard-chart-card__title">Daily Total Sales</h5>
                                <span class="dashboard-chart-card__subtitle">{{ $dateRange }}</span>
                            </div>
                            <div class="dashboard-chart-card__picker input-group w-auto">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="far fa-calendar-alt"></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control" id="reservation">
                            </div>
                        </div>
                        <div class="dashboard-chart-card__body">
                            <canvas id="dailySaleLineChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-6">
                    <div class="dashboard-chart-card">
                        <div class="dashboard-chart-card__header">
                            <div>
                                <h5 class="dashboard-chart-card__title">Monthly Total Sales</h5>
                                <span class="dashboard-chart-card__subtitle">For {{ $currentYear }}</span>
                            </div>
                        </div>
                        <div class="dashboard-chart-card__body">
                            <canvas id="barChartYear"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartFont = "'Plus Jakarta Sans', 'Source Sans Pro', sans-serif";
        const chartGrid = '#e2e8f0';
        const chartText = '#64748b';
        const chartBlue = '#2563eb';
        const chartBlueSoft = 'rgba(37, 99, 235, 0.12)';

        const baseChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: { family: chartFont, size: 12 },
                        color: chartText,
                        boxWidth: 12,
                        usePointStyle: true
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: chartFont }, color: chartText }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: chartGrid },
                    ticks: { font: { family: chartFont }, color: chartText }
                }
            }
        };

        new Chart(document.getElementById('dailySaleLineChart'), {
            type: 'line',
            data: {
                labels: @json($dates),
                datasets: [{
                    label: 'Sales',
                    data: @json($totalAmounts),
                    borderColor: chartBlue,
                    backgroundColor: chartBlueSoft,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: chartBlue
                }]
            },
            options: baseChartOptions
        });

        new Chart(document.getElementById('barChartYear'), {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Sales',
                    data: @json($totalAmountMonth),
                    backgroundColor: chartBlue,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: baseChartOptions
        });
    </script>
    <script>
        $(function() {
            $('#reservation').daterangepicker().on('apply.daterangepicker', function(e, picker) {
                const selectedDateRange = picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate
                    .format('YYYY-MM-DD');
                const url = new URL(window.location.href);
                url.searchParams.set('daterange', selectedDateRange);
                window.location.href = url.toString();
            });
        });
    </script>
@endpush
