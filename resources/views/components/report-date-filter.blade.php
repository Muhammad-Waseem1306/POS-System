@props([
    'startDate',
    'endDate',
    'startId' => 'report_start_date',
    'endId' => 'report_end_date',
    'clearUrl' => null,
])

<form method="GET" {{ $attributes->merge(['class' => 'filter-bar__form form-modern']) }}>
    <div class="filter-bar__grid filter-bar__grid--date-range">
        <div class="filter-bar__field">
            <label class="form-label" for="{{ $startId }}">From</label>
            <input
                type="date"
                id="{{ $startId }}"
                name="start_date"
                class="form-control form-control-sm"
                value="{{ $startDate }}"
            >
        </div>
        <div class="filter-bar__field">
            <label class="form-label" for="{{ $endId }}">To</label>
            <input
                type="date"
                id="{{ $endId }}"
                name="end_date"
                class="form-control form-control-sm"
                value="{{ $endDate }}"
            >
        </div>
        <x-filter-actions :clear-url="$clearUrl" />
    </div>
</form>
