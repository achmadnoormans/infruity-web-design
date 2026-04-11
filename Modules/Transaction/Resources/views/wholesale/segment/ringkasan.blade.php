<div class="mt-4 border-top pt-3">
    <div class="d-flex justify-content-between">
        <div>
            <span>Total</span> <span x-text="totalProduk"></span><span> Item</span>
        </div>
        <div class="fw-bold">
            <span>Rp</span>
            <span x-text="formatRupiah(totalHargaKeseluruhan)"></span>
        </div>
    </div>
    <div class="row mt-5 gap-2">
        <div class="col" x-data="{ loadingDraft: false }">
            <button class="btn btn-primary w-100" @click="loadingDraft = true; saveTransaction(() => loadingDraft = false)"
                :disabled="loadingDraft">
                <template x-if="!loadingDraft">
                    <span><i class="bi bi-save me-2"></i> Draft</span>
                </template>
                <template x-if="loadingDraft">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </template>
            </button>
        </div>
        <div class="col" x-data="{ loadingFinal: false }">
            <button class="btn btn-success w-100" @click="loadingFinal = true; saveToOrderBook(() => loadingFinal = false)"
                :disabled="loadingFinal">
                <template x-if="!loadingFinal">
                    <span><i class="bi bi-cash-stack me-2"></i> Submit</span>
                </template>
                <template x-if="loadingFinal">
                    <span>
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                        Memproses...
                    </span>
                </template>
            </button>
        </div>
    </div>
</div>