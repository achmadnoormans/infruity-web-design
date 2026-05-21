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
                padding: 1.25rem;
                margin-bottom: 1rem;
                border: 1px solid rgba(229, 231, 235, 0.8);
                box-shadow: 0 1px 2px rgba(0,0,0,0.02);
                transition: box-shadow .2s;
            }
            .so-card:hover {
                box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            }
            .so-card-title {
                font-size: 1.125rem;
                font-weight: 800;
                color: #111827;
                line-height: 1.3;
            }
            .so-card-badge {
                font-size: 10px;
                font-weight: 600;
                background: #f3f4f6;
                color: #4b5563;
                border: 1px solid #e5e7eb;
                border-radius: 999px;
                padding: 0.1rem 0.5rem;
                white-space: nowrap;
            }
            .so-card-date {
                font-size: 0.875rem;
                font-weight: 600;
                color: #1f2937;
            }
            .so-card-creator {
                font-size: 0.75rem;
                color: #6b7280;
            }
            .so-grid-row {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
                gap: 0;
                background: rgba(249,250,251,0.8);
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                overflow: hidden;
                margin-top: 1rem;
            }
            .so-grid-cell {
                padding: 0.75rem 0.25rem;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .so-grid-cell + .so-grid-cell {
                border-left: 1px solid rgba(229,231,235,0.6);
            }
            .so-grid-cell.is-active {
                background: #fff;
                cursor: pointer;
                transition: background .15s, box-shadow .15s;
            }
            .so-grid-cell.is-active:hover {
                background: #eff6ff;
                box-shadow: inset 0 0 0 1px #bfdbfe;
            }
            .so-grid-label {
                font-size: 11px;
                font-weight: 700;
                color: #6b7280;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 0.15rem;
            }
            .so-grid-label.has-icon {
                display: inline-flex;
                align-items: center;
                gap: 0.2rem;
            }
            .so-grid-label.has-icon svg {
                width: 10px;
                height: 10px;
            }
            .so-grid-label.is-primary {
                color: #2563eb;
            }
            .so-grid-number {
                font-weight: 700;
                color: #1f2937;
            }
            .so-grid-number.lg {
                font-size: 1.125rem;
            }
            .so-grid-unit {
                font-size: 0.75rem;
                font-weight: 400;
                color: #6b7280;
            }
            .so-chip {
                display: inline-block;
                font-size: 0.8125rem;
                font-weight: 700;
                border-radius: 6px;
                padding: 0.1rem 0.5rem;
                border: 1px solid;
                line-height: 1.5;
            }
            .so-chip.danger {
                color: #dc2626;
                background: #fef2f2;
                border-color: #fecaca;
            }
            .so-chip.success {
                color: #059669;
                background: #ecfdf5;
                border-color: #a7f3d0;
            }
            .so-chip .so-chip-unit {
                font-size: 10px;
                font-weight: 400;
            }
            .so-loss-bar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 0.75rem;
                border: 1px solid #fecaca;
                background: #fef2f2;
                border-radius: 12px;
                padding: 0.65rem 1rem;
                color: #dc2626;
            }
            .so-loss-bar.success {
                border-color: #a7f3d0;
                background: #ecfdf5;
                color: #059669;
            }
            .so-loss-label {
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }
            .so-loss-value {
                font-size: 0.875rem;
                font-weight: 900;
            }
            .so-actions {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-top: 0.25rem;
                padding-top: 0.25rem;
            }
            .so-btn-group {
                display: flex;
                gap: 0.3rem;
            }
            .so-btn-sm {
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.8125rem;
                font-weight: 500;
                border: 0;
                border-radius: 8px;
                padding: 0.35rem 0.65rem;
                cursor: pointer;
                transition: background .15s;
                text-decoration: none;
            }
            .so-btn-sm svg {
                width: 14px;
                height: 14px;
            }
            .so-btn-ghost {
                background: transparent;
                color: #9ca3af;
                border: 0;
                cursor: pointer;
                padding: 0.15rem;
                transition: color .15s;
            }
            .so-btn-ghost:hover {
                color: #3b82f6;
            }
            #transaction-table tbody tr td {
                border: 0;
                padding: 0;
                background: transparent;
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
                                <textarea class="form-control form-control-solid" rows="3" placeholder="Tulis catatan audit..."></textarea>
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

        function formatShortDate(dateValue) {
            if (!dateValue) {
                return '-';
            }
            const dt = new Date(dateValue);
            if (Number.isNaN(dt.getTime())) {
                return dateValue;
            }
            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            }).format(dt);
        }

        function escapeHtml(value) {
            return $('<div>').text(value ?? '').html();
        }

        function formatTime(dateValue) {
            if (!dateValue) return '';
            const dt = new Date(dateValue);
            if (Number.isNaN(dt.getTime())) return '';
            const h = String(dt.getHours()).padStart(2, '0');
            const m = String(dt.getMinutes()).padStart(2, '0');
            return `${h}.${m}`;
        }

        function renderStockOpnameCard(row) {
            const productName = row?.product?.name || row?.name || '-';
            const code = row?.code ? `#${row.code}` : '-';
            const dateLabel = formatShortDate(row?.date);
            const timeLabel = formatTime(row?.created_at);
            const creatorName = row?.creator_name || '';
            const stockSystem = Number(row?.stock || 0);
            const stockFisik = Number(row?.real_stock || 0);
            const selisih = Number(row?.difference || 0);
            const hpp = Number(row?.avg_price || 0);
            const nilaiSelisih = selisih * hpp;
            const id = row?.id || 0;

            const isMinus = selisih < 0;
            const chipClass = isMinus ? 'danger' : 'success';
            const lossClass = isMinus ? '' : 'success';
            const hppText = `HPP: Rp ${formatRupiah(Math.abs(hpp))}/Kg`;
            const nilaiText = `${nilaiSelisih < 0 ? '-' : ''}Rp ${formatRupiah(Math.abs(nilaiSelisih))}`;
            const chipSign = selisih > 0 ? '+' : '';

            const creatorHtml = creatorName
                ? `<span class="so-card-creator">${escapeHtml(timeLabel)} &bull; ${escapeHtml(creatorName)}</span>`
                : '';

            return `
                <div class="so-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="so-card-title">${escapeHtml(productName)}</div>
                            <div class="d-flex align-items-center gap-2 mt-1">
                                <span style="font-size:0.8125rem;font-weight:500;color:#6b7280;">${escapeHtml(code)}</span>
                                <span class="so-card-badge">${escapeHtml(hppText)}</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="so-card-date">${escapeHtml(dateLabel)}</div>
                            ${creatorHtml}
                        </div>
                    </div>

                    <div class="so-grid-row">
                        <div class="so-grid-cell">
                            <div class="so-grid-label">Sistem</div>
                            <div><span class="so-grid-number lg">${formatRupiah(stockSystem)}</span> <span class="so-grid-unit">Kg</span></div>
                        </div>
                        <div class="so-grid-cell is-active" onclick="editProduct(${id})" title="Klik untuk sesuaikan stock fisik">
                            <div class="so-grid-label is-primary has-icon">
                                Fisik
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                            </div>
                            <div><span class="so-grid-number lg" style="color:#111827;">${formatRupiah(stockFisik)}</span> <span class="so-grid-unit">Kg</span></div>
                        </div>
                        <div class="so-grid-cell">
                            <div class="so-grid-label">Selisih Qty</div>
                            <div><span class="so-chip ${chipClass}">${chipSign}${formatRupiah(Math.abs(selisih))} <span class="so-chip-unit">Kg</span></span></div>
                        </div>
                    </div>

                    <div class="so-loss-bar ${lossClass}">
                        <span class="so-loss-label">Potensi Kerugian</span>
                        <span class="so-loss-value">${nilaiText}</span>
                    </div>

                    <div class="so-actions">
                        <div class="so-btn-group">
                            <button class="so-btn-sm" style="background:#eff6ff;color:#2563eb;" onclick="viewProduct(${id})">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                History
                            </button>
                            <button class="so-btn-sm" style="background:#fff7ed;color:#ea580c;">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                Obrolan
                            </button>
                            <button class="so-btn-sm" style="background:#fef2f2;color:#dc2626;" onclick="deleteProduct(${id})">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                Hapus
                            </button>
                        </div>
                        <button class="so-btn-ghost" onclick="viewProduct(${id})">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
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
