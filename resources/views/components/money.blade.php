@props(['value' => 0])

<span {{ $attributes->class(['tnum']) }}>{{ number_format((float) $value, 2, ',', ' ') }} DH</span>
