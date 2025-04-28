@if (isset($bap))
    <br><br>
    <div class="d-flex justify-content-between">
        <div>
            <h3>Detail BAP</h3>
            <span style="color: red">{{ isset($bap) && $data->id_status == 1 ? '(Reject)' : '' }}</span>
        </div>
        {{-- <a class="btn btn-primary" target="_blank" href="{{ url('permohonan/' . $data->id . '/show-bap') }}"><i
                class="fa-solid fa-print"></i>
            Preview BAP </a> --}}
    </div>
    <hr>
    <table class="table">
        @if ($data->id_layanan != 7)
            <tr>
                <td>No IPT</td>
                <td>:</td>
                <td>{{ $bap->no_ipt }}</td>
            </tr>
            <tr>
                <td>Tanggal IPT</td>
                <td>:</td>
                <td>{{ isset($bap->tanggal_ipt) ? dateindo($bap->tanggal_ipt) : '' }}</td>
            </tr>
            <tr>
                <td>Peruntukan</td>
                <td>:</td>
                <td>{{ $bap->peruntukan }}</td>
            </tr>
            <tr>
                <td>Penggunaan</td>
                <td>:</td>
                <td>{{ $bap->penggunaan }}</td>
            </tr>
            <tr>
                <td>Luas</td>
                <td>:</td>
                <td>{{ $bap->luas }}</td>
            </tr>
        @endif
        <tr>
            <td>File BAP</td>
            <td>:</td>
            <td>
                {{-- @php
                        if (Storage::exists($bap->file ?? 'fc')) {
                            $path = $bap->file;
                            $full_path = Storage::path($path);
                            $base64 = base64_encode(Storage::get($path));
                            $image = 'data:' . mime_content_type($full_path) . ';base64,' . $base64;
                            $data->foto = $image;
                        }
                    @endphp
                    <img src="{{ $data->foto }}" style="height: 150px"> --}}
                <a href="{{ route('show-document', [
                    'pdf' => $bap->file,
                ]) }}"
                    target="_blank" class="badge badge-light-success">
                    <span>Lihat Document</span>
                </a>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <iframe src="{{ route('show-document', [
                    'pdf' => $bap->file,
                ]) }}" width="100%"
                    height="600px" frameborder="0"></iframe>
            </td>
        </tr>
        @if (isset($data->type))
            <tr>
                <td>Type Pengurangan</td>
                <td>:</td>
                <td>{{ $data->type }}</td>
            </tr>
        @endif
    </table>
@endif
