<div class="card-body pt-6">
    <!--begin::Item-->
    @foreach ($totalCustomer as $key => $item)
        @php
            $colors = ['warning', 'success', 'info', 'primary'];
            $color = $colors[$key % count($colors)];
        @endphp
        <div class="d-flex flex-stack">
            <!--begin::Symbol-->
            <div class="symbol symbol-40px me-4">
                <div class="symbol-label fs-2 fw-semibold bg-{{ $color }} text-inverse-{{ $color }}">
                    {{ strtoupper(substr($item->name, 0, 1)) }}</div>
            </div>
            <!--end::Symbol-->
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <!--begin:Author-->
                <div class="flex-grow-1 me-2">
                    <a href="pages/user-profile/overview.html"
                        class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $item->name }}</a>
                    <span class="text-muted fw-semibold d-block fs-7">{{ $item->total }} Customer</span>
                </div>
                <!--end:Author-->
                <!--begin::Actions-->
                <a href="#" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary w-30px h-30px">
                    <i class="ki-duotone ki-arrow-right fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </a>
                <!--begin::Actions-->
            </div>
            <!--end::Section-->
        </div>
        <div class="separator separator-dashed my-4"></div>
    @endforeach
</div>
