<x-layout>
    <x-slot:title>Dashboard Mahasiswa (Pelapor) | SIPMA</x-slot:title>

    <!-- Hero Welcome Banner for Mahasiswa -->
    <div class="card border-0 text-white shadow-sm mb-4 rounded-lg overflow-hidden" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #14b8a6 100%);">
        <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div>
                <span class="badge badge-light font-weight-bold mb-2 px-3 py-1 text-uppercase" style="letter-spacing:0.5px; color:#0f766e;">
                    <i class="fas fa-user-shield mr-1"></i> Portal Pelaporan Mahasiswa
                </span>
                <h2 class="font-weight-bold mb-2 text-white">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <p class="text-white-50 mb-3" style="max-width: 650px;">
                    Sampaikan laporan pelanggaran tata tertib mahasiswa secara objektif. Identitas Anda dijamin aman dan <strong>dirahasiakan (Anonymous)</strong> dari pihak luar untuk menghindari intimidasi.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('reports.create') }}" class="btn btn-light btn-sm font-weight-bold px-3 rounded-pill text-dark shadow-sm mr-2">
                        <i class="fas fa-paper-plane mr-1" style="color:#0d9488;"></i> Buat Laporan Pelanggaran Manual
                    </a>
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-light btn-sm font-weight-bold px-3 rounded-pill">
                        <i class="fas fa-history mr-1"></i> Lihat Status Riwayat Laporan Saya
                    </a>
                </div>
            </div>
            <div class="d-none d-md-block text-center mt-3 mt-md-0">
                <i class="fas fa-user-graduate fa-5x text-white-50"></i>
            </div>
        </div>
    </div>

    <!-- Informasi & Panduan Pelaporan -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-left-info shadow-sm">
                <div class="card-body">
                    <h6 class="font-weight-bold text-info"><i class="fas fa-shield-alt mr-1"></i> 1. Kerahasiaan Terjamin</h6>
                    <p class="small text-muted mb-0">Nama pelapor hanya diketahui oleh Bagian Kemahasiswaan untuk verifikasi. Pada akun Kajur, Dekan, dan Warek, identitas dibuat anonim.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-left-warning shadow-sm">
                <div class="card-body">
                    <h6 class="font-weight-bold text-warning"><i class="fas fa-camera mr-1"></i> 2. Siapkan Bukti Valid</h6>
                    <p class="small text-muted mb-0">Lampirkan foto, berkas PDF dokumen, atau video singkat (maks. 10MB) agar laporan Anda dapat segera diverifikasi oleh Kemahasiswaan.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-left-success shadow-sm">
                <div class="card-body">
                    <h6 class="font-weight-bold text-success"><i class="fas fa-tasks mr-1"></i> 3. Pantau Alur Penanganan</h6>
                    <p class="small text-muted mb-0">Status laporan Anda akan diperbarui secara real-time: <em>Pending &rarr; Dikonfirmasi &rarr; Diproses &rarr; Selesai Sanksi</em>.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row">
        <!-- TOTAL LAPORAN -->
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Laporan</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $totalSubmitted }}</div>
                </div>
            </div>
        </div>

        <!-- MENUNGGU VERIFIKASI -->
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $pending }}</div>
                </div>
            </div>
        </div>

        <!-- DIKONFIRMASI KE KAJUR -->
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Dikonfirmasi</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $confirmed }}</div>
                </div>
            </div>
        </div>

        <!-- DALAM PENANGANAN -->
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-secondary">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Diproses Kajur</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $inProgress }}</div>
                </div>
            </div>
        </div>

        <!-- SELESAI -->
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $completed }}</div>
                </div>
            </div>
        </div>

        <!-- DITOLAK -->
        <div class="col-xl-2 col-md-4 col-6 mb-4">
            <div class="card shadow-sm h-100 py-2 border-left-danger">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ditolak</div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $rejected }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Pelaporan Saya -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-list-alt text-primary mr-1"></i> Ringkasan Status Laporan Terbaru Saya
            </h6>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-primary font-weight-bold">
                Lihat Selengkapnya <i class="fas fa-arrow-right ml-1"></i>
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
                            <th>Jurusan Terlapor</th>
                            <th>Kategori</th>
                            <th>Status Laporan</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentReports as $rep)
                        <tr>
                            <td class="font-weight-bold">{{ $rep->report_number }}</td>
                            <td>{{ $rep->incident_date ? $rep->incident_date->format('d M Y') : '-' }}</td>
                            <td>{{ $rep->student_name }} ({{ $rep->student_nim }})</td>
                            <td><span class="badge badge-light border">{{ $rep->reported_department }}</span></td>
                            <td><span class="badge badge-info">{{ $rep->severity ?? 'Umum' }}</span></td>
                            <td>
                                <span class="badge {{ $rep->status_badge_class }} px-2 py-1">
                                    {{ $rep->status_label }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('reports.show', $rep->id) }}" class="btn btn-sm btn-outline-primary font-weight-bold">
                                    Detail <i class="fas fa-chevron-right ml-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Anda belum memiliki laporan. Klik tombol <strong>"Buat Laporan Pelanggaran Manual"</strong> untuk mengirim laporan baru.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
