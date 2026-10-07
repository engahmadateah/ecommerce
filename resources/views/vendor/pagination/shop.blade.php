@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination') }}" class="flex flex-wrap items-center justify-center gap-2">
        @if ($paginator->onFirstPage())
            <span class="btn btn-line btn-icon pointer-events-none opacity-40" aria-hidden="true"><i class="icon-[ph--caret-left] text-lg rtl:-scale-x-100"></i></span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="btn btn-line btn-icon" aria-label="{{ __('Previous') }}"><i class="icon-[ph--caret-left] text-lg rtl:-scale-x-100"></i></a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1 text-mute">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="btn btn-ink btn-icon">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="btn btn-line btn-icon" aria-label="{{ __('Page :page', ['page' => $page]) }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="btn btn-line btn-icon" aria-label="{{ __('Next') }}"><i class="icon-[ph--caret-right] text-lg rtl:-scale-x-100"></i></a>
        @else
            <span class="btn btn-line btn-icon pointer-events-none opacity-40" aria-hidden="true"><i class="icon-[ph--caret-right] text-lg rtl:-scale-x-100"></i></span>
        @endif
    </nav>
@endif
