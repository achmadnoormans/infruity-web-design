<script>
    $('#branch_id').select2({
        placeholder: 'Pilih Cabang',
        ajax: {
            url: '/ajax/getBranch', // ganti sesuai route
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
    $('#payment_id').select2({
        placeholder: 'Pilih Tipe Pembayaran',
        ajax: {
            url: '/ajax/getPaymentMethod', // ganti sesuai route
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
