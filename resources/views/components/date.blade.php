@props(['value' => null])

@if ($value)
    @php
        $date = $value instanceof \DateTimeInterface
            ? \Illuminate\Support\Carbon::instance($value)
            : \Illuminate\Support\Carbon::parse($value);
    @endphp
    <time datetime="{{ $date->toDateString() }}">{{ $date->format('d/m/Y') }}</time>
@else
    <span aria-hidden="true">—</span>
@endif
