@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        {{-- Bagian "Showing X to Y of Z results" dan "Page X" --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 leading-5">
                    Menampilkan
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    hasil
                </p>
            </div>

            {{-- Tombol-tombol Paginasi Utama --}}
            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    {{-- Tombol "Previous" --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-3 py-1 border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-default rounded-l-md" aria-hidden="true">
                                «
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-3 py-1 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-l-md" aria-label="{{ __('pagination.previous') }}">
                            «
                        </a>
                    @endif

                    {{-- Elemen Paginasi (Nomor Halaman) --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-3 py-1 border border-gray-300 bg-white text-sm font-medium text-gray-700 -ml-px">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Nomor Halaman --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center px-3 py-1 border border-blue-500 bg-blue-100 text-sm font-medium text-blue-700 -ml-px cursor-default">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-3 py-1 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 -ml-px">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol "Next" --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-3 py-1 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-r-md -ml-px" aria-label="{{ __('pagination.next') }}">
                            »
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-3 py-1 border border-gray-300 bg-white text-sm font-medium text-gray-400 cursor-default rounded-r-md -ml-px" aria-hidden="true">
                                »
                            </span>
                        </span>
                    @endif
                </span>
            </div>
             <div>
                <p class="text-sm text-gray-700 leading-5">
                    Halaman
                    <span class="font-medium">{{ $paginator->currentPage() }}</span>
                </p>
            </div>
        </div>
    </nav>
@endif