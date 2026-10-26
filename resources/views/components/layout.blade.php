<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SIPMA | Sistem Pendataan Pelanggaran Mahasiswa' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/sipma-emblem.png') }}?v=20261024-4">
    
    <!-- FontAwesome & Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-app.css') }}" rel="stylesheet">

    <!-- Chart.js for Dashboards (Local Bundle, Fast & Offline-Safe) -->
    <script src="{{ asset('vendor/chart.js/chart.umd.js') }}"></script>
    <script>
        // Setel animasi segera setelah Chart.js dimuat, sebelum script dashboard membuat grafik.
        if (window.Chart && Chart.defaults) {
            Chart.defaults.animation.duration = 1700;
            Chart.defaults.animation.easing = 'easeOutBounce';
        }
    </script>

    <style>
        :root {
            --sipma-teal: #0d9488;
            --sipma-teal-dark: #0f766e;
            --sipma-teal-light: #14b8a6;
            --sipma-sidebar-bg: #134e4a;
            --sipma-sidebar-hover: rgba(255,255,255,0.08);
            --sipma-sidebar-active: #0d9488;
        }

        /* ── Layout: fixed sidebar, scrollable content ── */
        html, body { margin: 0; padding: 0; height: 100%; }
        body { overflow: hidden; }

        #wrapper {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        /* ── Sidebar ── */
        #accordionSidebar {
            width: 260px;
            min-width: 260px;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            background-color: #134e4a !important;
            background-image: none !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            scrollbar-width: none;
        }
        #accordionSidebar::-webkit-scrollbar { display: none; }
        #accordionSidebar .sidebar-spacer { flex: 1 1 auto; min-height: 16px; }
        #accordionSidebar .sidebar-user-panel {
            margin: 12px 8px 14px;
            padding: 10px 8px;
            border-radius: 9px;
            background: rgba(255,255,255,0.06);
            color: #fff;
            width: calc(100% - 16px);
            box-sizing: border-box;
        }
        #accordionSidebar .sidebar-user-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        #accordionSidebar .sidebar-logout { color: #fecaca !important; }
        #accordionSidebar .sidebar-user-email { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        #accordionSidebar .sidebar-user-avatar { width: 30px; height: 30px; border-radius: 50%; background: #0d9488; color: #fff; font-size: 0.8rem; font-weight: 700; }
        #accordionSidebar .sidebar-logout { min-height: 32px; border: 1px solid rgba(248,113,113,0.35); border-radius: 7px; justify-content: center !important; font-size: 0.78rem !important; }
        #accordionSidebar .sidebar-logout:hover { background: rgba(248,113,113,0.12) !important; color: #fff !important; }

        /* Collapsed sidebar: completely hidden */
        #accordionSidebar.toggled {
            width: 0 !important;
            min-width: 0 !important;
            max-width: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            overflow: hidden !important;
        }
        #accordionSidebar.toggled .sidebar-user-panel { display: none; }

        /* Sidebar toggle button floating round circle */
        #sidebarCollapseBtn {
            position: fixed;
            top: 50%;
            left: 242px;
            z-index: 1050;
            width: 36px;
            height: 36px;
            background: #1e293b;
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            color: #ffffff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            transform: translate(-50%, -50%);
        }
        #mobileSidebarToggle {
            display: none;
            position: fixed;
            top: 12px;
            left: 12px;
            z-index: 1050;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #134e4a;
            color: #fff !important;
            box-shadow: 0 3px 10px rgba(15, 23, 42, 0.2);
        }
        @media (max-width: 767.98px) {
            #mobileSidebarToggle { display: inline-flex; align-items: center; justify-content: center; }
        }
        #sidebarCollapseBtn:hover {
            background: #0d9488;
            color: #ffffff;
            transform: translate(-50%, -50%) scale(1.08);
            box-shadow: 0 4px 16px rgba(13, 148, 136, 0.45);
        }
        #sidebarCollapseBtn.collapsed {
            left: 18px;
            background: #134e4a;
            border-color: rgba(255, 255, 255, 0.3);
        }
        #sidebarCollapseBtn.collapsed:hover { transform: translate(-50%, -50%) scale(1.08); }
        #sidebarCollapseBtn i {
            font-size: 0.85rem;
            transition: transform 0.25s ease;
        }
        #sidebarCollapseBtn.collapsed i {
            transform: rotate(180deg);
        }

        /* ── Sidebar Nav Items ── */
        .sidebar .nav-item .nav-link {
            padding: 11px 14px !important;
            font-weight: 500 !important;
            font-size: 0.88rem !important;
            border-radius: 8px !important;
            margin: 2px 8px !important;
            transition: all 0.15s ease !important;
            display: flex !important;
            align-items: center !important;
            color: #99f6e4 !important;
            white-space: normal;
            line-height: 1.3;
            width: calc(100% - 16px) !important;
        }
        .sidebar .nav-item .nav-link span { min-width: 0; overflow-wrap: anywhere; }
        .sidebar .nav-item .nav-link i {
            margin-right: 10px !important;
            font-size: 0.95rem !important;
            width: 20px !important;
            text-align: center !important;
            flex-shrink: 0;
        }
        .sidebar .nav-item .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        .sidebar .nav-item.active .nav-link {
            color: #ffffff !important;
            background-color: var(--sipma-teal) !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.35) !important;
        }
        .sidebar-heading {
            font-size: 0.68rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.8px !important;
            color: #5eead4 !important;
            padding: 14px 16px 4px 16px !important;
            line-height: 1.35;
        }
        .sidebar-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            margin: 8px 10px !important;
        }
        #accordionSidebar .nav-item form { width: 100%; }
        #accordionSidebar .sidebar-brand { min-width: 0; padding-left: 12px !important; padding-right: 12px !important; }
        #accordionSidebar .sidebar-brand-text { min-width: 0; }
        #accordionSidebar .sidebar-brand-text > div:last-child { overflow-wrap: anywhere; }

        /* ── Content Wrapper ── */
        #content-wrapper {
            flex: 1 1 0%;
            min-width: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            background-color: #f1f5f9;
            transition: margin-left 0.25s ease;
        }

        /* ── Topbar (admin) ── */
        .admin-topbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 24px;
            min-height: 0;
            flex-shrink: 0;
        }

        /* ── Main content padding ── */
        #main-content {
            flex: 1;
            padding: 12px 24px 32px;
        }
        @media (max-width: 767.98px) {
            #main-content { padding: 10px 14px 28px; }
        }

        /* ── Full width topbar layout (Mahasiswa, Wadek3, Dekan) ── */
        body.is-topbar-only #wrapper {
            display: block;
            height: auto;
            min-height: 100vh;
            overflow: visible;
        }
        body.is-topbar-only #content-wrapper {
            min-height: 100vh;
            height: auto;
            overflow-y: visible;
            overflow-x: hidden;
        }
        body.is-topbar-only {
            height: auto;
            min-height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
        }
        body.is-topbar-only .topbar {
            border-bottom: 1px solid #e2e8f0;
        }

        /* ── Banner alur: teal color ── */
        .sop-banner {
            background: linear-gradient(135deg, #134e4a 0%, #0f766e 55%, #0d9488 100%) !important;
            border-radius: 16px;
        }

        /* ── Sidebar active link: teal override ── */
        .border-left-primary { border-left: 4px solid #0d9488 !important; }
        .text-primary { color: #0d9488 !important; }
        .btn-primary, .badge-primary { background-color: #0d9488 !important; border-color: #0d9488 !important; }
        .border-left-info { border-left: 4px solid #0891b2 !important; }

        /* ── Chart expand modal overlay ── */
        #chartModal {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(5px);
            z-index: 999999 !important;
            align-items: center;
            justify-content: center;
        }
        #chartModal.open { display: flex !important; }
        #chartModal .chart-modal-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 28px;
            width: 92vw;
            max-width: 1100px;
            max-height: 88vh;
            overflow-y: auto;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        #chartModal .close-modal-btn {
            position: absolute;
            top: 16px; right: 16px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            width: 36px; height: 36px;
            cursor: pointer;
            font-size: 1rem;
            color: #334155;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        #chartModal .close-modal-btn:hover { 
            background: #ef4444; 
            color: #ffffff;
            border-color: #ef4444;
        }
        .chart-expandable { cursor: pointer; position: relative; }
        .chart-expandable::after {
            content: '\f065';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 8px; right: 8px;
            background: rgba(13,148,136,0.12);
            color: #0d9488;
            width: 28px; height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .chart-expandable:hover::after { opacity: 1; }
    </style>
</head>
<body id="page-top" class="@auth{{ (auth()->user()->isMahasiswa() || auth()->user()->isWadek3() || auth()->user()->isDekan() || auth()->user()->isWarek()) ? ' is-topbar-only' : '' }}@endauth">
    <div id="wrapper">

        <!-- ── Sidebar Navigation (Kemahasiswaan, Kajur, Warek) ── -->
        @auth
        @if(!auth()->user()->isMahasiswa() && !auth()->user()->isWadek3() && !auth()->user()->isDekan() && !auth()->user()->isWarek())
        @php
            $dashboardRoute = match(auth()->user()->role) {
                'kemahasiswaan', 'admin' => route('kemahasiswaan.dashboard'),
                'kajur', 'kaprodi' => route('kajur.dashboard'),
                'dekan' => route('dekan.dashboard'),
                'wadek3' => route('wadek3.dashboard'),
                'warek' => route('warek.dashboard'),
                default => route('dashboard'),
            };
        @endphp

        <!-- Sidebar Collapse Button -->
        <button id="sidebarCollapseBtn" title="Toggle Sidebar">
            <i class="fas fa-chevron-left"></i>
        </button>

        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="{{ $dashboardRoute }}">
                <div class="sidebar-brand-icon mr-2">
                    <img src="{{ asset('img/sipma-emblem.png') }}?v=20261024-4" alt="Lambang SIPMA" style="width:42px;height:42px;object-fit:contain;object-position:center;border-radius:12px;background:transparent;">
                </div>
                <div class="sidebar-brand-text text-left">
                    <div class="font-weight-bold" style="font-size: 1.15rem; line-height: 1.2;">SIPMA</div>
                    <div class="text-xs text-white-50 font-weight-normal">
                        @if(auth()->user()->isKemahasiswaan())
                            Super Admin Kampus
                        @elseif(auth()->user()->isKajur())
                            Jurusan {{ auth()->user()->department }}
                        @else
                            Universitas
                        @endif
                    </div>
                </div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ $dashboardRoute }}">
                    <i class="fas fa-home"></i>
                    <span>
                        @if(auth()->user()->isKemahasiswaan())
                            Dashboard Super Admin
                        @elseif(auth()->user()->isKajur())
                            Dashboard Kajur
                        @else
                            Dashboard Wakil Rektor
                        @endif
                    </span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Kasus & Pelaporan</div>

            @if(auth()->user()->isKemahasiswaan() || auth()->user()->isKajur())
            <li class="nav-item {{ request()->routeIs('reports.create') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reports.create') }}">
                    <i class="fas fa-paper-plane text-success"></i>
                    <span>Form Pelaporan</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->isKemahasiswaan())
            <li class="nav-item {{ request()->routeIs('reports.inbox') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reports.inbox') }}">
                    <i class="fas fa-inbox text-warning"></i>
                    <span>Inbox Verifikasi</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('reports.history') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reports.history') }}">
                    <i class="fas fa-history text-info"></i>
                    <span>Riwayat Laporan</span>
                </a>
            </li>
            @elseif(auth()->user()->isKajur())
            <li class="nav-item {{ request()->routeIs('reports.history') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reports.history') }}">
                    <i class="fas fa-clipboard-list text-info"></i>
                    <span>Data Laporan</span>
                </a>
            </li>
            @else
            <li class="nav-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reports.index') }}">
                    <i class="fas fa-clipboard-list text-info"></i>
                    <span>Rekapitulasi Pelanggaran</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->isKemahasiswaan())
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Profil</div>
            <li class="nav-item {{ request()->routeIs('kemahasiswaan.accounts.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kemahasiswaan.accounts.index') }}">
                    <i class="fas fa-users-cog text-primary"></i>
                    <span>Kelola Akun</span>
                </a>
            </li>
            @endif

            @if(auth()->user()->isKemahasiswaan() || auth()->user()->isWarek() || auth()->user()->isKajur())
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Ekspor & Rekapitulasi</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.export.excel') }}">
                    <i class="fas fa-file-excel text-success"></i>
                    <span>Ekspor Data Excel</span>
                </a>
            </li>
            @endif

            <div class="sidebar-spacer"></div>
            <li class="sidebar-user-panel list-unstyled">
                <div class="d-flex align-items-center mb-2 px-1">
                    <div class="sidebar-user-avatar d-flex align-items-center justify-content-center mr-2 flex-shrink-0">
                        @if (auth()->user()->clean_name !== '')
                            {{ mb_strtoupper(mb_substr(auth()->user()->clean_name, 0, 1)) }}
                        @else
                            <i class="fas fa-user" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="min-w-0" style="min-width: 0;">
                        @if (auth()->user()->isKemahasiswaan())
                            <div class="d-flex align-items-center" style="gap:5px;">
                                <div class="sidebar-user-name font-weight-bold text-truncate" style="font-size: 0.7rem;">{{ in_array(mb_strtolower(auth()->user()->clean_name), ['', 'bagian kemahasiswaan', 'admin kemahasiswaan'], true) ? 'Kemahasiswaan' : auth()->user()->clean_name }}</div>
                                <button type="button" class="btn btn-link p-0 text-white-50" style="font-size:.62rem;line-height:1;" data-toggle="modal" data-target="#editOwnNameModal" title="Edit nama" aria-label="Edit nama Kemahasiswaan"><i class="fas fa-pencil-alt"></i></button>
                            </div>
                            <div class="sidebar-user-email" style="font-size: 0.62rem; color: #99f6e4;">{{ auth()->user()->username }}</div>
                        @elseif (auth()->user()->isKajur())
                            <div class="sidebar-user-name font-weight-bold" style="font-size: 0.7rem;">{{ auth()->user()->clean_name }}</div>
                            <div class="sidebar-user-email" style="font-size: 0.62rem; color: #99f6e4;">{{ auth()->user()->username }}</div>
                        @else
                            <div class="sidebar-user-name font-weight-bold" style="font-size: 0.7rem;">{{ auth()->user()->clean_name }}</div>
                            <div class="sidebar-user-email" style="font-size: 0.62rem; color: #99f6e4;">{{ auth()->user()->email }}</div>
                        @endif
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link w-100 sidebar-logout px-2 py-1">
                        <i class="fas fa-sign-out-alt"></i><span>Keluar</span>
                    </button>
                </form>
            </li>
        </ul>
        @endif
        @endauth
        <!-- End Sidebar -->

        @auth
            @if (auth()->user()->isKemahasiswaan())
                <div class="modal fade" id="editOwnNameModal" tabindex="-1" role="dialog" aria-labelledby="editOwnNameTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form method="POST" action="{{ route('kemahasiswaan.profile.name.update') }}" class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="profile_name_form" value="1">
                            <div class="modal-header text-white border-0" style="background:linear-gradient(110deg,#134e4a,#0d9488);">
                                <div>
                                    <h5 class="modal-title font-weight-bold mb-1" id="editOwnNameTitle"><i class="fas fa-user-edit mr-2"></i>Edit Nama</h5>
                                    <small class="text-white-50">Ubah nama yang tampil di profil Kemahasiswaan.</small>
                                </div>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <div class="modal-body p-4">
                                <label for="kemahasiswaanDisplayName" class="font-weight-bold">Nama</label>
                                <input type="text" id="kemahasiswaanDisplayName" name="name" value="{{ old('profile_name_form') ? old('name') : auth()->user()->clean_name }}" class="form-control @error('name') is-invalid @enderror" maxlength="100" required placeholder="Masukkan nama">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="modal-footer bg-light border-0">
                                <button type="button" class="btn btn-light font-weight-bold" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary font-weight-bold px-4"><i class="fas fa-save mr-1"></i>Simpan Nama</button>
                            </div>
                        </form>
                    </div>
                </div>
                @if (old('profile_name_form'))
                    <script>document.addEventListener('DOMContentLoaded', function () { $('#editOwnNameModal').modal('show'); });</script>
                @endif
            @endif
        @endauth

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

    @auth
            @if(auth()->user()->isMahasiswa())
            <!-- ── Topbar Mahasiswa ── -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow-sm px-3 px-sm-4 justify-content-between" style="border-bottom: 1px solid #e2e8f0;">
                <div class="d-flex align-items-center">
                    <a href="{{ route('mahasiswa.dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <img src="{{ asset('img/sipma-logo-horizontal.png') }}?v=20261024-4" alt="SIPMA — Sistem Pendataan Pelanggaran Mahasiswa" style="display:block;width:125px;height:auto;object-fit:contain;">
                    </a>
                </div>
                <div class="d-flex align-items-center">
                    <div class="text-right mr-3">
                        <div class="font-weight-bold text-dark small" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ auth()->user()->clean_name }}</div>
                        <div class="text-xs text-muted font-weight-bold">{{ auth()->user()->nim ?? (auth()->user()->student?->nim ?? '2024001') }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill px-3 shadow-sm d-flex align-items-center" style="border-color: #fca5a5; color: #ef4444;" title="Keluar">
                            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </nav>
            @elseif(auth()->user()->isWadek3() || auth()->user()->isDekan() || auth()->user()->isWarek())
            <!-- ── Topbar pimpinan (Dekan, Wadek III, Warek) ── -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow-sm px-3 px-sm-4 justify-content-between" style="border-bottom: 1px solid #e2e8f0; height: 68px;">
                <!-- Kiri: Logo SIPMA & Judul "Dashboard Monitoring" -->
                <div class="d-flex align-items-center">
                    <a href="{{ auth()->user()->isWadek3() ? route('wadek3.dashboard') : (auth()->user()->isWarek() ? route('warek.dashboard') : route('dekan.dashboard')) }}" class="d-flex align-items-center text-decoration-none">
                        <img src="{{ asset('img/sipma-logo-horizontal.png') }}?v=20261024-4" alt="SIPMA — Sistem Pendataan Pelanggaran Mahasiswa" style="display:block;width:125px;height:auto;object-fit:contain;">
                    </a>
                </div>

                <!-- Kanan: Jabatan + Tombol Logout -->
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <div class="d-none d-sm-block text-right">
                        <div class="font-weight-bold text-dark small" style="max-width: 240px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; line-height: 1.2;">
                            {{ auth()->user()->isWadek3() ? 'Wakil Dekan III' : (auth()->user()->isWarek() ? 'Wakil Rektor III' : 'Dekan') }}
                        </div>
                        <div class="text-xs font-weight-bold" style="color: #0d9488; line-height: 1.2;">
                            {{ auth()->user()->isWarek() ? 'Universitas' : (auth()->user()->faculty ?? 'Fakultas') }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill px-3 py-1.5 shadow-sm d-flex align-items-center" style="border-color: #fca5a5; color: #ef4444; font-size: 0.82rem;" title="Keluar dari Sistem">
                            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </nav>
            @else
            <!-- Tombol buka sidebar untuk layar kecil; tidak memakai ruang di atas konten. -->
            <button class="btn btn-sm border-0" id="mobileSidebarToggle" aria-label="Buka menu">
                <i class="fas fa-bars"></i>
            </button>
            @endif
            @endauth

            <!-- Main Content -->
            <div id="main-content">
                {{ $slot }}
            </div>

            <footer class="bg-white border-top py-3 mt-auto">
                <div class="container my-auto">
                    <div class="text-center text-muted small">
                        SIPMA &copy; {{ date('Y') }} — Sistem Informasi Pendataan & Penanganan Pelanggaran Mahasiswa
                    </div>
                </div>
            </footer>
        </div>
        <!-- End Content Wrapper -->
    </div>
    <!-- End Wrapper -->

    <!-- ── Chart Expand Modal ── -->
    <div id="chartModal">
        <div class="chart-modal-box">
            <button class="close-modal-btn" id="closeChartModal"><i class="fas fa-times"></i></button>
            <h5 class="font-weight-bold text-dark mb-3 pr-4" id="chartModalTitle" style="color: #0f766e !important;"></h5>
            <div id="chartModalCanvasWrapper" style="position: relative; width: 100%; height: 68vh;">
                <canvas id="chartModalCanvas"></canvas>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script>
        /* ── Sidebar collapse toggle ── */
        (function () {
            const btn   = document.getElementById('sidebarCollapseBtn');
            const sb    = document.getElementById('accordionSidebar');
            if (!btn || !sb) return;

            const KEY = 'sipma_sidebar_collapsed';
            // Restore state
            if (localStorage.getItem(KEY) === '1') {
                sb.classList.add('toggled');
                btn.classList.add('collapsed');
            }

            btn.addEventListener('click', function () {
                sb.classList.toggle('toggled');
                btn.classList.toggle('collapsed');
                localStorage.setItem(KEY, sb.classList.contains('toggled') ? '1' : '0');
            });

            // Mobile sidebar toggle
            const mobileBtn = document.getElementById('mobileSidebarToggle');
            if (mobileBtn) {
                mobileBtn.addEventListener('click', function () {
                    sb.classList.toggle('toggled');
                    btn.classList.toggle('collapsed');
                });
            }
        })();

        /* ── Chart expand modal ── */
        (function () {
            // Satu standar animasi untuk setiap grafik Chart.js di seluruh peran.
            // Berlaku saat grafik pertama dibuat, data diperbarui, dan modal perbesar dibuka.
            if (window.Chart && Chart.defaults) {
                Chart.defaults.animation.duration = 1700;
                Chart.defaults.animation.easing = 'easeOutBounce';
                if (Chart.defaults.transitions && Chart.defaults.transitions.active) {
                    Chart.defaults.transitions.active.animation.duration = 280;
                }
            }

            const modal      = document.getElementById('chartModal');
            const closeBtn   = document.getElementById('closeChartModal');
            const modalTitle = document.getElementById('chartModalTitle');
            let   modalChart = null;

            window.openChartModal = function (title, type, labels, datasets, options) {
                if (!modal) return;
                modalTitle.textContent = title;
                modal.classList.add('open');

                if (modalChart) { 
                    try { modalChart.destroy(); } catch(e){}
                    modalChart = null; 
                }

                const wrapper = document.getElementById('chartModalCanvasWrapper');
                if (wrapper) {
                    const isHorizontal = options && options.indexAxis === 'y';
                    if (isHorizontal && labels && labels.length > 5) {
                        // Beri ruang tiap label/bar dan scroll di modal untuk daftar panjang.
                        wrapper.style.height = Math.max(520, labels.length * 52) + 'px';
                        wrapper.style.maxHeight = 'calc(100vh - 190px)';
                        wrapper.style.overflowY = 'auto';
                    } else {
                        wrapper.style.height = '65vh';
                        wrapper.style.maxHeight = '';
                        wrapper.style.overflowY = 'hidden';
                    }
                    wrapper.innerHTML = '<canvas id="chartModalCanvas"></canvas>';
                    const canvas = document.getElementById('chartModalCanvas');
                    modalChart = new Chart(canvas, {
                        type: type,
                        data: { labels: labels, datasets: datasets },
                        options: Object.assign({
                            responsive: true,
                            maintainAspectRatio: false,
                            animation: { duration: 1800, easing: 'easeOutBounce' },
                            plugins: {
                                legend: { position: type === 'bar' ? (isHorizontal ? 'bottom' : 'top') : 'bottom' }
                            }
                        }, options || {})
                    });
                }
            };

            if (closeBtn) closeBtn.addEventListener('click', () => modal.classList.remove('open'));
            if (modal) modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });
            document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal) modal.classList.remove('open'); });
        })();
    </script>
</body>
</html>
