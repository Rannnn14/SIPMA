<x-layout>
    <x-slot:title>Dashboard Ketua Jurusan {{ $myDept }} | SIPMA</x-slot:title>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                <i class="fas fa-chalkboard-teacher text-info mr-1"></i> Dashboard Ketua Jurusan: {{ $myDept }}
            </h1>
            <p class="text-muted small mb-0">Tindak lanjut laporan mahasiswa terkonfirmasi khusus di lingkungan Jurusan {{ $myDept }}.</p>
        </div>
        <div class="mt-3 mt-sm-0">
            <span class="badge badge-info px-3 py-2 font-weight-bold" style="font-size: 0.85rem;">
                <i class="fas fa-building mr-1"></i> Wewenang: {{ $myDept }}
            </span>
        </div>
    </div>

    <!-- Kajur Stat Cards -->
    <div class="row">
        <!-- TOTAL KASUS JURUSAN SAYA -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Kasus Jurusan</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $totalKajurReports }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder-open fa-2x text-primary" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERLU TINDAK LANJUT SEGERA -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Perlu Tindak Lanjut</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $needsActionCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-warning" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEDANG DIPROSES -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sedang Diproses</div>
                            <div class="h2 mb-0 font-weight-bold text-gray-800">{{ $inProcessCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-info" style="opacity: 0.6;"></i>
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
                            <i class="fas fa-check-circle fa-2x text-success" style="opacity: 0.6;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Khusus Jurusan & Penjelasan -->
    <div class="row">
        <!-- Grafik Dominansi Pelanggaran di Jurusan Ini -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-chart-pie mr-1"></i> Grafik Pelanggaran Khusus Jurusan: {{ $myDept }}
                    </h6>
                    <span class="badge badge-light border text-muted">Internal Analyst Only</span>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 260px;">
                        <canvas id="kajurChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small text-muted">
                        *Grafik ini khusus merangkum pelanggaran di Jurusan {{ $myDept }} saja, menjaga isolasi data internal.
                    </div>
                </div>
            </div>
        </div>

        <!-- Panduan Tindak Lanjut Sanksi oleh Kajur -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow mb-4 h-100 border-left-info">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-gavel mr-1"></i> Standar Operasional Prosedur Sanksi Kajur</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Sebagai pimpinan jurusan, lakukan verifikasi faktual dan klarifikasi bersama mahasiswa bersangkutan dengan tahapan berikut:
                    </p>
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item px-0 py-2 border-0">
                            <strong>1. Pemanggilan Mahasiswa:</strong> Buka kasus berstatus <em>Dikonfirmasi</em> lalu ubah status menjadi <strong>Sedang Diproses</strong>.
                        </div>
                        <div class="list-group-item px-0 py-2 border-0">
                            <strong>2. Sidang / Mediasi Jurusan:</strong> Hadirkan dosen wali untuk klarifikasi kronologi dan bukti yang telah divalidasi Kemahasiswaan.
                        </div>
                        <div class="list-group-item px-0 py-2 border-0">
                            <strong>3. Penetapan Sanksi:</strong> Pilih sanksi (Teguran Lisan, SP 1/2/3, Skorsing, atau Pembinaan) dan tulis catatan berita acara.
                        </div>
                        <div class="list-group-item px-0 py-2 border-0">
                            <strong>4. Tutup Kasus:</strong> Ubah status menjadi <strong>Selesai Tindak Lanjut</strong> untuk finalisasi laporan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Kasus Masuk Jurusan Saya -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-list-alt text-info mr-1"></i> Kasus Masuk Terkonfirmasi (Jurusan {{ $myDept }})
            </h6>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-info font-weight-bold">
                Lihat Semua Kasus Jurusan <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th>No. Laporan</th>
                            <th>Tanggal Kejadian</th>
                            <th>Mahasiswa Terlapor</th>
                            <th>NIM</th>
                            <th>Kategori</th>
                            <th>Pelapor</th>
                            <th>Status Saat Ini</th>
                            <th class="text-center">Aksi Tindak Lanjut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCases as $case)
                        <tr>
                            <td class="font-weight-bold text-primary">{{ $case->report_number }}</td>
                            <td>{{ $case->incident_date ? $case->incident_date->format('d M Y') : '-' }}</td>
                            <td class="font-weight-bold text-dark">{{ $case->student_name }}</td>
                            <td>{{ $case->student_nim }}</td>
                            <td><span class="badge badge-info">{{ $case->severity ?? 'Umum' }}</span></td>
                            <td class="small text-muted font-italic">{{ $case->reporter_display_name }}</td>
                            <td>
                                <span class="badge {{ $case->status_badge_class }} px-2 py-1">
                                    {{ $case->status_label }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('reports.show', $case->id) }}" class="btn btn-sm btn-info font-weight-bold px-3">
                                    <i class="fas fa-gavel mr-1"></i> Proses / Sanksi
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada kasus aktif di Jurusan {{ $myDept }}.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Script Kajur -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('kajurChart').getContext('2d');
            const dataCounts = {!! json_encode(array_values($severityCounts)) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Ringan', 'Sedang', 'Berat', 'Suspek'],
                    datasets: [{
                        label: 'Jumlah Pelanggaran',
                        data: dataCounts,
                        backgroundColor: ['#06b6d4', '#f59e0b', '#ef4444', '#64748b'],
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
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
</x-layout>
