<div class="modal fade" id="verifikasi-berkas-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="verifikasi-modal" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Berkas Permohonan No {{ $data->no_permohonan }} Lengkap?</h4>
                    <form action="{{ url(Request::segment(1) . '/verifikasi-berkas') }}" method="POST"
                        enctype="multipart/form-data" class="row">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <input type="hidden" name="id_status" id="id_statusverifikasi" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Tgl Verifikasi</label>
                            <input class="form-control" id="tgl" name="tgl" type="date"
                                value="{{ isset($arsip->tgl) ? $arsip->tgl : date('Y-m-d') }}"
                                placeholder="Enter Your Email">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3">{{ $arsip->keterangan ?? old('keterangan') }}</textarea>
                        </div>
                        <div class="form-check-size rtl-input mb-3">
                            @foreach ($status as $key => $item)
                                <div class="form-check form-check-inline col-md-12">
                                    <input class="form-check-input me-2" id="inlineRadio1" type="radio" name="action"
                                        value="{{ $key }}" checked="" required>
                                    <label class="form-check-label" for="inlineRadio1">{{ $item }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" id="persetujuan" name="persetujuan" type="checkbox"
                                value="true" required>
                            <label class="form-check-label" for="checkbox-primary-1">Saya menyetujui verifikasi data
                                diatas</label>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> <i class="fa fa-check"></i>
                                Proses</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verifikasi-data-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="verifikasi-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Verifikasi Data dan Cek Arsip {{ $data->no_permohonan }}?</h4>
                    <form action="{{ url(Request::segment(1) . '/verifikasi-arsip') }}" method="POST"
                        enctype="multipart/form-data" class="row">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <input type="hidden" name="id_status" id="id_statusverifikasi" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Tgl Verifikasi</label>
                            <input class="form-control" id="tgl" name="tgl" type="date"
                                value="{{ isset($arsip->tgl) ? $arsip->tgl : date('Y-m-d') }}"
                                placeholder="Enter Your Email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">Alamat Persil</label>
                            <input class="form-control" id="alamat_persil" name="alamat_persil" type="text"
                                value="{{ $arsip->alamat_persil ?? old('alamat_persil') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">Nama Pemegang Ijin</label>
                            <input class="form-control" id="nama_pemegang_ijin" name="nama_pemegang_ijin"
                                type="text" value="{{ $arsip->nama_pemegang_ijin ?? old('nama_pemegang_ijin') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">No SK</label>
                            <input class="form-control" id="no_persil" name="no_persil" type="text"
                                value="{{ $arsip->no_persil ?? old('no_persil') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">Tgl SK Terakhir</label>
                            <input class="form-control" id="tanggal_ipt" name="tanggal_ipt" type="date"
                                value="{{ $arsip->tanggal_ipt ?? old('tanggal_ipt') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Arsip IPT</label>
                            <input class="form-control" id="file_ipt" name="file_ipt" type="file"
                                value="{{ $arsip->file ?? '' }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan Arsip</label>
                            @php
                                $text =
                                    "1. Hasil Pengecekan data : \nBahwa persil yang dimohonkan " .
                                    strtoupper($data->alamat_persil) .
                                    ' atas nama ' .
                                    strtoupper($data->nama_pemegang_ipt);
                                $text .= "\n\n2. Keterangan Ini diberikan untuk ...";
                            @endphp
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="5">{{ $arsip->keterangan ?? (old('keterangan') ?? $text) }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan History</label>
                            <textarea name="keterangan_history" id="keterangan_history" class="form-control" cols="30" rows="2">{{ old('keterangan_history') ?? '' }}</textarea>
                        </div>
                        <div class="form-check-size rtl-input mb-3">
                            @foreach ($status as $key => $item)
                                <div class="form-check form-check-inline col-md-12">
                                    <input class="form-check-input me-2" id="inlineRadio1" type="radio"
                                        name="action" value="{{ $key }}" checked="" required>
                                    <label class="form-check-label" for="inlineRadio1">{{ $item }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" id="persetujuan" name="persetujuan" type="checkbox"
                                value="true" required>
                            <label class="form-check-label" for="checkbox-primary-1">Saya menyetujui verifikasi data
                                diatas</label>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> <i class="fa fa-check"></i>
                                Proses</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verifikasi-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="verifikasi-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Verifikasi Data {{ $data->no_permohonan }}?</h4>
                    <form action="{{ url(Request::segment(1) . '/do-verifikasi') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <input type="hidden" name="id_status" id="id_statusverifikasi" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Tgl Verifikasi</label>
                            <input class="form-control" id="tgl" name="tgl" type="date"
                                value="{{ date('Y-m-d') }}" placeholder="Enter Your Email">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3"></textarea>
                        </div>
                        <div class="form-check-size rtl-input mb-3">
                            @foreach ($status as $key => $item)
                                <div class="form-check form-check-inline col-md-12">
                                    <input class="form-check-input me-2" id="inlineRadio1" type="radio"
                                        name="action" value="{{ $key }}" checked="" required>
                                    <label class="form-check-label" for="inlineRadio1">{{ $item }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" id="persetujuan" name="persetujuan" type="checkbox"
                                value="true" required>
                            <label class="form-check-label" for="checkbox-primary-1">Saya menyetujui verifikasi data
                                diatas</label>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> <i class="fa fa-check"></i>
                                Proses</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upload-bap-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="bap-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Upload BAP Untuk Data {{ $data->no_permohonan }}?</h4>
                    <form action="{{ url(Request::segment(1) . '/upload-bap') }}" method="POST"
                        enctype="multipart/form-data" class="row">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <input type="hidden" name="id_status" id="id_statusupload-bap" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Tgl Upload</label>
                            <input class="form-control" id="tgl" name="tgl" type="date"
                                value="{{ date('Y-m-d') }}" placeholder="Tanggal Hari ini">
                        </div>
                        <div class="col-md-12 mb-3">
                            <select class="form-control" name="type" id="type" required>
                                <option value="">-- Pilih Jenis Pengurangan --</option>
                                @foreach ($type as $item)
                                    <option value="{{ $item }}" {{ $item == $data->type ? 'selected' : '' }}>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">File BAP (Yang sudah ditanda tangani)</label>
                            <input class="form-control" id="file_bap" name="file_bap" type="file"
                                value="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">Peruntukan</label>
                            <input class="form-control" id="peruntukan" name="peruntukan" type="text"
                                value="{{ $bap->peruntukan ?? old('peruntukan') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">Penggunaan</label>
                            <input class="form-control" id="penggunaan" name="penggunaan" type="text"
                                value="{{ $bap->penggunaan ?? old('penggunaan') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">No. SK</label>
                            <input class="form-control" id="no_ipt" name="no_ipt" type="text"
                                value="{{ $data->no_sk ?? old('no_ipt') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">Tanggal SK</label>
                            <input class="form-control" id="tanggal_ipt" name="tanggal_ipt" type="date"
                                value="{{ $arsip->tanggal_ipt ?? old('tanggal_ipt') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="tgl">Luas</label>
                            <input class="form-control" id="luas" name="luas" type="text"
                                value="{{ $bap->luas ?? old('luas') }}">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3">{{ $bap->keterangan ?? old('keterangan') }}</textarea>
                        </div>
                        <div class="form-check-size rtl-input mb-3">
                            @foreach ($status as $key => $item)
                                <div class="form-check form-check-inline col-md-12">
                                    <input class="form-check-input me-2" id="inlineRadio1" type="radio"
                                        name="action" value="{{ $key }}" checked="" required>
                                    <label class="form-check-label" for="inlineRadio1">{{ $item }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" id="persetujuan" name="persetujuan" type="checkbox"
                                value="true" required>
                            <label class="form-check-label" for="checkbox-primary-1">Saya menyetujui verifikasi data
                                diatas</label>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> <i class="fa fa-check"></i>
                                Proses</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upload-bap-status-tanah-modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="bap-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Upload BAP Untuk Data {{ $data->no_permohonan }}?</h4>
                    <form action="{{ url(Request::segment(1) . '/upload-bap') }}" method="POST"
                        enctype="multipart/form-data" class="row">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <input type="hidden" name="id_status" id="id_statusupload-bap" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Tgl Upload</label>
                            <input class="form-control" id="tgl" name="tgl" type="date"
                                value="{{ date('Y-m-d') }}" placeholder="Tanggal Hari ini">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">File BAP (Yang sudah ditanda tangani)</label>
                            <input class="form-control" id="file_bap" name="file_bap" type="file"
                                value="">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3">{{ $bap->keterangan ?? old('keterangan') }}</textarea>
                        </div>
                        <div class="form-check-size rtl-input mb-3">
                            @foreach ($status as $key => $item)
                                <div class="form-check form-check-inline col-md-12">
                                    <input class="form-check-input me-2" id="inlineRadio1" type="radio"
                                        name="action" value="{{ $key }}" checked="" required>
                                    <label class="form-check-label" for="inlineRadio1">{{ $item }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" id="persetujuan" name="persetujuan" type="checkbox"
                                value="true" required>
                            <label class="form-check-label" for="checkbox-primary-1">Saya menyetujui verifikasi data
                                diatas</label>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> <i class="fa fa-check"></i>
                                Proses</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="upload-surat-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="bap-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Upload Surat {{ $data->no_permohonan }}?</h4>
                    <form action="{{ url(Request::segment(1) . '/upload-surat') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <input type="hidden" name="id_status" id="id_statusupload-surat" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Tgl Upload</label>
                            <input class="form-control" id="tgl" name="tgl" type="date"
                                value="{{ date('Y-m-d') }}" placeholder="Tanggal Hari ini">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Nama Pengupload</label>
                            <input class="form-control" id="nama_verifikator" name="nama_verifikator" type="text"
                                value="{{ Auth::user()->nm_user }}" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Surat</label>
                            <input class="form-control" id="file_surat" name="file_surat" type="file"
                                value="">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3"></textarea>
                        </div>
                        <div class="form-check-size rtl-input mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input me-2" id="inlineRadio1" type="radio" name="action"
                                    value="verifikasi" checked="">
                                <label class="form-check-label" for="inlineRadio1">Verifikasi & Menuju Ke Step
                                    Selanjutnya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input me-2" id="inlineRadio2" type="radio" name="action"
                                    value="reject">
                                <label class="form-check-label" for="inlineRadio2">Reject</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" id="persetujuan" name="persetujuan" type="checkbox"
                                value="true" required>
                            <label class="form-check-label" for="checkbox-primary-1">Saya menyetujui verifikasi data
                                diatas</label>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> <i class="fa fa-check"></i>
                                Proses</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verifikasi-surat-modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="bap-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Verifikasi Surat {{ $data->no_permohonan }}?</h4>
                    <form action="{{ url(Request::segment(1) . '/verifikasi-surat') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <input type="hidden" name="id_status" id="id_statusverifikasi-surat" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Tgl Upload</label>
                            <input class="form-control" id="tgl" name="tgl" type="date"
                                value="{{ date('Y-m-d') }}" placeholder="Tanggal Hari ini">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3"></textarea>
                        </div>
                        <div class="form-check-size rtl-input mb-3">
                            @foreach ($status as $key => $item)
                                <div class="form-check form-check-inline col-md-12">
                                    <input class="form-check-input me-2" id="inlineRadio1" type="radio"
                                        name="action" value="{{ $key }}" checked="" required>
                                    <label class="form-check-label" for="inlineRadio1">{{ $item }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" id="persetujuan" name="persetujuan" type="checkbox"
                                value="true" required>
                            <label class="form-check-label" for="checkbox-primary-1">Saya menyetujui verifikasi data
                                diatas</label>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> <i class="fa fa-check"></i>
                                Proses</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="verifikasi-kaban-modal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="bap-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Verifikasi Surat {{ $data->no_permohonan }}?</h4>
                    <form action="{{ url(Request::segment(1) . '/verifikasi-kaban') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <input type="hidden" name="id_status" id="id_statusverifikasi-surat" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Tgl Verifikasi</label>
                            <input class="form-control" id="tgl" name="tgl" type="date"
                                value="{{ date('Y-m-d') }}" placeholder="Tanggal Hari ini" readonly>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3"></textarea>
                        </div>
                        <div class="form-check-size rtl-input mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input me-2" id="inlineRadio1" type="radio" name="action"
                                    value="verifikasi" checked="">
                                <label class="form-check-label" for="inlineRadio1">Verifikasi & Menuju Ke Step
                                    Selanjutnya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input me-2" id="inlineRadio2" type="radio" name="action"
                                    value="reject">
                                <label class="form-check-label" for="inlineRadio2">Reject</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" id="persetujuan" name="persetujuan" type="checkbox"
                                value="true" required>
                            <label class="form-check-label" for="checkbox-primary-1">Saya menyetujui verifikasi data
                                diatas</label>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> <i class="fa fa-check"></i>
                                Proses</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="finish-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="bap-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Menyelesaikan Permohonan {{ $data->no_permohonan }} ?</h4>
                    <form action="{{ url(Request::segment(1) . '/selesaikan-proses') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <input type="hidden" name="id_status" id="id_statusverifikasi-surat" value="">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="tgl">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3"></textarea>
                        </div>
                        <div class="form-check-size rtl-input mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input me-2" id="inlineRadio1" type="radio" name="action"
                                    value="verifikasi" checked="">
                                <label class="form-check-label" for="inlineRadio1">Verifikasi & Menuju Ke Step
                                    Selanjutnya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input me-2" id="inlineRadio2" type="radio" name="action"
                                    value="reject">
                                <label class="form-check-label" for="inlineRadio2">Reject</label>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <input class="form-check-input" id="persetujuan" name="persetujuan" type="checkbox"
                                value="true" required>
                            <label class="form-check-label" for="checkbox-primary-1">Saya menyetujui verifikasi data
                                diatas</label>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit" name="action" value="verifikasi"> <i
                                    class="fa fa-check"></i> Selesaikan</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
