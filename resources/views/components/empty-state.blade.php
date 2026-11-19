@props(['icon' => 'inbox', 'title', 'text' => null])

<div class="empty">
    <span class="empty__icon" aria-hidden="true"><i class="ti ti-{{ $icon }}"></i></span>
    <h3>{{ $title }}</h3>
    @if ($text)
        <p>{{ $text }}</p>
    @endif
    {{ $slot }}
</div>
