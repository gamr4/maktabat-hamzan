@props(['paginator'])

@if ($paginator->hasPages())
    @php
        $links = collect($paginator->linkCollection())->slice(1, -1);
    @endphp

    <nav class="pagination" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="page-link is-disabled" aria-disabled="true" aria-label="Page précédente">
                <i class="ti ti-chevron-left" aria-hidden="true"></i>
            </span>
        @else
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Page précédente">
                <i class="ti ti-chevron-left" aria-hidden="true"></i>
            </a>
        @endif

        @foreach ($links as $link)
            @if ($link['url'] === null)
                <span class="page-ellipsis" aria-hidden="true">{{ $link['label'] }}</span>
            @elseif ($link['active'])
                <span class="page-link is-active" aria-current="page">{{ $link['label'] }}</span>
            @else
                <a class="page-link" href="{{ $link['url'] }}" aria-label="Page {{ $link['label'] }}">{{ $link['label'] }}</a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Page suivante">
                <i class="ti ti-chevron-right" aria-hidden="true"></i>
            </a>
        @else
            <span class="page-link is-disabled" aria-disabled="true" aria-label="Page suivante">
                <i class="ti ti-chevron-right" aria-hidden="true"></i>
            </span>
        @endif
    </nav>
@endif
