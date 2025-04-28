@if (isset($surat))
    <br><br>
    <div class="d-flex justify-content-between">
        <span>
            <h3>Konsep Surat</h3>
            @if ($data->id_status < 4)
                <h6 class="text-danger">(Surat Ditolak)</h6>
            @endif
        </span>
        @if ($data->id_layanan == 7)
            <a href="{{ route('show-document', [
                'pdf' => $surat->file,
            ]) }}" target="_blank"
                class="btn btn-primary">
                <i class="fa-solid fa-print"></i>
                Preview Surat </a>
        @else
            <a class="btn btn-primary" target="_blank" href="{{ url('surat/' . $surat->id . '/cetak-surat') }}"><i
                    class="fa-solid fa-print"></i>
                Preview Surat </a>
        @endif
    </div>
    <hr>
    @if ($data->id_layanan == 7)
        <iframe src="{{ route('show-document', [
            'pdf' => $surat->file,
        ]) }}"
            width="100%" height="600px" frameborder="0"></iframe>
    @else
        <iframe src="{{ url('surat/' . $surat->id . '/cetak-surat') }}" width="100%" height="600px"
            frameborder="0"></iframe>
    @endif
@endif
