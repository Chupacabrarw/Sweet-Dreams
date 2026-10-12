{{-- Pagination minimal ala admin (pakai: $rows->links('vendor.pagination.admin')) --}}
@if ($paginator->hasPages())
    <div class="admin-pagination-inner">
        @if ($paginator->onFirstPage())
            <span class="pg-btn pg-disabled" aria-disabled="true">‹</span>
        @else
            <a class="pg-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="pg-btn pg-dots" aria-disabled="true">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pg-btn pg-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="pg-btn" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pg-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
        @else
            <span class="pg-btn pg-disabled" aria-disabled="true">›</span>
        @endif
    </div>
@endif
