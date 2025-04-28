<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Permohonan IPT, Badan Pengelola Keuangan dan Aset Daerah Pemerintah Kota Surabaya">
    <meta name="keywords" content="Permohonan IPT, Badan Pengelola Keuangan dan Aset Daerah Pemerintah Kota Surabaya">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('cuba/images/logo/logo.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('cuba/images/logo/logo.png') }}" type="image/x-icon">
    <title>Daftar | Aplikasi Surat Keterangan | BPKAD - Pemerintah Kota Surabaya</title>
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
    <style>
        .valid {
            color: green;
            opacity: 0.8;
        }

        .invalid {
            color: red;
            opacity: 0.8;
        }

        .valid::before {
            content: '✔ ';
        }

        .invalid::before {
            content: '✘ ';
        }

        .box {
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- login page start-->
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
                            <form class="theme-form" method="POST" action="{{ url('register') }}">
                                @csrf
                                <h4>Buat Akun</h4>
                                <p>Harap Isi data berikut</p>
                                <div class="form-group">
                                    <label class="col-form-label pt-0">Nama Lengkap</label>
                                    <div class="row g-2">
                                        <input class="form-control" type="text" id="full_name" name="full_name"
                                            required placeholder="Nama Lengkap" name="full_name"
                                            value="{{ old('full_name') ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Email</label>
                                    <input class="form-control @error('email') is-invalid @enderror" type="email"
                                        name="email" placeholder="test@gmail.com" value="{{ old('email') ?? '' }}">
                                    @error('email')
                                        <span class="text-danger">Email Tidak Valid</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Password</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control" type="password" name="password" required=""
                                            id="password" value="{{ old('password') ?? '' }}" placeholder=""
                                            oninput="validatePassword()">
                                        <div class="show-hide"><span class="show"></span></div>
                                        @error('password')
                                            <span class="text-danger">Password Tidak Valid</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label">Confirmation Password</label>
                                    <div class="form-input position-relative">
                                        <input class="form-control" type="password" name="password" required=""
                                            id="confirmPassword" value="{{ old('password') ?? '' }}" placeholder=""
                                            oninput="checkPassword()">
                                        <span id="error-message"></span>
                                        @error('password')
                                            <span class="text-danger">Password Tidak Valid</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="box">
                                    <ul>
                                        <li id="length" class="invalid">Minimal 12 karakter</li>
                                        <li id="uppercase" class="invalid">Minimal satu huruf besar</li>
                                        <li id="number" class="invalid">Minimal satu angka</li>
                                        <li id="special" class="invalid">Minimal satu karakter spesial
                                            (!@#$%^&*()_+-)</li>
                                    </ul>
                                </div>
                                <br>
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
                                    {{-- <div class="checkbox p-0">
                                        <input id="checkbox1" type="checkbox">
                                        <label class="text-muted" for="checkbox1">Setuju<a class="ms-2"
                                                href="#">Kebijakan Privasi</a></label>
                                    </div> --}}
                                    <button class="btn btn-primary btn-block w-100" type="submit"
                                        id="submit-button">Daftar
                                        Akun</button>
                                </div>
                                <p class="mt-4 mb-0">Sudah Punya akun?<a class="ms-2"
                                        href="{{ url('auth/login') }}">Login</a>
                                </p>
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

            function validatePassword() {
                const password = document.getElementById('password').value;

                document.getElementById('length').className = password.length >= 12 ? 'valid' : 'invalid';
                document.getElementById('uppercase').className = /[A-Z]/.test(password) ? 'valid' : 'invalid';
                document.getElementById('number').className = /[0-9]/.test(password) ? 'valid' : 'invalid';
                document.getElementById('special').className = /[!@#$%^&*()_+\-]/.test(password) ? 'valid' : 'invalid';
            }

            function checkPassword() {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('confirmPassword').value;
                const errorMessage = document.getElementById('error-message');
                const submitButton = document.getElementById('submit-button');

                if (password !== confirmPassword) {
                    errorMessage.textContent = 'Password tidak sama!';
                    errorMessage.style.color = 'red';
                    submitButton.disabled = true;
                    return false;
                }
                errorMessage.textContent = '';
                submitButton.disabled = false;
                return true;
            }
        </script>
    </div>
</body>

</html>
