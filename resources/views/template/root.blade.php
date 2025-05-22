<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="/" />
    <title>Infruity - UMKM Jual Buah Terbaik di Negeri Ini</title>
    <meta charset="utf-8" />
    <meta name="description" content="UMKM jual buah dengan harga terjangkau" />
    <meta name="keywords" content="buah, umkm, pasar" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Infruity - UMKM Olahan Buah Terbesar di Abad Ini" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Infruity" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- <link rel="canonical" href="https://preview.keenthemes.com/metronic8" /> --}}
    <link rel="shortcut icon" href="{{ asset('images/logo-infruity.png') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}">
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.bundle.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- @livewireStyles --}}
    <!--end::Global Stylesheets Bundle-->
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
    <style>
        @media (max-width: 767.98px) {
            #kt_wrapper {
                max-height: calc(100vh -100px);
                /* 70px adalah tinggi mobile-footer */
                overflow-y: auto !important;
            }
        }
    </style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="aside-enabled">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            @include('template.aside')
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                @include('template.header')
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid main-content pb-5 pb-md-0" id="kt_content">
                    <!--begin::Post-->
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <!--begin::Container-->
                        <div id="kt_content_container" class="container-xxl">
                            @include('template.notif')
                            @yield('content')
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Post-->
                </div>
                <!--end::Content-->
                @include('template.footer')
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    {{-- @include('template.drawer') --}}
    <!--end::Main-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>
    <!--end::Scrolltop-->
    {{-- @include('template.modals') --}}
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <!--end::Vendors Javascript-->
    <!--begin::Custom Javascript(used for this page only)-->
    {{-- <script src="assets/js/custom/apps/ecommerce/catalog/products.js"></script>
    <script src="assets/js/widgets.bundle.js"></script>
    <script src="assets/js/custom/widgets.js"></script>
    <script src="assets/js/custom/apps/chat/chat.js"></script>
    <script src="assets/js/custom/utilities/modals/users-search.js"></script> --}}
    @if (isset($page_plugin_js))
        @foreach ($page_plugin_js as $item)
            <script type="text/javascript" src="{{ asset($item) }}"></script>
        @endforeach
    @endif
    @yield('script')
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
    <script>
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Unformat: 1.000 -> 1000
        function unformatNumber(numStr) {
            return numStr.replace(/[.,]/g, "");
        }

        // Bind input format handler (untuk semua elemen format-number)
        function bindFormatNumber() {
            $('.format-number').each(function() {
                // Format isi awal jika belum diformat
                let raw = unformatNumber($(this).val());
                if (!isNaN(raw) && raw !== "") {
                    $(this).val(formatNumber(Number(raw)));
                }

                // Hapus event sebelumnya lalu bind ulang
                $(this).off('input').on('input', function() {
                    let valRaw = unformatNumber($(this).val());
                    if (!isNaN(valRaw) && valRaw !== "") {
                        $(this).val(formatNumber(Number(valRaw)));
                    } else {
                        $(this).val('');
                    }
                });
            });
        }

        $(document).ready(function() {
            bindFormatNumber(); // Jalankan saat awal

            // Sebelum form disubmit, ubah semua format-number jadi angka murni
            $('form').on('submit', function() {
                $('.format-number').each(function() {
                    $(this).val(unformatNumber($(this).val()));
                });
            });
        });

    </script>
</body>
<!--end::Body-->

</html>
