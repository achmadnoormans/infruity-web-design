@section('script')
    <script>
        $(document).ready(function() {
            // Select2 untuk Department
            $('#department').select2({
                placeholder: 'Select a Department',
                ajax: {
                    url: '{{ route('ajax.department') }}', // sesuaikan jika ada
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Select2 untuk Position, bergantung pada Department
            $('#position').select2({
                placeholder: 'Select a Position',
                ajax: {
                    url: '{{ route('ajax.position') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            department_id: $('#department').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Reset position ketika department berubah
            $('#department').on('change', function() {
                $('#position').val(null).trigger('change');
            });

            $("#kt_ecommerce_edit_order_date").flatpickr({
                altInput: !0,
                altFormat: "d F, Y",
                dateFormat: "Y-m-d"
            });

            $("form").submit(function() {
                $(this).find(":submit").attr('disabled', 'disabled');
                $(this).find(":submit").html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                );
            });
        });
        let quill; // Definisikan di luar agar bisa diakses global

        document.addEventListener("DOMContentLoaded", function() {
            const quillElement = document.getElementById('kt_ecommerce_add_product_description');

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

            quill.root.innerHTML = '{{ $data->description ?? old('description') }}'; // Set konten awal

            document.getElementById('add_product_form').addEventListener('submit', function() {
                const description = document.getElementById('description_input');
                description.value = quill.root.innerHTML; // Ambil konten HTML
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah URL saat ini mengandung '/show'
            if (window.location.pathname.includes('/show')) {
                // Disable semua input, select, textarea
                document.querySelectorAll('input, select, textarea').forEach(function(el) {
                    el.setAttribute('disabled', 'disabled');
                });

                // Sembunyikan semua tombol submit
                document.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function(btn) {
                    btn.style.display = 'none';
                });
            }
        });
    </script>
@endsection
