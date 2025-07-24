<div class="card-body pt-5">
    @foreach ($data as $key => $item)
        @php
            $colors = ['warning', 'success', 'info', 'primary'];
            $color = $colors[$key % count($colors)];
        @endphp
        <div class="d-flex flex-stack">
            <!--begin::Wrapper-->
            <div class="d-flex align-items-center me-3">
                <!--begin::Logo-->
                <div class="symbol symbol-40px me-4">
                    <div class="symbol-label fs-2 fw-semibold bg-{{ $color }} text-inverse-{{ $color }}">
                        {{ strtoupper(substr($item->name, 0, 1)) }}</div>
                </div>
                <!--end::Logo-->
                <!--begin::Section-->
                <div class="flex-grow-1">
                    <!--begin::Text-->
                    <a href="#" class="text-gray-800 text-hover-primary fs-5 fw-bold lh-0">{{ $item->name }}</a>
                    <!--end::Text-->
                    <!--begin::Description-->
                    <span class="text-gray-500 fw-semibold d-block fs-6">{{ $item->tier_name }}</span>
                    <!--end::Description=-->
                </div>
                <!--end::Section-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Statistics-->
            <div class="d-flex align-items-center w-100 mw-125px">
                <!--begin::Progress-->
                <div class="progress h-6px w-100 me-2 bg-light-success">
                    <div class="progress-bar bg-success" role="progressbar"
                        style="width: {{ $item->progress_percentage }}%"
                        aria-valuenow="{{ $item->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <!--end::Progress-->
                <!--begin::Value-->
                <span class="text-gray-500 fw-semibold">{{ $item->progress_percentage }}%</span>
                <!--end::Value-->
            </div>
            <!--end::Statistics-->
        </div>
        <div class="separator separator-dashed my-3"></div>
    @endforeach
</div>
