<script>
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

    $('#customer_id').on('select2:select', function(e) {
        const data = e.params.data;
        const tierId = data.tier_id || ''; // Pastikan Anda mengirimkan tier_id dari server jika dibutuhkan

        $('#tier_id').val(tierId); // Set ke input hidden
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
        const tier_id = parseInt(customer.tier_id || 0);
        const tierBadgeClass = customer.tier_style || 'badge-light-secondary'; // Ambil style dari data

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

        const name = customer.name ?? 'Pelanggan Umum';

        if (customer.id === '0') {
            return name;
        }

        const whatsapp = customer.whatsapp || '-';
        const tier_name = customer.tier_name || '-';
        return `${name} (${tier_name})`;
    }

    $('#customer_id').append(new Option('Pelanggan Umum', '0', true, true)).trigger('change');
</script>
