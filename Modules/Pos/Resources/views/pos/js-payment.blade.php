@section('script')
    <script type="text/javascript">
        $('#payment_id').select2({
            placeholder: 'Select a payment',
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

        $('#branch_id').select2({
            placeholder: 'Select a branch',
            ajax: {
                url: '{{ route('ajax.getBranch') }}',
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
                totalDue: {{ $data->total - $data->paid }}, // Ganti dengan nilai dari data.total misalnya dari backend
                totalPayment: {{ $data->total - $data->paid }},
                paymentDifference: 0,

                init() {
                    this.totalPayment = this.totalDue;
                    this.paymentDifference = 0;

                },

                get formattedTotalPayment() {
                    return this.formatRupiah(this.totalPayment);
                },
                set formattedTotalPayment(val) {
                    const raw = val.replace(/\./g, '').replace(/[^0-9]/g, '');
                    this.totalPayment = Number(raw || 0);
                    this.paymentDifference = this.totalPayment - this.totalDue;
                },

                updatePayment(e) {
                    this.formattedTotalPayment = e.target.value;
                },

                formatRupiah(angka) {
                    if (typeof angka !== 'number') angka = parseInt(angka);
                    return angka.toLocaleString('id-ID');
                },

                submitPayment() {
                    const payload = {
                        date: document.querySelector('[name="date"]').value,
                        payment_id: document.querySelector('[name="payment_id"]').value,
                        account_id: document.querySelector('[name="account_id"]').value,
                        total_payment: this.totalPayment,
                        branch_id: document.querySelector('[name="branch_id"]').value,
                        customer_id: '{{ $data->customer_id ?? '' }}', // kalau tersedia dari server
                        transaction_id: '{{ $data->id ?? '' }}'
                    };

                    if (!payload.payment_id || !payload.branch_id || !payload.date || !payload.total_payment) {
                        Swal.fire('Lengkapi data', 'Semua input wajib diisi.', 'warning');
                        return;
                    }

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
                                // console.log(res);
                                Swal.fire("Sukses", res.message, "success");
                                window.location.href = '/pos'; // redirect jika perlu
                            } else {
                                Swal.fire("Gagal", res.message, "error");
                            }
                        })
                        .catch(err => {
                            Swal.fire("Error", "Terjadi kesalahan menyimpan pembayaran.", "error");
                            console.error(err);
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
                            this.previousPayments = data;
                        })
                        .catch(error => {
                            console.error('Gagal mengambil pembayaran sebelumnya:', error);
                        });
                }

            }
        }
    </script>
@endsection
