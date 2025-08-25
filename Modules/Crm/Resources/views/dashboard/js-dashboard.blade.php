<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
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
                                        // enabled: !0,
                                        enabled: false,
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

    let customerMapping = [];
    let customerLabels = [];
    fetch("{{ route('crm.dashboard.customer-distribution') }}")
        .then(response => response.json())
        .then(result => {
            if (result.status && Array.isArray(result.data)) {
                customerMapping = result.data.map(item => item.total);
                customerLabels = result.data.map(item => item.hari);
            } else {
                console.error("Data tidak valid:", result);
            }

            console.log("customerMapping:", customerMapping);

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
                                        data: customerMapping
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
                                        categories: customerLabels,
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
                                        max: 10,
                                        min: 1,
                                        labels: {
                                            style: {
                                                colors: l,
                                                fontSize: "12px"
                                            },
                                            formatter: function(e) {
                                                return "" + e + ""
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
            "undefined" != typeof module && (module.exports = KTChartsWidget3), KTUtil.onDOMContentLoaded((
                function() {
                    KTChartsWidget3.init()
                }));
        })
        .catch(error => {
            console.error('Error:', error);
        });


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
