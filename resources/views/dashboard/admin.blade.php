<x-layout>
    <!-- Hero Executive Banner Card -->
    <div class="card border-0 text-white shadow-sm mb-4 rounded-lg overflow-hidden" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
        <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <div class="d-flex align-items-center mb-2">
                    <span class="badge badge-danger font-weight-bold px-3 py-1 text-uppercase mr-2" style="letter-spacing:0.5px;">SIPMA Admin Hub</span>
                </div>
                <h2 class="font-weight-bold mb-2 text-white">Dashboard Penanganan Kasus</h2>
                <p class="text-white-50 mb-3" style="max-width: 720px;">
                    Penanganan eskalasi pelanggaran berat (≥100 poin) dan pemberian sanksi resmi kampus.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('reports.index') }}" class="btn btn-purple btn-sm font-weight-bold text-white px-3 rounded-pill" style="background:#7c3aed;">
                        <i class="fas fa-gavel mr-1"></i> Penanganan Kemahasiswaan
                    </a>
                </div>
            </div>
            <div class="d-none d-lg-block text-center mt-3 mt-lg-0">
                <div class="p-3 rounded-circle d-inline-block" style="background: rgba(255,255,255,0.1);">
                    <i class="fas fa-user-shield fa-4x text-white-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <div>
            <h5 class="h5 mb-1 text-gray-900 font-weight-bold"><i class="fas fa-chart-pie text-primary mr-2"></i>Metrik Penanganan Admin</h5>
        </div>
    </div>

    <div class="row">
        <!-- TOTAL LAPORAN -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">TOTAL KASUS</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalReports }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-primary" style="opacity:0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ESKALASI -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">KASUS DITERUSKAN</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $escalatedCases }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger" style="opacity:0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEDANG DIPROSES -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">SEDANG DIPROSES</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $processingCases }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-warning" style="opacity:0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SELESAI -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">KASUS SELESAI</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $completedCases }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-success" style="opacity:0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
