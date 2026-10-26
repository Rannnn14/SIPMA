<x-layout>
    <x-slot:title>Dashboard Ketua Jurusan | SIPMA</x-slot:title>

    <style>
        .sop-banner {
            background: linear-gradient(135deg, #134e4a 0%, #0d9488 60%, #14b8a6 100%);
            border-radius: 18px;
        }
        .notif-banner {
            background: linear-gradient(135deg, #92400e 0%, #b45309 100%);
            border-radius: 14px;
            color: #fff;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
            box-shadow: 0 4px 14px rgba(180, 83, 9, 0.3);
        }
        .academic-year-select {
            height: 38px;
            border-radius: 10px;
            border: none;
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0 12px;
            outline: none;
            cursor: pointer;
        }
        .academic-year-select option { color: #134e4a; background: #fff; }

        /* Modern Table */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        .modern-table thead th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            border-top: none;
            border-bottom: 1.5px solid #e2e8f0;
            padding: 14px 16px;
        }
        .modern-table tbody tr {
            transition: background-color 0.15s;
        }
        .modern-table tbody tr:hover {
            background-color: #f8fafc;
        }
        .modern-table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }
        .report-num-badge {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-weight: 700;
            color: #0f766e;
            background-color: #f0fdfa;
            border: 1px solid #ccfbf1;
            padding: 4px 10px;
            border-radius: 6px;
            white-space: nowrap;
            display: inline-block;
        }
    </style>

    {{-- ── Banner Teal (Judul + Filter T.A.) ── --}}
    <div class="card border-0 text-white shadow-sm mb-4 overflow-hidden sop-banner">
        <div class="card-body p-4">
            {{-- Row 1: Judul + Filter TA --}}
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 pb-2 border-bottom"
                 style="border-color: rgba(255,255,255,0.15) !important;">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0"
                         style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); font-size: 1.1rem;">
                        <i class="fas fa-route text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold text-white mb-0" style="letter-spacing: -0.2px;">
                            Alur Penanganan Pelanggaran Mahasiswa
                        </h5>
                        <small class="text-white-50">Jurusan {{ $myDept }} — Laporan Terverifikasi dari Kemahasiswaan</small>
                    </div>
                </div>
                {{-- Filter Tahun Akademik --}}
                <div class="d-flex align-items-center mt-2 mt-md-0" style="gap: 8px;">
                    <form method="GET" action="{{ route('kajur.dashboard') }}" class="m-0" id="kajurAyForm">
                        <div class="d-flex align-items-center px-3 py-1 rounded-pill"
                             style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                            <i class="fas fa-calendar-alt text-white-50 mr-2" style="font-size: 0.82rem;"></i>
                            <select name="academic_year" class="academic-year-select"
                                    onchange="document.getElementById('kajurAyForm').submit()">
                                <option value="all" style="color:#000;" {{ $selectedAcademicYear === 'all' ? 'selected' : '' }}>
                                    Semua Periode
                                </option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->year }}" style="color:#000;"
                                        {{ $selectedAcademicYear === $ay->year ? 'selected' : '' }}>
                                        T.A. {{ $ay->year }}{{ $ay->is_active ? ' (Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            {{-- 3 Langkah Alur (informasi) --}}
            <div class="row text-white">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <div class="d-flex align-items-center mb-1">
                            <span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2 flex-shrink-0"
                                  style="width: 26px; height: 26px; background: #fff; color: #0d9488; font-size: 0.78rem;">1</span>
                            <span class="font-weight-bold small text-white">Laporan Masuk</span>
                        </div>
                        <p class="mb-0 text-white-50" style="font-size: 0.78rem; line-height: 1.45;">
                            Mahasiswa atau Kemahasiswaan memasukkan data pelanggaran & bukti digital terlapor.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <div class="d-flex align-items-center mb-1">
                            <span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2 flex-shrink-0"
                                  style="width: 26px; height: 26px; background: #fff; color: #0d9488; font-size: 0.78rem;">2</span>
                            <span class="font-weight-bold small text-white">Verifikasi Kemahasiswaan</span>
                        </div>
                        <p class="mb-0 text-white-50" style="font-size: 0.78rem; line-height: 1.45;">
                            Bagian Kemahasiswaan memvalidasi identitas terlapor dan keaslian bukti yang diunggah.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <div class="d-flex align-items-center mb-1">
                            <span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2 flex-shrink-0"
                                  style="width: 26px; height: 26px; background: #fff; color: #0d9488; font-size: 0.78rem;">3</span>
                            <span class="font-weight-bold small text-white">Laporan ke Kajur</span>
                        </div>
                        <p class="mb-0 text-white-50" style="font-size: 0.78rem; line-height: 1.45;">
                            Laporan terverifikasi otomatis masuk ke akun Ketua Jurusan terkait untuk ditindaklanjuti.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifikasi diletakkan tepat setelah banner alur. --}}
    @if($hasUnreadReports)
    <div class="alert border-0 shadow-sm mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between p-3" style="background: #fffbeb; border-left: 5px solid #f59e0b !important; border-radius: 14px;">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px; background: #fef3c7; color: #b45309;">
                <i class="fas fa-bell"></i>
            </div>
            <div>
                <h6 class="font-weight-bold mb-0 text-dark">Ada {{ $newReportsCount }} laporan baru masuk ke Jurusan Anda!</h6>
                <small class="text-muted">Laporan terverifikasi dari Kemahasiswaan yang belum Anda buka.</small>
            </div>
        </div>
        <a href="{{ route('reports.history') }}" class="btn btn-sm font-weight-bold px-3 py-2 text-dark shadow-sm" style="background: #fde68a; border-radius: 8px;">
            Buka Data Laporan <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    @endif

    {{-- ── Stat Card: HANYA Total Laporan ── --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm text-white" style="border-radius:16px;background:linear-gradient(110deg,#0f766e 0%,#0d9488 58%,#14b8a6 100%);">
                <div class="card-body d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between px-4 py-3" style="min-height:142px;gap:10px;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width:48px;height:48px;background:rgba(255,255,255,.17);">
                            <i class="fas fa-folder-open" style="font-size:1.2rem;"></i>
                        </div>
                        <div>
                            <div class="text-white-50 text-uppercase font-weight-bold mb-1" style="font-size:.76rem;letter-spacing:.55px;">Total Laporan Jurusan</div>
                            <div class="font-weight-bold" style="font-size:2.35rem;line-height:1.05;">{{ $totalKajurReports }}</div>
                            <small class="text-white-50">{{ $selectedAcademicYear === 'all' ? 'Seluruh Periode' : 'T.A. ' . $selectedAcademicYear }}</small>
                        </div>
                    </div>
                    <span class="badge px-3 py-2" style="background:rgba(255,255,255,.16);color:#fff;border:1px solid rgba(255,255,255,.25);border-radius:20px;font-size:.72rem;letter-spacing:.35px;">{{ strtoupper($myDept) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Grafik Tingkat Pelanggaran Jurusan ── --}}
    <div class="row">
        <div class="col-xl-7 col-lg-7 mb-4">
            <div class="card shadow-sm mb-4 h-100">
                <div class="card-header py-3 d-flex align-items-center justify-content-between bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold" style="color: #0d9488;">
                        <i class="fas fa-chart-bar mr-1"></i> Sebaran Tingkat Pelanggaran — {{ $myDept }}
                    </h6>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 240px;">
                        <canvas id="kajurChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small text-muted">
                        *Grafik berdasarkan laporan terverifikasi di Jurusan {{ $myDept }}
                        @if($selectedAcademicYear !== 'all')
                            — T.A. {{ $selectedAcademicYear }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Jurusan --}}
        <div class="col-xl-5 col-lg-5 mb-4">
            <div class="card shadow-sm mb-4 h-100">
                <div class="card-header py-3 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-info-circle mr-1" style="color: #0d9488;"></i> Informasi Jurusan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column" style="gap: 14px;">
                        <div class="p-3 rounded" style="background: #f0fdfa; border: 1px solid #ccfbf1;">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #0f766e; letter-spacing: 0.5px;">Jurusan</div>
                            <div class="font-weight-bold text-dark">{{ $myDept }}</div>
                        </div>
                        <div class="p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #475569; letter-spacing: 0.5px;">Penanggung Jawab</div>
                            <div class="font-weight-bold text-dark">{{ auth()->user()->clean_name }}</div>
                        </div>
                        <div class="p-3 rounded" style="background: #fffbeb; border: 1px solid #fef3c7;">
                            <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #b45309; letter-spacing: 0.5px;">Periode Dipilih</div>
                            <div class="font-weight-bold text-dark">
                                {{ $selectedAcademicYear === 'all' ? 'Semua Periode' : 'T.A. ' . $selectedAcademicYear }}
                            </div>
                        </div>
                        <div class="d-flex flex-column mt-2" style="gap: 10px;">
                            <a href="{{ route('reports.index') }}"
                               class="btn font-weight-bold text-white px-4 py-2 text-center"
                               style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); border-radius: 10px; font-size: 0.88rem; box-shadow: 0 4px 10px rgba(15,118,110,0.25);">
                                <i class="fas fa-list mr-1"></i> Lihat Data Laporan
                            </a>
                            <a href="{{ route('reports.create') }}"
                               class="btn font-weight-bold text-white px-4 py-2 text-center"
                               style="background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 100%); border-radius: 10px; font-size: 0.88rem; box-shadow: 0 4px 10px rgba(29,78,216,0.25);">
                                <i class="fas fa-file-signature mr-1"></i> Buat Laporan Baru
                            </a>
                            <a href="{{ route('reports.export.excel') }}"
                               class="btn font-weight-bold text-white px-4 py-2 text-center"
                               style="background: linear-gradient(135deg, #166534 0%, #16a34a 100%); border-radius: 10px; font-size: 0.88rem; box-shadow: 0 4px 10px rgba(22,101,52,0.25);">
                                <i class="fas fa-file-excel mr-1"></i> Export Excel Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tabel 5 Kasus Terbaru ── --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-list-alt text-info mr-1"></i> Laporan Terbaru — Jurusan {{ $myDept }}
            </h6>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-info font-weight-bold">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th style="width: 170px;">NO. LAPORAN</th>
                            <th style="width: 130px;">TGL KEJADIAN</th>
                            <th>MAHASISWA TERLAPOR</th>
                            <th>PROGRAM STUDI</th>
                            <th style="width: 110px;" class="text-center">KATEGORI</th>
                            <th style="width: 180px;" class="text-center">STATUS</th>
                            <th style="width: 110px;" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCases as $case)
                            <tr>
                                <td>
                                    <span class="report-num-badge">
                                        {{ $case->report_number }}
                                    </span>
                                    @if($case->evidence_path)
                                        <span class="text-muted ml-1" title="Terdapat berkas bukti"><i class="fas fa-paperclip text-info" style="font-size: 0.8rem;"></i></span>
                                    @endif
                                </td>
                                <td class="text-muted" style="white-space: nowrap;">
                                    <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                    {{ $case->incident_date ? $case->incident_date->format('d M Y') : '-' }}
                                </td>
                                <td>
                                    <div class="font-weight-bold text-dark">{{ $case->student_name }}</div>
                                    <div class="text-xs text-muted font-weight-bold">NIM: {{ $case->student_nim }}</div>
                                </td>
                                <td>
                                    <span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-size: 0.82rem;">
                                        {{ $case->department_name }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $sevStyle = match($case->severity) {
                                            'Berat'  => 'background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2;',
                                            'Sedang' => 'background: #fffbeb; color: #b45309; border: 1px solid #fef3c7;',
                                            default  => 'background: #ecfdf5; color: #059669; border: 1px solid #d1fae5;',
                                        };
                                    @endphp
                                    <span class="badge px-2.5 py-1 font-weight-bold" style="{{ $sevStyle }} border-radius: 8px;">
                                        {{ $case->severity ?? 'Ringan' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if(in_array($case->status, ['pending', 'dilaporkan']))
                                        <span class="badge px-2.5 py-1.5 font-weight-bold" style="background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; border-radius: 8px; font-size: 0.78rem;">
                                            <i class="fas fa-hourglass-half mr-1"></i> Menunggu Verifikasi
                                        </span>
                                    @elseif($case->status === 'selesai')
                                        <span class="badge px-2.5 py-1.5 font-weight-bold" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; border-radius: 8px; font-size: 0.78rem;">
                                            <i class="fas fa-check-double mr-1"></i> Selesai
                                        </span>
                                    @elseif($case->status === 'diproses')
                                        <span class="badge px-2.5 py-1.5 font-weight-bold" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; border-radius: 8px; font-size: 0.78rem;">
                                            <i class="fas fa-spinner fa-spin mr-1"></i> Diproses
                                        </span>
                                    @else
                                        <span class="badge px-2.5 py-1.5 font-weight-bold" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; border-radius: 8px; font-size: 0.78rem;">
                                            <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('reports.show', ['id' => $case->id, 'return_to' => request()->getRequestUri()]) }}" class="btn btn-sm btn-outline-secondary font-weight-bold px-3 py-1.5" style="border-radius: 8px; font-size: 0.82rem;" title="Lihat Detail">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-clipboard-check fa-3x mb-2 text-muted" style="opacity: 0.35;"></i>
                                    <div class="font-weight-bold">Belum ada laporan terverifikasi di Jurusan {{ $myDept }}.</div>
                                    <small class="text-muted">Data akan otomatis terisi ketika laporan terverifikasi masuk ke jurusan ini.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Chart.js Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('kajurChart').getContext('2d');
            const dataCounts = {!! json_encode(array_values($severityCounts)) !!};
            const labels = ['Ringan', 'Sedang', 'Berat'];
            const colors = ['#10b981', '#f59e0b', '#ef4444'];

            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah Laporan',
                        data: dataCounts,
                        backgroundColor: colors,
                        borderColor: colors,
                        borderWidth: 1,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    scales: {
                        y: { beginAtZero: true, ticks: { stepSize: 1 } }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });

            // Klik chart untuk perbesar
            const kajurCanvas = document.getElementById('kajurChart');
            if (kajurCanvas) {
                kajurCanvas.style.cursor = 'pointer';
                kajurCanvas.addEventListener('click', function () {
                    if (typeof openChartModal === 'function') {
                        openChartModal(
                            'Sebaran Tingkat Pelanggaran — {{ $myDept }}',
                            'bar',
                            labels,
                            [{ label: 'Jumlah Laporan', data: dataCounts, backgroundColor: colors, borderRadius: 8 }],
                            { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }, plugins: { legend: { display: false } } }
                        );
                    }
                });
            }
        });
    </script>
</x-layout>

