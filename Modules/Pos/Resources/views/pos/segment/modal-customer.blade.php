{{-- Modal Add CCustomer --}}
<div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content" x-data="{ customerName: '', customerPhone: '', customerAddress: '' }">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="customerModalLabel">Tambah Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label required">Nama</label>
                    <input type="text" class="form-control" x-model="customerName" placeholder="Nama customer">
                </div>
                <div class="mb-3">
                    <label class="form-label required">No WhatsApp</label>
                    <input type="text" class="form-control" x-model="customerPhone" placeholder="08xxxxxxxxxx">
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" x-model="customerAddress" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" @click="saveCustomer()">Simpan</button>
            </div>
        </div>
    </div>
</div>
