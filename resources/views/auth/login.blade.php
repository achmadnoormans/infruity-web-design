<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Permohonan IPT Badan Pengelola Keuangan dan Aset Daerah Pemerintah Kota Surabaya">
    <meta name="keywords" content="Permohonan IPT Badan Pengelola Keuangan dan Aset Daerah Pemerintah Kota Surabaya">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('cuba/images/logo/logo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('cuba/images/logo/logo.png') }}" type="image/x-icon">
    <title>Login | Aplikasi Surat Keterangan | BPKAD - Pemerintah Kota Surabaya</title>
    <link href="https://fonts.googleapis.com/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('cuba/css/fontawesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('cuba/css/vendors/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('cuba/css/vendors/themify.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('cuba/css/vendors/flag-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('cuba/css/vendors/feather-icon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('cuba/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('cuba/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('cuba/css/color-1.css') }}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('cuba/css/responsive.css') }}">
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row m-0">
            <div class="col-12 p-0">
                <div class="login-card login-dark">
                    <div>
                        <div>
                            <a class="logo" href="index.html">
                                <img class="img-fluid for-light" src="{{ asset('cuba/images/logo/logo.png') }}"
                                    alt="looginpage">
                                <img class="img-fluid for-dark" src="{{ asset('cuba/images/logo/logo.png') }}"
                                    alt="looginpage"></a>
                        </div>
                        <div>
                            @include('template.notif')
                        </div>
                        <div class="login-main">
                            <form class="theme-form" method="POST" action="{{ url('auth/login') }}">
                                @csrf
                                <h4>Login ke Akun</h4>
                                <p>Masukkan email dan password anda untuk masuk ke dalam aplikasi</p>
                                <div class="form-group">
                                    <label class="col-form-label">User</label>
                                    <input class="form-control" type="text" name="email" required=""
                                        placeholder="Masukkan email atau username" value="{{ old('email') }}">
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control" type="password" name="password" required=""
                                            placeholder="Masukkan password" value="{{ old('password') }}">
                                        <div class="show-hide"><span class="show"> </span></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="captcha" class="form-label">Captcha:</label>
                                    <div class="mb-3">
                                        <img src="{{ captcha_src('default') }}" alt="captcha" id="captcha_image">
                                        <button type="button" onclick="refreshCaptcha()" class="btn btn-primary"><i
                                                class="fa-solid fa-arrows-rotate"></i></button>
                                    </div>
                                    <input type="text" name="captcha"
                                        class="form-control @error('captcha') is-invalid @enderror" required>
                                    @error('captcha')
                                        <span class="text-danger">Captcha Tidak Valid</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-0">
                                    <div class="checkbox p-0">
                                        <a class="ms-2 mb-0 text-center" href="{{ url('forgot-password') }}">Lupa
                                            Password?</a>
                                    </div><a class="link" href="{{ url('/') }}"></a>
                                    <div class="text-end mt-3">
                                        <button class="btn btn-primary btn-block w-100" type="submit">Login</button>
                                    </div>
                                </div>
                                <p class="mt-4 mb-0 text-center">Belum Punya Akun?<a class="ms-2"
                                        href="{{ url('register') }}">Daftar Akun</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('cuba/js/jquery.min.js') }}"></script>
        <script src="{{ asset('cuba/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('cuba/js/icons/feather-icon/feather.min.js') }}"></script>
        <script src="{{ asset('cuba/js/icons/feather-icon/feather-icon.js') }}"></script>
        <script src="{{ asset('cuba/js/config.js') }}"></script>
        <script src="{{ asset('cuba/js/script.js') }}"></script>
        <script src="{{ asset('cuba/js/script1.js') }}"></script>
        <script src="{{ asset('cuba/js/custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                refreshCaptcha();
            });

            function refreshCaptcha() {
                document.getElementById('captcha_image').src = "{{ captcha_src('default') }}" + "?" + Math.random();
            }
        </script>
    </div>
</body>

</html>
