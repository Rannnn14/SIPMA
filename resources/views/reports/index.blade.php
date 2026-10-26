<x-layout>
    <x-slot:title>
        @if(auth()->user()->isKajur())
            Data Laporan Pelanggaran | Jurusan {{ auth()->user()->department }} | SIPMA
        @elseif(auth()->user()->isKemahasiswaan())
            Inbox Verifikasi & Rekap Data Pelanggaran | SIPMA
        @else
            Rekapitulasi Pelanggaran Mahasiswa | SIPMA
        @endif
    </x-slot:title>

    @if(auth()->user()->isKajur())
    {{-- ── Banner Teal Kajur ── --}}
    <style>
        .kajur-banner { background: linear-gradient(135deg, #134e4a 0%, #0d9488 60%, #14b8a6 100%); }
        .kajur-ay-select { border: none; background: rgba(255,255,255,0.15); color: #fff;
            font-size: 0.85rem; font-weight: 600; padding: 4px 12px; border-radius: 20px; outline: none; cursor: pointer; }
        .kajur-ay-select option { color: #134e4a; background: #fff; }
    </style>
    <div class="card border-0 text-white shadow-sm mb-4 overflow-hidden kajur-banner">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0"
                         style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); font-size: 1.2rem;">
                        <i class="fas fa-clipboard-list text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold text-white mb-0">Data Laporan Pelanggaran Mahasiswa</h5>
                        <small class="text-white-50">
                            Jurusan {{ auth()->user()->department }} — Laporan terverifikasi dari Kemahasiswaan
                        </small>
                    </div>
                </div>
                {{-- Filter T.A. --}}
                <div class="mt-2 mt-md-0">
                    <form method="GET" action="{{ route('reports.index') }}" class="m-0" id="kajurIndexAyForm">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('severity'))
                            <input type="hidden" name="severity" value="{{ request('severity') }}">
                        @endif
                        <div class="d-flex align-items-center px-3 py-2 rounded-pill"
                             style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                            <i class="fas fa-calendar-alt text-white-50 mr-2" style="font-size: 0.82rem;"></i>
                            <select name="academic_year" class="kajur-ay-select"
                                    onchange="document.getElementById('kajurIndexAyForm').submit()">
                                <option value="all" style="color:#000;" {{ request('academic_year', 'all') === 'all' ? 'selected' : '' }}>
                                    Semua Periode
                                </option>
                                @foreach(\App\Models\AcademicYear::orderByDesc('is_active')->orderByDesc('year')->get() as $ay)
                                    <option value="{{ $ay->year }}" style="color:#000;"
                                        {{ request('academic_year') === $ay->year ? 'selected' : '' }}>
                                        T.A. {{ $ay->year }}{{ $ay->is_active ? ' (Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">
                @if(auth()->user()->isKemahasiswaan())
                    <i class="fas fa-inbox text-primary mr-1"></i> Inbox Verifikasi & Seluruh Laporan Kampus
                @else
                    <i class="fas fa-chart-bar text-primary mr-1"></i> Rekapitulasi Data Pelanggaran Mahasiswa
                @endif
            </h1>
            <p class="text-muted small mb-0">
                @if(auth()->user()->isKemahasiswaan())
                    Validasi bukti, koreksi data manual mahasiswa terlapor, dan teruskan ke Ketua Jurusan terkait.
                @else
                    Monitoring rekapitulasi data dan statistik penanganan pelanggaran (Hak Akses Read-Only).
                @endif
            </p>
        </div>
        <div class="mt-3 mt-sm-0 d-flex flex-wrap gap-2">
            @if(auth()->user()->isKemahasiswaan() || auth()->user()->isKajur())
            <a href="{{ route('reports.create') }}" class="btn btn-primary shadow-sm font-weight-bold mr-2">
                <i class="fas fa-plus mr-1"></i> Buat Laporan Manual
            </a>
            @endif
            @if(auth()->user()->isKemahasiswaan() || auth()->user()->isKajur() || auth()->user()->isDekan() || auth()->user()->isWarek())
            <a href="{{ route('reports.export.excel') }}" class="btn btn-success shadow-sm font-weight-bold mr-2">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </a>
            <a href="{{ route('reports.export.pdf') }}" target="_blank" class="btn btn-outline-danger shadow-sm font-weight-bold">
                <i class="fas fa-print mr-1"></i> Cetak / PDF
            </a>
            @endif
        </div>
    </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filter Bar -->
    <div class="card shadow-sm mb-4">
        <div class="card-body py-3 bg-light">
            <form method="GET" action="{{ route('reports.index') }}" class="row align-items-center">
                {{-- Pertahankan T.A. filter jika kajur --}}
                @if(auth()->user()->isKajur() && request('academic_year') && request('academic_year') !== 'all')
                    <input type="hidden" name="academic_year" value="{{ request('academic_year') }}">
                @endif

                <div class="col-md-4 mb-2 mb-md-0">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control form-control-sm"
                           placeholder="Cari No. Laporan / Nama / NIM / Lokasi...">
                </div>

                @if(!auth()->user()->isKajur())
                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="department" class="form-control form-control-sm">
                        <option value="">-- Semua Jurusan --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                                {{ $dept }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="col-md-3 mb-2 mb-md-0">
                    <select name="severity" class="form-control form-control-sm">
                        <option value="">-- Semua Kategori --</option>
                        <option value="Ringan" {{ request('severity') === 'Ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="Sedang" {{ request('severity') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="Berat" {{ request('severity') === 'Berat' ? 'selected' : '' }}>Berat</option>
                        <option value="Suspek" {{ request('severity') === 'Suspek' ? 'selected' : '' }}>Suspek</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm btn-block font-weight-bold">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-1"></i>
                @if(auth()->user()->isKajur())
                    Data Laporan Jurusan {{ auth()->user()->department }} ({{ $reports->total() }} Laporan)
                @else
                    Data Laporan Pelanggaran ({{ $reports->total() }} Kasus)
                @endif
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
                                    <span class="badge badge-light border text-muted ml-1" title="Terdapat lampiran bukti">
                                        <i class="fas fa-paperclip text-info"></i>
                                    </span>
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
                                        'Berat'  => 'badge-danger',
                                        'Sedang' => 'badge-warning text-dark',
                                        'Ringan' => 'badge-info',
                                        default  => 'badge-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $sevBadge }} px-2 py-1">
                                    {{ $report->severity ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="small">
                                <span class="{{ !auth()->user()->isKemahasiswaan() && $report->reporter_id !== auth()->id() ? 'text-muted font-italic' : 'font-weight-bold text-dark' }}">
                                    {{ $report->reporter_display_name }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $report->status_badge_class }} px-2 py-1" style="font-size:0.8rem;">
                                    {{ $report->status_label }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('reports.show', ['id' => $report->id, 'return_to' => request()->getRequestUri()]) }}"
                                   class="btn btn-sm btn-outline-primary font-weight-bold px-3">
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

