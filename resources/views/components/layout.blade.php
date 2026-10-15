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
</head>
<body id="page-top">
    <div id="wrapper">

        <!-- Sidebar Navigation -->
        @auth
        @php
            $dashboardRoute = match(auth()->user()->role) {
                'kemahasiswaan', 'admin' => route('kemahasiswaan.dashboard'),
                'mahasiswa' => route('mahasiswa.dashboard'),
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
                        @elseif(auth()->user()->isMahasiswa())
                            Portal Mahasiswa
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
                        @elseif(auth()->user()->isMahasiswa())
                            Dashboard Pelapor
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

            @if(auth()->user()->isMahasiswa() || auth()->user()->isKemahasiswaan())
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
                        @if(auth()->user()->isMahasiswa())
                            Riwayat Pelaporan Saya
                        @elseif(auth()->user()->isKemahasiswaan())
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
        @endauth
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                @auth
                <!-- Topbar Header -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow-sm px-4 justify-content-between">
                    
                    <!-- Role Indicator Badge -->
                    <div class="d-flex align-items-center">
                        @php
                            $roleBadgeColor = 'badge-secondary';
                            if (auth()->user()->isMahasiswa()) $roleBadgeColor = 'badge-success';
                            elseif (auth()->user()->isKemahasiswaan()) $roleBadgeColor = 'badge-primary';
                            elseif (auth()->user()->isKajur()) $roleBadgeColor = 'badge-info';
                            elseif (auth()->user()->isDekan()) $roleBadgeColor = 'badge-warning text-dark';
                            elseif (auth()->user()->isWarek()) $roleBadgeColor = 'badge-dark';
                        @endphp

                        <div class="d-flex align-items-center">
                            <span class="badge {{ $roleBadgeColor }} px-3 py-2 text-uppercase font-weight-bold mr-2" style="font-size:0.85rem;">
                                <i class="fas fa-user-tag mr-1"></i> {{ auth()->user()->role_title }}
                            </span>
                            @if(auth()->user()->isKemahasiswaan())
                                <span class="badge badge-light border text-primary font-weight-bold small d-none d-md-inline">
                                    <i class="fas fa-crown text-warning mr-1"></i> Super Admin Akses
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- User Profile & Logout -->
                    <div class="d-flex align-items-center">
                        <div class="text-right mr-3 d-none d-sm-block">
                            <div class="font-weight-bold text-dark small">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-muted">
                                @if(auth()->user()->isMahasiswa() && auth()->user()->nim)
                                    NIM: {{ auth()->user()->nim }}
                                @else
                                    {{ auth()->user()->email }}
                                @endif
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold rounded-pill px-3 shadow-sm" title="Keluar dari Sistem">
                                <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                            </button>
                        </form>
                    </div>
                </nav>
                <!-- End Topbar -->
                @endauth

                <!-- Container Fluid -->
                <div class="container-fluid px-4 pb-5">
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
