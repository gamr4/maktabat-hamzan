@props(['condition' => null])

<span @class([
    'badge',
    'badge--success' => $condition === 'LIV',
    'badge--warning' => $condition === 'T30',
    'badge--neutral' => ! in_array($condition, ['LIV', 'T30'], true),
])>
    {{ $condition ?: '—' }}
</span>
