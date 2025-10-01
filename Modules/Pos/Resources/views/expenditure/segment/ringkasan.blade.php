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
                <div class="row mb-3">
                    <div class="col">
                        <select class="form-select" id="payment_id" name="payment_id">
                            <option value="">Pilih Tipe</option>
                        </select>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control text-end" :value="formatRupiah(payment)"
                            @input="updatePayment">
                    </div>
                </div>


                {{-- <!-- Total Setelah Diskon -->
                <div class="pt-2 border-top mt-3 d-flex justify-content-between">
                    <span class="fw-bold">Total Setelah Diskon</span>
                    <span class="fw-bold text-end">Rp <span x-text="formatRupiah(totalHargaKeseluruhan)"></span></span>
                </div> --}}
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
        {{-- <div class="col">
        </div> --}}
        {{-- <div class="col">
            <!-- Di elemen root Alpine.js, misalnya -->
            <div x-data="{ loading: false }">
                <button class="btn btn-sm btn-primary w-100"
                    @click="loading = true; goToPayment(() => loading = false)" :disabled="loading">
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
        </div> --}}
    </div>
    <div x-data="{ showActions: false }" class="position-fixed d-flex flex-column-reverse align-items-center"
        style="bottom: 70px; right: 30px; z-index: 1050; gap: 10px;">

        <!-- Tombol utama (floating) -->
        <button class="btn btn-primary rounded-circle shadow-lg" style="width: 50px; height: 50px;"
            @click="showActions = !showActions">
            <i class="bi bi-three-dots-vertical"></i>
        </button>

        <!-- Tombol tambahan (floating) -->
        <template x-if="showActions">
            <div class="d-flex flex-column align-items-center gap-2 mb-2">
                <button class="btn btn-sm btn-success shadow-lg" @click="saveTransaction()">
                    Simpan (Draft)
                </button>
                <button class="btn btn-sm btn-warning shadow-lg" @click="saveToOrderBook()">
                    Simpan (Final)
                </button>
            </div>
        </template>
    </div>
</div>
