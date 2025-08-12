<div class="accordion mb-3" id="accordionTotal">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTotal">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#collapseTotal" aria-expanded="false" aria-controls="collapseTotal">
                Rincian Total
            </button>
        </h2>
        <div id="collapseTotal" class="accordion-collapse collapse" aria-labelledby="headingTotal"
            data-bs-parent="#accordionTotal">
            <div class="accordion-body">
                <!-- Subtotal -->
                <div class="mb-3 d-flex justify-content-between">
                    <span class="fw-semibold">Subtotal</span>
                    <span class="text-end">Rp <span x-text="formatRupiah(subtotal)"></span></span>
                </div>

                <!-- Input Diskon -->
                <div class="mb-3">
                    <label class="form-label mb-1">Diskon (Rp jika > 100, % jika ≤ 100)</label>
                    <input type="text" class="form-control text-end" :value="formatRupiah(diskonGlobal)"
                        @input="updateDiskonGlobal">
                </div>

                <!-- Note -->
                <div x-data="{ showNote: false }" class="mb-3">
                    <!-- Tombol Toggle -->
                    <button type="button" class="btn btn-sm" :class="showNote ? 'btn-danger' : 'btn-outline-primary'"
                        @click="showNote = !showNote">
                        <template x-if="!showNote">
                            <span><i class="fa fa-plus"></i> Tambah Catatan</span>
                        </template>
                        <template x-if="showNote">
                            <span><i class="fa fa-times"></i> Sembunyikan Catatan</span>
                        </template>
                    </button>

                    <!-- Input Note -->
                    <div x-show="showNote" x-transition class="mt-2">
                        <label class="form-label mb-1">Note</label>
                        <textarea name="note" id="note" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>

                <!-- Input Ongkir -->
                <div class="mb-3">
                    <label class="form-label mb-1">Biaya Pengiriman</label>
                    <input type="text" class="form-control text-end" :value="formatRupiah(ongkirGlobal)"
                        @input="updateOngkirGlobal">
                </div>
                <!-- Input Ongkir -->
                <div class="mb-3 row">
                    <div class="col">
                        <label class="form-label mb-1">Jadwal</label>
                        <input type="date" class="form-control" name="ongkir_date">
                    </div>
                    <div class="col mt-1">
                        <label class="form-label mb-1"></label>
                        <input type="time" class="form-control" name="ongkir_time">
                    </div>
                </div>

                <!-- Total Setelah Diskon -->
                <div class="pt-2 border-top mt-3 d-flex justify-content-between">
                    <span class="fw-bold">Total Setelah Diskon</span>
                    <span class="fw-bold text-end">Rp <span x-text="formatRupiah(totalHargaKeseluruhan)"></span></span>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="mt-4 border-top pt-3">
    <div class="d-flex justify-content-between">
        <div>
            <span>Total</span> <span x-text="totalProduk"></span>
        </div>
        <div class="fw-bold">
            <span>Rp</span>
            <span x-text="formatRupiah(totalHargaKeseluruhan)"></span>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <button class="btn btn-sm btn-outline btn-outline-primary btn-active-light-primary w-100"
                @click="saveTransaction()">Simpan</button>
        </div>
        <div class="col">
            <!-- Di elemen root Alpine.js, misalnya -->
            <div x-data="{ loading: false }">
                <button class="btn btn-sm btn-primary w-100" @click="loading = true; goToPayment(() => loading = false)"
                    :disabled="loading">
                    <template x-if="!loading">
                        <span>Bayar</span>
                    </template>
                    <template x-if="loading">
                        <span>
                            <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                            Memproses...
                        </span>
                    </template>
                </button>
            </div>
        </div>
    </div>
</div>
