<!--begin::User-->
<div class="aside-user d-flex align-items-sm-center justify-content-center py-5">
    <!--begin::Symbol-->
    <div class="symbol symbol-50px">
        <img src="assets/media/avatars/300-1.jpg" alt="" />
    </div>
    <!--end::Symbol-->
    <!--begin::Wrapper-->
    <div class="aside-user-info flex-row-fluid flex-wrap ms-5">
        <!--begin::Section-->
        <div class="d-flex">
            <!--begin::Info-->
            <div class="flex-grow-1 me-2">
                <!--begin::Username-->
                <a href="{{ url('/') }}"
                    class="text-white text-hover-primary fs-6 fw-bold">{{ Auth::user()->nickname }}</a>
                <!--end::Username-->
                <!--begin::Description-->
                <span class="text-gray-600 fw-semibold d-block fs-8 mb-1">{{ Session('role')['nm_role'] }}</span>
                <!--end::Description-->
                <!--begin::Label-->
                <div class="d-flex align-items-center text-success fs-9">
                    <span class="bullet bullet-dot bg-success me-1"></span>online
                </div>
                <!--end::Label-->
            </div>
            <!--end::Info-->
            <!--begin::User menu-->
            <div class="me-n2">
                <!--begin::Action-->
                <a href="#" class="btn btn-icon btn-sm btn-active-color-primary mt-n2"
                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-start" data-kt-menu-overflow="true">
                    <i class="ki-duotone ki-setting-2 text-muted fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </a>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img alt="Logo" src="assets/media/avatars/300-1.jpg" />
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ Auth::user()->nm_user }}
                                    <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span>
                                </div>
                                <a href="#"
                                    class="fw-semibold text-muted text-hover-primary fs-7">{{ Auth::user()->email }}</a>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    {{-- <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="account/overview.html" class="menu-link px-5">My Profile</a>
                    </div>
                    <!--end::Menu item--> --}}
                    <!--begin::Menu item-->
                    {{-- <div class="menu-item px-5">
                        <a href="apps/projects/list.html" class="menu-link px-5">
                            <span class="menu-text">My Projects</span>
                            <span class="menu-badge">
                                <span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span>
                            </span>
                        </a>
                    </div> --}}
                    {{-- <!--end::Menu item-->
                    @include('template.aside.user.subscribetion')
                    <!--begin::Menu item--> --}}
                    {{-- <div class="menu-item px-5">
                        <a href="account/statements.html" class="menu-link px-5">My Statements</a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu separator--> --}}
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    {{-- @include('template.aside.user.language') --}}
                    {{-- <!--begin::Menu item-->
                    <div class="menu-item px-5 my-1">
                        <a href="account/settings.html" class="menu-link px-5">Account Settings</a>
                    </div>
                    <!--end::Menu item--> --}}
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="{{ url('auth/logout') }}" class="menu-link px-5">Sign Out</a>
                    </div>
                    <!--end::Menu item-->
                </div>
                <!--end::User account menu-->
                <!--end::Action-->
            </div>
            <!--end::User menu-->
        </div>
        <!--end::Section-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::User-->
