@section('script')
    <script>
        let quill; // Definisikan di luar agar bisa diakses global

        document.addEventListener("DOMContentLoaded", function() {
            const quillElement = document.getElementById('kt_ecommerce_add_product_description');

            $("#kt_ecommerce_edit_order_date").flatpickr({
                altInput: !0,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d"
            });

            if (quillElement) {
                quill = new Quill(quillElement, {
                    modules: {
                        toolbar: [
                            [{
                                header: [1, 2, false]
                            }],
                            ['bold', 'italic', 'underline'],
                            ['image', 'code-block']
                        ]
                    },
                    placeholder: "Type your text here...",
                    theme: "snow"
                });
            }

            // quill.root.innerHTML = '{{ $data->description ?? old('description') }}'; // Set konten awal

            // document.getElementById('add_product_form').addEventListener('submit', function() {
            //     const description = document.getElementById('description_input');
            //     description.value = quill.root.innerHTML; // Ambil konten HTML
            // });
        });

        $("form").submit(function() {
            $(this).find(":submit").attr('disabled', 'disabled');
            $(this).find(":submit").html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
            );
        });

        // Province
        $('#province').select2({
            placeholder: 'Select a province',
            ajax: {
                url: '{{ route('ajax.province') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term
                }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                })
            }
        });

        // City
        $('#city').select2({
            placeholder: 'Select a city',
            ajax: {
                url: '{{ route('ajax.city') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term,
                    province_id: $('#province').val()
                }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                })
            }
        });

        // District
        $('#district').select2({
            placeholder: 'Select a district',
            ajax: {
                url: '{{ route('ajax.district') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term,
                    city_id: $('#city').val()
                }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                })
            }
        });

        // Village
        $('#village').select2({
            placeholder: 'Select a village',
            ajax: {
                url: '{{ route('ajax.village') }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    search: params.term,
                    district_id: $('#district').val()
                }),
                processResults: data => ({
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                })
            }
        });

        $('#province').on('change', function() {
            $('#city').val(null).trigger('change');
            $('#district').val(null).trigger('change');
            $('#village').val(null).trigger('change');
        });

        $('#city').on('change', function() {
            $('#district').val(null).trigger('change');
            $('#village').val(null).trigger('change');
        });

        $('#district').on('change', function() {
            $('#village').val(null).trigger('change');
        });
    </script>
@endsection
