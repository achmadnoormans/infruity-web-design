@if (isset($arsip))
    <br><br>
    <div class="d-flex justify-content-between">
        <div>
            <h3>Detail Keterangan Arsip</h3>
            <span style="color: red">{{ isset($arsip) && $data->id_status == 1 ? '(Reject)' : '' }}</span>
        </div>
        <a class="btn btn-primary" target="_blank" href="{{ url('permohonan/' . $arsip->id . '/show-keterangan-arsip') }}"><i
                class="fa-solid fa-print"></i>
            Preview Keterangan Arsip </a>
    </div>
    <hr>
    <table class="table">
        <tr>
            <td>No Persil</td>
            <td>:</td>
            <td>{{ $arsip->no_persil }}</td>
        </tr>
        <tr>
            <td>Alamat Persil</td>
            <td>:</td>
            <td>{{ $arsip->alamat_persil }}</td>
        </tr>
        <tr>
            <td>Tanggal IPT</td>
            <td>:</td>
            <td>{{ isset($arsip->tanggal_ipt) ? dateindo($arsip->tanggal_ipt) : '' }}</td>
        </tr>
        <tr>
            <td>Keperluan</td>
            <td>:</td>
            <td>{{ $data->layanan->nm_layanan ?? '' }}</td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>:</td>
            <td>{{ $arsip->keterangan }}</td>
        </tr>
        <tr>
            <td>File IPT</td>
            <td>:</td>
            <td>
                <a href="{{ route('show-document', [
                    'pdf' => $arsip->file,
                ]) }}"
                    target="_blank" class="badge badge-light-success">
                    <span>Lihat Document</span>
                </a>
            </td>
        </tr>
    </table>
@endif
