<!--begin::Header-->
<div id="kt_app_header" class="app-header" data-kt-sticky="true"
data-kt-sticky-activate="{default: false, lg: true}" data-kt-sticky-name="app-header-sticky"
data-kt-sticky-offset="{default: false, lg: '300px'}">
<!--begin::Header container-->
<div class="app-container container-xxl d-flex align-items-stretch justify-content-between"
    id="kt_app_header_container">
    <!--begin::Header mobile toggle-->
    <div class="d-flex align-items-center d-lg-none ms-n3 me-2" title="Show sidebar menu">
        <div class="btn btn-icon btn-color-gray-600 btn-active-color-primary w-35px h-35px"
            id="kt_app_header_menu_toggle">
            <i class="ki-outline ki-abstract-14 fs-2"></i>
        </div>
    </div>
    <!--end::Header mobile toggle-->
    <!--begin::Logo-->
    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
        <a href="{{url('/')}}">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-infruity.png') }}" class="h-25px d-lg-none" />
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-infruity.png') }}"
                class="h-25px d-none d-lg-inline app-sidebar-logo-default theme-light-show" />
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-infruity.png') }}"
                class="h-25px d-none d-lg-inline app-sidebar-logo-default theme-dark-show" />
        </a>
    </div>
    <!--end::Logo-->
    <!--begin::Header wrapper-->
    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1"
        id="kt_app_header_wrapper">
        <!--begin::Menu wrapper-->
        <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
            data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
            data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
            data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
            data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
            data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
            <!--begin::Menu-->
            <div class="menu menu-rounded menu-active-bg menu-state-primary menu-column menu-lg-row menu-title-gray-700 menu-icon-gray-500 menu-arrow-gray-500 menu-bullet-gray-500 my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
                id="kt_app_header_menu" data-kt-menu="true">
                <!--begin:Menu item-->
                {{-- @include('template.menu.dashboards') --}}
                @include('template.menu.main-menu')
                <!--end:Menu item-->
                {{-- @include('template.menu.pages') --}}
                {{-- @include('template.menu.apps')                 --}}
                {{-- @include('template.menu.helps') --}}
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
        <!--begin::Navbar-->
        <div class="app-navbar flex-shrink-0">
            {{-- @include('template.header.search') --}}
            @include('template.header.notifications')
            @include('template.header.quick-links')
            {{-- @include('template.header.button-chat') --}}
            @include('template.header.user-menu')
            <!--begin::Header menu toggle-->
            <!--end::Header menu toggle-->
        </div>
        <!--end::Navbar-->
    </div>
    <!--end::Header wrapper-->
</div>
<!--end::Header container-->
</div>
<!--end::Header-->