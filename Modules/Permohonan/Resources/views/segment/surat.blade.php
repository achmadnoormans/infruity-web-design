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
    @switch($data->id_layanan)
        @case(1)
            <p style="text-align: justify">
                <span style="margin-left: 50px">Sehubungan</span> dengan surat saudara tanggal
                {{ isset($data->tanggal_pengajuan) ? dateindo($data->tanggal_pengajuan) : '-' }}
                perihal permohonan
                Fotocopy Surat Izin Pemakaian Tanah yang terletak di <b>{{ $surat->alamat_persil ?? '' }}</b> maka
                dengan
                ini dapat disampaikan sebagai berikut :
            </p>
            <div style="text-align: justify">
                {!! $surat->isi !!}
            </div>
            <p style="text-align: justify">
                2. Surat keterangan ini dipergunakan untuk keperluan pengurusan surat kehilangan
                kepolisian.
            </p>
            <p>
                3. Apabila dikemudian hari ada sengketa / tuntutan dari pihak lain maka sepenuhnya
                menjadi tanggung jawab pemohon.
            </p>
            <p>
                4. Apabila surat keterangan ini terdapat kekeliruan akan dilakukan perbaikan sesuai
                ketentuan yang berlaku.
            </p>
            <p>
                Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.
            </p>
        @break

        @case(1 - 2)
            <p style="text-align: justify">
                <span style="margin-left: 50px">Berdasarkan</span> surat keterangan tanda lapor kehilangan dari
                Polrestabes
                Surabaya
                No.{{ $data->nomor_kehilangan_dari_kepolisian ?? '--' }} tanggal
                {{ isset($data->tanggal_pengajuan) ? dateindo($data->tanggal_pengajuan) : '______' }} telah hilang sebuah
                buku Surat Izin Pemakaian Tanah (IPT) No. {{ $surat->no_persil ?? '______' }} tanggal
                {{ isset($surat->tgl_ipt) ? dateindo($surat->tgl_ipt) : '______' }} dengan
                letak
                persil di
                {{ isset($surat->alamat_persil) && $surat->alamat_persil != '' ? $surat->alamat_persil : '_________' }} atas
                nama
                {{ isset($surat->nama_pemegang_ipt) && $data->nama_pemegang_ipt != '' ? $data->nama_pemegang_ipt : '_________' }}.
                Saat ini
                Sdr. {{ $data->nama_pemohon ?? '--' }}
                akan mengajukan permohonan Izin Pemakaian Tanah di lokasi dimaksud ke Badan Pengelolaan Keuangan dan
                Aset
                Daerah.
            </p>
        @break

        @case(2)
            <p style="text-align: justify">
                <span style="margin-left: 50px">Sehubungan</span> dengan surat permohonan balik nama Izin Pemakaian Tanah
                Nomor :
                {{ $surat->no_persil ?? '_____' }} tanggal
                {{ isset($surat->tgl_ipt) ? dateindo($surat->tgl_ipt) : '_____' }} yang
                terletak
                di <b>{{ $surat->alamat_persil ?? '___' }}</b> dari <b>Sdr.
                    {{ ucwords(strtolower($data->nama_pemohon ?? '___')) }}</b>
                tanggal {{ isset($data->tanggal_pengajuan) ? dateindo($data->tanggal_pengajuan) : '_____' }}, maka Badan
                Pengelolaan Keuangan dan Aset Daerah Kota Surabaya akan menerbitkan Izin
                Pemakaian Tanah kepada Sdr. {{ ucwords(strtolower($data->nama_pemohon ?? '')) }} dengan letak persil tanah
                {{ $surat->alamat_persil ?? '' }} mendasarkan pada dokumen sebagai berikut :
            </p>
            <div style="text-align: justify;">
                {!! $surat->isi !!}
            </div>
            <p style="text-align: justify;">
                <span style="margin-left: 50px">Terhadap</span> permohonan penerbitan surat Izin Pemakaian Tanah di persil
                dimaksud atas nama <b>{{ $surat->list_nama }}</b> maka apabila terdapat pihak-pihak yang
                keberatan
                terhadap pengajuan permohonan, agar mengajukan keberatan ke Badan Pengelolaan Keuangan dan Aset Daerah Kota
                Surabaya paling lambat 30 (tiga puluh) hari terhitung sejak tanggal pengumuman ini diterbitkan.
            </p>
            <p> <span style="margin-left: 50px">Demikian </span> atas perhatiannya disampaikan terima kasih.</p>
        @break

        @case(3)
            <p style="text-align: justify">
                <span style="margin-left: 50px">Sehubungan</span> dengan surat permohonan balik nama Izin Pemakaian Tanah
                Nomor :
                {{ $surat->no_persil ?? '_____' }} tanggal
                {{ isset($surat->tgl_ipt) ? dateindo($surat->tgl_ipt) : '_____' }} yang
                terletak
                di <b>{{ $surat->alamat_persil ?? '___' }}</b> dari <b>Sdr.
                    {{ ucwords(strtolower($data->nama_pemohon ?? '___')) }}</b>
                tanggal {{ isset($data->tanggal_pengajuan) ? dateindo($data->tanggal_pengajuan) : '_____' }}, maka Badan
                Pengelolaan Keuangan dan Aset Daerah Kota Surabaya akan menerbitkan Izin
                Pemakaian Tanah kepada Sdr. {{ ucwords(strtolower($data->nama_pemohon ?? '')) }} dengan letak persil tanah
                {{ $surat->alamat_persil ?? '' }} mendasarkan pada dokumen sebagai berikut :
            </p>
            <div style="text-align: justify;">
                {!! $surat->isi !!}
            </div>
            <p style="text-align: justify;">
                <span style="margin-left: 50px">Terhadap</span> permohonan penerbitan surat Izin Pemakaian Tanah di persil
                dimaksud atas nama <b>{{ $surat->list_nama }}</b> maka apabila terdapat pihak-pihak yang
                keberatan
                terhadap pengajuan permohonan, agar mengajukan keberatan ke Badan Pengelolaan Keuangan dan Aset Daerah Kota
                Surabaya paling lambat 30 (tiga puluh) hari terhitung sejak tanggal pengumuman ini diterbitkan.
            </p>
            <p> <span style="margin-left: 50px">Demikian </span> atas perhatiannya disampaikan terima kasih.</p>
        @break

        @default
            <div style="text-align: justify">
                {!! $surat->isi !!}
            </div>
    @endswitch
@endif
