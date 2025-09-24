<script>
    function parcelForm() {
        return {
            parcels: [],
            totalAll: 0,
            parcelId: 0,
            cart: [],
            qtyParcel: 1,
            budgetParcel: '',
            // get totalAll() {
            //     // kalau "Harga Jual" = budget per item, cukup jumlahkan price
            //     const total = this.parcels.reduce((sum, p) => sum + (Number(p.price) || 0), 0);
            //     const biayaJasa = parseInt(document.getElementById('parcel_jasa').value.replace(/\./g, ''), 10) ||
            //         0;
            //     const kemasanPrice = parseInt(document.getElementById('kemasan_price').value.replace(/\./g, ''),
            //             10) ||
            //         0;
            //     return total + biayaJasa + kemasanPrice;

            //     // kalau maunya total = qty * hargaAsli per item, pakai ini:
            //     // return this.parcels.reduce((sum, p) => sum + (Number(p.qty||0) * Number(p.priceAwal||0)), 0);
            // },

            setParcelId(id) {
                this.parcelId = id;
            },

            resetParcel() {
                this.parcels = [];
            },
            setParcel(item) {
                // this.parcels = []; // reset
                this.parcels.push(item); // masukkan data baru
                console.log('Parcel data set:', this.parcels);
            },

            addParcel() {
                this.parcels.push({
                    product: '',
                    name: '',
                    unit: '',
                    priceAwal: 0,
                    qty: 1,
                    price: 0,
                    priceFormatted: '',
                    hpp: 0
                });
                this.$nextTick(() => {
                    this.initSelect2();
                    this.updateTotal();
                });
            },
            removeParcel(index) {
                this.parcels.splice(index, 1);
                this.updateTotal();
            },
            updatePrice(index) {
                let raw = (this.parcels[index].priceFormatted || '').replace(/\D/g, '');
                let hargaJualBaru = parseInt(raw);
                if (isNaN(hargaJualBaru)) hargaJualBaru = 0;

                this.parcels[index].price = hargaJualBaru;
                this.parcels[index].priceFormatted = this.formatRupiah(hargaJualBaru);

                let hargaAsli = parseInt(this.parcels[index].priceAwal || 0);
                if (hargaAsli > 0) {
                    let qtyBaru = hargaJualBaru / hargaAsli;
                    this.parcels[index].qty = parseFloat(qtyBaru.toFixed(2));
                }
                this.updateTotal();
            },
            updateFromQty(index) {
                let hargaAsli = parseInt(this.parcels[index].priceAwal || 0);
                if (hargaAsli > 0) {
                    let hargaBaru = this.parcels[index].qty * hargaAsli;
                    this.parcels[index].price = hargaBaru;
                    this.parcels[index].priceFormatted = this.formatRupiah(hargaBaru);
                }
                this.updateTotal();
            },

            parseNumber(value) {
                if (!value) return 0;

                // Coba pakai regex untuk hapus karakter non-digit
                const clean = value.toString().replace(/[^\d]/g, "");
                return parseInt(clean, 10) || 0;
            },

            updateTotal() {
                // total produk: per item price * qty
                const totalProduk = this.parcels.reduce((sum, p) => {
                    const price = this.parseNumber(p.price || p.priceFormatted);
                    const qty = parseInt(p.qty || 1, 10) || 0;
                    return sum + (price * qty);
                }, 0);

                // baca biaya jasa / kemasan: coba DOM dulu; kalau tidak ada fallback ke state
                const jasaEl = document.getElementById('parcel_jasa');
                const kemasanEl = document.getElementById('kemasan_price');

                const biayaJasa = jasaEl ? this.parseNumber(jasaEl.value) : this.parseNumber(this.biayaJasa);
                const kemasanPrice = kemasanEl ? this.parseNumber(kemasanEl.value) : this.parseNumber(this
                    .kemasanPrice);

                this.biayaJasa = biayaJasa;
                this.kemasanPrice = kemasanPrice;

                // set final total numeric
                this.totalAll = totalProduk + biayaJasa + kemasanPrice;

                // (opsional) update kemasan input display
                if (kemasanEl) kemasanEl.value = kemasanPrice ? kemasanPrice.toLocaleString('id-ID') : '';
                // (opsional) jika ada elemen di UI yang menampilkan total, kamu bind ke totalAll dan format di template
            },

            formatRupiah(angka) {
                let num = parseInt(angka);
                if (isNaN(num) || num < 0) num = 0;
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(num);
            },
            initSelect2() {
                $('.parcel-select').select2({
                    placeholder: 'Pilih Parcel',
                    ajax: {
                        url: '/ajax/listProduct',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.id,
                                    text: item.name,
                                    unit: item.unit,
                                    price: item.price,
                                    hpp: item.hpp,
                                }))
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', (e) => {
                    let index = $(e.target).data('index');
                    let data = e.params.data;
                    this.parcels[index].product = data.id;
                    this.parcels[index].name = data.text;
                    this.parcels[index].unit = data.unit;
                    this.parcels[index].priceAwal = data.price;
                    this.parcels[index].hpp = data.hpp;
                    this.parcels[index].price = data.price;
                    this.parcels[index].priceFormatted = this.formatRupiah(data.price);
                    this.parcels[index].qty = 1;
                    this.updateTotal();
                });

                $('.parcel-select-edit').select2({
                    placeholder: 'Pilih Parcel',
                    ajax: {
                        url: '/ajax/listProduct',
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: data.map(item => ({
                                    id: item.id,
                                    text: item.name,
                                    unit: item.unit,
                                    price: item.price,
                                    hpp: item.hpp,
                                }))
                            };
                        },
                        cache: true
                    }
                }).on('select2:select', (e) => {
                    let index = $(e.target).data('index');
                    let data = e.params.data;
                    this.parcels[index].product = data.id;
                    this.parcels[index].name = data.text;
                    this.parcels[index].unit = data.unit;
                    this.parcels[index].priceAwal = data.price;
                    this.parcels[index].hpp = data.hpp;
                    this.parcels[index].price = data.price;
                    this.parcels[index].priceFormatted = this.formatRupiah(data.price);
                    this.parcels[index].qty = 1;
                    this.updateTotal();
                });
            },
            saveParcelToCart() {
                const budget = document.getElementById('parcel_budget').value;
                const qty = document.getElementById('parcel_qty').value;
                const fee = document.getElementById('parcel_jasa').value;
                const kemasan = $('#select_kemasan option:selected').text();
                const kemasanId = $('#select_kemasan option:selected').val();
                const parcel = {
                    id: 'parcel' + kemasanId + this.formatShortNumber(budget),
                    name: 'Parcel ' + kemasan + '-' + this.formatShortNumber(budget),
                    price: parseInt(budget.replace(/\./g, ''), 10),
                    fee: parseInt(fee.replace(/\./g, ''), 10) || 0,
                    hpp: 0,
                    qty: qty,
                    unit: 'Parcel',
                    discount: 0,
                    discountPercent: 0,
                    total_input: 0,
                    kemasanId: kemasanId,
                    kemasanName: kemasan,
                    typeProduct: 'parcel',
                };
                const posParcel = {
                    id: 'parcel' + kemasanId + this.formatShortNumber(budget),
                    budget: budget,
                    qty: qty,
                    kemasan: kemasan,
                    kemasanId: kemasanId,
                    hpp: this.totalAll,
                    fee: fee,
                    data: this.parcels,
                    type: 'parcel',
                }

                let posAppInstance = Alpine.$data(document.querySelector('[x-data="posApp()"]'));
                posAppInstance.cart.push(parcel);
                posAppInstance.parcel.push(posParcel);
                document.getElementById('parcel_budget').value = '';
                document.getElementById('parcel_qty').value = 1;
                document.getElementById('parcel_jasa').value = '';
                $('#select_kemasan').val(null).trigger('change');
                this.parcels = [];

                // console.log("Cart sekarang:", posAppInstance.cart, posAppInstance.parcel);
                // console.log('Parcel:', this.parcels, 'Product', parcel);
                // this.parcels.forEach(item => {
                //     console.log("Produk ID:", item.product);
                //     console.log("Nama:", item.name);
                //     console.log("Unit:", item.unit.name);
                //     console.log("Harga Awal:", item.priceAwal);
                //     console.log("Qty:", item.qty);
                // });
                // let budgetClean = parseInt(this.budgetParcel.replace(/[^0-9]/g, '')) || 0;

                // posApp.cart.push({
                //     id: Date.now(), // ID unik
                //     name: 'Parcel',
                //     qty: this.qtyParcel,
                //     budget: budgetClean
                // });

                // this.closeAddModal();
            },

            editParcelToCart(parcelId) {
                console.log(parcelId);
                const budget = document.getElementById('parcel_edit_budget').value;
                const qty = document.getElementById('parcel_edit_qty').value;
                const fee = document.getElementById('parcel_edit_jasa').value;
                const kemasan = $('#select_edit_kemasan option:selected').text();
                const kemasanId = $('#select_edit_kemasan option:selected').val();

                const parcel = {
                    id: 'parcel' + kemasanId + this.formatShortNumber(budget),
                    name: 'Parcel ' + kemasan + '-' + this.formatShortNumber(budget),
                    price: parseInt(budget.replace(/\./g, ''), 10),
                    fee: parseInt(fee.replace(/\./g, ''), 10) || 0,
                    hpp: 0,
                    qty: qty,
                    unit: 'Parcel',
                    discount: 0,
                    discountPercent: 0,
                    total_input: 0,
                    typeProduct: 'parcel',
                };

                const posParcel = {
                    id: 'parcel' + kemasanId + this.formatShortNumber(budget),
                    budget: budget,
                    qty: qty,
                    kemasan: kemasan,
                    hpp: this.totalAll,
                    fee: fee,
                    data: this.parcels, // isi produk dalam parcel
                    type: 'parcel',
                };

                let posAppInstance = Alpine.$data(document.querySelector('[x-data="posApp()"]'));

                // cari index berdasarkan parcelId
                let idxCart = posAppInstance.cart.findIndex(p => p.id === parcelId);
                let idxParcel = posAppInstance.parcel.findIndex(p => p.id === parcelId);

                if (idxCart !== -1) {
                    posAppInstance.cart.splice(idxCart, 1, parcel);
                }
                if (idxParcel !== -1) {
                    posAppInstance.parcel.splice(idxParcel, 1, posParcel);
                }

                // kalau mau reset form setelah edit
                document.getElementById('parcel_budget').value = '';
                document.getElementById('parcel_qty').value = 1;
                document.getElementById('parcel_jasa').value = '';
                $('#select_kemasan').val(null).trigger('change');
                this.parcels = [];
            },

            deleteParcel(parcelId) {
                if (!parcelId) return;

                let posAppInstance = Alpine.$data(document.querySelector('[x-data="posApp()"]'));
                let idxCart = posAppInstance.cart.findIndex(p => p.id === parcelId);
                let idxParcel = posAppInstance.parcel.findIndex(p => p.id === parcelId);
                if (idxCart !== -1) {
                    posAppInstance.cart.splice(idxCart, 1);
                }
                if (idxParcel !== -1) {
                    posAppInstance.parcel.splice(idxParcel, 1);
                }
                this.showParcelEditModal = false;
                const modal = bootstrap.Modal.getInstance(document.getElementById('parcelEditModal'));
                if (modal) modal.hide();
            },

            formatShortNumber(num) {
                num = parseInt(num.toString().replace(/\./g, '')) || 0; // hapus titik & jadi integer

                if (num >= 1_000_000_000) {
                    return (num / 1_000_000_000).toFixed(1).replace(/\.0$/, '') + 'B';
                } else if (num >= 1_000_000) {
                    return (num / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
                } else if (num >= 1_000) {
                    return (num / 1_000).toFixed(1).replace(/\.0$/, '') + 'K';
                }
                return num.toString();
            },

        }
    }
</script>
