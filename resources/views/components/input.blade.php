@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'hint' => null,
])

@php
    $id = $attributes->get('id', $name);
    $invalid = $errors->has($name);
    $describedBy = collect([
        $hint ? $name . '-hint' : null,
        $invalid ? $name . '-error' : null,
    ])->filter()->implode(' ');
@endphp

<div class="field">
    <label for="{{ $id }}">
        {{ $label }}@if ($required)<span class="req" aria-hidden="true">*</span>@endif
    </label>

    <input
        id="{{ $id }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        @if ($required) required @endif
        @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
        @if ($invalid) aria-invalid="true" @endif
        {{ $attributes->except('id')->class(['input', 'is-invalid' => $invalid]) }}
    >

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
