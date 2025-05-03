<div class="fs-6 fw-semibold text-gray-700">Showing 1 to {{$data->perpage()}} of {{ $data->total() }} entries</div>
@php
    $params = request()->query();
    $a_params = null;
    $is = 0;
    foreach ($params as $key => $value) {
        if ($key != 'page') {
            $a_params .= '&' . $key . '=' . $value;
        }
    }
@endphp
@if ($data->hasPages())
    <ul class="pagination">
        @if ($data->total() > 1)
            @if ($data->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                </li>
            @else
                @php
                    $number = $data->currentPage() - 1;
                    $prev = url()->full() . '&page=' . $number . $a_params;
                    if (Request::segment(2) == null) {
                        $prev = url()->current() . '?page=' . $number . $a_params;
                    }
                @endphp
                <li class="page-item"><a class="page-link" href="{{ $prev }}">Previous</a></li>
            @endif
            @foreach ($data->links()->elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled">{{ $element }}</li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $data->currentPage())
                            <li class="page-item active">
                                <a class="page-link">{{ $page }}</a>
                            </li>
                        @else
                            @php
                                $url = url()->full() . '&page=' . $page . $a_params;
                                if (Request::segment(2) == null) {
                                    $url = url()->current() . '?page=' . $page . $a_params;
                                }
                            @endphp
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($data->hasMorePages())
                <li class="page-item">
                    @php
                        $number = $data->currentPage() + 1;
                        $next = url()->full() . '&page=' . $number . $a_params;
                        if (Request::segment(2) == null) {
                            $next = url()->current() . '?page=' . $number . $a_params;
                        }
                    @endphp
                    <a class="page-link" href="{{ $next }}" rel="next">Next</a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" href="#">Next</a>
                </li>
            @endif

    </ul>
@endif
<!--end::Pages-->
</div>
@endif
