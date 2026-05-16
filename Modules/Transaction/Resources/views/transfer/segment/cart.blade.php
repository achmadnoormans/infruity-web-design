<div class="col-md-12" style="height: 400px; overflow-y: auto;">
    @if ($is_view && ($data->status ?? '') !== 'selesai' && ($type ?? '') == 'transfer-penerima')
        <div class="mb-3">
            <span class="text-muted fs-7"><i class="bi bi-info-circle me-1"></i> Klik pada bahan untuk edit jumlah</span>
        </div>
    @endif
    <div>
        <template x-if="cart.length === 0">
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="text-muted">Keranjang kosong.</p>
            </div>
        </template>

        <div id="cart-items-container">
            <template x-for="(item, index) in cart" :key="item.id">
                <div class="card mb-3 p-4 cart-item position-relative" style="cursor: pointer;"
                    :class="item.typeProduct === 'gift' ?
                        'btn btn-outline btn-outline-dashed btn-outline-success' : ''"
                    @click="handleItemClick(item)">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center">
                                <h6 class="mb-1 fw-bold" x-text="item.name"></h6>
                                <template x-if="item.original_qty != item.qty">
                                    @if (($type ?? '') != 'transfer-pengirim')
                                        <span class="badge badge-light-danger ms-2 py-1 px-2"
                                            style="font-size: 0.65rem;">
                                            Dikoreksi
                                        </span>
                                    @endif
                                </template>
                                @if ($is_view && ($data->status ?? '') !== 'selesai' && ($type ?? '') == 'transfer-penerima')
                                    <i class="bi bi-pencil-fill text-warning fs-9 ms-2"></i>
                                @endif
                            </div>
                            <small class="text-muted d-flex align-items-center">
                                <template x-if="item.original_qty != item.qty">
                                    <div class="d-flex align-items-center">
                                        <span class="text-decoration-line-through text-gray-500 me-1"
                                            x-text="item.original_qty"></span>
                                        <i class="bi bi-arrow-right fs-9 text-danger me-1"></i>
                                        <span class="fw-bold text-danger" x-text="item.qty"></span>
                                    </div>
                                </template>
                                <template x-if="item.original_qty == item.qty">
                                    <span class="fw-bold" x-text="item.qty"></span>
                                </template>
                                <span class="ms-1" x-text="item.unit"></span>
                            </small>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
