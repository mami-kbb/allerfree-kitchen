@if ($paginator->hasPages())
    <nav class="flex justify-center" aria-label="Pagination">
        <ul class="flex items-center gap-2">
            @if ($paginator->onFirstPage())
                <li>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full text-gray-300">
                        &lt;
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center justify-center w-10 h-10 rounded-full text-primary border border-primary hover:bg-primary hover:text-white">
                        &lt;
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)

                @if (is_string($element))
                    <li>
                        <span class="flex items-center justify-center w-10 h-10 rounded-full">
                            {{ $element }}
                        </span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-primary text-white">
                                {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url}}" class="flex items-center justify-center w-10 h-10 rounded-full border text-primary border-primary hover:bg-primary hover:text-white">
                                {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center justify-center w-10 h-10 rounded-full border border-primary text-primary hover:bg-primary hover:text-white">
                        &gt;
                    </a>
                </li>
            @else
                <li>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full text-gray-300">
                        &gt;
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif