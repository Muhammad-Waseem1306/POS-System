@props([
    'value',
    'label',
    'href' => '#',
    'linkText' => 'View',
    'icon' => 'fas fa-chart-bar',
    'variant' => 'blue',
    'subtitle' => null,
])

<a href="{{ $href }}" class="dashboard-metric-card">
    <div class="dashboard-metric-card__top">
        <div class="dashboard-metric-card__value">{{ $value }}</div>
        <div class="dashboard-metric-card__icon dashboard-metric-card__icon--{{ $variant }}">
            <i class="{{ $icon }}" aria-hidden="true"></i>
        </div>
    </div>
    <div class="dashboard-metric-card__label">{{ $label }}</div>
    @if ($subtitle)
        <small class="stat-card__subtitle">{{ $subtitle }}</small>
    @endif
    <span class="dashboard-metric-card__link">{{ $linkText }} <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
</a>
