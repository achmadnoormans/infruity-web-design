<script>
    loadCustomer();

    function loadCustomer() {
        $('#customer_id').select2({
            placeholder: 'Pilih pelanggan',
            ajax: {
                url: '{{ route('customer.get-customer') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term
                }),
                processResults: data => {
                    const umum = {
                        id: '0',
                        name: 'Pelanggan Umum',
                        address: '-',
                        whatsapp: '-'
                    };
                    const results = data.map(item => ({
                        id: item.id,
                        name: item.name,
                        address: item.address,
                        whatsapp: item.whatsapp,
                        tier_id: item.tier_id,
                        tier_name: item.tier_name,
                        tier_style: item.tier_style || 'badge-light-secondary',
                        minimalPurchase: item.minimal_purchase || 0,
                        voucher: item.voucher,
                        discount: item.discount,
                    }));
                    return {
                        results: [umum, ...results]
                    };
                }
            },
            templateResult: formatCustomerOption, // render dropdown list
            templateSelection: formatCustomerSelection // render selected item
        });
    }

    $('#customer_id').on('select2:select', function(e) {
        const data = e.params.data;
        const tierId = data.tier_id || ''; // Pastikan Anda mengirimkan tier_id dari server jika dibutuhkan
        $('#tier_id').val(tierId); // Set ke input hidden
        $('#ongkir_address').val(data.address);
        const newOption = new Option(data.address, data.address, true, true);
        $('#address_id').append(newOption).trigger('change');
    });

    // Fungsi render untuk item di dropdown
    function formatCustomerOption(customer) {
        if (!customer.id) return customer.text;

        const name = customer.name ?? 'Pelanggan Umum';
        if (customer.id === '0') {
            return $(`<div style="font-size: 13px;"><strong>${name}</strong></div>`);
        }

        const whatsapp = customer.whatsapp || '-';
        const address = customer.address || '-';
        const tier_name = customer.tier_name || '-';
        const tierBadgeClass = customer.tier_style || 'badge-light-secondary';

        return $(`
        <div style="font-size: 13px; line-height: 1.4;">
            <strong>${name}</strong>
            <span class="text-muted d-block fs-7">${whatsapp}</span>
            <span class="text-muted d-block fs-7">${address}</span>
            <span class="badge ${tierBadgeClass} fs-7">${tier_name}</span>
        </div>
    `);
    }

    // Fungsi render untuk item terpilih
    function formatCustomerSelection(customer) {
        if (!customer.id) return customer.text;

        // cari <option> yang sesuai
        const el = $('#customer_id').find(`option[value="${customer.id}"]`);

        const name = customer.name ?? el.data('name') ?? 'Pelanggan Umum';
        const tier_name = customer.tier_name ?? el.data('tier_name') ?? '-';

        if (customer.id === '0') return name;

        return `${name} (${tier_name})`;
    }


    $url = '{{ Request::segment(3) }}';
    var data = @json($data ?? null);
    console.log(data);
    if ($url === 'edit' && data) {
        let c = {
            id: data.customer_id || 0,
            name: data.customer?.name || 'Pelanggan Umum',
            address: data.customer?.address || '-',
            phone: data.customer?.whatsapp || '-',
            tier_id: data.customer?.customer_tier?.tier_id || '',
            tier_name: data.customer?.customer_tier?.tier_name || '-',
            tier_style: data.customer?.customer_tier?.tier_style || 'badge-light-secondary'
        };

        // Buat option baru
        let option = new Option(c.name, c.id, true, true);

        // Tambahkan atribut data-*
        $(option).attr({
            'data-name': c.name,
            'data-address': c.address,
            'data-whatsapp': c.phone,
            'data-tier_id': c.tier_id,
            'data-tier_name': c.tier_name,
            'data-tier_style': c.tier_style
        });

        // Append ke select2 + set value
        $('#customer_id').append(option).val(c.id).trigger('change');
    } else {
        let c = {
            id: 0,
            name: 'Pelanggan Umum',
            address: '-',
            phone: '-',
            tier_id: '',
            tier_name: '-',
            tier_style: 'badge-light-secondary'
        };

        let option = new Option(c.name, c.id, true, true);

        $(option).attr({
            'data-name': c.name,
            'data-address': c.address,
            'data-whatsapp': c.phone,
            'data-tier_id': c.tier_id,
            'data-tier_name': c.tier_name,
            'data-tier_style': c.tier_style
        });

        $('#customer_id').append(option).val(c.id).trigger('change');
    }
</script>
<script>
    $('#courier_id').select2({
        placeholder: 'Pilih Kurir',
        ajax: {
            url: '/staff/get-staff', // ganti sesuai route
            dataType: 'json',
            delay: 250,
            processResults: data => ({
                results: data.map(item => ({
                    id: item.id,
                    text: item.name,
                }))
            })
        }
    })

    // Pastikan ada elemen <select id="branch_id"></select> di HTML

    // Tambahkan option Default ke select sebelum inisialisasi Select2
    $('#branch_id').select2({
        placeholder: 'Pilih Cabang',
        ajax: {
            url: '/ajax/getBranch', // ganti sesuai route
            dataType: 'json',
            delay: 250,
            processResults: data => {
                // ubah hasil dari server jadi format select2
                const branchOptions = data.map(item => ({
                    id: item.id,
                    text: item.name,
                }));

                // tambahkan opsi Default di awal
                branchOptions.unshift({
                    id: 0,
                    text: 'Pilih Cabang'
                });

                return {
                    results: branchOptions
                };
            }
        }
    });

    // Set default value setelah Select2 diinisialisasi
    const defaultOption = new Option('Pilih Cabang', 0, true, true);
    $('#branch_id').append(defaultOption).trigger('change');

    $('#address_id').select2({
        placeholder: 'Pilih Alamat Pengiriman',
        ajax: {
            url: '/customer/get-address',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    term: params.term,
                    customer_id: $('#customer_id').val(),
                    status: 'aktif',
                    limit: 10
                };
            },
            processResults: data => ({
                results: data.map(item => ({
                    id: item.address,
                    text: item.address,
                }))
            })
        },
        language: {
            noResults: function() {
                return 'Alamat tidak ditemukan.';
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    });


    $('#address_id').on('select2:open', function() {
        let addButton = `
        <div class="select2-add-address"
             style="padding: 8px; text-align: center; cursor: pointer; border-top: 1px solid #eee;">
            ➕ Tambah Alamat Baru
        </div>`;

        if (!$('.select2-add-address').length) {
            $('.select2-results').append(addButton);
        }

        $(document).off('click', '.select2-add-address').on('click', '.select2-add-address', function(e) {
            e.stopPropagation();
            $('#address_id').select2('close');
            $('#modal_tambah_alamat').modal('show');
        });
    });

    $('#form_tambah_alamat').on('submit', function(e) {
        e.preventDefault();

        const customerId = $('#customer_id').val();
        const alamatBaru = $('#alamat_baru').val().trim();

        if (!alamatBaru) {
            Swal.fire('Peringatan', 'Alamat tidak boleh kosong.', 'warning');
            return;
        }

        if (customerId == 0) {
            Swal.fire('Peringatan', 'Pelanggan tidak boleh kosong.', 'warning');
            return;
        }

        $.ajax({
            url: '/customer/store-address',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                customer_id: customerId,
                address: alamatBaru
            },
            success: function(response) {
                $('#modal_tambah_alamat').modal('hide');
                $('#alamat_baru').val('');

                // Tambahkan alamat baru ke dropdown
                const newOption = new Option(response.address, response.address, true, true);
                $('#address_id').append(newOption).trigger('change');

                // Select2 akan langsung menampilkan alamat baru sebagai terpilih
                $('#address_id').val(response.address).trigger('change');

                Swal.fire({
                    title: 'Berhasil',
                    text: 'Alamat baru berhasil ditambahkan dan dipilih.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan.', 'error');
            }
        });
    });
</script>
