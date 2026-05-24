@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

    <div class="flex gap-1 items-center">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-2 py-2 text-sm text-[#d8c6c6] bg-white border border-[#f3dede] cursor-not-allowed rounded-lg">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                class="inline-flex items-center px-2 py-2 text-sm text-[#b04a4a] bg-white border border-[#f3dede] rounded-lg hover:bg-[#fff7f7] transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center px-3 py-2 text-sm text-[#b04a4a] bg-white border border-[#f3dede] rounded-lg cursor-default">
                    {{ $element }}
                </span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page"
                            class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-[#f45b69] border border-[#f45b69] rounded-lg cursor-default">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                            class="inline-flex items-center px-3 py-2 text-sm text-[#b04a4a] bg-white border border-[#f3dede] rounded-lg hover:bg-[#fff7f7] transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                class="inline-flex items-center px-2 py-2 text-sm text-[#b04a4a] bg-white border border-[#f3dede] rounded-lg hover:bg-[#fff7f7] transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        @else
            <span class="inline-flex items-center px-2 py-2 text-sm text-[#d8c6c6] bg-white border border-[#f3dede] cursor-not-allowed rounded-lg">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </span>
        @endif

    </div>

</nav>
@endif