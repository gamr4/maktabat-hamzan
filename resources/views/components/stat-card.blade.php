@props([
    'label',
    'value',
    'icon',
    'href',
    'accent' => false,
    'trend' => '+12% ce mois',
    'trendType' => 'up',
    'sparkline' => 'orders',
    'unit' => null,
])

<a
    href="{{ $href }}"
    @class([
        'stat-card',
        'stat-card--accent' => $accent,
    ])
>
    <div class="stat-card__top">
        <span class="stat-card__label">{{ $label }}</span>
        <span class="stat-card__icon stat-card__icon--glow" aria-hidden="true">
            <i class="ti ti-{{ $icon }}"></i>
        </span>
    </div>

    <div class="stat-card__middle">
        <span class="stat-card__value">{{ $value }}@if($unit) <span style="font-size: 0.6em; font-weight: 500; opacity: 0.85; margin-left: 0.25rem;">{{ $unit }}</span>@endif</span>
        @if ($sparkline)
            <div class="stat-card__sparkline" aria-hidden="true">
                @if ($sparkline === 'orders')
                    <svg viewBox="0 0 72 26" fill="none">
                        <path d="M1 21L15 17L29 19L43 11L57 13L71 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1 21L15 17L29 19L43 11L57 13L71 4V26H1Z" fill="currentColor" fill-opacity="0.12"/>
                    </svg>
                @elseif ($sparkline === 'clients')
                    <svg viewBox="0 0 72 26" fill="none">
                        <path d="M1 22L15 18L29 13L43 15L57 9L71 3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1 22L15 18L29 13L43 15L57 9L71 3V26H1Z" fill="currentColor" fill-opacity="0.12"/>
                    </svg>
                @elseif ($sparkline === 'suppliers')
                    <svg viewBox="0 0 72 26" fill="none">
                        <path d="M1 15L15 13L29 16L43 12L57 11L71 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1 15L15 13L29 16L43 12L57 11L71 8V26H1Z" fill="currentColor" fill-opacity="0.12"/>
                    </svg>
                @else
                    <svg viewBox="0 0 72 26" fill="none">
                        <path d="M1 20L15 16L29 10L43 14L57 8L71 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1 20L15 16L29 10L43 14L57 8L71 4V26H1Z" fill="currentColor" fill-opacity="0.12"/>
                    </svg>
                @endif
            </div>
        @endif
    </div>

    @if ($trend)
        <div class="stat-card__bottom">
            <span @class([
                'stat-card__trend',
                'stat-card__trend--up' => $trendType === 'up',
                'stat-card__trend--down' => $trendType === 'down',
                'stat-card__trend--neutral' => $trendType === 'neutral',
            ])>
                @if ($trendType === 'up')
                    <i class="ti ti-trending-up" aria-hidden="true"></i>
                @elseif ($trendType === 'down')
                    <i class="ti ti-trending-down" aria-hidden="true"></i>
                @else
                    <i class="ti ti-point-filled" aria-hidden="true"></i>
                @endif
                <span>{{ $trend }}</span>
            </span>
        </div>
    @endif
</a>
