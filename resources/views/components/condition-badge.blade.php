@props(['condition' => null])

@php
    $cond = strtoupper(trim((string) $condition));
    $isLiv = $cond === 'LIV';
    $isT30 = $cond === 'T30';
    $label = match($cond) {
        'LIV' => 'Livraison',
        'T30' => '30 jours',
        default => $condition ?: '—',
    };
@endphp

<span @class([
    'status-pill',
    'status-pill--liv' => $isLiv,
    'status-pill--t30' => $isT30,
    'status-pill--neutral' => ! $isLiv && ! $isT30,
]) title="{{ $isLiv ? 'Livraison directe (LIV)' : ($isT30 ? 'Paiement à 30 jours (T30)' : 'Condition: ' . $condition) }}">
    <span class="status-pill__dot" aria-hidden="true"></span>
    <span class="status-pill__label">{{ $label }}</span>
</span>
