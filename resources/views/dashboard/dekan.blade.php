<x-layout>
    <x-slot:title>Dashboard Dekan {{ $myFaculty }} | SIPMA</x-slot:title>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                <i class="fas fa-university text-warning mr-1"></i> Dashboard Dekan: {{ $myFaculty }}
            </h1>
            <p class="text-muted small mb-0">Executive Monitoring & Rekapitulasi Pelanggaran Tingkat Fakultas (Hak Akses Read-Only).</p>
        </div>

        <div class="mt-3 mt-sm-0">
            <a href="{{ route('reports.export.excel') }}" class="btn btn-sm btn-success font-weight-bold shadow-sm mr-2">
                <i class="fas fa-file-excel mr-1"></i> Ekspor Excel
            </a>
            <a href="{{ route('reports.export.pdf') }}" target="_blank" class="btn btn-sm btn-outline-danger font-weight-bold shadow-sm">
                <i class="fas fa-print mr-1"></i> Cetak Rekap PDF
            </a>
        </div>
    </div>

    <!-- Dekan Stat Cards -->
    <div class="row">
        <!-- TOTAL KASUS FAKULTAS -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pelanggaran Fakultas</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalFacultyReports }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-primary" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SELESAI SANKSI -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kasus Tuntas Selesai</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $completedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DALAM PENANGANAN KAJUR -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Dalam Proses Kajur</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $inProgressCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-info" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JURUSAN DI BAWAH FAKULTAS -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jurusan / Program Studi</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ count($facultyDepts) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-warning" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Dekan Tingkat Fakultas -->
    <div class="row">
        <!-- Grafik 1: Pelanggaran Jurusan-Jurusan di bawah Fakultasnya -->
        <div class="col-xl-7 col-lg-7 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-chart-bar mr-1"></i> Grafik Pelanggaran Jurusan di {{ $myFaculty }}
                    </h6>
                    <span class="badge badge-light border text-muted">Statistik Fakultas</span>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 280px;">
                        <canvas id="facultyDeptChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik 2: Komposisi Jenis/Kategori Pelanggaran Fakultas -->
        <div class="col-xl-5 col-lg-5 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-chart-pie mr-1"></i> Jenis Pelanggaran Paling Umum di Fakultas
                    </h6>
                    <span class="badge badge-light border text-muted">Distribusi Keparahan</span>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 240px;">
                        <canvas id="facultySeverityChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-info"></i> Ringan</span>
                        <span class="mr-2"><i class="fas fa-circle text-warning"></i> Sedang</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Berat</span>
                        <span class="mr-2"><i class="fas fa-circle text-secondary"></i> Suspek</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Rekap Jurusan di Bawah Fakultas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-list-alt text-warning mr-1"></i> Rekapitulasi Data Kasus Jurusan di Bawah {{ $myFaculty }}
            </h6>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary font-weight-bold">
                Lihat Seluruh Kasus <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Program Studi / Jurusan</th>
                            <th>Total Kasus</th>
                            <th>Tingkat Risiko</th>
                            <th>Status Penanganan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deptStats as $dName => $count)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $dName }}</td>
                            <td><span class="badge badge-primary px-3 py-1 font-weight-bold">{{ $count }} Kasus</span></td>
                            <td>
                                @if($count > 2)
                                    <span class="badge badge-danger">Perhatian Khusus</span>
                                @elseif($count > 0)
                                    <span class="badge badge-warning text-dark">Terkendali</span>
                                @else
                                    <span class="badge badge-success">Nol Pelanggaran</span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted font-italic">Diawasi oleh Ketua Jurusan {{ $dName }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Script Dekan -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Bar Chart Jurusan Fakultas
            const ctx1 = document.getElementById('facultyDeptChart').getContext('2d');
            const deptLabels = {!! json_encode(array_keys($deptStats)) !!};
            const deptValues = {!! json_encode(array_values($deptStats)) !!};

            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: deptLabels,
                    datasets: [{
                        label: 'Total Kasus',
                        data: deptValues,
                        backgroundColor: '#f59e0b',
                        hoverBackgroundColor: '#d97706',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // Chart 2: Donut Chart Jenis Pelanggaran
            const ctx2 = document.getElementById('facultySeverityChart').getContext('2d');
            const sevValues = {!! json_encode(array_values($severityCounts)) !!};

            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Ringan', 'Sedang', 'Berat', 'Suspek'],
                    datasets: [{
                        data: sevValues,
                        backgroundColor: ['#06b6d4', '#f59e0b', '#ef4444', '#64748b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    cutout: '65%'
                }
            });
        });
    </script>
</x-layout>
