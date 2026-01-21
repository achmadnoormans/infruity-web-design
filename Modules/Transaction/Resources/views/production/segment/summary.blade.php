 <!-- Production Summary -->
 <div class="d-flex flex-column gap-4">
     <button class="btn btn-sm btn-light-secondary w-100 mb-3" @click="showSummaryDetails = !showSummaryDetails">
         <div class="d-flex justify-content-between align-items-center">
             <span class="fw-bold">
                 <template x-if="showSummaryDetails">
                     <span><i class="bi bi-chevron-up me-2"></i> <span>Total Biaya Produksi (</span> <span x-text="ingredients.length"></span><span> Bahan</span>)</span>
                 </template>
                 <template x-if="!showSummaryDetails">
                     <span><i class="bi bi-chevron-down me-2"></i> <span>Total Biaya Produksi (</span> <span x-text="ingredients.length"></span><span> Bahan</span>)</span>
                 </template>
             </span>
             {{-- <span class="badge badge-light-info fs-7" x-text="'Total: ' + ingredients.length + ' bahan'"></span> --}}
             <span class="badge badge-light-info fs-7" x-text="formatCurrency(totalHpp)"></span>
         </div>
     </button>

     <div x-show="showSummaryDetails" x-transition>
         <div class="d-flex justify-content-between align-items-center mb-3">
             <span class="fs-5 fw-bold">Ringkasan Produksi</span>
         </div>

         <div class="separator separator-dashed"></div>

         <!-- Summary Items -->
         <div class="d-flex flex-column gap-3 mt-3">
             {{-- <!-- Total Bahan -->
             <div class="d-flex justify-content-between align-items-center">
                 <span class="text-gray-600">Total Bahan Baku:</span>
                 <span class="fw-bold text-gray-800" x-text="ingredients.length + ' item'"></span>
             </div>

             <!-- Total HPP -->
             <div class="d-flex justify-content-between align-items-center">
                 <span class="text-gray-600">Total HPP:</span>
                 <span class="fw-bold text-primary" x-text="formatCurrency(totalHpp)"></span>
             </div> --}}

             <!-- Quantity Production -->
             <div class="d-flex justify-content-between align-items-center">
                 <span class="text-gray-600">Jumlah Produksi:</span>
                 <span class="fw-bold text-gray-800" x-text="productionQuantity + ' unit'"></span>
             </div>

             <!-- HPP per Unit -->
             <div class="d-flex justify-content-between align-items-center">
                 <span class="text-gray-600">HPP per Unit:</span>
                 <span class="fw-bold text-success" x-text="formatCurrency(hppPerUnit)"></span>
             </div>

             <!-- Biaya Jasa -->
             <div class="d-flex justify-content-between align-items-center">
                 <span class="text-gray-600">Biaya Jasa:</span>
                 <span class="fw-bold text-success" x-text="formatCurrency(totalServiceCost / productionQuantity)"></span>
             </div>
         </div>

         <div class="separator separator-dashed mt-3"></div>

         <!-- Production Status -->
         <div class="d-flex justify-content-between align-items-center mt-3">
             <span class="text-gray-600">Status Produksi:</span>
             <span class="badge" :class="{
                 'badge-light-secondary': status === 'temp',
                 'badge-light-warning': status === 'draft',
                 'badge-light-success': status === 'posting'
             }" x-text="getStatusText(status)"></span>
         </div>

         <!-- Production Notes -->
         <div class="mt-3">
             <label class="form-label text-gray-600">Catatan Produksi:</label>
             <textarea class="form-control form-control-sm" name="notes" rows="3"
                 placeholder="Tambahkan catatan untuk produksi ini..." x-model="notes"></textarea>
         </div>
     </div>

     <!-- Action Buttons -->
    <div class="mt-4 border-top pt-3">
        {{-- <div class="d-flex justify-content-between mb-3">
             <div>
                 <span>Total Biaya Produksi (</span> <span x-text="ingredients.length"></span><span> Bahan + Jasa</span>)
             </div>
            <div class="fw-bold">
                <span></span>
                <span x-text="formatCurrency(totalHpp)"></span>
            </div>
        </div> --}}
        <div class="row mt-3 gap-2">
            <div class="col" x-data="{ loading: false }">
                <button class="btn btn-secondary w-100" @click="loading = true; saveProduction('temp', () => loading = false)"
                    :disabled="loading">
                    <template x-if="!loading">
                        <span><i class="bi bi-save me-2"></i> Simpan Draft</span>
                    </template>
                    <template x-if="loading">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    </template>
                </button>
            </div>
            {{-- <div class="col" x-data="{ loading: false }">
                <button class="btn btn-warning w-100" @click="loading = true; saveProduction('draft', () => loading = false)"
                    :disabled="loading">
                    <template x-if="!loading">
                        <span><i class="bi bi-clock me-2"></i> Siap Produksi</span>
                    </template>
                    <template x-if="loading">
                        <span>
                            <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                            Memproses...
                        </span>
                    </template>
                </button>
            </div> --}}
            <div class="col" x-data="{ loading: false }">
                <button class="btn btn-primary w-100" @click="loading = true; saveProduction('posting', () => loading = false)"
                    :disabled="loading">
                    <template x-if="!loading">
                        <span><i class="bi bi-check-circle me-2"></i> Selesai</span>
                    </template>
                    <template x-if="loading">
                        <span>
                            <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                            Menyelesaikan...
                        </span>
                    </template>
                </button>
            </div>
        </div>
    </div>
</div>
