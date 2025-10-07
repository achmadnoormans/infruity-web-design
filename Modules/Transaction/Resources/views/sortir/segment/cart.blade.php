<div class="col-md-12" style="height: 200px; overflow-y: auto;">
    <div>
        <template x-if="cart.length === 0">
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="text-muted">Keranjang kosong.</p>
            </div>
        </template>

        <div id="cart-items-container">
            <template x-for="(item, index) in cart" :key="item.id">
                <div class="card mb-3 p-4 cart-item"
                    :class="item.typeProduct === 'gift' ?
                        'btn btn-outline btn-outline-dashed btn-outline-success' : ''">
                    <!-- Mobile Layout (Stack Vertically) -->
                    <div class="d-block d-lg-none"
                        @click="item.typeProduct === 'parcel' ? openEditParcelModal(item) : openEditModal(item)">
                        <!-- Product Name & Price -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mb-2">
                                <h6 class="mb-1 fw-bold" x-text="item.name"></h6>
                                <small class="text-muted d-flex">
                                    <span x-text="item.price.toLocaleString()"></span> &nbsp; x &nbsp;
                                    <span x-text="item.qty"></span>(<span x-text="item.unit"></span>)
                                </small>
                                <div x-show="item.discount > 0">
                                    <small class="text-muted">Diskon</small>
                                    <small class="text-muted" x-show="item.discountPercent > 0">
                                        <span x-text="item.discountPercent"></span>%
                                    </small>
                                    <small class="text-muted">
                                        (-<span x-text="item.discount.toLocaleString()"></span>)
                                    </small>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h6 class="mb-1 fw-bold text-transparent">a</h6>
                                <span class="text-muted">
                                    Rp <span
                                        x-text="(item.total_input ||( (item.price * item.qty) - item.discount)).toLocaleString()">
                                        ></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Layout (Horizontal) -->
                    <div class="d-none d-lg-block"
                        @click="item.typeProduct === 'parcel' ? openEditParcelModal(item) : openEditModal(item)">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="mb-2">
                                <h6 class="mb-1 fw-bold" x-text="item.name"></h6>
                                <small class="text-muted d-flex">
                                    <span x-text="item.price.toLocaleString()"></span> &nbsp; x &nbsp;
                                    <span x-text="item.qty"></span>(<span x-text="item.unit"></span>)
                                </small>
                                <div x-show="item.discount > 0">
                                    <small class="text-muted">Diskon</small>
                                    <small class="text-muted" x-show="item.discountPercent > 0">
                                        <span x-text="item.discountPercent"></span>%
                                    </small>
                                    <small class="text-muted">
                                        (-<span x-text="item.discount.toLocaleString()"></span>)
                                    </small>
                                </div>
                            </div>
                            <div class="mb-2">
                                <h6 class="mb-1 fw-bold text-transparent">a</h6>
                                <span class="text-muted">
                                    Rp <span
                                        x-text="(item.total_input ||( (item.price * item.qty) - item.discount)).toLocaleString()">
                                        ></span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
