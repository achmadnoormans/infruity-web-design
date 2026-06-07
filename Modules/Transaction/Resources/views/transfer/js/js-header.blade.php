<script>
    // STEP 1: Ambil data pertama dulu
    // Detect jika URL mengandung kata "create"
    $.ajax({
        url: '/ajax/getBranch',
        data: {
            show_all: 1
        },
        dataType: 'json',
        success: function(data) {

            if (window.location.pathname.includes("create")) {
                @if(isset($branches) && count($branches) > 0)
                $('#branch_id')
                    .append(new Option('{{ $branches[0]->name }}', '{{ $branches[0]->id }}', true, true))
                    .trigger('change');
                @else
                if (data.length > 0) {
                    let first = data[0];
                    $('#branch_id')
                        .append(new Option(first.name, first.id, true, true))
                        .trigger('change');
                }
                @endif
            }

            $('#branch_id').select2({
                placeholder: 'Pilih Cabang',
                ajax: {
                    url: '/ajax/getBranch',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term,
                            show_all: 1
                        };
                    },
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.name,
                        }))
                    })
                }
            });

        }
    });

    $.ajax({
        url: '/ajax/getBranch',
        data: {
            show_all: 1
        },
        dataType: 'json',
        success: function(data) {
            if (window.location.pathname.includes("create")) {
                @if(isset($branches) && count($branches) > 0)
                $('#branch_id')
                    .append(new Option('{{ $branches[0]->name }}', '{{ $branches[0]->id }}', true, true))
                    .trigger('change');
                @else
                if (data.length > 0) {
                    let first = data[0];
                    $('#branch_id')
                        .append(new Option(first.name, first.id, true, true))
                        .trigger('change');
                }
                @endif
            }
            $('#branch_destination_id').select2({
                placeholder: 'Pilih Cabang',
                ajax: {
                    url: '/ajax/getBranch',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            term: params.term,
                            show_all: 1
                        };
                    },
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.name,
                        }))
                    })
                }
            });
        }
    });


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
