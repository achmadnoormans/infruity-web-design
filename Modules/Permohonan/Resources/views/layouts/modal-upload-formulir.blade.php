<div class="modal fade" id="modal-upload-formulir" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="bap-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Upload <span id="nama_form"></span> {{ $data->no_permohonan }}?</h4>
                    <form action="{{ url(Request::segment(1) . '/upload-file') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="formulir"><span id="nama_title"></span></label>
                            <br>
                            <span class="text-danger">(Pastikan
                                Dokumen yang diupload sudah benar, dan Maksimal file 5MB)</span>
                            <input class="form-control" id="formulir" name="formulir" type="file" value=""
                                required>
                        </div>
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit" name="action" value="verifikasi"> <i
                                    class="fa fa-check"></i> Submit</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-submit-ulang" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="bap-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Submit Ulang Document? <span id="nama_form"></span>
                        {{ $data->no_permohonan }}?</h4>
                    <form action="{{ url(Request::segment(1) . '/submit-ulang') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit" name="action" value="verifikasi"> <i
                                    class="fa fa-check"></i> Submit</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal-submit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="bap-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-toggle-wrapper">
                    <h4 class="text-center pb-2">Anda sudah yakin untuk mengirim berkas? <span id="nama_form"></span>
                    </h4>
                    <p class="text-danger">Untuk menghindari kesalahan, Pastikan bahwa Data yang Anda kirim sudah benar
                    </p>
                    <form action="{{ url(Request::segment(1) . '/' . $data->id . '/submit-data') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id_permohonan" value="{{ $data->id }}">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-primary" type="submit"> Ya</button>
                            <button class="btn btn-warning" type="button" data-bs-dismiss="modal">Tidak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
    <script>
        function upload_formulir(namaDocument, namaFile) {
            console.log(namaDocument, namaFile);
            let inputElement = document.getElementById("formulir");
            $('#nama_form').text(namaDocument);
            // $('#nama_title').text(namaDocument);
            inputElement.setAttribute("name", namaFile);
            $('#modal-upload-formulir').modal('show');
        }

        function submit_ulang() {
            $('#modal-submit-ulang').modal('show');
        }

        function submit() {
            $('#modal-submit').modal('show');
        };
    </script>
@endsection
