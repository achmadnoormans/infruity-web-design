<!--begin::Footer-->
<div class="footer py-4 d-none d-md-flex flex-lg-column" id="kt_footer">
    <!--begin::Container-->
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
        <!--begin::Copyright-->
        <div class="text-gray-900 order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">{{ date('Y') }}&copy;</span>
            <a href="{{ url('/') }}" target="_blank" class="text-gray-800 text-hover-primary">Infruity</a>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="{{ url('/') }}" target="_blank" class="menu-link px-2">About</a>
            </li>
            <li class="menu-item">
                <a href="{{ url('/') }}" target="_blank" class="menu-link px-2">Support</a>
            </li>
            <li class="menu-item">
                <a href="{{ url('/') }}" target="_blank" class="menu-link px-2">Purchase</a>
            </li>
        </ul>
        <!--end::Menu-->
    </div>
    <!--end::Container-->
</div>
<!--end::Footer-->
<!-- Mobile Bottom Navigation -->
{{-- <style>
    .mobile-footer a:hover {
        color: #0d6efd !important;
        /* Bootstrap primary */
    }
</style> --}}

{{-- <div class="mobile-footer d-flex d-md-none justify-content-around align-items-center bg-dark border-top py-2 position-fixed w-100 bottom-0 shadow"
    style="z-index: 999;">
    <a href="{{ url('products') }}" class="text-center text-white">
        <i class="ki-duotone ki-home text-white"></i><br>
        <small>Home</small>
    </a>
    <a href="{{ url('products') }}" class="text-center text-white">
        <i class="ki-duotone ki-search-list text-white">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
        </i>
        <br>
        <small>Search</small>
    </a>
    <a href="{{ url('products') }}" class="text-center text-white">
        <i class="ki-duotone ki-questionnaire-tablet text-white">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
        <br>
        <small>Transaksi</small>
    </a>
    <a href="{{ url('products') }}" class="text-center text-white">
        <i class="ki-duotone ki-user-tick text-white">
            <span class="path1"></span>
            <span class="path2"></span>
            <span class="path3"></span>
        </i>
        <br>
        <small>Profil</small>
    </a>
</div> --}}
