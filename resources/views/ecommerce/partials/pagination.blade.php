@if ($paginator->hasPages())
    <ul class="pagination-box">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span class="Previous">Previous</span></li>
        @else
            <li><a class="Previous" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a class="Next" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>
        @else
            <li class="disabled"><span class="Next">Next</span></li>
        @endif
    </ul>
@endif 