<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'SIPMA | Sistem Pendataan Pelanggaran Mahasiswa' }}</title>
    
    <!-- FontAwesome & Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-app.css') }}" rel="stylesheet">

    <!-- Chart.js for Dashboards -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        html, body {
            width: 100%;
            min-height: 100vh;
        }
        #wrapper {
            display: flex;
            width: 100% !important;
            min-height: 100vh;
        }
        #wrapper #content-wrapper {
            width: 100% !important;
            min-width: 0;
            flex: 1 1 auto !important;
            overflow-x: hidden;
        }
        #wrapper #content-wrapper #content {
            width: 100% !important;
            flex: 1 0 auto;
        }

        /* If student (no sidebar), make page completely full width and responsive */
        @auth
        @if(auth()->user()->isMahasiswa())
        #wrapper {
            width: 100% !important;
        }
        #wrapper #content-wrapper {
            background-color: #f8fafc;
            width: 100% !important;
            flex: 1 1 100% !important;
            min-width: 0;
        }
        .container-fluid {
            width: 100% !important;
            max-width: 100% !important;
        }
        .topbar {
            width: 100% !important;
            border-bottom: 1px solid #e2e8f0;
        }
        @endif
        @endauth
    </style>
</head>
<body id="page-top">
    <div id="wrapper">

        <!-- Sidebar Navigation (Only for Non-Mahasiswa: Kemahasiswaan, Kajur, Dekan, Warek) -->
        @auth
        @if(!auth()->user()->isMahasiswa())
        @php
            $dashboardRoute = match(auth()->user()->role) {
                'kemahasiswaan', 'admin' => route('kemahasiswaan.dashboard'),
                'kajur', 'kaprodi' => route('kajur.dashboard'),
                'dekan' => route('dekan.dashboard'),
                'warek' => route('warek.dashboard'),
                default => route('dashboard'),
            };
        @endphp

        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #1e1b4b; background-image: none;">
            <a class="sidebar-brand d-flex align-items-center justify-content-center py-4" href="{{ $dashboardRoute }}">
                <div class="sidebar-brand-icon mr-2">
                    <i class="fas fa-shield-alt text-warning fa-lg"></i>
                </div>
                <div class="sidebar-brand-text text-left">
                    <div class="font-weight-bold" style="font-size: 1.15rem; line-height: 1.2;">SIPMA</div>
                    <div class="text-xs text-white-50 font-weight-normal">
                        @if(auth()->user()->isKemahasiswaan())
                            Super Admin Kampus
                        @elseif(auth()->user()->isKajur())
                            Jurusan {{ auth()->user()->department }}
                        @elseif(auth()->user()->isDekan())
                            Fakultas
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
                        @elseif(auth()->user()->isDekan())
                            Dashboard Dekan
                        @else
                            Dashboard Wakil Rektor
                        @endif
                    </span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Kasus & Pelaporan</div>

            @if(auth()->user()->isKemahasiswaan())
            <li class="nav-item {{ request()->routeIs('reports.create') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reports.create') }}">
                    <i class="fas fa-paper-plane text-success"></i>
                    <span>Form Pelaporan Manual</span>
                </a>
            </li>
            @endif

            <li class="nav-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('reports.index') }}">
                    <i class="fas fa-clipboard-list text-info"></i>
                    <span>
                        @if(auth()->user()->isKemahasiswaan())
                            Inbox Verifikasi (Semua)
                        @elseif(auth()->user()->isKajur())
                            Kasus Jurusan Saya
                        @else
                            Rekapitulasi Pelanggaran
                        @endif
                    </span>
                </a>
            </li>

            @if(auth()->user()->isKemahasiswaan() || auth()->user()->isDekan() || auth()->user()->isWarek())
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Ekspor & Rekapitulasi</div>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.export.excel') }}">
                    <i class="fas fa-file-excel text-success"></i>
                    <span>Ekspor Data Excel (CSV)</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('reports.export.pdf') }}" target="_blank">
                    <i class="fas fa-print text-danger"></i>
                    <span>Cetak Dokumen Rapat (PDF)</span>
                </a>
            </li>
            @endif

            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        @endif
        @endauth
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="min-height: 100vh;">

            <!-- Main Content -->
            <div id="content">

                @auth
                <!-- Topbar Header -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm px-3 px-sm-4 justify-content-between">
                    
                    @if(auth()->user()->isMahasiswa())
                        <!-- Topbar Khusus Mahasiswa dengan Logo SIPMA -->
                        <div class="d-flex align-items-center">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="d-flex align-items-center text-decoration-none mr-3 mr-md-4">
                                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm mr-2" style="width: 42px; height: 42px; background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
                                    <i class="fas fa-shield-alt text-warning fa-lg"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="d-flex align-items-center">
                                        <span class="font-weight-bold text-dark" style="font-size: 1.25rem; letter-spacing: 0.5px; line-height: 1;">SIPMA</span>
                                        <span class="badge badge-light border text-primary ml-2 font-weight-bold" style="font-size: 0.65rem;">KAMPUS</span>
                                    </div>
                                    <small class="text-muted d-none d-sm-inline" style="font-size: 0.72rem; font-weight: 500;">Sistem Pendataan Pelanggaran Mahaiswa</small>
                                </div>
                            </a>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="text-right mr-3">
                                <div class="font-weight-bold text-dark small" style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ auth()->user()->clean_name }}
                                </div>
                                <div class="text-xs text-muted font-weight-bold">
                                    NIM: {{ auth()->user()->nim ?? (auth()->user()->student?->nim ?? '2024001') }}
                                </div>
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill px-3 shadow-sm d-flex align-items-center" style="border-color: #fca5a5; color: #ef4444;" title="Keluar">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Topbar untuk Role Lain (Kemahasiswaan, Kajur, Dekan, Warek) -->
                        <div class="d-flex align-items-center">
                            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-2 text-primary" type="button" aria-label="Buka Menu">
                                <i class="fa fa-bars fa-lg"></i>
                            </button>

                            @php
                                $roleBadgeColor = 'badge-secondary';
                                if (auth()->user()->isKemahasiswaan()) $roleBadgeColor = 'badge-primary';
                                elseif (auth()->user()->isKajur()) $roleBadgeColor = 'badge-info';
                                elseif (auth()->user()->isDekan()) $roleBadgeColor = 'badge-warning text-dark';
                                elseif (auth()->user()->isWarek()) $roleBadgeColor = 'badge-dark';
                            @endphp

                            <div class="d-flex align-items-center flex-wrap">
                                <span class="badge {{ $roleBadgeColor }} px-3 py-2 text-uppercase font-weight-bold mr-2" style="font-size: 0.85rem;">
                                    <i class="fas fa-user-tag mr-1"></i> {{ auth()->user()->role_title }}
                                </span>
                                @if(auth()->user()->isKemahasiswaan())
                                    <span class="badge badge-light border text-primary font-weight-bold small d-none d-lg-inline">
                                        <i class="fas fa-crown text-warning mr-1"></i> Super Admin Akses
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Right: User Profile & Logout -->
                        <div class="d-flex align-items-center">
                            <div class="text-right mr-3 d-none d-sm-block">
                                <div class="font-weight-bold text-dark small">{{ auth()->user()->clean_name }}</div>
                                @if(auth()->user()->nim)
                                    <div class="text-xs text-muted font-weight-bold">NIM: {{ auth()->user()->nim }}</div>
                                @else
                                    <div class="text-xs text-muted">{{ auth()->user()->email }}</div>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill px-3 shadow-sm" title="Keluar dari Sistem">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                                </button>
                            </form>
                        </div>
                    @endif
                </nav>
                <!-- End Topbar -->
                @endauth

                <!-- Container Fluid -->
                <div class="container-fluid px-3 px-sm-4 pb-5">
                    {{ $slot }}
                </div>

            </div>
            <!-- End of Main Content -->

            <footer class="sticky-footer bg-white shadow-sm mt-auto py-3">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto text-muted small">
                        <span>SIPMA &copy; {{ date('Y') }} - Sistem Informasi Pendataan & Penanganan Pelanggaran Mahasiswa</span>
                    </div>
                </div>
            </footer>
        </div>
        <!-- End of Content Wrapper -->
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>
</html>
