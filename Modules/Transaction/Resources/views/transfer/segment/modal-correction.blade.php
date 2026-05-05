<!-- Modal Koreksi -->
<div class="modal fade" id="correctionModal" tabindex="-1" aria-hidden="true" x-ref="correctionModal">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="fw-bold">Koreksi Quantity</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <i class="bi bi-x fs-1"></i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <template x-if="correctionItem">
                    <div>
                        <div class="mb-5">
                            <label class="fs-6 fw-semibold mb-2">Produk</label>
                            <input type="text" class="form-control form-control-solid" x-bind:value="correctionItem.name" readonly />
                        </div>
                        <div class="mb-5">
                            <label class="fs-6 fw-semibold mb-2">Quantity Sebelumnya</label>
                            <input type="text" class="form-control form-control-solid" x-bind:value="correctionItem.qty + ' ' + correctionItem.unit" readonly />
                        </div>
                        <div class="mb-5">
                            <label class="required fs-6 fw-semibold mb-2">Quantity Baru</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" x-model="correctionQty" />
                                <span class="input-group-text" x-text="correctionItem.unit"></span>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="fs-6 fw-semibold mb-2">Catatan Koreksi</label>
                            <textarea class="form-control" x-model="correctionNote" rows="3" placeholder="Alasan koreksi..."></textarea>
                        </div>
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" :disabled="correctionLoading">Batal</button>
                            <button type="button" class="btn btn-primary" @click="saveCorrection()" :disabled="correctionLoading">
                                <span class="indicator-label" x-show="!correctionLoading">Simpan Perubahan</span>
                                <span class="indicator-progress" x-show="correctionLoading">
                                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>

                        <!-- History Section -->
                        <div class="mt-10" x-show="correctionItem.corrections && correctionItem.corrections.length > 0">
                            <h3 class="fw-bold mb-5">History Perubahan</h3>
                            <div class="table-responsive">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-100px">Tanggal</th>
                                            <th class="min-w-100px">Qty</th>
                                            <th class="min-w-150px">Oleh</th>
                                            <th class="min-w-150px">Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="corr in correctionItem.corrections" :key="corr.id">
                                            <tr>
                                                <td>
                                                    <span class="text-dark fw-bold d-block fs-7" x-text="new Date(corr.created_at).toLocaleString('id-ID')"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted fw-semibold d-block fs-7" x-text="corr.old_quantity + ' → ' + corr.new_quantity"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted fw-semibold d-block fs-7" x-text="corr.user ? corr.user.nm_user : 'Unknown'"></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted fw-semibold d-block fs-7" x-text="corr.note || '-'"></span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
