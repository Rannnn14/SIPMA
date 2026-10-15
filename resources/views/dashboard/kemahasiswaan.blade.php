<x-layout>
    <x-slot:title>Dashboard Kemahasiswaan | SIPMA</x-slot:title>

    <!-- Top Header & Filter Bar -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                <i class="fas fa-chart-line text-primary mr-1"></i> Dashboard Kemahasiswaan
            </h1>
            <p class="text-muted small mb-0">Pusat Verifikasi Laporan Masuk & Analisis Data Pelanggaran Seluruh Kampus.</p>
        </div>

        <!-- Filter Semester & Tahun Akademik -->
        <form method="GET" action="{{ route('dashboard') }}" class="form-inline mt-3 mt-sm-0">
            <div class="form-group mr-2">
                <select name="academic_year" class="form-control form-control-sm shadow-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Tahun Akademik --</option>
                    <option value="2026/2027" {{ request('academic_year') === '2026/2027' ? 'selected' : '' }}>2026/2027</option>
                    <option value="2025/2026" {{ request('academic_year') === '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                </select>
            </div>
            <div class="form-group mr-2">
                <select name="semester" class="form-control form-control-sm shadow-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Semester --</option>
                    <option value="Ganjil" {{ request('semester') === 'Ganjil' ? 'selected' : '' }}>Semester Ganjil</option>
                    <option value="Genap" {{ request('semester') === 'Genap' ? 'selected' : '' }}>Semester Genap</option>
                </select>
            </div>
            @if(request('academic_year') || request('semester'))
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary font-weight-bold" title="Reset Filter">
                    <i class="fas fa-undo"></i>
                </a>
            @endif
        </form>
    </div>

    <!-- Alert / Notice for Pending Reports -->
    @if($pendingCount > 0)
    <div class="alert alert-warning border-left-warning shadow-sm d-flex align-items-center justify-content-between mb-4">
        <div>
            <i class="fas fa-bell text-warning fa-lg mr-2"></i>
            <strong>Terdapat {{ $pendingCount }} laporan baru</strong> yang masuk ke inbox dan membutuhkan verifikasi bukti serta koreksi data terlapor.
        </div>
        <a href="{{ route('reports.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning font-weight-bold text-dark">
            Buka Inbox Verifikasi <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    @endif

    <!-- Stat Cards Row -->
    <div class="row">
        <!-- TOTAL LAPORAN -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Laporan Masuk</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalReports }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-primary" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERLU VERIFIKASI -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Perlu Verifikasi Segera</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $pendingCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hourglass-half fa-2x text-warning" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DIKONFIRMASI KE KAJUR -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Dikonfirmasi ke Kajur</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $confirmedCount + $inProgressCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-share-square fa-2x text-info" style="opacity: 0.6;"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai Tindak Lanjut</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $completedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-success" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Visualisasi Grafik 1 & Grafik 2 -->
    <div class="row">
        <!-- Grafik 1 (Bar Chart): Perbandingan Total Pelanggaran antar-Jurusan -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar mr-1"></i> Grafik 1: Perbandingan Total Pelanggaran Antar-Jurusan
                    </h6>
                    <span class="badge badge-light border text-muted">Universal Bar Chart</span>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="position: relative; height: 320px;">
                        <canvas id="deptBarChart"></canvas>
                    </div>
                    <small class="text-muted d-block text-center mt-2">
                        *Menunjukkan jurusan dengan frekuensi pelanggaran terbanyak secara kumulatif.
                    </small>
                </div>
            </div>
        </div>

        <!-- Grafik 2 (Donut Chart): Jenis/Kategori Pelanggaran yang Paling Sering Terjadi -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie mr-1"></i> Grafik 2: Kategori Pelanggaran
                    </h6>
                    <span class="badge badge-light border text-muted">Donut Chart</span>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-2 pb-2" style="position: relative; height: 260px;">
                        <canvas id="severityDonutChart"></canvas>
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

    <!-- Quick Inbox Verifikasi Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-inbox text-warning mr-1"></i> Laporan Masuk Menunggu Verifikasi & Koreksi Data
            </h6>
            <a href="{{ route('reports.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-primary font-weight-bold">
                Buka Seluruh Inbox <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>No. Laporan</th>
                            <th>Tanggal Kejadian</th>
                            <th>Nama Terlapor (Manual)</th>
                            <th>NIM (Manual)</th>
                            <th>Jurusan Terlapor</th>
                            <th>Kategori</th>
                            <th>Lampiran</th>
                            <th class="text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingReports as $rep)
                        <tr>
                            <td class="font-weight-bold text-primary">{{ $rep->report_number }}</td>
                            <td>{{ $rep->incident_date ? $rep->incident_date->format('d M Y') : '-' }}</td>
                            <td class="font-weight-bold">{{ $rep->reported_name }}</td>
                            <td>{{ $rep->reported_nim }}</td>
                            <td><span class="badge badge-light border">{{ $rep->reported_department }}</span></td>
                            <td><span class="badge badge-info">{{ $rep->severity ?? 'Umum' }}</span></td>
                            <td>
                                @if($rep->evidence_path)
                                    <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Ada Bukti</span>
                                @else
                                    <span class="badge badge-secondary">Tanpa Berkas</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('reports.show', $rep->id) }}" class="btn btn-sm btn-primary font-weight-bold px-3">
                                    <i class="fas fa-user-check mr-1"></i> Verifikasi / Koreksi
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-check-circle text-success mr-1"></i> Semua laporan baru telah diverifikasi. Tidak ada pending task.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Chart 1: Bar Chart Antar-Jurusan
            const deptCtx = document.getElementById('deptBarChart').getContext('2d');
            const deptLabels = {!! json_encode(array_keys($deptCounts)) !!};
            const deptData = {!! json_encode(array_values($deptCounts)) !!};

            new Chart(deptCtx, {
                type: 'bar',
                data: {
                    labels: deptLabels,
                    datasets: [{
                        label: 'Total Kasus Pelanggaran',
                        data: deptData,
                        backgroundColor: '#4f46e5',
                        hoverBackgroundColor: '#4338ca',
                        borderRadius: 6,
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
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Chart 2: Donut Chart Kategori
            const sevCtx = document.getElementById('severityDonutChart').getContext('2d');
            const sevData = {!! json_encode(array_values($severityCounts)) !!};

            new Chart(sevCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Ringan', 'Sedang', 'Berat', 'Suspek'],
                    datasets: [{
                        data: sevData,
                        backgroundColor: ['#06b6d4', '#f59e0b', '#ef4444', '#64748b'],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</x-layout>
