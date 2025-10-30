@extends('template.root')

@section('content')
    <div class="w-100 flex-lg-row-auto me-7 me-lg-10" x-data="posApp()" x-init="init()">
        <div class="card card-body mb-3">
            <div class="d-flex flex-column gap-10 mb-3">
                <!--begin::Input group-->
                <div class="row">
                    <div class="col-9">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Pilih Pelanggan</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <select class="form-select" id="customer_id" name="customer_id">
                                <option value="">Pilih Pelanggan</option>
                            </select>
                            <!--end::Editor-->
                        </div>
                    </div>
                    <input type="hidden" name="tier_id" id="tier_id">
                    <div class="col-3 mt-8">
                        <button @click="addCustomer()"
                            class="btn btn-outline btn-outline-dashed btn-outline-primary btn-active-light-primary">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>

                </div>
                <!--end::Input group-->
            </div>
            <div class="d-flex flex-column gap-10">
                <!--begin::Input group-->
                <div class="row">
                    <div class="col">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Tanggal Transaksi</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}">
                            <!--end::Editor-->
                        </div>
                    </div>
                    <div class="col">
                        <div class="fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">Nomor Faktur</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <input type="text" class="form-control" name="invoice_number" value="{{ $invoice_number }}"
                                readonly>
                            <!--end::Editor-->
                        </div>
                    </div>
                </div>
                <!--end::Input group-->
            </div>
        </div>
        <div>
            <div class="card card-body mb-3">
                <div class="mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fs-5 fw-bold d-flex">Masukkan Produk yang di Pesan</span>
                        <span class="text-danger">Diperbarui per {{ date('d/m/Y') }}</span>
                    </div>
                </div>
                <div>
                    <textarea name="note" id="note" cols="30" rows="20" class="form-control"
                        placeholder="Silahkan paste note disini">{{ $data->note ?? '' }}</textarea>
                </div>
            </div>
            @include('pos::pos.segment.modal-customer')
        </div>
        <div class="text-end" x-data="{ loading: false }">
            <button class="btn btn-warning" @click="loading = true; saveDraft(() => loading = false)"
                :disabled="loading">
                <template x-if="!loading">
                    <span>Draft</span>
                </template>
                <template x-if="loading">
                    <span>
                        <span class="spinner-border spinner-border-sm align-middle me-2"></span>
                        Memproses...
                    </span>
                </template>
            </button>
            <button class="btn btn-primary" @click="loading = true; saveTransaction(() => loading = false)"
                :disabled="loading">
                <template x-if="!loading">
                    <span>Simpan dan Proses</span>
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
@section('script')
    @include('pos::pos.js.js-header')
    <script>
        function posApp() {
            return {
                init() {
                    const self = this; // simpan konteks Alpine
                    $('#customer_id').on('select2:select', function(e) {
                        const data = e.params.data;
                        self.setMinimalPurchase(data.minimalPurchase || 0);
                        self.setVoucher(data.voucher || 0);
                        self.setDiscountGlobal(data.discount || 0);
                    });
                },

                addCustomer() {
                    const modal = new bootstrap.Modal(document.getElementById('customerModal'));
                    modal.show();
                },

                saveCustomer() {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('customerModal'));
                    const name = document.querySelector('[x-model="customerName"]').value;
                    const phone = document.querySelector('[x-model="customerPhone"]').value;
                    const address = document.querySelector('[x-model="customerAddress"]').value;

                    if (!name || !phone) {
                        Swal.fire('Lengkapi data', 'Nama dan nomor telepon wajib diisi.', 'warning');
                        return;
                    }

                    fetch('/pos/customers', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                name,
                                phone,
                                address
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                modal.hide();
                                console.log(res);

                                const c = res.customer;

                                // Buat <option> baru dengan atribut tambahan
                                const option = new Option(c.name, c.id, true, true);
                                $(option).attr({
                                    'data-name': c.name,
                                    'data-address': c.address,
                                    'data-whatsapp': c.phone,
                                    'data-tier_id': c.tier_id || '',
                                    'data-tier_name': c.tier_name || '-',
                                    'data-tier_style': c.tier_style || 'badge-light-secondary'
                                });

                                // Tambahkan ke select2
                                $('#customer_id').append(option).trigger('change');
                                $('#ongkir_address').text(c.address);

                                // Swal.fire('Berhasil', 'Customer berhasil ditambahkan.', 'success');
                            } else {
                                Swal.fire('Gagal', res.message ?? 'Gagal menyimpan customer.', 'error');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire('Error', 'Terjadi kesalahan saat menyimpan.', 'error');
                        });
                },

                saveTransaction(doneCallback) {

                    const customerId = document.querySelector('select[name="customer_id"]').value;
                    const transactionDate = document.querySelector('input[name="date"]').value;
                    const note = document.querySelector('textarea[name="note"]').value;
                    const invoice_number = document.querySelector('input[name="invoice_number"]').value;

                    if (note == null || note == '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Inputan Kosong',
                            text: 'Silahkan masukkan pesanan!',
                        });
                        if (typeof doneCallback === 'function') doneCallback();
                        return;
                    }


                    const data = {
                        customer_id: customerId,
                        date: transactionDate,
                        status: 'process',
                        note: note,
                        invoice_number: invoice_number,
                    };

                    // Simulasi kirim ke server
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch('/order-book/save-transaction', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data),
                        })
                        .then(res => res.json())
                        .then(res => {
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Berhasil',
                            //     text: 'Transaksi berhasil disimpan!',
                            // });
                            // this.resetPOS(); // Reset cart dsb.
                            window.location.href = '/order-book/' + res.order_book_id + '/order';
                            // redirectToHome();

                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menyimpan transaksi.',
                            });
                            console.error(err);
                            if (typeof doneCallback === 'function') doneCallback();
                            return;
                        });
                },

                saveDraft(doneCallback) {

                    const customerId = document.querySelector('select[name="customer_id"]').value;
                    const transactionDate = document.querySelector('input[name="date"]').value;
                    const note = document.querySelector('textarea[name="note"]').value;
                    const invoice_number = document.querySelector('input[name="invoice_number"]').value;

                    if (note == null || note == '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Inputan Kosong',
                            text: 'Silahkan masukkan pesanan!',
                        });
                        if (typeof doneCallback === 'function') doneCallback();
                        return;
                    }


                    const data = {
                        customer_id: customerId,
                        date: transactionDate,
                        status: 'draft',
                        note: note,
                        invoice_number: invoice_number,
                    };

                    // Simulasi kirim ke server
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch('/order-book/save-transaction', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data),
                        })
                        .then(res => res.json())
                        .then(res => {
                            // Swal.fire({
                            //     icon: 'success',
                            //     title: 'Berhasil',
                            //     text: 'Transaksi berhasil disimpan!',
                            // });
                            // this.resetPOS(); // Reset cart dsb.
                            window.location.href = '/order-book';
                            // redirectToHome();

                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal menyimpan transaksi.',
                            });
                            console.error(err);
                            if (typeof doneCallback === 'function') doneCallback();
                            return;
                        });
                },
            }
        }
    </script>
@endsection
@endsection
