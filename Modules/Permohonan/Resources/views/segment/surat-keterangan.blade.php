@if (isset($surat))
    <br><br>
    <div class="d-flex justify-content-between">
        <span>
            <h3>Konsep Surat</h3>
            @if ($data->id_status < 3)
                <h6 class="text-danger">(Surat Ditolak)</h6>
            @endif
        </span>
    </div>
    <hr>
    <iframe src="{{ url('surat-keterangan/' . $surat->id . '/cetak-surat') }}" width="100%" height="600px" frameborder="0"></iframe>
@endif
