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

                <div x-data="{ showOngkir: false }" class="mb-3">
                    <!-- Tombol Toggle -->
                    <button type="button" class="btn btn-sm" :class="showOngkir ? 'btn-danger' : 'btn-outline-primary'"
                        @click="showOngkir = !showOngkir">
                        <template x-if="!showOngkir">
                            <span><i class="fa fa-plus"></i> Tambah Ongkir</span>
                        </template>
                        <template x-if="showOngkir">
                            <span><i class="fa fa-times"></i> Sembunyikan Ongkir</span>
                        </template>
                    </button>

                    <!-- Input Note -->
                    <div x-show="showOngkir" x-transition class="mt-2">
                        <!-- Input Ongkir -->
                        <div class="row">
                            <div class="mb-3 col">
                                <label class="form-label mb-1">Biaya Pengiriman</label>
                                <input type="text" class="form-control text-end" inputmode="numeric"
                                    :value="formatRupiah(ongkirGlobal)" @input="updateOngkirGlobal">
                            </div>
                            <div class="mb-3 col">
                                <label class="form-label mb-1">Diskon Ongkir</label>
                                <input type="text" class="form-control text-end" inputmode="numeric"
                                    :value="formatRupiah(diskonOngkir)" @input="updateDiskonOngkir">
                            </div>
                        </div>
                        <!-- Input Ongkir -->
                        <div class="mb-3 row">
                            <div class="col">
                                <label class="form-label mb-1">Jadwal</label>
                                <input type="date" class="form-control" name="ongkir_date" id="ongkir_date"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col mt-1">
                                <label class="form-label mb-1"></label>
                                <input type="time" class="form-control" name="ongkir_time" id="ongkir_time">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-1">Pilih Kurir</label>
                            <select class="form-control" name="courier_id" id="courier_id">
                                <option value="">Pilih Kurir</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label mb-1">Alamat Pengiriman</label>
                            <textarea name="ongkir_address" id="ongkir_address" cols="30" rows="5" class="form-control"></textarea>
                        </div>
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
        style="bottom: 70px; right: 85px; z-index: 1050; gap: 10px;">

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
                    Simpan (Order Book)
                </button>
            </div>
        </template>
    </div>
    <div x-data="{ loading: false }">
        <button class="btn btn-success rounded-circle shadow-lg position-fixed"
            style="bottom: 70px; right: 30px; width: 50px; height: 50px; z-index: 1050; display: flex; align-items: center; justify-content: center;"
            @click="loading = true; goToPayment(() => loading = false)" :disabled="loading">
            <template x-if="!loading">
                <i class="bi bi-cash-stack"></i>
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
