@extends('template.root')

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Row-->
                <div class="row g-5 g-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-4 mb-xl-10">
                        <!--begin::Lists Widget 19-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Heading-->
                            <div class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-250px"
                                style="background-image:url('assets/media/svg/shapes/top-green.png" data-bs-theme="light">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column text-white pt-15">
                                    <span class="fw-bold fs-2x mb-3">Overview</span>
                                    <div class="fs-4 text-white">
                                        <span class="opacity-75">You have</span>
                                        <span class="position-relative d-inline-block">
                                            <a href="{{ route('crm.dashboard') }}"
                                                class="link-white opacity-75-hover fw-bold d-block mb-1">4 data</a>
                                            <!--begin::Separator-->
                                            <span
                                                class="position-absolute opacity-50 bottom-0 start-0 border-2 border-body border-bottom w-100"></span>
                                            <!--end::Separator-->
                                        </span>
                                        <span class="opacity-75">to overview</span>
                                    </div>
                                </h3>
                                <!--end::Title-->
                                <!--begin::Toolbar-->
                                <div class="card-toolbar pt-5">
                                    <!--begin::Menu-->
                                    <button
                                        class="btn btn-sm btn-icon btn-active-color-primary btn-color-white bg-white bg-opacity-25 bg-hover-opacity-100 bg-hover-white bg-active-opacity-25 w-20px h-20px"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end"
                                        data-kt-menu-overflow="true">
                                        <i class="ki-duotone ki-dots-square fs-4">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                        </i>
                                    </button>
                                    <!--begin::Menu 2-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px"
                                        data-kt-menu="true">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">Quick Actions
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu separator-->
                                        <div class="separator mb-3 opacity-75"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">New Ticket</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">New Customer</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3" data-kt-menu-trigger="hover"
                                            data-kt-menu-placement="right-start">
                                            <!--begin::Menu item-->
                                            <a href="#" class="menu-link px-3">
                                                <span class="menu-title">New Group</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <!--end::Menu item-->
                                            <!--begin::Menu sub-->
                                            <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">Admin Group</a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">Staff Group</a>
                                                </div>
                                                <!--end::Menu item-->
                                                <!--begin::Menu item-->
                                                <div class="menu-item px-3">
                                                    <a href="#" class="menu-link px-3">Member Group</a>
                                                </div>
                                                <!--end::Menu item-->
                                            </div>
                                            <!--end::Menu sub-->
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">New Contact</a>
                                        </div>
                                        <!--end::Menu item-->
                                        <!--begin::Menu separator-->
                                        <div class="separator mt-3 opacity-75"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <div class="menu-content px-3 py-3">
                                                <a class="btn btn-primary btn-sm px-4" href="#">Generate Reports</a>
                                            </div>
                                        </div>
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu 2-->
                                    <!--end::Menu-->
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Heading-->
                            <!--begin::Body-->
                            <div class="card-body mt-n20">
                                <!--begin::Stats-->
                                <div class="mt-n20 position-relative">
                                    <!--begin::Row-->
                                    <div class="row g-3 g-lg-6">
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">
                                                        <i class="ki-duotone ki-flask fs-1 text-primary">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ $totalCustomer }}</span>
                                                    <!--end::Number-->
                                                    <!--begin::Desc-->
                                                    <span class="text-gray-500 fw-semibold fs-6">New Customer</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">
                                                        <i class="ki-duotone ki-bank fs-1 text-primary">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ $totalCustomer }}</span>
                                                    <!--end::Number-->
                                                    <!--begin::Desc-->
                                                    <span class="text-gray-500 fw-semibold fs-6">Total Customer</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">
                                                        <i class="ki-duotone ki-award fs-1 text-primary">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ $totalCustomer }}</span>
                                                    <!--end::Number-->
                                                    <!--begin::Desc-->
                                                    <span class="text-gray-500 fw-semibold fs-6">Customer Active</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-6">
                                            <!--begin::Items-->
                                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                                <!--begin::Symbol-->
                                                <div class="symbol symbol-30px me-5 mb-8">
                                                    <span class="symbol-label">
                                                        <i class="ki-duotone ki-timer fs-1 text-primary">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </span>
                                                </div>
                                                <!--end::Symbol-->
                                                <!--begin::Stats-->
                                                <div class="m-0">
                                                    <!--begin::Number-->
                                                    <span
                                                        class="text-gray-700 fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">{{ $totalCustomer }}</span>
                                                    <!--end::Number-->
                                                    <!--begin::Desc-->
                                                    <span class="text-gray-500 fw-semibold fs-6">Customer inactive</span>
                                                    <!--end::Desc-->
                                                </div>
                                                <!--end::Stats-->
                                            </div>
                                            <!--end::Items-->
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Stats-->
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::Lists Widget 19-->
                    </div>
                    <!--end::Col-->
                    <div class="col-lg-12 col-xl-8 col-xxl-8 mb-5 mb-xl-0">
                        <!--begin::Chart widget 3-->
                        <div class="card card-flush overflow-hidden h-md-99">
                            <!--begin::Header-->
                            <div class="card-header py-5">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Trend New Customer</span>
                                    <span class="text-gray-500 mt-1 fw-semibold fs-6">All Customer</span>
                                </h3>
                                <!--end::Title-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Card body-->
                            <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                                <!--begin::Chart-->
                                <div id="kt_charts_widget_3" class="min-h-auto ps-4 pe-6" style="height: 400px"></div>
                                <!--end::Chart-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Chart widget 3-->
                    </div>
                    <!--begin::Col-->
                    <div class="col-xl-4 mb-xl-10">
                        <!--begin::List widget 20-->
                        <div class="card h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Top Domisili Distribution</span>
                                    <span class="text-muted mt-1 fw-semibold fs-7">8k social visitors</span>
                                </h3>
                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <a href="#" class="btn btn-sm btn-light">All Courses</a>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div id="segment-top-retribusion">
                                <div class="text-center py-10 text-primary">
                                    Loading...
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List widget 20-->
                    </div>
                    <div class="col-xxl-8 mb-xl-10">
                        <!--begin::Chart widget 22-->
                        <div class="card h-xl-100">
                            <!--begin::Body-->
                            <div class="card-body pb-3">
                                <!--begin::Tab Content-->
                                <div class="d-flex flex-wrap flex-md-nowrap">
                                    <!--begin::Container-->
                                    <div
                                        class="d-flex justify-content-between flex-column w-225px w-md-600px mx-auto mx-md-0 pt-3 pb-10">
                                        <!--begin::Title-->
                                        <div class="fs-4 fw-bold text-gray-900 text-center mb-5">Gender Distribution
                                            <br />for All Customer
                                        </div>
                                        <!--end::Title-->
                                        <!--begin::Chart-->
                                        <div id="kt_chart_widgets_22_chart_1" class="mx-auto mb-4"></div>
                                        <!--end::Chart-->
                                        {{-- <!--begin::Labels-->
                                        <div class="mx-auto">
                                            <!--begin::Label-->
                                            <div class="d-flex align-items-center mb-2">
                                                <!--begin::Bullet-->
                                                <div class="bullet bullet-dot w-8px h-7px bg-success me-2"></div>
                                                <!--end::Bullet-->
                                                <!--begin::Label-->
                                                <div class="fs-8 fw-semibold text-muted">Male(133)</div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Label-->
                                            <!--begin::Label-->
                                            <div class="d-flex align-items-center mb-2">
                                                <!--begin::Bullet-->
                                                <div class="bullet bullet-dot w-8px h-7px bg-primary me-2"></div>
                                                <!--end::Bullet-->
                                                <!--begin::Label-->
                                                <div class="fs-8 fw-semibold text-muted">Female(9)</div>
                                                <!--end::Label-->
                                            </div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Labels--> --}}
                                    </div>
                                    <!--end::Container-->
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                        <!--end::Chart widget 22-->
                    </div>
                    <!--end::Col-->
                    {{-- sini --}}
                </div>
                <!--end::Row-->
                <!--begin::Row-->
                <div class="row g-5 g-xl-10">
                    <!--begin::Col-->
                    <div class="col-xl-4">
                        <!--begin::List widget 21-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-900">Top Tier Distribution</span>
                                    <span class="text-muted mt-1 fw-semibold fs-7">Avg. 72% completed tiers</span>
                                </h3>
                                <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <a href="#" class="btn btn-sm btn-light">All Tier</a>
                                </div>
                                <!--end::Toolbar-->
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div id="segment-top-tier">
                                <div class="text-center py-10 text-primary">
                                    Loading...
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <!--end::List widget 21-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-xl-8">
                        <!--begin::Chart widget 18-->
                        <div class="card card-flush h-xl-100">
                            <!--begin::Header-->
                            <div class="card-header pt-7">
                                <!--begin::Title-->
                                <h3 class="card-title align-items-start flex-column">
                                    <span class="card-label fw-bold text-gray-800">Tier Distribution (Count)</span>
                                    {{-- <span class="text-gray-500 mt-1 fw-semibold fs-6">Tier per Customer</span> --}}
                                </h3>
                                <!--end::Title-->
                                {{-- <!--begin::Toolbar-->
                                <div class="card-toolbar">
                                    <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                                    <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left"
                                        class="btn btn-sm btn-light d-flex align-items-center px-4">
                                        <!--begin::Display range-->
                                        <div class="text-gray-600 fw-bold">Loading date range...</div>
                                        <!--end::Display range-->
                                        <i class="ki-duotone ki-calendar-8 text-gray-500 lh-0 fs-2 ms-2 me-0">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                            <span class="path4"></span>
                                            <span class="path5"></span>
                                            <span class="path6"></span>
                                        </i>
                                    </div>
                                    <!--end::Daterangepicker-->
                                </div>
                                <!--end::Toolbar--> --}}
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body d-flex align-items-end px-0 pt-3 pb-5">
                                <!--begin::Chart-->
                                <div id="kt_charts_widget_18_chart" class="h-325px w-100 min-h-auto ps-4 pe-6"></div>
                                <!--end::Chart-->
                            </div>
                            <!--end: Card Body-->
                        </div>
                        <!--end::Chart widget 18-->
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Row-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@section('script')
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    {{-- <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script> --}}
    <script>
        let tierMapping = [];
        let tierLabels = [];

        fetch("{{ route('crm.dashboard.tier-graphic') }}")
            .then(response => response.json())
            .then(result => {
                if (result.status && Array.isArray(result.data)) {
                    tierMapping = result.data.map(item => item.total);
                    tierLabels = result.data.map(item => item.name);
                } else {
                    console.error("Data tidak valid:", result);
                }

                console.log("tierMapping:", tierMapping);
                // Jika mau ditampilkan di HTML juga
                topTier = true;

                // Inisialisasi grafik
                var KTChartsWidget18 = function() {
                    var e = {
                            self: null,
                            rendered: !1
                        },
                        t = function(e) {
                            var t = document.getElementById("kt_charts_widget_18_chart");
                            if (t) {
                                var a = parseInt(KTUtil.css(t, "height")),
                                    l = KTUtil.getCssVariableValue("--bs-gray-900"),
                                    r = KTUtil.getCssVariableValue("--bs-border-dashed-color"),
                                    o = {
                                        series: [{
                                            name: "Spent time",
                                            data: tierMapping
                                        }],
                                        chart: {
                                            fontFamily: "inherit",
                                            type: "bar",
                                            height: a,
                                            toolbar: {
                                                show: !1
                                            }
                                        },
                                        plotOptions: {
                                            bar: {
                                                horizontal: !1,
                                                columnWidth: ["28%"],
                                                borderRadius: 5,
                                                dataLabels: {
                                                    position: "top"
                                                },
                                                startingShape: "flat"
                                            }
                                        },
                                        legend: {
                                            show: !1
                                        },
                                        dataLabels: {
                                            enabled: !0,
                                            offsetY: -28,
                                            style: {
                                                fontSize: "13px",
                                                colors: [l]
                                            },
                                            formatter: function(e) {
                                                return e
                                            }
                                        },
                                        stroke: {
                                            show: !0,
                                            width: 2,
                                            colors: ["transparent"]
                                        },
                                        xaxis: {
                                            categories: tierLabels,
                                            axisBorder: {
                                                show: !1
                                            },
                                            axisTicks: {
                                                show: !1
                                            },
                                            labels: {
                                                style: {
                                                    colors: KTUtil.getCssVariableValue("--bs-gray-500"),
                                                    fontSize: "13px"
                                                }
                                            },
                                            crosshairs: {
                                                fill: {
                                                    gradient: {
                                                        opacityFrom: 0,
                                                        opacityTo: 0
                                                    }
                                                }
                                            }
                                        },
                                        yaxis: {
                                            labels: {
                                                style: {
                                                    colors: KTUtil.getCssVariableValue("--bs-gray-500"),
                                                    fontSize: "13px"
                                                },
                                                formatter: function(e) {
                                                    return e + ""
                                                }
                                            }
                                        },
                                        fill: {
                                            opacity: 1
                                        },
                                        states: {
                                            normal: {
                                                filter: {
                                                    type: "none",
                                                    value: 0
                                                }
                                            },
                                            hover: {
                                                filter: {
                                                    type: "none",
                                                    value: 0
                                                }
                                            },
                                            active: {
                                                allowMultipleDataPointsSelection: !1,
                                                filter: {
                                                    type: "none",
                                                    value: 0
                                                }
                                            }
                                        },
                                        tooltip: {
                                            style: {
                                                fontSize: "12px"
                                            },
                                            y: {
                                                formatter: function(e) {
                                                    return +e + " Customer"
                                                }
                                            }
                                        },
                                        colors: [KTUtil.getCssVariableValue("--bs-primary"), KTUtil
                                            .getCssVariableValue(
                                                "--bs-primary-light")
                                        ],
                                        grid: {
                                            borderColor: r,
                                            strokeDashArray: 4,
                                            yaxis: {
                                                lines: {
                                                    show: !0
                                                }
                                            }
                                        }
                                    };
                                e.self = new ApexCharts(t, o), setTimeout((function() {
                                    e.self.render(), e.rendered = !0
                                }), 200)
                            }
                        };
                    return {
                        init: function() {
                            t(e), KTThemeMode.on("kt.thememode.change", (function() {
                                e.rendered && e.self.destroy(), t(e)
                            }))
                        }
                    }
                }();
                "undefined" != typeof module && (module.exports = KTChartsWidget18), KTUtil.onDOMContentLoaded((
                    function() {
                        KTChartsWidget18.init()
                    }));
            })
            .catch(error => {
                topTierContainer.innerHTML =
                    '<div class="alert alert-danger">Gagal memuat data Top Tier.</div>';
                console.error('Error:', error);
            });

        var KTChartsWidget3 = function() {
            var e = {
                    self: null,
                    rendered: !1
                },
                t = function(e) {
                    var t = document.getElementById("kt_charts_widget_3");
                    if (t) {
                        var a = parseInt(KTUtil.css(t, "height")),
                            l = KTUtil.getCssVariableValue("--bs-gray-500"),
                            r = KTUtil.getCssVariableValue("--bs-border-dashed-color"),
                            o = KTUtil.getCssVariableValue("--bs-success"),
                            i = {
                                series: [{
                                    name: "Sales",
                                    data: [18, 18, 20, 20, 18, 18, 22, 22, 20, 20, 18, 18, 20, 20, 18, 18, 20,
                                        20, 22
                                    ]
                                }],
                                chart: {
                                    fontFamily: "inherit",
                                    type: "area",
                                    height: a,
                                    toolbar: {
                                        show: !1
                                    }
                                },
                                plotOptions: {},
                                legend: {
                                    show: !1
                                },
                                dataLabels: {
                                    enabled: !1
                                },
                                fill: {
                                    type: "gradient",
                                    gradient: {
                                        shadeIntensity: 1,
                                        opacityFrom: .4,
                                        opacityTo: 0,
                                        stops: [0, 80, 100]
                                    }
                                },
                                stroke: {
                                    curve: "smooth",
                                    show: !0,
                                    width: 3,
                                    colors: [o]
                                },
                                xaxis: {
                                    categories: ["", "Apr 02", "Apr 03", "Apr 04", "Apr 05", "Apr 06", "Apr 07",
                                        "Apr 08", "Apr 09", "Apr 10", "Apr 11", "Apr 12", "Apr 13", "Apr 14",
                                        "Apr 15", "Apr 16", "Apr 17", "Apr 18", ""
                                    ],
                                    axisBorder: {
                                        show: !1
                                    },
                                    axisTicks: {
                                        show: !1
                                    },
                                    tickAmount: 6,
                                    labels: {
                                        rotate: 0,
                                        rotateAlways: !0,
                                        style: {
                                            colors: l,
                                            fontSize: "12px"
                                        }
                                    },
                                    crosshairs: {
                                        position: "front",
                                        stroke: {
                                            color: o,
                                            width: 1,
                                            dashArray: 3
                                        }
                                    },
                                    tooltip: {
                                        enabled: !0,
                                        formatter: void 0,
                                        offsetY: 0,
                                        style: {
                                            fontSize: "12px"
                                        }
                                    }
                                },
                                yaxis: {
                                    tickAmount: 4,
                                    max: 24,
                                    min: 10,
                                    labels: {
                                        style: {
                                            colors: l,
                                            fontSize: "12px"
                                        },
                                        formatter: function(e) {
                                            return "$" + e + "K"
                                        }
                                    }
                                },
                                states: {
                                    normal: {
                                        filter: {
                                            type: "none",
                                            value: 0
                                        }
                                    },
                                    hover: {
                                        filter: {
                                            type: "none",
                                            value: 0
                                        }
                                    },
                                    active: {
                                        allowMultipleDataPointsSelection: !1,
                                        filter: {
                                            type: "none",
                                            value: 0
                                        }
                                    }
                                },
                                tooltip: {
                                    style: {
                                        fontSize: "12px"
                                    },
                                    y: {
                                        formatter: function(e) {
                                            return "$" + e + "K"
                                        }
                                    }
                                },
                                colors: [KTUtil.getCssVariableValue("--bs-success")],
                                grid: {
                                    borderColor: r,
                                    strokeDashArray: 4,
                                    yaxis: {
                                        lines: {
                                            show: !0
                                        }
                                    }
                                },
                                markers: {
                                    strokeColor: o,
                                    strokeWidth: 3
                                }
                            };
                        e.self = new ApexCharts(t, i), setTimeout((function() {
                            e.self.render(), e.rendered = !0
                        }), 200)
                    }
                };
            return {
                init: function() {
                    t(e), KTThemeMode.on("kt.thememode.change", (function() {
                        e.rendered && e.self.destroy(), t(e)
                    }))
                }
            }
        }();
        "undefined" != typeof module && (module.exports = KTChartsWidget3), KTUtil.onDOMContentLoaded((function() {
            KTChartsWidget3.init()
        }));

        let genderMapping = [];
        let genderLabels = [];

        fetch("{{ route('crm.dashboard.gender-distribution') }}")
            .then(response => response.json())
            .then(result => {
                if (result.status && Array.isArray(result.data)) {
                    genderMapping = result.data.map(item => item.total); // [jumlah data]
                    genderLabels = result.data.map(item => item.gender); // [nama gender]
                } else {
                    console.error("Data tidak valid:", result);
                }

                console.log("genderMapping:", genderMapping);
                console.log("genderLabels:", genderLabels);

                // Render chart setelah data tersedia
                var chartElement = document.querySelector("#kt_chart_widgets_22_chart_1");
                if (chartElement) {
                    var options = {
                        series: genderMapping, // Data dari API
                        chart: {
                            fontFamily: "inherit",
                            type: "donut",
                            height: 250,
                            width: 250
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: "50%",
                                    labels: {
                                        value: {
                                            fontSize: "10px"
                                        }
                                    }
                                }
                            }
                        },
                        colors: [
                            KTUtil.getCssVariableValue("--bs-primary"),
                            KTUtil.getCssVariableValue("--bs-success"),
                            KTUtil.getCssVariableValue("--bs-info")
                        ],
                        stroke: {
                            width: 0
                        },
                        labels: genderLabels, // Label dari API
                        legend: {
                            show: false
                        },
                        fill: {
                            type: "solid"
                        }
                    };

                    var chart = new ApexCharts(chartElement, options);
                    chart.render();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });



        let topRetribusion = false;
        const topRetribusionContainer = document.querySelector('#segment-top-retribusion');
        if (!topRetribusion) {
            fetch(
                    "{{ route('crm.dashboard.top-distribution') }}"
                ) // sesuaikan route dan parameternya
                .then(response => response.text())
                .then(html => {
                    topRetribusionContainer.innerHTML = html;
                    topRetribusion = true;
                })
                .catch(error => {
                    topRetribusionContainer.innerHTML =
                        '<div class="alert alert-danger">Gagal memuat data Top Distribution.</div>';
                    console.error('Error:', error);
                });
        }

        let topTier = false;
        const topTierContainer = document.querySelector('#segment-top-tier');
        if (!topTier) {
            fetch(
                    "{{ route('crm.dashboard.top-tier') }}"
                ) // sesuaikan route dan parameternya
                .then(response => response.text())
                .then(html => {
                    topTierContainer.innerHTML = html;
                    topTier = true;
                })
                .catch(error => {
                    topTierContainer.innerHTML =
                        '<div class="alert alert-danger">Gagal memuat data Top Tier.</div>';
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
@endsection
