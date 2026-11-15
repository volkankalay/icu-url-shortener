@props(['label', 'value', 'icon'])

<div class="dashboard-stat">
    <i class="fas fa-{{ $icon }}" aria-hidden="true"></i>
    <strong>{{ number_format($value, 0, ',', '.') }}</strong>
    <span>{{ $label }}</span>
</div>
