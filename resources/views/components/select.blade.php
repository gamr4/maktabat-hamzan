@props([
    'name',
    'label',
    'options' => [],
    'selected' => null,
    'required' => false,
    'placeholder' => null,
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
    $invalid = $errors->has($name);
    $describedBy = collect([
        $hint ? $name . '-hint' : null,
        $invalid ? $name . '-error' : null,
    ])->filter()->implode(' ');
    $current = (string) old($name, $selected);
@endphp

<div class="field">
    <label for="{{ $id }}">
        {{ $label }}@if ($required)<span class="req" aria-hidden="true">*</span>@endif
    </label>

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        @if ($required) required @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($invalid) aria-invalid="true" @endif
        {{ $attributes->except('id')->class(['select', 'is-invalid' => $invalid]) }}
    >
        @if ($placeholder !== null)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected($current === (string) $value)>{{ $text }}</option>
        @endforeach
    </select>

    @if ($hint)
        <p class="field-hint" id="{{ $name }}-hint">{{ $hint }}</p>
    @endif

    @if ($invalid)
        <p class="field-error" id="{{ $name }}-error">
            <i class="ti ti-alert-circle" aria-hidden="true"></i>
            {{ $errors->first($name) }}
        </p>
    @endif
</div>
