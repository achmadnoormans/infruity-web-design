<div class="card-footer text-end">
    <div class="col-sm-9 offset-sm-3">
        @if ($data->id_status == 1 && $data->is_lengkap != 1 && check_access('verifikasi-berkas'))
            <button class="btn btn-success me-3" onclick="verifikasi({{ json_encode($data) }}, 'verifikasi-berkas', 2)"><i
                    class="fas fa-check"></i>
                {{ isset($arsip) ? 'Verifikasi Ulang Berkas' : 'Verifikasi Berkas' }}</button>
        @endif
        @if ($data->id_status == 1 && $data->is_lengkap == 1 && check_access('verifikasi-arsip'))
            <button class="btn btn-success me-3" onclick="verifikasi({{ json_encode($data) }}, 'verifikasi-data', 2)"><i
                    class="fas fa-check"></i>
                {{ isset($arsip) ? 'Verifikasi Ulang' : 'Verifikasi Data' }}</button>
        @endif
        @if ($data->id_status == 1 && check_access('upload-bap'))
            @php
                $modal = 'upload-bap';
            @endphp
            <button class="btn btn-success me-3"
                onclick="verifikasi({{ json_encode($data) }}, '{{ $modal }}', 3)"><i class="fas fa-file"></i>
                {{ isset($bap) ? 'Upload Ulang BAP' : 'Upload BAP' }}</button>
        @endif
        @if ($data->id_status == 2 && check_access('create-surat'))
            @if (isset($surat))
                <a href="{{ url('surat-keterangan') . '/' . $surat->id . '/edit' }}" class="btn btn-success me-3"><i
                        class="fas fa-file"></i> Revisi Konsep SK</a>
            @else
                <a href="{{ url('surat-keterangan') . '/' . $data->id . '/create-surat' }}" class="btn btn-success me-3"><i
                        class="fas fa-file"></i> Buat Konsep SK</a>
            @endif
        @endif
        @if ($data->id_status == 3 && check_access('verifikasi-surat'))
            <button class="btn btn-success me-3"
                onclick="verifikasi({{ json_encode($data) }}, 'verifikasi-surat', 5)"><i class="fas fa-file"></i>
                Verifikasi Ketua Tim</button>
        @endif
        @if ($data->id_status == 4 && check_access('verifikasi-kabid'))
            <button class="btn btn-success me-3" onclick="verifikasi({{ json_encode($data) }}, 'verifikasi', 6)"><i
                    class="fas fa-file"></i>
                Verifikasi Kabid</button>
        @endif
        @if ($data->id_status == 5 && check_access('verifikasi-sekretaris'))
            <button class="btn btn-success me-3" onclick="verifikasi({{ json_encode($data) }}, 'verifikasi', 7)"><i
                    class="fas fa-file"></i>
                Verifikasi Sekertaris</button>
        @endif
        @if ($data->id_status == 6 && check_access('verifikasi-kaban'))
            <button class="btn btn-success me-3"
                onclick="verifikasi({{ json_encode($data) }}, 'verifikasi-kaban', 8)"><i class="fas fa-file"></i>
                Verifikasi KA BPKAD</button>
        @endif
        @if ($data->id_status == 7 && check_access('selesaikan-proses'))
            <button class="btn btn-success me-3" onclick="verifikasi({{ json_encode($data) }}, 'finish', 8)"><i
                    class="fas fa-check"></i>
                Selesai ?</button>
        @endif
        <a class="btn btn-light" href="{{ url('/permohonan') }}"><i class="far fa-times-circle"></i>
            Kembali</a>
    </div>
</div>
