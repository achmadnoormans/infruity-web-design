<div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper">
            <a href="{{ url('/') }}">
                <img class="img-fluid for-light" src="{{ asset('cuba/images/logo/logo.png') }}" alt="">
                <img class="img-fluid for-dark" src="{{ asset('cuba/images/logo/logo.png') }}" alt="">
            </a>
            <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="{{ url('/') }}">
                <img class="img-fluid" src="" alt="">
            </a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn">
                        <a href="{{ url('/') }}">
                            <img class="img-fluid" src="{{ asset('cuba/images/logo/logo.png') }}" alt="">
                        </a>
                        <div class="mobile-back text-end"><span>Back</span>
                            <i class="fa-solid fa-angle-right ps-2" aria-hidden="true"></i>
                        </div>
                    </li>
                    <li class="pin-title sidebar-main-title">
                        <div>
                            <h6>Pinned</h6>
                        </div>
                    </li>
                    @if (check_access('show-dashboard'))
                        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title link-nav" href="{{ url('/dashboard') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-landing-page') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-landing-page') }}"></use>
                                </svg>
                                <span class="lan-menu-dasboard">Dashboard</span>
                            </a>
                        </li>
                    @endif
                    @if (check_access('show-list-permohonan'))
                        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title link-nav" href="{{ url('/list-permohonan') }}">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-task') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-task') }}"></use>
                                </svg>
                                <span class="lan-menu-dasboard">Permohonan Saya</span>
                            </a>
                        </li>
                    @endif
                    @if (check_access('show-monitoring'))
                        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-learning') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-learning') }}"></use>
                                </svg><span class="lan-master">Monitoring</span></a>
                            <ul class="sidebar-submenu">
                                <li><a href="{{ url('permohonan-selesai') }}">Berkas Selesai <label class="badge badge-light-success"><i class="fa-regular fa-circle-check"></i></label></a></li>
                                <li><a href="{{ url('permohonan-proses') }}">Berkas Pemohon</a></li>
                                <li><a href="{{ url('permohonan-ditolak') }}">Berkas Ditolak <label class="badge badge-light-danger"><i class="icofont icofont-close-circled"></i></label></a></li>
                            </ul>
                        </li>
                    @endif
                    @if (check_access('show-master'))
                        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-landing-page') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-landing-page') }}"></use>
                                </svg><span class="lan-master">Master</span></a>
                            <ul class="sidebar-submenu">
                                @if (in_array(Auth::user()->id_user, [1]))
                                    <li><a href="{{ url('user') }}">User</a></li>
                                @endif
                                <li><a href="{{ url('layanan') }}">Layanan</a></li>
                                <li><a href="{{ url('layanan-form') }}">Layanan Form</a></li>
                                <li><a href="{{ url('layanan-dokumen') }}">Layanan Document</a></li>
                                <li><a href="{{ url('status-dokumen') }}">Status Document</a></li>
                            </ul>
                        </li>
                    @endif
                    @if (check_access('show-setting'))
                        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-landing-page') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-landing-page') }}"></use>
                                </svg><span class="lan-master">Setting Menu</span></a>
                            <ul class="sidebar-submenu">
                                <li><a href="{{ url('role-menu') }}">Role Menu</a></li>
                            </ul>
                        </li>
                    @endif
                    @if (check_access('show-permohonan') && Session('role')['id_role'] != 99)
                        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-widget') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-widget') }}"></use>
                                </svg><span class="lan-">Surat Keterangan</span></a>
                            <ul class="sidebar-submenu">
                                @if (check_access('show-permohonan'))
                                    <li><a href="{{ url('permohonan') }}">List Permohonan</a></li>
                                @endif
                                @if (check_access('do-verifikasi') && check_access('show-permohonan-submit'))
                                    <li><a href="{{ url('permohonan-submit') }}">Validasi Dokumen</a></li>
                                @endif
                                @if (check_access('upload-bap') && check_access('show-permohonan-bap'))
                                    <li><a href="{{ url('permohonan-bap') }}"> Proses BAP</a></li>
                                @endif
                                @if (check_access('create-surat') && check_access('show-permohonan-konsep-surat'))
                                    <li><a href="{{ url('permohonan-konsep-surat') }}"> Pembuatan Konsep Surat</a>
                                    </li>
                                @endif
                                @if (check_access('show-penyelia-surat'))
                                    <li><a href="{{ url('penyelia-surat') }}"> Penyelia Surat</a>
                                    </li>
                                @endif
                                @if (check_access('verifikasi-surat') && check_access('show-permohonan-verifikasi-ketua'))
                                    <li><a href="{{ url('permohonan-verifikasi-ketua') }}"> Verifikasi Ketua</a></li>
                                @endif
                                @if (check_access('verifikasi-kabid') && check_access('show-permohonan-verifikasi-kabid'))
                                    <li><a href="{{ url('permohonan-verifikasi-kabid') }}"> Verifikasi Kabid</a></li>
                                @endif
                                @if (check_access('verifikasi-sekretaris') && check_access('show-permohonan-verifikasi-sekretaris'))
                                    <li><a href="{{ url('permohonan-verifikasi-sekretaris') }}"> Verifikasi
                                            Sekretaris</a></li>
                                @endif
                                @if (check_access('verifikasi-kaban') && check_access('show-permohonan-verifikasi-kaban'))
                                    <li><a href="{{ url('permohonan-verifikasi-kaban') }}"> Verifikasi KA BPKAD</a>
                                    </li>
                                @endif
                                @if (check_access('verifikasi') && check_access('show-permohonan-proses-selesai'))
                                    <li><a href="{{ url('permohonan-proses-selesai') }}"> Penyelesaian Permohonan</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (check_access('show-ipt-pengurangan') && in_array(Auth::user()->bidang, ['P3BMD', 'SEKRETARIAT']))
                        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#c-invoice') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#c-invoice') }}"></use>
                                </svg><span class="lan-">IPT Pengurangan</span></a>
                            <ul class="sidebar-submenu">
                                @if (check_access('show-permohonan'))
                                    <li><a href="{{ url('ipt-pengurangan') }}">List Permohonan</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (check_access('show-surat'))
                        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-email-temp') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-email-temp') }}"></use>
                                </svg><span class="lan-">Agenda Surat</span></a>
                            <ul class="sidebar-submenu">
                                @if (check_access('show-surat'))
                                    <li><a href="{{ url('surat') }}">List Surat Keterangan</a></li>
                                    @if (in_array(Auth::user()->bidang, ['P3BMD', 'SEKRETARIAT']))
                                        <li><a href="{{ url('surat-keterangan') }}">List Surat Pengurangan</a></li>
                                    @endif
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (in_array(Auth::user()->bidang, ['P2BMD', 'SEKRETARIAT']))
                        <li class="sidebar-list"><i class="fa-solid fa-thumbtack"></i>
                            <a class="sidebar-link sidebar-title" href="#">
                                <svg class="stroke-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-file') }}"></use>
                                </svg>
                                <svg class="fill-icon">
                                    <use href="{{ asset('cuba/svg/icon-sprite.svg#stroke-file') }}"></use>
                                </svg><span class="lan-">Arsip Berkas</span></a>
                            <ul class="sidebar-submenu">
                                <li><a href="{{ url('arsip') }}">Semua Arsip</a></li>
                                <li><a href="{{ url('arsip-2025') }}">2025</a></li>
                                <li><a href="{{ url('arsip-2024') }}">2024</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
