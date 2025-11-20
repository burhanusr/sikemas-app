@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="mt-6 flex items-center justify-between">
        {{-- Mobile View --}}
        <div class="flex flex-1 justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="cursor-not-allowed rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-400">←
                    Sebelumnya</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-green-700 transition hover:border-green-400 hover:bg-green-50">←
                    Sebelumnya</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-green-700 transition hover:border-green-400 hover:bg-green-50">Berikutnya
                    →</a>
            @else
                <span class="cursor-not-allowed rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-400">Berikutnya
                    →</span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    Menampilkan
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    hingga
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    hasil
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span
                            class="relative inline-flex cursor-default items-center rounded-l-md border border-gray-200 bg-gray-100 px-3 py-2 text-sm font-medium text-gray-400">←</span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}"
                            class="relative inline-flex items-center rounded-l-md border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-green-700 transition hover:border-green-400 hover:bg-green-50">←</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- Dots --}}
                        @if (is_string($element))
                            <span
                                class="relative inline-flex items-center border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-500">{{ $element }}</span>
                        @endif

                        {{-- Array of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span
                                        class="relative inline-flex items-center border border-green-600 bg-green-600 px-3 py-2 text-sm font-medium text-white">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="relative inline-flex items-center border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-green-700 transition hover:border-green-400 hover:bg-green-50">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}"
                            class="relative inline-flex items-center rounded-r-md border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-green-700 transition hover:border-green-400 hover:bg-green-50">→</a>
                    @else
                        <span
                            class="relative inline-flex cursor-default items-center rounded-r-md border border-gray-200 bg-gray-100 px-3 py-2 text-sm font-medium text-gray-400">→</span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
