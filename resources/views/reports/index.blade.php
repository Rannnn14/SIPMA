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
        .kajur-reports-table { border-collapse: separate; border-spacing: 0; color: #334155; }
        .kajur-reports-table thead th { background: #f8fafc; color: #334155; font-size: .82rem; font-weight: 700;
            letter-spacing: .35px; border-top: 0; border-bottom: 1.5px solid #e2e8f0; padding: 14px 16px; }
        .kajur-reports-table tbody tr { background: #fff; transition: background-color .15s ease; }
        .kajur-reports-table tbody tr:hover { background: #f8fafc !important; }
        .kajur-reports-table tbody td { padding: 14px 16px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
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
                            Jurusan {{ auth()->user()->department }} — Laporan terverifikasi dari Kemahasiswaan · {{ $selectedAcademicYear === 'all' ? 'Semua TA' : 'T.A. ' . $selectedAcademicYear }}
                        </small>
                    </div>
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
                    Validasi bukti dan koreksi data mahasiswa terlapor.
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
            <a href="{{ route('reports.export.excel', ['academic_year' => $selectedAcademicYear]) }}" class="btn btn-success shadow-sm font-weight-bold mr-2 mobile-hide-export">
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
                    Data Laporan Jurusan {{ auth()->user()->department }} ({{ $reports->count() }} Laporan)
                @else
                    Data Laporan Pelanggaran ({{ $reports->count() }} Kasus)
                @endif
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive report-list-scroll">
                <table class="table table-hover align-middle mb-0 {{ auth()->user()->isKajur() ? 'kajur-reports-table' : '' }}" width="100%" cellspacing="0">
                    <thead class="bg-light text-dark">
                        <tr>
                            <th class="py-3">No. Laporan</th>
                            <th class="py-3">Tgl Kejadian</th>
                            <th class="py-3">Mahasiswa Terlapor</th>
                            <th class="py-3">Jurusan Terkait</th>
                            <th class="py-3">Kategori</th>
                            @unless(auth()->user()->isKajur())
                            <th class="py-3">Pelapor</th>
                            @endunless
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
                                    $kajurSeverityStyle = match($report->severity) {
                                        'Berat' => 'background:#fef2f2;color:#dc2626;border:1px solid #fee2e2;',
                                        'Sedang' => 'background:#fffbeb;color:#b45309;border:1px solid #fef3c7;',
                                        default => 'background:#ecfdf5;color:#059669;border:1px solid #d1fae5;',
                                    };
                                @endphp
                                <span class="badge {{ auth()->user()->isKajur() ? '' : $sevBadge }} px-2 py-1" @if(auth()->user()->isKajur()) style="{{ $kajurSeverityStyle }} border-radius:8px;padding:4px 10px !important;" @endif>
                                    {{ $report->severity ?? 'Umum' }}
                                </span>
                            </td>
                            @unless(auth()->user()->isKajur())
                            <td class="small">
                                <span class="{{ !auth()->user()->isKemahasiswaan() && $report->reporter_id !== auth()->id() ? 'text-muted font-italic' : 'font-weight-bold text-dark' }}">
                                    {{ $report->reporter_display_name }}
                                </span>
                            </td>
                            @endunless
                            <td>
                                <span class="report-status-badge {{ $report->status_badge_class }}">
                                    <span class="report-status-icon"><i class="fas {{ $report->status === 'ditolak' ? 'fa-times' : 'fa-check' }}"></i></span>
                                    {{ $report->status_label }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('reports.show', ['id' => $report->id, 'return_to' => request()->getRequestUri()]) }}"
                                   class="btn btn-sm {{ auth()->user()->isKajur() ? 'btn-outline-secondary' : 'btn-outline-primary' }} font-weight-bold px-3">
                                    Detail <i class="fas fa-chevron-right ml-1"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isKajur() ? 7 : 8 }}" class="text-center text-muted py-5">
                                <i class="fas fa-folder-open fa-3x text-gray-300 mb-3 d-block"></i>
                                Tidak ada data laporan yang sesuai kriteria pencarian.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        (() => {
            const key = 'sipma-last-list:' + location.pathname;
            if (new URLSearchParams(location.search).has('restore_position')) {
                const saved = JSON.parse(sessionStorage.getItem(key) || 'null');
                if (saved?.url) history.replaceState(null, '', saved.url);
                requestAnimationFrame(() => {
                    const list = document.querySelector('.report-list-scroll');
                    if (list && saved?.listScrollTop) list.scrollTop = saved.listScrollTop;
                    else if (saved?.scrollY) window.scrollTo(0, saved.scrollY);
                });
                sessionStorage.removeItem(key);
                return;
            }
            document.querySelectorAll('a[href*="/laporan/"]').forEach(link => {
                if (!link.href.includes('return_to=')) return;
                link.addEventListener('click', () => {
                    const list = document.querySelector('.report-list-scroll');
                    sessionStorage.setItem(key, JSON.stringify({url: location.href, scrollY: window.scrollY, listScrollTop: list?.scrollTop || 0}));
                });
            });
        })();
    </script>
</x-layout>



