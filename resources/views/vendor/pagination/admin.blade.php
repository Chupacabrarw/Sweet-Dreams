{{-- Pagination admin: maksimal 5 nomor sliding window (1-5, lalu 6-10, dst) --}}
@if ($paginator->hasPages())
    @php
        $window = 5;
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, min($current - intdiv($window, 2), $last - $window + 1));
        $end = min($last, $start + $window - 1);
        $start = max(1, $end - $window + 1);
    @endphp
    <div class="admin-pagination-inner">
        @if ($paginator->onFirstPage())
            <span class="pg-btn pg-disabled" aria-disabled="true">‹</span>
        @else
            <a class="pg-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
        @endif

        @foreach (range($start, $end) as $page)
            @if ($page == $current)
                <span class="pg-btn pg-active" aria-current="page">{{ $page }}</span>
            @else
                <a class="pg-btn" href="{{ $paginator->url($page) }}">{{ $page }}</a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a class="pg-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
        @else
            <span class="pg-btn pg-disabled" aria-disabled="true">›</span>
        @endif
    </div>
@endif
