<x-layout>
    <!-- Hero Executive Banner Card -->
    <div class="card border-0 text-white shadow-sm mb-4 rounded-lg overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 40%, #312e81 100%);">
        <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-primary font-weight-bold px-3 py-1 text-uppercase mr-2" style="letter-spacing:0.5px;">SIPMA Kaprodi Hub</span>
                </div>
                <h2 class="font-weight-bold mb-2 text-white">Dashboard Manajemen & Verifikasi</h2>
                <p class="text-white-50 mb-3" style="max-width: 720px;">
                    Pusat pemantauan rekapitulasi pelanggaran, antrean verifikasi Kaprodi, eskalasi poin ≥ 100, dan pemantauan penanganan.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('reports.index') }}" class="btn btn-warning btn-sm font-weight-bold text-dark px-3 rounded-pill">
                        <i class="fas fa-user-check mr-1"></i> Verifikasi Laporan
                    </a>
                    <a href="{{ route('reports.create') }}" class="btn btn-light btn-sm font-weight-bold text-primary px-3 rounded-pill">
                        <i class="fas fa-plus-circle mr-1"></i> Buat Laporan Baru
                    </a>
                </div>
            </div>
            <div class="d-none d-lg-block text-center mt-3 mt-lg-0">
                <div class="p-3 rounded-circle d-inline-block" style="background: rgba(255,255,255,0.1);">
                    <i class="fas fa-chart-line fa-4x text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Heading & Quick Stats Subtitle -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="h5 mb-1 text-gray-900 font-weight-bold"><i class="fas fa-chart-pie text-primary mr-2"></i>Metrik Operasional Kaprodi</h5>
        </div>
    </div>

    <div class="row">
        <!-- TOTAL LAPORAN -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-stat stat-primary shadow-sm h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">TOTAL LAPORAN KAMPUS</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalReports }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-primary" style="opacity:0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MENUNGGU VERIFIKASI -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-stat stat-warning shadow-sm h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">ANTREAN VERIFIKASI</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $pendingVerification }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock fa-2x text-warning" style="opacity:0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ESKALASI -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card card-stat shadow-sm h-100 py-2" style="border-left: .25rem solid #7c3aed !important;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color:#7c3aed;">ESKALASI KEMAHASISWAAN</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $escalated }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-right fa-2x" style="color:#7c3aed; opacity:0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
