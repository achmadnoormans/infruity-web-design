@section('script')
    <script>
        const tableData = $('#table-data');
        tableData.on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const parent = e.target.closest('tr');

            var urlnya = $(this).data('url');
            var token = TOKEN;
            var noRegistrasi = $(this).data('kode');
            console.log(noRegistrasi);
            Swal.fire({
                title: "Apa Kamu Ingin Mengpus Data " + noRegistrasi + " ?",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: "Hapus",
                denyButtonText: `Batal`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: urlnya,
                        data: {
                            _token: token
                        },
                        success: function(data) {
                            location.reload();
                        },
                        error: function(err) {
                            location.reload();
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire("Changes are not saved", "", "info");
                }
            });
        });

        $(document).ready(function() {
            $('.search-surat').select2({
                placeholder: 'Cari Surat',
                ajax: {
                    url: '{{ route('ajax.search.surat') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            });
        });

        $(document).ready(function() {
            $('.search-alamat-persil').select2({
                placeholder: 'Cari Alamat Persil',
                ajax: {
                    url: '{{ route('ajax.search.persil') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            });
        });

        $(document).ready(function() {
            $('.search-permohonan').select2({
                placeholder: 'Cari Permohonan',
                ajax: {
                    url: '{{ route('ajax.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            });
        });

        $(document).ready(function() {
            $('.search-nm-permohonan').select2({
                placeholder: 'Cari Nama Pemohon',
                ajax: {
                    url: '{{ route('ajax.search.pemohon') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
            });
        });

        $('#id_layanan').select2();
    </script>
@endsection
