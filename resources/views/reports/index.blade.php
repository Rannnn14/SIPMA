<x-layout>
    <x-slot:title>
        @if(auth()->user()->isMahasiswa())
            Riwayat Pelaporan Saya | SIPMA
        @elseif(auth()->user()->isKemahasiswaan())
            Inbox Verifikasi & Rekap Data Pelanggaran | SIPMA
        @elseif(auth()->user()->isKajur())
            Kasus Masuk Jurusan {{ auth()->user()->department }} | SIPMA
        @else
            Rekapitulasi Pelanggaran Mahasiswa | SIPMA
        @endif
    </x-slot:title>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                @if(auth()->user()->isMahasiswa())
                    <i class="fas fa-clipboard-list text-primary mr-1"></i> Riwayat Pelaporan Saya
                @elseif(auth()->user()->isKemahasiswaan())
                    <i class="fas fa-inbox text-primary mr-1"></i> Inbox Verifikasi & Seluruh Laporan Kampus
                @elseif(auth()->user()->isKajur())
                    <i class="fas fa-briefcase text-primary mr-1"></i> Kasus Terkonfirmasi Jurusan: {{ auth()->user()->department }}
                @else
                    <i class="fas fa-chart-bar text-primary mr-1"></i> Rekapitulasi Data Pelanggaran Mahasiswa
                @endif
            </h1>
            <p class="text-muted small mb-0">
                @if(auth()->user()->isMahasiswa())
                    Pantau tahapan verifikasi dan tindak lanjut laporan yang telah Anda ajukan.
                @elseif(auth()->user()->isKemahasiswaan())
                    Validasi bukti, koreksi data manual mahasiswa terlapor, dan teruskan ke Ketua Jurusan terkait.
                @elseif(auth()->user()->isKajur())
                    Daftar laporan terkonfirmasi dari Kemahasiswaan yang siap ditindaklanjuti dan diberikan sanksi.
                @else
                    Monitoring rekapitulasi data dan statistik penanganan pelanggaran (Hak Akses Read-Only).
                @endif
            </p>
        </div>

        <div class="mt-3 mt-sm-0 d-flex flex-wrap gap-2">
            @if(auth()->user()->isMahasiswa() || auth()->user()->isKemahasiswaan())
            <a href="{{ route('reports.create') }}" class="btn btn-primary shadow-sm font-weight-bold mr-2">
                <i class="fas fa-plus mr-1"></i> Buat Laporan Manual
            </a>
            @endif

            @if(auth()->user()->isKemahasiswaan() || auth()->user()->isDekan() || auth()->user()->isWarek())
            <a href="{{ route('reports.export.excel') }}" class="btn btn-success shadow-sm font-weight-bold mr-2">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="{{ route('reports.export.pdf') }}" target="_blank" class="btn btn-outline-danger shadow-sm font-weight-bold">
                <i class="fas fa-print mr-1"></i> Cetak / PDF
            </a>
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filter Bar for Kemahasiswaan, Kajur, Dekan, Warek -->
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3 bg-light">
            <form method="GET" action="{{ route('reports.index') }}" class="row align-items-center">
                <div class="col-md-3 mb-2 mb-md-0">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari No. Laporan / Nama / Lokasi...">
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu Verifikasi)</option>
                        <option value="dikonfirmasi" {{ request('status') === 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi (Ke Kajur)</option>
                        <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Dalam Penanganan Kajur</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai Tindak Lanjut</option>
                        <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak Kemahasiswaan</option>
                    </select>
                </div>

                @if(!auth()->user()->isKajur())
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="department" class="form-control form-control-sm">
                        <option value="">-- Semua Jurusan --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-2 mb-2 mb-md-0">
                    <select name="severity" class="form-control form-control-sm">
                        <option value="">-- Kategori --</option>
                        <option value="Ringan" {{ request('severity') === 'Ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="Sedang" {{ request('severity') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Berat" {{ request('severity') === 'Berat' ? 'selected' : '' }}>Berat</option>
                        <option value="Suspek" {{ request('severity') === 'Suspek' ? 'selected' : '' }}>Suspek</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-sm btn-block font-weight-bold" title="Terapkan Filter">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-1"></i> Data Laporan Pelanggaran ({{ $reports->total() }} Kasus)
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th class="py-3">No. Laporan</th>
                            <th class="py-3">Tgl Kejadian</th>
                            <th class="py-3">Mahasiswa Terlapor</th>
                            <th class="py-3">Jurusan Terkait</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3">Pelapor</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td class="font-weight-bold text-gray-900">
                                {{ $report->report_number }}
                                @if($report->evidence_path)
                                    <span class="badge badge-light border text-muted ml-1" title="Terdapat lampiran bukti"><i class="fas fa-paperclip text-info"></i></span>
                                @endif
                            </td>
                            <td class="small">
                                {{ $report->incident_date ? $report->incident_date->format('d M Y') : '-' }}
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $report->student_name }}</div>
                                <div class="small text-muted">NIM: {{ $report->student_nim }}</div>
                            </td>
                            <td>
                                <span class="badge badge-light border text-dark font-weight-bold">
                                    {{ $report->department_name }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $sevBadge = match($report->severity) {
                                        'Berat' => 'badge-danger',
                                        'Sedang' => 'badge-warning text-dark',
                                        'Ringan' => 'badge-info',
                                        default => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $sevBadge }} px-2 py-1">
                                    {{ $report->severity ?? 'Umum' }}
                                </span>
                                @if($report->points > 0)
                                    <div class="small text-muted font-weight-bold mt-1">{{ $report->points }} Poin</div>
                                @endif
                            </td>
                            <td class="small">
                                <!-- Anonimitas Pelapor: Terlindungi untuk Kajur, Dekan, Warek -->
                                <span class="{{ !auth()->user()->isKemahasiswaan() && $report->reporter_id !== auth()->id() ? 'text-muted font-italic' : 'font-weight-bold text-dark' }}">
                                    {{ $report->reporter_display_name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $report->status_badge_class }} px-2 py-1" style="font-size:0.8rem;">
                                    {{ $report->status_label }}
                                </span>
                                @if($report->status === 'ditolak' && $report->rejection_reason)
                                    <div class="small text-danger mt-1 font-italic" style="max-width: 180px; font-size: 0.75rem;">
                                        Alasan: {{ \Illuminate\Support\Str::limit($report->rejection_reason, 40) }}
                                    </div>
                                @endif
                                @if($report->sanction_type)
                                    <div class="small text-success mt-1 font-weight-bold" style="font-size: 0.75rem;">
                                        Sanksi: {{ $report->sanction_type }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary font-weight-bold px-3">
                                    Detail <i class="fas fa-chevron-right ml-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-3x text-gray-300 mb-3 d-block"></i>
                                Tidak ada data laporan yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $reports->links() }}
            </div>
            @endif
        </div>
    </div>
</x-layout>
