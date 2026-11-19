@props(['label', 'value', 'icon', 'href', 'accent' => false])

<a
    href="{{ $href }}"
    @class([
        'stat-card',
        'stat-card--accent' => $accent,
    ])
>
    <span class="stat-card__top">
        <span class="stat-card__label">{{ $label }}</span>
        <span class="stat-card__icon" aria-hidden="true"><i class="ti ti-{{ $icon }}"></i></span>
    </span>
    <span class="stat-card__value">{{ $value }}</span>
</a>
