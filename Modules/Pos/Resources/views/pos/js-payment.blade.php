@section('script')
    <script type="text/javascript">
        $('#payment_id').select2({
            placeholder: 'Pilih Pembayaran',
            ajax: {
                url: '{{ route('ajax.getPaymentMethod') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term
                }),
                processResults: data => {
                    return {
                        results: data.map(item => ({
                            id: item.id,
                            text: item.name
                        }))
                    };
                }
            }
        });

        // $('#branch_id').select2({
        //     placeholder: 'Pilih Cabang',
        //     ajax: {
        //         url: '{{ route('ajax.getBranch') }}',
        //         dataType: 'json',
        //         delay: 250,
        //         data: params => ({
        //             search: params.term
        //         }),
        //         processResults: data => {
        //             return {
        //                 results: data.map(item => ({
        //                     id: item.id,
        //                     text: item.name
        //                 }))
        //             };
        //         }
        //     }
        // });
        $.ajax({
            url: '{{ route('ajax.getBranch') }}',
            dataType: 'json',
            success: function(data) {
                const defaultBranch = data.find(item => item.id === 1);
                if (defaultBranch) {
                    const option = new Option(defaultBranch.name, defaultBranch.id, true, true);
                    $('#branch_id').append(option).trigger('change');
                }
            }
        });

        function posApp() {
            return {
                @php
                    $voucher = $deposito->voucher ?? 0;
                @endphp
                totalDue: {{ floor($data->total - $data->paid >= ($voucher ?? 0) ? $data->total - $data->paid - $voucher : $data->total - $data->paid) }}, // Ganti dengan nilai dari data.total misalnya dari backend
                totalPayment: {{ floor($data->total - $data->paid) }},
                paymentDifference: 0,
                loading: false,
                paymentMethods: [],

                async init() {
                    this.totalPayment = this.totalDue;
                    this.paymentDifference = 0;

                    try {
                        let res = await fetch("{{ route('ajax.getPaymentMethod') }}");
                        let data = await res.json();
                        this.paymentMethods = data; // pastikan server return array JSON [{id:1, name:'Cash'}, ...]
                    } catch (e) {
                        console.error("Gagal ambil payment methods:", e);
                    }

                    if (this.payments.length > 0) {
                        this.payments[0].amount = this.totalDue;
                    }

                    this.payments.forEach((p, i) => {
                        this.formatAmount(i);
                    });
                },

                payments: [{
                    payment_id: '',
                    payment_name: '',
                    amount: this.totalPayment,
                }],
                addPayment() {
                    this.payments.push({
                        payment_id: '',
                        payment_name: '',
                        amount: ''
                    });
                },

                removePayment(index) {
                    this.payments.splice(index, 1);
                },

                get formattedTotalPayment() {
                    return this.formatRupiah(this.totalPayment);
                },
                set formattedTotalPayment(val) {
                    const raw = val.replace(/\./g, '').replace(/[^0-9]/g, '');
                    this.totalPayment = Number(raw || 0);
                    this.paymentDifference = this.totalPayment - this.totalDue;

                    // update payment pertama jika ada
                    if (this.payments.length > 0) {
                        this.payments[0].amount = this.totalPayment;
                    }
                },

                formatAmount(index) {
                    let val = (this.payments[index].amount || '').toString().replace(/\D/g, "");
                    let num = parseInt(val) || 0;
                    this.payments[index].amount = num.toLocaleString('id-ID');
                },

                get totalPayment() {
                    return this.payments.reduce((sum, p) => {
                        let val = parseInt((p.amount || '').toString().replace(/\D/g, "")) || 0;
                        return sum + val;
                    }, 0);
                },

                get paymentStatus() {
                    if (this.totalPayment > this.totalDue) {
                        return {
                            status: 'Lebih Bayar',
                            selisih: this.totalPayment - this.totalDue
                        };
                    } else if (this.totalPayment < this.totalDue) {
                        return {
                            status: 'Kurang Bayar',
                            selisih: this.totalDue - this.totalPayment
                        };
                    }
                    return {
                        status: 'Pembayaran pas ✔ ',
                        selisih: 0
                    };
                },

                setPaymentName(index) {
                    const selectedId = this.payments[index].payment_id;
                    const method = this.paymentMethods.find(m => m.id == selectedId);
                    this.payments[index].payment_name = method ? method.name : '';
                },

                updatePayment(e) {
                    this.formattedTotalPayment = e.target.value;
                },

                formatRupiah(angka) {
                    if (typeof angka !== 'number') angka = parseInt(angka);
                    return angka.toLocaleString('id-ID');
                },

                formatDateDMY(dateString) {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    if (isNaN(date)) return '-';

                    const day = date.getDate().toString().padStart(2, '0');
                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                        'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                    ];
                    const month = monthNames[date.getMonth()];
                    const year = date.getFullYear().toString().slice(-2);

                    return `${day} ${month} ${year}`;
                },

                submitPayment() {
                    // this.loading = true; // mulai loading

                    const payload = {
                        date: document.querySelector('[name="date"]').value,
                        payments: this.payments,
                        // account_id: document.querySelector('[name="account_id"]').value,
                        total_payment: this.totalPayment,
                        // branch_id: document.querySelector('[name="branch_id"]').value,
                        customer_id: '{{ $data->customer_id ?? '' }}', // kalau tersedia dari server
                        transaction_id: '{{ $data->id ?? '' }}'
                    };

                    console.log(payload);
                    for (let p of this.payments) {
                        if (!p.payment_id || !p.payment_name) {
                            Swal.fire('Perhatian', 'Harus pilih metode pembayaran', 'warning');
                            this.loading = false;
                            return;
                        }
                    }

                    if (!payload.payments || !payload.date) {
                        Swal.fire('Lengkapi data', 'Semua input wajib diisi.', 'warning');
                        this.loading = false; // hentikan loading jika validasi gagal
                        return;
                    }

                    // Check if total penjualan is 0 and payment is 0
                    const totalPenjualan = {{ floor($data->total) }};
                    if (totalPenjualan == 0 && this.totalPayment == 0) {
                        Swal.fire({
                            title: 'Peringatan',
                            text: 'Total Penjualan dan Jumlah Pembayaran adalah 0. Apakah Anda yakin ingin menyimpan?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Simpan',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.doSavePayment(payload);
                            } else {
                                this.loading = false;
                            }
                        });
                        return;
                    }

                    if (payload.total_payment === undefined || payload.total_payment === null) {
                        Swal.fire('Lengkapi data', 'Jumlah pembayaran wajib diisi.', 'warning');
                        this.loading = false;
                        return;
                    }

                    this.doSavePayment(payload);
                },

                doSavePayment(payload) {

                    fetch("{{ route('pos.savePayment') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                console.log(res);
                                window.location.href = '/pos/payment-notification/' + res.payment
                                    .id; // redirect jika perlu
                            } else {
                                let errorMsg = res.error ? res.error : res.message;
                                Swal.fire("Gagal", errorMsg, "error");
                            }
                        })
                        .catch(err => {
                            Swal.fire("Error", "Terjadi kesalahan menyimpan pembayaran.", "error");
                            console.error(err);
                        })
                        .finally(() => {
                            this.loading =
                                false; // pastikan loading dihentikan di akhir proses, baik sukses maupun error
                        });
                },

                transaction_id: {{ $data->id ?? 'null' }},
                previousPayments: [],
                loadPreviousPayments() {
                    if (!this.transaction_id) return;

                    fetch(`/pos/listPayment/${this.transaction_id}`)
                        .then(res => res.json())
                        .then(data => {
                            console.log('Pembayaran sebelumnya:', data);
                            this.previousPayments = data.filter(payment => parseFloat(payment.total) > 0);
                        })
                        .catch(error => {
                            console.error('Gagal mengambil pembayaran sebelumnya:', error);
                        });
                }

            }
        }
    </script>
@endsection
