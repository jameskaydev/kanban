@if ($paginator->hasPages())
    <nav aria-label="Page navigation" style="margin-top:20px">
        <ul class="pagination pagination-borderless justify-content-center">



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



        </ul>
    </nav>
@endif
