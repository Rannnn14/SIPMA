<x-layout>
    <x-slot:title>Dashboard Eksekutif Wakil Rektor | SIPMA</x-slot:title>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                <i class="fas fa-award text-dark mr-1"></i> Dashboard Eksekutif Wakil Rektor
            </h1>
            <p class="text-muted small mb-0">Pemantauan & Evaluasi Kebijakan Kedisiplinan Mahasiswa Tingkat Universitas (Read-Only).</p>
        </div>

        <!-- Tombol Export Rekap Data PDF & Excel -->
        <div class="mt-3 mt-sm-0">
            <a href="{{ route('reports.export.excel') }}" class="btn btn-sm btn-success font-weight-bold shadow-sm mr-2">
                <i class="fas fa-file-excel mr-1"></i> Ekspor Excel (CSV)
            </a>
            <a href="{{ route('reports.export.pdf') }}" target="_blank" class="btn btn-sm btn-danger font-weight-bold shadow-sm">
                <i class="fas fa-print mr-1"></i> Cetak Dokumen Rapat (PDF)
            </a>
        </div>
    </div>

    <!-- Warek Stat Cards -->
    <div class="row">
        <!-- TOTAL PELANGGARAN KAMPUS -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-dark">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Total Kasus Universitas</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalReports }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-university fa-2x text-dark" style="opacity: 0.6;"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kasus Telah Tuntas</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $completedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-success" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DALAM PROSES -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sedang Dalam Penanganan</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $inProgressCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-sync-alt fa-2x text-info" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERSENTASE TUNTAS -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Efektivitas Penyelesaian</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $resolutionRate }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-primary" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Universitas: Ranking Jurusan & Tren Pelanggaran -->
    <div class="row">
        <!-- Grafik 1: Urutan / Ranking Jurusan dengan Pelanggaran Tertinggi -->
        <div class="col-xl-7 col-lg-7 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-trophy mr-1 text-warning"></i> Ranking Jurusan Berdasarkan Tingkat Pelanggaran
                    </h6>
                    <span class="badge badge-light border text-muted">Seluruh Universitas</span>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="rankingChart"></canvas>
                    </div>
                    <small class="text-muted d-block text-center mt-2">
                        *Urutan jurusan dari tingkat pelanggaran tertinggi secara menyeluruh di tingkat universitas.
                    </small>
                </div>
            </div>
        </div>

        <!-- Grafik 2: Tren Pelanggaran per Bulan/Semester -->
        <div class="col-xl-5 col-lg-5 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-chart-line mr-1 text-primary"></i> Grafik Tren Pelanggaran Bulanan
                    </h6>
                    <span class="badge badge-light border text-muted">6 Bulan Terakhir</span>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 260px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                    <small class="text-muted d-block text-center mt-2">
                        *Menunjukkan dinamika tren naik/turun kasus untuk evaluasi efektivitas aturan kampus.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Cepat & Tombol Aksi Evaluasi -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-table mr-1 text-dark"></i> Rekapitulasi Pelanggaran Universitas untuk Bahan Rapat Evaluasi
            </h6>
            <div class="d-flex">
                <a href="{{ route('reports.export.excel') }}" class="btn btn-sm btn-outline-success font-weight-bold mr-2">
                    <i class="fas fa-download mr-1"></i> Unduh Rekap Data (.csv)
                </a>
                <a href="{{ route('reports.export.pdf') }}" target="_blank" class="btn btn-sm btn-outline-danger font-weight-bold">
                    <i class="fas fa-file-pdf mr-1"></i> Cetak Laporan Formal
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>Peringkat</th>
                            <th>Nama Program Studi / Jurusan</th>
                            <th>Total Kasus</th>
                            <th>Status Tindak Lanjut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $rank = 1; @endphp
                        @foreach($deptCounts as $deptName => $cnt)
                        <tr>
                            <td class="font-weight-bold">#{{ $rank++ }}</td>
                            <td class="font-weight-bold text-dark">{{ $deptName }}</td>
                            <td>
                                <span class="badge {{ $cnt > 2 ? 'badge-danger' : ($cnt > 0 ? 'badge-warning text-dark' : 'badge-light border') }} px-3 py-1 font-weight-bold">
                                    {{ $cnt }} Kejadian
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('reports.index', ['department' => $deptName]) }}" class="btn btn-xs btn-outline-primary font-weight-bold">
                                    Lihat Detail Kasus <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts Warek -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Ranking Jurusan (Horizontal Bar)
            const rankCtx = document.getElementById('rankingChart').getContext('2d');
            const rankLabels = {!! json_encode(array_keys($deptCounts)) !!};
            const rankValues = {!! json_encode(array_values($deptCounts)) !!};

            new Chart(rankCtx, {
                type: 'bar',
                data: {
                    labels: rankLabels,
                    datasets: [{
                        label: 'Total Pelanggaran',
                        data: rankValues,
                        backgroundColor: '#1e1b4b',
                        hoverBackgroundColor: '#312e81',
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // Chart 2: Tren Bulanan (Line Chart)
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            const trendLabels = {!! json_encode(array_keys($monthlyTrend)) !!};
            const trendValues = {!! json_encode(array_values($monthlyTrend)) !!};

            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Tren Pelanggaran',
                        data: trendValues,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#4f46e5',
                        pointRadius: 5
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
        });
    </script>
</x-layout>
