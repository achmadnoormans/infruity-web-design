@extends('template.root')
@section('page-name', Request::segment(1))
@section('title-page', 'List Surat')
@section('content')
    <div class="table-responsive signal-table custom-scrollbar">
        <table id="usersTable" class="table responsive table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No Surat</th>
                    <th>No Permohonan :</th>
                    <th>Nama Pemegang IPT</th>
                    <th>Tipe Permohonan :</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
        
    </div>
    @include('permohonan::surat.js-surat')
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('surat-data') }}",
                    data: function(d) {
                        d.id_permohonan = $('#id_permohonan').val();
                        d.nm_pemohon = $('#nm_pemohon').val();
                        d.alamat_persil = $('#alamat_persil').val();
                        d.id_layanan = $('#id_layanan').val();
                        d.id_status = $('#id_status').val();
                        d.url = "{{ request()->segment(1) }}";
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'no_surat',
                        name: 'no_surat'
                    },
                    {
                        data: 'no_permohonan',
                        name: 'no_permohonan'
                    },
                    {
                        data: 'nama_pemohon',
                        name: 'nama_pemohon'
                    },
                    {
                        data: 'nm_layanan',
                        name: 'nm_layanan'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },

                ]
            });
        });
    </script>
@endsection
