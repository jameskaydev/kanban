@if ($paginator->hasPages())
    <nav aria-label="Page navigation" style="margin-top:20px">
        <ul class="pagination pagination-borderless justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
            <li class="page-item prev">
                <a class="page-link" href="javascript:void(0);"
                  ><i class="ti ti-chevron-left ti-xs"></i
                ></a>
              </li>
            @else
            <li class="page-item prev">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                  ><i class="ti ti-chevron-left ti-xs"></i
                ></a>
              </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <a class="page-link" href="javascript:void(0);">{{ $page }}</a>
                          </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                          </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
            <li class="page-item next">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"
                  ><i class="ti ti-chevron-right ti-xs"></i
                ></a>
              </li>

            @else
            <li class="page-item next">
                <a class="page-link" href="javascript:void(0);"
                  ><i class="ti ti-chevron-right ti-xs"></i
                ></a>
              </li>

            @endif
        </ul>
    </nav>
@endif
