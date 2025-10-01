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
</script>
