{{-- Modal Add Parcel --}}
<div class="modal fade" id="parcelModal" tabindex="-1" aria-labelledby="parcelModalLabel" aria-hidden="true"
    x-show="showParcelModal" style="display: none;">
    <div class="modal-dialog modal-fullscreen-sm-down">
        <div class="modal-content" x-data>
            <div class="modal-header" style="background-color: #ff000d; color: #fff;">
                <h5 class="modal-title" style="color: #fff">Tambah Parcel</h5>
                <button type="button" class="btn-close" @click="closeAddModal()"></button>
            </div>
            <div class="modal-body">
                <!-- Select Produk -->
                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <select id="select_gift" class="form-select"></select>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" @click="closeParcelModal()">Tutup</button>
                <button class="btn btn-primary" @click="saveParcelToCart()">Simpan</button>
            </div>
        </div>
    </div>
</div>
