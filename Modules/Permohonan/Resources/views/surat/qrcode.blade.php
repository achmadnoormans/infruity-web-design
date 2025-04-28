<!DOCTYPE html>
<html lang="id">

<head>
    <script src="https://cdn.jsdelivr.net/npm/easyqrcodejs@4.4.10/dist/easy.qrcode.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div id="qrcode"></div>

    <!-- Form yang akan dikirim otomatis -->
    <form id="qrcodeForm" method="POST" action="{{ route('qrcode.store') }}">
        @csrf
        <input type="hidden" name="qrcode" id="qrcodeInput">
        <input type="hidden" name="id_surat" value="{{ $surat->id }}">
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                text: "{{ url('/surat') . '/' . my_encrypt($surat->id) }}",
                width: 200,
                height: 200,
                logo: "{{ asset('cuba/images/logo/logo-surabaya.png') }}", // Logo
                logoWidth: 40,
                logoHeight: 40,
                quietZone: 10,
                onRenderingEnd: function(qrCode) {
                    setTimeout(() => {
                        let canvas = document.querySelector("#qrcode canvas");
                        if (canvas) {
                            let qrBase64 = canvas.toDataURL("image/png"); // Convert ke base64
                            document.getElementById("qrcodeInput").value = qrBase64;
                            document.getElementById("qrcodeForm").submit(); // Kirim form otomatis
                        } else {
                            console.error("QR Code gagal dibuat.");
                        }
                    }, 5);
                }
            };

            new QRCode(document.getElementById("qrcode"), options);
        });
    </script>
</body>

</html>
