@extends('template.root')

@section('content')
    <div class="pos-index-page">
        <style>
            .pos-index-page .pos-index-search {
                width: 100%;
                max-width: 320px;
            }

            .pos-index-page .pos-index-search .form-control {
                height: 44px;
                border-radius: 12px;
            }

            .pos-index-page .pos-index-filter-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.45rem;
                min-height: 44px;
                border-radius: 12px;
                white-space: nowrap;
            }

            .pos-index-page #active-branch-button-label {
                display: inline-block;
                max-width: 160px;
                overflow: hidden;
                text-overflow: ellipsis;
                vertical-align: middle;
            }

            @media (max-width: 767.98px) {
                .pos-index-page .pos-index-search {
                    max-width: 100%;
                }

                .pos-index-page .pos-index-filter-toolbar {
                    width: 100%;
                    justify-content: flex-start !important;
                }

                .pos-index-page .pos-index-filter-btn {
                    width: 100%;
                }
            }

            .stock-opname-modal .modal-content {
                border-radius: 28px;
                border: 0;
                overflow: hidden;
            }

            .stock-opname-modal .modal-header {
                border-bottom: 1px solid #eef1f7;
                padding: 1.25rem 1.5rem;
            }

            .stock-opname-modal .modal-title {
                color: #1f2937;
                font-size: 1.45rem;
                letter-spacing: -0.01em;
            }

            .stock-opname-modal .btn-close-soft {
                width: 40px;
                height: 40px;
                border: 0;
                border-radius: 50%;
                background: #eef1f7;
                color: #6b7280;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .stock-opname-modal .modal-body {
                padding: 1.5rem;
                background: #fbfcff;
            }

            .stock-opname-modal .time-card {
                border: 1px solid #dbe6ff;
                border-radius: 14px;
                background: #edf3ff;
                padding: 1rem;
                margin-bottom: 1.5rem;
            }

            .stock-opname-modal .time-title {
                color: #2557d6;
                font-weight: 700;
            }

            .stock-opname-modal .time-subtitle {
                color: #4f73ca;
                font-size: 0.9rem;
            }

            .stock-opname-modal .stock-box {
                border: 1px solid #d9dde6;
                border-radius: 12px;
                background: #f2f3f7;
                min-height: 56px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.75rem 0.95rem;
                font-weight: 700;
                color: #5f6878;
            }

            .stock-opname-modal .stock-box.stock-input {
                background: #fff;
                border: 2px solid #2f66ef;
                color: #111827;
            }

            .stock-opname-modal .stock-box input {
                border: 0;
                background: transparent;
                width: 100%;
                text-align: center;
                font-size: 1.35rem;
                font-weight: 800;
                color: inherit;
                outline: none;
            }

            .stock-opname-modal .summary-card {
                border: 1px solid #f3c5cb;
                border-radius: 12px;
                background: #fff6f7;
                color: #b4232d;
                padding: 0.95rem;
            }

            .stock-opname-modal .summary-card.summary-positive {
                border-color: #b8e6c9;
                background: #f0fdf4;
                color: #067647;
            }

            .stock-opname-modal .summary-value {
                font-size: 1.95rem;
                font-weight: 800;
                line-height: 1;
            }

            .stock-opname-modal .modal-footer {
                border-top: 1px solid #eef1f7;
                background: #fff;
                padding: 1rem 1.5rem 1.35rem;
            }

            .stock-opname-modal .btn-submit-stock {
                width: 100%;
                border-radius: 12px;
                min-height: 52px;
                font-weight: 700;
                font-size: 1.08rem;
            }

            .so-card {
                background: #fff;
                border-radius: 16px;
                padding: 1.5rem;
                margin-bottom: 1.25rem;
                border: 1px solid rgba(229, 231, 235, 0.7);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 1px 2px rgba(0, 0, 0, 0.04);
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .so-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            }
            .so-card-title {
                font-size: 1.15rem;
                font-weight: 800;
                color: #111827;
                line-height: 1.3;
                letter-spacing: -0.015em;
            }
            .so-card-code {
                font-size: 11px;
                font-weight: 700;
                background: #f3f4f6;
                color: #4b5563;
                border: 1px solid #e5e7eb;
                border-radius: 6px;
                padding: 0.2rem 0.5rem;
                white-space: nowrap;
            }
            .so-meta-item {
                font-size: 12.5px;
                font-weight: 500;
                color: #6b7280;
            }
            .so-time-row {
                font-size: 12.5px;
                font-weight: 500;
                color: #6b7280;
                display: inline-flex;
                align-items: center;
                gap: 0.35rem;
            }
            .so-creator-avatar {
                width: 18px;
                height: 18px;
                border-radius: 50%;
                object-fit: cover;
                border: 1px solid #e5e7eb;
            }
            .so-stock-box-container {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                width: 100%;
            }
            @media (min-width: 992px) {
                .so-stock-box-container {
                    width: auto;
                }
            }
            .so-stock-box {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                flex: 1;
                height: 64px;
                border-radius: 10px;
                border: 1px solid #e5e7eb;
                background: #f9fafb;
                transition: all 0.2s ease;
            }
            @media (min-width: 992px) {
                .so-stock-box {
                    flex: none;
                    width: 78px;
                }
            }
            .so-stock-box.is-active {
                border: 1.5px solid #dbe6ff;
                background: #f5f8ff;
                cursor: pointer;
            }
            .so-stock-box.is-active:hover {
                border-color: #2f66ef;
                background: #edf3ff;
                transform: translateY(-1px);
            }
            .so-stock-box.is-surplus {
                background-color: #ecfdf5;
                border-color: #c2f0d5;
            }
            .so-stock-box.is-surplus .so-stock-label,
            .so-stock-box.is-surplus .so-stock-number {
                color: #059669;
            }
            .so-stock-box.is-loss {
                background-color: #fef2f2;
                border-color: #fcd2d2;
            }
            .so-stock-box.is-loss .so-stock-label,
            .so-stock-box.is-loss .so-stock-number {
                color: #dc2626;
            }
            .so-stock-label {
                font-size: 9px;
                font-weight: 700;
                color: #8892a2;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                margin-bottom: 0.15rem;
            }
            .so-stock-label.is-primary {
                color: #2563eb;
            }
            .so-stock-number {
                font-size: 16px;
                font-weight: 800;
                color: #1f2937;
            }
            .so-stock-number.is-primary {
                color: #2563eb;
            }
            .so-match-section {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
                min-width: 110px;
            }
            @media (min-width: 992px) {
                .so-match-section {
                    align-items: flex-end;
                }
            }
            .so-match-label {
                font-size: 9px;
                font-weight: 700;
                color: #8892a2;
                text-transform: uppercase;
                letter-spacing: 0.06em;
                margin-bottom: 0.15rem;
            }
            .so-match-value {
                font-size: 16px;
                font-weight: 800;
            }
            .so-mobile-match-bar {
                width: 100%;
                min-height: 48px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: 800;
                letter-spacing: 0.05em;
                padding: 0.75rem 1rem;
            }
            .so-mobile-match-bar.is-surplus {
                background-color: #ecfdf5;
                border: 1px solid #c2f0d5;
                color: #059669;
            }
            .so-mobile-match-bar.is-loss {
                background-color: #fef2f2;
                border: 1px solid #fcd2d2;
                color: #dc2626;
            }
            .so-mobile-match-bar.is-match {
                background-color: #f9fafb;
                border: 1px solid #e5e7eb;
                color: #1f2937;
            }
            .so-mobile-divider {
                width: 100%;
                height: 1px;
                background-color: #f1f3f7;
            }
            .so-divider {
                width: 1px;
                height: 28px;
                background-color: #e5e7eb;
            }
            .so-btn-icon-clean {
                background: transparent;
                border: 0;
                color: #6b7280;
                width: 36px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                transition: all 0.2s ease;
                cursor: pointer;
            }
            .so-btn-icon-clean:hover {
                background-color: #f3f4f6;
                color: #111827;
            }
            .so-btn-icon-clean.is-blue {
                color: #0d6efd;
            }
            .so-btn-icon-clean.is-blue:hover {
                background-color: #eef2ff;
                color: #0056b3;
            }
            .so-btn-quick-adj {
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                transition: all 0.2s ease;
                cursor: pointer;
                font-size: 11.5px;
                font-weight: 700;
                gap: 0.35rem;
                padding: 0.5rem 0.85rem;
                border: 1px solid transparent;
            }
            @media (min-width: 992px) {
                .so-btn-quick-adj {
                    width: 36px;
                    height: 36px;
                    padding: 0;
                }
            }
            .so-btn-quick-adj.is-up {
                background-color: #ecfdf5;
                border: 1px solid #a7f3d0;
                color: #059669;
            }
            .so-btn-quick-adj.is-up:hover {
                background-color: #d1fae5;
                border-color: #34d399;
                color: #047857;
            }
            .so-btn-quick-adj.is-down {
                background-color: #fef2f2;
                border: 1px solid #fecaca;
                color: #dc2626;
            }
            .so-btn-quick-adj.is-down:hover {
                background-color: #fee2e2;
                border-color: #f87171;
                color: #b91c1c;
            }
            .so-btn-delete-clean {
                background: transparent;
                border: 0;
                color: #9ca3af;
                width: 36px;
                height: 36px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                transition: all 0.2s ease;
                cursor: pointer;
            }
            .so-btn-delete-clean:hover {
                background-color: #fef2f2;
                color: #ef4444;
            }
            #transaction-table tbody tr td {
                border: 0;
                padding: 0;
                background: transparent;
            }

            /* History Timeline Drawer Styles */
            .history-card {
                background: #fff;
                border: 1px solid #f1f3f7;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
                border-radius: 14px;
                padding: 1.15rem;
                margin-bottom: 0.5rem;
                position: relative;
            }
            .history-action-badge {
                font-size: 10px;
                font-weight: 700;
                border-radius: 6px;
                padding: 0.2rem 0.5rem;
                text-transform: uppercase;
                letter-spacing: 0.03em;
            }
            .history-action-badge.is-initial {
                background-color: #eef2ff;
                color: #4f46e5;
            }
            .history-action-badge.is-update {
                background-color: #fff7ed;
                color: #ea580c;
            }
            .history-time-text {
                font-size: 11.5px;
                color: #8892a2;
                font-weight: 500;
            }
            .history-note-text {
                font-size: 13.5px;
                font-weight: 700;
                color: #1f2937;
                line-height: 1.4;
            }
            .history-qty-badge {
                font-size: 12px;
                font-weight: 800;
                background-color: #eff6ff;
                color: #2563eb;
                border-radius: 8px;
                padding: 0.35rem 0.75rem;
            }
            #kt_stock_opname_history {
                border-left: 1px solid rgba(229, 231, 235, 0.5);
                box-shadow: -10px 0 30px rgba(0, 0, 0, 0.03);
            }
        </style>
        <div class="card card-flush" style="border:0px">
            <div class="card-header align-items-stretch py-3 gap-3 flex-column flex-md-row">
                <div class="card-title flex-grow-1 w-100 mb-0">
                    <div class="d-flex align-items-center position-relative my-1 pos-index-search">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-4"></i>
                        <input type="text" data-kt-ecommerce-product-filter="search" id="search"
                            class="form-control form-control-solid w-100 ps-12" placeholder="Cari Transaksi" />
                    </div>
                </div>
                <div class="card-toolbar w-100 w-md-auto ms-md-auto">
                    <div class="d-flex align-items-center justify-content-md-end pos-index-filter-toolbar gap-3"
                        data-kt-user-table-toolbar="base">
                        <button type="button" class="btn btn-light-primary px-4 pos-index-filter-btn"
                            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <i class="ki-duotone ki-filter fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <span id="active-branch-button-label">Cabang</span>
                        </button>
                        <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                            <div class="px-7 py-5">
                                <div class="fs-5 text-gray-900 fw-bold">Pilihan Filter</div>
                            </div>
                            <div class="separator border-gray-200"></div>
                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                <div>
                                    <label class="form-label fs-6 fw-semibold">Cabang:</label>
                                    <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="true" data-placeholder="Cabang"
                                        data-kt-ecommerce-product-filter="cabang">
                                        <option value="all">Semua</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ ucwords($branch->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--begin::Card body-->
            <div class="card-body p-0">
                <!--begin::Table-->
                <table class="table fs-6" id="transaction-table" width="100%">
                    <thead class="d-none">
                        <tr>
                            <th>Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600"></tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
    <button type="button" class="btn btn-primary rounded-circle shadow-lg position-fixed"
        style="bottom: 60px; right: 30px; width: 60px; height: 60px; z-index: 1050; display: flex; align-items: center; justify-content: center;"
        data-bs-toggle="modal" data-bs-target="#kt_modal_add_customer">
        <i class="ki-duotone ki-plus fs-3x text-white">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </button>
    <div class="modal fade stock-opname-modal" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-fullscreen">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Form-->
                <form class="form" action="{{ url(Request::segment(1)) }}" id="kt_modal_add_customer_form"
                    data-kt-redirect="#">
                    @csrf
                    <!--begin::Modal header-->
                    <div class="modal-header" id="kt_modal_add_customer_header">
                        <!--begin::Modal title-->
                        <h2 class="modal-title fw-bold">Input Stock Fisik &amp; Akumulasi</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <button type="button" id="kt_modal_add_customer_close" class="btn-close-soft"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-outline ki-cross fs-2"></i>
                        </button>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body">
                        <!--begin::Scroll-->
                        <div class="scroll-y" id="kt_modal_add_customer_scroll" data-kt-scroll="true"
                            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                            data-kt-scroll-dependencies="#kt_modal_add_customer_header"
                            data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="220px">
                            <!--begin::Input group-->
                            <div class="time-card d-flex align-items-center gap-4">
                                <i class="ki-outline ki-calendar fs-2 text-primary"></i>
                                <div>
                                    <div class="time-title">Waktu Pencatatan</div>
                                    <div class="time-subtitle">Otomatis saat ini</div>
                                </div>
                                <input type="hidden" id="date" name="date" value="{{ date('Y-m-d') }}" />
                            </div>

                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required form-label text-gray-700 fw-semibold">Cabang</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select form-select-solid" name="branch_id" id="branch_id"
                                    data-placeholder="Pilih Cabang">
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required form-label text-gray-700 fw-semibold">Pilih Produk</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <select class="form-select form-select-solid" name="product_id" id="product_id"
                                    data-placeholder="Pilih Produk">
                                    <option value="">Pilih Product</option>
                                </select>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->

                            <div class="separator my-8"></div>

                            <div class="row g-5 mb-7">
                                <div class="col-6">
                                    <label class="form-label text-gray-600 fw-semibold mb-2">Stock Sistem</label>
                                    <div class="stock-box">
                                        <input type="number" step="0.01" name="quantity" value="0" readonly />
                                        <span class="text-gray-500">Kg</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-primary fw-semibold mb-2">Stock Fisik</label>
                                    <div class="stock-box stock-input">
                                        <input type="number" step="0.01" name="real_stock" value="0" />
                                        <span class="text-gray-500">Kg</span>
                                    </div>
                                </div>
                            </div>

                            <div class="summary-card mb-8">
                                <div class="row g-3 align-items-end">
                                    <div class="col-6">
                                        <div class="fw-semibold fs-7">Total Selisih Stock:</div>
                                        <div class="summary-value" id="stock-difference-text">0 Kg</div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <div class="fw-semibold fs-7">Nilai Kerugian/Lebih:</div>
                                        <div class="summary-value" id="stock-difference-value">Rp 0</div>
                                    </div>
                                </div>
                            </div>

                            <div class="fv-row mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="form-label text-gray-700 fw-semibold mb-0">Catatan Audit</label>
                                    <span class="badge badge-light-primary">Draft Otomatis</span>
                                </div>
                                <textarea class="form-control form-control-solid" name="note" rows="3" placeholder="Tulis catatan audit..."></textarea>
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->
                    <!--begin::Modal footer-->
                    <div class="modal-footer">
                        <!--begin::Button-->
                        <button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary btn-submit-stock">
                            <span class="indicator-label">Buat Transaksi <i class="ki-outline ki-arrow-right text-white fs-4 ms-1"></i></span>
                            <span class="indicator-progress">Mohon tunggu...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                    <!--end::Modal footer-->
                </form>
                <!--end::Form-->
            </div>
        </div>
    </div>

    <!--begin::Stock Opname History Drawer-->
    <div id="kt_stock_opname_history" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="stock-opname-history"
        data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
        data-kt-drawer-close="#kt_stock_opname_history_close">
        <div class="card w-100 shadow-none border-0 rounded-0" style="height: 100vh;">
            <!--begin::Header-->
            <div class="card-header border-0 pe-5" id="kt_stock_opname_history_header" style="min-height: 70px;">
                <div class="card-title d-flex flex-column align-items-start">
                    <h3 class="fw-bold text-gray-900 mb-1 d-flex align-items-center gap-2">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #4b5563;">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                            <path d="M12 7v5l4 2"/>
                        </svg>
                        Audit Timeline
                    </h3>
                    <span class="text-muted fs-7 fw-semibold" id="history-transaction-code">-</span>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5" id="kt_stock_opname_history_close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </button>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body position-relative pt-0" id="kt_stock_opname_history_body">
                <!--begin::Scroll-->
                <div id="kt_stock_opname_history_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true"
                    data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_stock_opname_history_body"
                    data-kt-scroll-dependencies="#kt_stock_opname_history_header" data-kt-scroll-offset="5px" style="height: calc(100vh - 100px);">
                    
                    <div class="timeline timeline-border-dashed mt-5" id="history-timeline-items">
                        <!-- Timeline items rendered dynamically -->
                    </div>

                </div>
                <!--end::Scroll-->
            </div>
            <!--end::Body-->
        </div>
    </div>
    <!--end::Stock Opname History Drawer-->

@section('script')
    <script type="text/javascript">
        // Data stock kosong dari server
        const emptyStockData = @json($emptyStockData ?? []);

        function showStockAlert(selectedBranchId) {
            let filteredProducts = emptyStockData;
            if (selectedBranchId && selectedBranchId !== 'all') {
                filteredProducts = emptyStockData.filter(p => p.branch_id == selectedBranchId);
            }

            if (filteredProducts.length > 0) {
                const count = filteredProducts.length;
                const badges = filteredProducts.slice(0, 10).map(p => {
                    return `<span class="badge badge-light-danger me-1 mb-1">${p.name} (${p.branch_name})</span>`;
                }).join('');

                const remainingHtml = count > 10
                    ? `<br><small>...dan ${count - 10} produk lainnya</small>`
                    : '';

                const scopeText = (selectedBranchId && selectedBranchId !== 'all') ? 'pada cabang ini' : 'pada seluruh cabang';
                const alertHtml = `Terdapat <strong>${count}</strong> produk dengan stok kosong ${scopeText}.<br><br>` +
                    `<div style="text-align: left; max-height: 200px; overflow-y: auto;">` +
                    badges +
                    remainingHtml +
                    `</div>`;

                Swal.fire({
                    title: 'Peringatan Stok Minus!',
                    html: alertHtml,
                    icon: 'warning',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#f1416c',
                    allowOutsideClick: false
                });
            }
        }

        var dataTable;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const segment1 = "{{ Request::segment(1) }}";

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID').format(Number(value || 0));
        }

        function updateStockSummary(unitPrice = null) {
            const systemStock = Number($('input[name="quantity"]').val() || 0);
            const realStock = Number($('input[name="real_stock"]').val() || 0);
            const difference = realStock - systemStock;
            const price = Number(unitPrice ?? $('#product_id').data('unit-price') ?? 0);
            const value = difference * price;
            const diffRounded = Math.round(difference * 100) / 100;

            $('#stock-difference-text').text(`${diffRounded} Kg`);
            $('#stock-difference-value').text(`${value < 0 ? '-' : ''}Rp ${formatRupiah(Math.abs(value))}`);

            const $card = $('.summary-card');
            if (difference > 0) {
                $card.addClass('summary-positive').removeClass('summary-negative');
            } else if (difference < 0) {
                $card.removeClass('summary-positive').addClass('summary-negative');
            } else {
                $card.removeClass('summary-positive summary-negative');
            }
        }

        function formatLongDateTime(dateValue) {
            if (!dateValue) return '-';
            const dt = new Date(dateValue);
            if (Number.isNaN(dt.getTime())) return dateValue;
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const day = dt.getDate();
            const month = months[dt.getMonth()];
            const year = dt.getFullYear();
            const h = String(dt.getHours()).padStart(2, '0');
            const m = String(dt.getMinutes()).padStart(2, '0');
            return `${day} ${month} ${year}, ${h}.${m}`;
        }

        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }

        function renderStockOpnameCard(row) {
            const productName = row?.product?.name || row?.name || '-';
            const code = row?.code ? `#${row.code}` : '-';
            const cleanCode = row?.code ? row.code : '-';
            const branchName = row?.branch_name || 'Semua Cabang';
            const dateLabel = formatLongDateTime(row?.created_at || row?.date);
            const creatorName = row?.creator_name || '';
            const stockSystem = Number(row?.stock || 0);
            const stockFisik = Number(row?.real_stock || 0);
            const selisih = Number(row?.difference || 0);
            const hpp = Number(row?.avg_hpp_calc || row?.avg_price || 0);
            const nilaiSelisih = Math.floor(selisih * hpp);
            const id = row?.id || 0;

            const isMinus = selisih < 0;
            const hppText = `HPP: Rp ${formatRupiah(Math.round(Math.abs(hpp)))}/Kg`;
            const nilaiText = `Rp ${formatRupiah(Math.abs(nilaiSelisih))}`;
            
            // Dynamic Stock Match Label & Color
            let stockMatchLabel = 'STOCK MATCH';
            let stockMatchColor = '#111827';
            if (selisih < 0) {
                stockMatchLabel = 'POTENSI RUGI';
                stockMatchColor = '#ef4444';
            } else if (selisih > 0) {
                stockMatchLabel = 'SURPLUS STOK';
                stockMatchColor = '#10b981';
            }

            // Dynamic Selisih Box Class
            let selisihClass = '';
            if (selisih > 0) {
                selisihClass = 'is-surplus';
            } else if (selisih < 0) {
                selisihClass = 'is-loss';
            }

            // Mobile Match Bar Class & Content
            let mobileMatchClass = 'is-match';
            let mobileMatchText = 'STOCK MATCH: Rp 0';
            if (selisih > 0) {
                mobileMatchClass = 'is-surplus';
                mobileMatchText = `NILAI KELEBIHAN: Rp ${formatRupiah(Math.abs(nilaiSelisih))}`;
            } else if (selisih < 0) {
                mobileMatchClass = 'is-loss';
                mobileMatchText = `NILAI KERUGIAN: -Rp ${formatRupiah(Math.abs(nilaiSelisih))}`;
            }

            // Dynamic User Avatar using userId modulo
            const userId = row?.created_by || 1;
            const avatarNum = (userId % 30) + 1;
            const avatarUrl = `/assets/media/avatars/300-${avatarNum}.jpg`;

            // Helper to format quantity nicely (e.g. keeping decimal if needed)
            const formatQty = (val) => {
                const num = Number(val || 0);
                if (num === 0) return '0.0';
                return num % 1 === 0 ? num.toString() : num.toFixed(2);
            };

            const creatorHtml = creatorName
                ? `
                <span class="text-muted mx-1">•</span>
                <span class="so-time-row">
                    <img src="${avatarUrl}" class="so-creator-avatar" alt="Avatar">
                    <span>${escapeHtml(creatorName)}</span>
                </span>
                `
                : '';

            return `
                <div class="so-card d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                    <!-- Left: Info -->
                    <div class="d-flex flex-column flex-grow-1" style="min-width: 280px;">
                        <h4 class="so-card-title mb-1">${escapeHtml(productName)}</h4>
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap text-muted fs-7">
                            <span class="so-card-code">${escapeHtml(cleanCode)}</span>
                            <span class="text-muted">•</span>
                            <span class="so-meta-item">${escapeHtml(branchName)}</span>
                            <span class="text-muted">•</span>
                            <span class="so-meta-item">${escapeHtml(hppText)}</span>
                        </div>
                        <div class="d-flex align-items-center gap-1 text-muted fs-8 flex-wrap">
                            <span class="so-time-row">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span>${escapeHtml(dateLabel)}</span>
                            </span>
                            ${creatorHtml}
                        </div>
                    </div>

                    <!-- Middle: Stock Boxes -->
                    <div class="so-stock-box-container justify-content-start justify-content-lg-center">
                        <!-- Box 1: Sistem -->
                        <div class="so-stock-box">
                            <span class="so-stock-label">Sistem</span>
                            <span class="so-stock-number">${formatQty(stockSystem)}</span>
                        </div>
                        <!-- Box 2: Fisik -->
                        <div class="so-stock-box is-active" onclick="editProduct(${id})" title="Klik untuk sesuaikan stock fisik">
                            <span class="so-stock-label is-primary">Fisik</span>
                            <span class="so-stock-number is-primary">${formatQty(stockFisik)}</span>
                        </div>
                        <!-- Box 3: Selisih -->
                        <div class="so-stock-box ${selisihClass}">
                            <span class="so-stock-label">Selisih</span>
                            <span class="so-stock-number">${formatQty(selisih)}</span>
                        </div>
                    </div>

                    <!-- Mobile Match Bar (only visible on mobile) -->
                    <div class="so-mobile-match-bar ${mobileMatchClass} d-flex d-lg-none align-items-center justify-content-center">
                        <span>${escapeHtml(mobileMatchText)}</span>
                    </div>

                    <!-- Mobile Divider (only visible on mobile) -->
                    <div class="so-mobile-divider d-lg-none mt-2 mb-3"></div>

                    <!-- Right: Match, Actions, Delete -->
                    <div class="d-flex align-items-center justify-content-between justify-content-lg-end gap-3 flex-wrap w-100 w-lg-auto">
                        <!-- Value Match -->
                        <div class="so-match-section d-none d-lg-flex">
                            <span class="so-match-label">${stockMatchLabel}</span>
                            <span class="so-match-value" style="color: ${stockMatchColor};">${selisih < 0 ? '-' : ''}${nilaiText}</span>
                        </div>

                        <!-- Logs & Chats -->
                        <div class="d-flex align-items-center gap-1">
                            <!-- History -->
                            <button class="so-btn-icon-clean" onclick="showHistory(${id})" title="History Transaksi">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                    <path d="M3 3v5h5"/>
                                    <path d="M12 7v5l4 2"/>
                                </svg>
                            </button>
                            <!-- Chat/Discussion -->
                            <button class="so-btn-icon-clean is-blue" title="Diskusi / Catatan" onclick="editProduct(${id})">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                                </svg>
                            </button>
                        </div>

                        <div class="so-divider"></div>

                        <!-- Quick Adjustments -->
                        <div class="d-flex align-items-center gap-2">
                            <!-- Adjust Up -->
                            <button class="so-btn-quick-adj is-up" onclick="editProduct(${id})" title="Sesuaikan Stok Masuk">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="7" y1="17" x2="17" y2="7"/>
                                    <polyline points="7 7 17 7 17 17"/>
                                </svg>
                                <span class="d-lg-none">+ Kredit</span>
                            </button>
                            <!-- Adjust Down -->
                            <button class="so-btn-quick-adj is-down" onclick="editProduct(${id})" title="Sesuaikan Stok Keluar">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="7" y1="7" x2="17" y2="17"/>
                                    <polyline points="17 7 17 17 7 17"/>
                                </svg>
                                <span class="d-lg-none">- Kredit</span>
                            </button>
                        </div>

                        <div class="so-divider d-none d-lg-block"></div>

                        <!-- Delete -->
                        <button class="so-btn-delete-clean" onclick="deleteProduct(${id})" title="Hapus Transaksi">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                <line x1="10" y1="11" x2="10" y2="17"/>
                                <line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
        }

        $(document).ready(function() {
            const $branchFilter = $('[data-kt-ecommerce-product-filter="cabang"]');
            const $activeBranchButtonLabel = $('#active-branch-button-label');

            function updateActiveFilterInfo() {
                const selectedBranch = $branchFilter.find('option:selected').text().trim() || 'Semua cabang';
                $activeBranchButtonLabel.text(selectedBranch);
            }

            dataTable = $('#transaction-table').DataTable({
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('stock-opname.data') }}",
                    data: function(d) {
                        d.url = "{{ request()->segment(1) }}";
                        d.cabang_filter = $('[data-kt-ecommerce-product-filter="cabang"]').val();
                    }
                },
                columns: [{
                    data: null,
                    name: 'name',
                    orderable: false,
                    searchable: true,
                    render: function(data, type, row) {
                        return renderStockOpnameCard(row);
                    }
                }]
            });
            $('#search').on('keyup', function() {
                dataTable.search(this.value).draw();
            });

            $('[data-kt-ecommerce-product-filter="cabang"]').on('change', function() {
                updateActiveFilterInfo();
                dataTable.draw();
                showStockAlert($(this).val());
            });

            updateActiveFilterInfo();
            showStockAlert($('[data-kt-ecommerce-product-filter="cabang"]').val());

            const cancelButton = document.getElementById('kt_modal_add_customer_cancel');
            if (cancelButton) {
                cancelButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        text: "Apakah Anda yakin ingin membatalkan?",
                        icon: "warning",
                        showCancelButton: !0,
                        buttonsStyling: !1,
                        confirmButtonText: "Ya, Batalkan!",
                        cancelButtonText: "Tidak, Kembali",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            cancelButton: "btn btn-active-light"
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'kt_modal_add_customer'));
                            var form = $('#kt_modal_add_customer_form');
                            form.find('input, select, textarea, button[type="submit"]').prop('disabled',
                                false);
                            modal.hide();
                            document.getElementById('kt_modal_add_customer_form').reset();
                        }
                    });
                });
            }

            $('#kt_modal_add_customer_form').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var url = form.attr('action');
                var submitBtn = $('#kt_modal_add_customer_submit');

                // Show loading
                submitBtn.prop('disabled', true);
                submitBtn.find('.indicator-label').hide();
                submitBtn.find('.indicator-progress').show();

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: form.serialize(), // gunakan FormData(form)[... jika pakai file]
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message || 'Data berhasil disimpan.',
                            showConfirmButton: false,
                            timer: 1500 // notifikasi akan hilang otomatis setelah 1.5 detik
                        }).then(() => {
                            // 1. Reset form
                            form.trigger('reset');

                            // 2. Hapus input _method
                            form.find('input[name="_method"]').remove();
                            $('select[name="product_id"]').val(null).trigger(
                            'change'); // Reset select2
                            $('#product_id select').val(null).trigger('change');

                            // 3. Kembalikan action form ke default (untuk create)
                            form.attr('action',
                                `/${segment1}`); // Misal segment1 = 'stock-opname'

                            // 4. Kembalikan judul modal (opsional)
                            $('#kt_modal_add_customer_header h2').text(
                                'Input Stock Fisik & Akumulasi');

                            $('#kt_modal_add_customer_submit .indicator-label').html(
                                'Buat Transaksi <i class="ki-outline ki-arrow-right text-white fs-4 ms-1"></i>');
                            updateStockSummary();

                            // 5. Tutup modal
                            const modal = bootstrap.Modal.getInstance(document
                                .getElementById('kt_modal_add_customer'));
                            if (modal) modal.hide();

                            // 6. Refresh DataTable
                            if (typeof dataTable !== 'undefined') {
                                dataTable.ajax.reload(null, false);
                            }
                        });
                    },
                    error: function(xhr) {
                        var msg = 'Terjadi kesalahan saat menyimpan data.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: msg
                        });
                    },
                    complete: function() {
                        // Reset loading state
                        submitBtn.prop('disabled', false);
                        submitBtn.find('.indicator-label').show();
                        submitBtn.find('.indicator-progress').hide();
                    }
                });
            });
        });

        function reloadDataTable() {
            // Pastikan dataTable sudah terinisialisasi sebelumnya
            if (typeof dataTable !== 'undefined') {
                dataTable.ajax.reload(null, false); // 'false' untuk tidak mereset ke halaman pertama
            } else {
                console.error('DataTable tidak terinisialisasi.');
            }
        }

        function deleteProduct(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/stock-opname/${id}`, // Ganti dengan URL yang sesuai
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message || 'Data berhasil dihapus.',
                                showConfirmButton: false,
                                timer: 1500 // notifikasi akan hilang otomatis setelah 1.5 detik
                            });

                            // Reload DataTable setelah berhasil menghapus data
                            reloadDataTable();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message ||
                                    'Terjadi kesalahan saat menghapus data.'
                            });
                        }
                    });
                }
            });
        }

        function editProduct(id) {
            $.ajax({
                url: `/stock-opname/${id}/edit`,
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    // Prevent old branch change handler from overwriting quantity
                    $('#branch_id').off('change.stockOpname');

                    // Clear and reset product_id select2 first
                    var $productSelect = $('select[name="product_id"]');
                    $productSelect.empty();
                    $productSelect.append(new Option('Pilih Product', '', false, false));
                    $productSelect.append(new Option(response.name, response.product_id, true, true));
                    $productSelect.val(response.product_id).trigger('change');

                    // Set flatpickr date
                    var fp = $('#date')[0]._flatpickr;
                    if (fp) {
                        fp.setDate(response.date);
                    }

                    // Set branch_id BEFORE quantity so re-init won't overwrite
                    $('select[name="branch_id"]').val(response.branch_id).trigger('change');

                    // Set form fields from database values (not live stock)
                    $('input[name="quantity"]').val(response.stock);
                    $('input[name="real_stock"]').val(response.real_stock);
                    $('textarea[name="note"]').val(response.note || '');
                    updateStockSummary(response.avg_price || 0);

                    // Ubah action form untuk update
                    var form = $('#kt_modal_add_customer_form');
                    form.attr('action', `/stock-opname/${id}`);
                    form.find('input[name="_method"]').remove();
                    form.append(
                        '<input type="hidden" name="_method" value="PUT">'
                    );

                    // --- ENABLE semua input/select/textarea di form untuk edit ---
                    form.find('input, select, textarea, button[type="submit"]').prop('disabled', false);

                    // Ubah judul modal
                    $('#kt_modal_add_customer_header h2').text('Edit Transaksi');
                    $('#kt_modal_add_customer_submit .indicator-label').text('Simpan Perubahan');

                    // Tampilkan modal untuk edit produk
                    var modal = new bootstrap.Modal(document.getElementById('kt_modal_add_customer'));
                    modal.show();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memuat data produk.'
                    });
                }
            });
        }

        function viewProduct(id) {
            $.ajax({
                url: `/stock-opname/${id}/edit`,
                type: 'GET',
                success: function(response) {
                    console.log(response);

                    // Prevent old branch change handler from overwriting quantity
                    $('#branch_id').off('change.stockOpname');

                    // Clear and reset product_id select2 first
                    var $productSelect = $('select[name="product_id"]');
                    $productSelect.empty();
                    $productSelect.append(new Option('Pilih Product', '', false, false));
                    $productSelect.append(new Option(response.name, response.product_id, true, true));
                    $productSelect.val(response.product_id).trigger('change');

                    // Set flatpickr date
                    var fp = $('#date')[0]._flatpickr;
                    if (fp) {
                        fp.setDate(response.date);
                    }

                    // Set branch_id BEFORE quantity so re-init won't overwrite
                    $('select[name="branch_id"]').val(response.branch_id).trigger('change');

                    // Set form fields from database values (not live stock)
                    $('input[name="quantity"]').val(response.stock);
                    $('input[name="real_stock"]').val(response.real_stock);
                    $('textarea[name="note"]').val(response.note || '');
                    updateStockSummary(response.avg_price || 0);

                    // Ubah action form untuk view (no action)
                    var form = $('#kt_modal_add_customer_form');
                    form.attr('action', '#');
                    form.find('input[name="_method"]').remove();

                    // --- DISABLE semua input/select/textarea di form supaya read-only ---
                    form.find('input, select, textarea, button[type="submit"]').prop('disabled', true);

                    // Ubah judul modal untuk view
                    $('#kt_modal_add_customer_header h2').text('Lihat Transaksi');

                    // Tampilkan modal untuk view produk
                    var modal = new bootstrap.Modal(document.getElementById('kt_modal_add_customer'));
                    modal.show();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memuat data produk.'
                    });
                }
            });
        }

        function showHistory(id) {
            $.ajax({
                url: `/stock-opname/${id}/history`,
                type: 'GET',
                success: function(response) {
                    $('#history-transaction-code').text(response.code || '-');
                    
                    const $timeline = $('#history-timeline-items');
                    $timeline.empty();
                    
                    if (response.history && response.history.length > 0) {
                        response.history.forEach((item, index) => {
                            const isInitial = item.action === 'INITIAL';
                            const badgeClass = isInitial ? 'is-initial' : 'is-update';
                            const actionLabel = isInitial ? 'INITIAL' : 'UPDATE';
                            const formattedTime = formatLongDateTime(item.created_at);
                            
                            // Dynamic User Avatar using userId modulo
                            const userId = item.created_by || 1;
                            const avatarNum = (userId % 30) + 1;
                            const avatarUrl = `/assets/media/avatars/300-${avatarNum}.jpg`;
                            
                            const timelineItemHtml = `
                                <div class="timeline-item">
                                    <div class="timeline-line"></div>
                                    <div class="timeline-icon">
                                        <span class="bullet bullet-dot bg-primary h-10px w-10px"></span>
                                    </div>
                                    <div class="timeline-content mb-8 mt-n1">
                                        <div class="history-card">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <span class="history-action-badge ${badgeClass}">${actionLabel}</span>
                                                <span class="history-time-text d-flex align-items-center gap-1">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                    ${formattedTime}
                                                </span>
                                            </div>
                                            
                                            <div class="history-note-text mb-3">
                                                "${escapeHtml(item.note || '')}"
                                            </div>
                                            
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="${avatarUrl}" class="so-creator-avatar" alt="Avatar">
                                                    <span class="fs-7 fw-semibold text-gray-700">${escapeHtml(item.creator_name || 'System')}</span>
                                                </div>
                                                <span class="history-qty-badge">${item.real_stock} Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $timeline.append(timelineItemHtml);
                        });
                    } else {
                        $timeline.append('<div class="text-muted text-center py-5">Belum ada riwayat audit.</div>');
                    }
                    
                    // Show Metronic Drawer
                    const drawerEl = document.getElementById('kt_stock_opname_history');
                    let drawer = KTDrawer.getInstance(drawerEl);
                    if (!drawer) {
                        drawer = new KTDrawer(drawerEl);
                    }
                    drawer.show();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memuat data riwayat audit.'
                    });
                }
            });
        }

        $('#kt_modal_add_customer').on('shown.bs.modal', function() {
            const $branch = $('#branch_id');
            const $product = $('#product_id');
            const $quantity = $('input[name="quantity"]');
            const $realStock = $('input[name="real_stock"]');
            const $form = $('#kt_modal_add_customer_form');

            if (!$form.find('input[name="_method"]').length) {
                $('#kt_modal_add_customer_header h2').text('Input Stock Fisik & Akumulasi');
                $('#kt_modal_add_customer_submit .indicator-label').html(
                    'Buat Transaksi <i class="ki-outline ki-arrow-right text-white fs-4 ms-1"></i>');
                $('textarea[name="note"]').val('');
            }

            if (!$realStock.val()) {
                $realStock.val(0);
            }

            if ($product.hasClass('select2-hidden-accessible')) {
                $product.select2('destroy');
            }

            $product.select2({
                placeholder: 'Pilih Produk',
                dropdownParent: $('#kt_modal_add_customer'),
                ajax: {
                    url: '{{ route('ajax.stock-available') }}',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term,
                        branch_id: $branch.val()
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.name,
                            stock_available: item.stock_available,
                            unit_price: item.hpp || item.price || item.cost_price || item.purchase_price || 0
                        }))
                    })
                }
            }).on('select2:select', function(e) {
                const data = e.params.data;
                $quantity.val(data.stock_available || 0);
                $product.data('unit-price', data.unit_price || 0);
                updateStockSummary(data.unit_price || 0);
            });

            if ($branch.hasClass('select2-hidden-accessible')) {
                $branch.select2('destroy');
            }

            $branch.select2({
                placeholder: 'Pilih Cabang',
                dropdownParent: $('#kt_modal_add_customer'),
            });

            $branch.off('change.stockOpname').on('change.stockOpname', function() {
                const branchId = $(this).val();
                const productId = $product.val();

                $quantity.val('');
                updateStockSummary();

                if (!branchId) {
                    $product.val(null).trigger('change');
                    return;
                }

                if (!productId) {
                    return;
                }

                $.ajax({
                    url: '{{ route('ajax.stock-available') }}',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        branch_id: branchId,
                        search: ''
                    },
                    success: function(items) {
                        const selected = (items || []).find(item => String(item.id) === String(productId));

                        if (selected) {
                            $quantity.val(selected.stock_available || 0);
                            $product.data('unit-price', selected.hpp || selected.price || selected.cost_price ||
                                selected.purchase_price || 0);
                            updateStockSummary();
                            return;
                        }

                        $product.val(null).trigger('change');
                    },
                    error: function() {
                        $product.val(null).trigger('change');
                    }
                });
            });

            $realStock.off('input.stockSummary change.stockSummary').on('input.stockSummary change.stockSummary',
                function() {
                    updateStockSummary();
                });

            updateStockSummary();
        });
    </script>
@endsection
@endsection
