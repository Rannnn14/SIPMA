<x-layout>
    <x-slot:title>Dashboard Kemahasiswaan | SIPMA</x-slot:title>

    <style>
        /* Modern Design System - Styled consistent with student portal */
        .feature-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.25s ease-in-out;
        }
        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        .section-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 8px 20px -6px rgba(0, 0, 0, 0.04);
        }

        .metric-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Modern Filter Controls */
        .academic-year-select {
            height: 42px;
            border-radius: 10px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
            background-color: #ffffff;
            padding: 0 16px;
            transition: all 0.2s;
        }
        .academic-year-select:focus {
            border-color: #0f766e;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
            outline: none;
        }

        .btn-teal-primary {
            background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 9px 18px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 10px rgba(15, 118, 110, 0.2);
        }
        .btn-teal-primary:hover {
            background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(15, 118, 110, 0.3);
        }

        /* Modern Subtle Badges */
        .badge-subtle-teal {
            background-color: #f0fdfa;
            color: #0f766e;
            border: 1px solid #ccfbf1;
            font-weight: 700;
            border-radius: 8px;
            padding: 4px 10px;
        }
        .badge-subtle-amber {
            background-color: #fffbeb;
            color: #b45309;
            border: 1px solid #fef3c7;
            font-weight: 700;
            border-radius: 8px;
            padding: 4px 10px;
        }
        .badge-subtle-rose {
            background-color: #fff1f2;
            color: #e11d48;
            border: 1px solid #ffe4e6;
            font-weight: 700;
            border-radius: 8px;
            padding: 4px 10px;
        }
        .badge-subtle-blue {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #dbeafe;
            font-weight: 700;
            border-radius: 8px;
            padding: 4px 10px;
        }
        .badge-subtle-gray {
            background-color: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
            font-weight: 700;
            border-radius: 8px;
            padding: 4px 10px;
        }

        /* Modern Nav Tabs for Faculty */
        .faculty-nav-tabs {
            border-bottom: 2px solid #f1f5f9;
            gap: 8px;
        }
        .faculty-nav-tabs .nav-link {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            color: #475569;
            font-weight: 600;
            font-size: 0.92rem;
            padding: 10px 18px;
            background: #ffffff;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .faculty-nav-tabs .nav-link:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            color: #0f766e;
        }
        .faculty-nav-tabs .nav-link.active {
            background: #0f766e;
            color: #ffffff;
            border-color: #0f766e;
            box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
        }
        .faculty-nav-tabs .nav-link.active .badge-count {
            background-color: #ffffff;
            color: #0f766e;
        }
        .faculty-nav-tabs .nav-link .badge-count {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 700;
        }

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
        .report-list-scroll { max-height: 570px; overflow-y: auto; }
        .report-list-scroll thead th { position: sticky; top: 0; z-index: 2; }
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

    <!-- Top Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 border-0" role="alert" style="border-radius: 14px; background: #ecfdf5; border-left: 5px solid #10b981 !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-lg text-success mr-3"></i>
                <div class="text-dark">
                    <span class="font-weight-bold">{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 border-0" role="alert" style="border-radius: 14px; border-left: 5px solid #ef4444 !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-lg text-danger mr-3"></i>
                <div class="text-dark">
                    @foreach($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="modal fade" id="modalManageAcademicYears" tabindex="-1" role="dialog" aria-labelledby="manageAcademicYearsTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow" style="border-radius: 14px;">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="manageAcademicYearsTitle"><i class="fas fa-calendar-alt text-primary mr-2"></i>Kelola Tahun Akademik</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kemahasiswaan.academic-years.store') }}" method="POST" class="p-3 mb-3 rounded border" style="background:#f8fafc;">
                        @csrf
                        <label class="font-weight-bold small mb-2">Tambah Tahun Akademik Baru</label>
                        <div class="form-row align-items-center">
                            <div class="col-sm-8 mb-2 mb-sm-0">
                                <input type="text" name="year" class="form-control" placeholder="Contoh: {{ $suggestedNextYear }}" value="{{ old('year', $suggestedNextYear) }}" required pattern="\d{4}/\d{4}" title="Format YYYY/YYYY">
                            </div>
                            <div class="col-sm-4">
                                <button type="submit" class="btn btn-teal-primary btn-block"><i class="fas fa-plus-circle mr-1"></i>Tambah & Aktifkan</button>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">Tahun akademik baru langsung menjadi periode aktif untuk laporan berikutnya.</small>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light"><tr><th>Tahun Akademik</th><th>Status</th><th class="text-right">Aksi</th></tr></thead>
                            <tbody>
                                @foreach($academicYears as $ay)
                                    <tr>
                                        <td class="align-middle">
                                            <form method="POST" action="{{ route('kemahasiswaan.academic-years.update', $ay) }}" class="d-flex align-items-center" style="gap:8px;">
                                                @csrf @method('PUT')
                                                <input name="year" value="{{ $ay->year }}" class="form-control form-control-sm" required pattern="\\d{4}/\\d{4}" title="Format YYYY/YYYY" style="max-width:150px;">
                                                <button class="btn btn-sm btn-outline-primary" title="Simpan perubahan"><i class="fas fa-save"></i></button>
                                            </form>
                                        </td>
                                        <td class="align-middle">
                                            @if($ay->is_active)<span class="badge badge-success">Aktif</span>@else<span class="badge badge-secondary">Arsip</span>@endif
                                        </td>
                                        <td class="text-right align-middle">
                                            <form method="POST" action="{{ route('kemahasiswaan.academic-years.destroy', $ay) }}" class="d-inline" onsubmit="return confirm('Hapus Tahun Akademik {{ $ay->year }}? Tahun yang masih dipakai laporan tidak dapat dihapus.');">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button></div>
            </div>
        </div>
    </div>

    <!-- ── Banner Alur SOP (Teal) + Filter Tahun Akademik ── -->
    <div class="card border-0 text-white shadow-sm mb-4 overflow-hidden sop-banner">
        <div class="card-body p-4">
            <!-- Row 1: Judul + Filter TA -->
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.15) !important;">
                <div class="d-flex align-items-center mb-2 mb-md-0">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 42px; height: 42px; background: rgba(255,255,255,0.2); font-size: 1.1rem;">
                        <i class="fas fa-route text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold text-white mb-0" style="letter-spacing: -0.2px;">Alur Penanganan Pelanggaran Mahasiswa</h5>
                        <small class="text-white-50">Tahapan resmi verifikasi dan penyelesaian laporan tata tertib mahasiswa di kampus</small>
                    </div>
                </div>
                <!-- Filter Tahun Akademik (moved here, below banner title) -->
                <div class="d-flex align-items-center flex-wrap mt-2 mt-md-0" style="gap: 8px;">
                    <form method="GET" action="{{ route('kemahasiswaan.dashboard') }}" class="m-0" id="academicYearForm">
                        <div class="d-flex align-items-center px-3 py-1 rounded-pill" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                            <i class="fas fa-calendar-alt text-white-50 mr-2" style="font-size: 0.82rem;"></i>
                            <select name="academic_year" class="border-0 bg-transparent text-white font-weight-bold" style="font-size: 0.85rem; outline: none; cursor: pointer;" onchange="document.getElementById('academicYearForm').submit()">
                                <option value="all" style="color:#000;" {{ $selectedAcademicYear === 'all' ? 'selected' : '' }}>Semua Periode</option>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->year }}" style="color:#000;" {{ $selectedAcademicYear === $ay->year ? 'selected' : '' }}>
                                        T.A. {{ $ay->year }}{{ $ay->is_active ? ' (Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    <button type="button" class="btn btn-sm font-weight-bold rounded-pill px-3 py-2 d-flex align-items-center" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color:#fff; font-size: 0.82rem;" data-toggle="modal" data-target="#modalManageAcademicYears">
                        <i class="fas fa-calendar-alt mr-1"></i> Tahun Akademik
                    </button>
                </div>
            </div>

            <!-- 3 Langkah Alur -->
            <div class="row text-white">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <div class="d-flex align-items-center mb-1">
                            <span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2 flex-shrink-0" style="width: 26px; height: 26px; background: #fff; color: #0d9488; font-size: 0.78rem;">1</span>
                            <span class="font-weight-bold small text-white">Laporan Masuk</span>
                        </div>
                        <p class="mb-0 text-white-50" style="font-size: 0.78rem; line-height: 1.45;">Mahasiswa atau Kemahasiswaan memasukkan data pelanggaran & bukti digital terlapor.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <div class="d-flex align-items-center mb-1">
                            <span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2 flex-shrink-0" style="width: 26px; height: 26px; background: #fff; color: #0d9488; font-size: 0.78rem;">2</span>
                            <span class="font-weight-bold small text-white">Verifikasi Data</span>
                        </div>
                        <p class="mb-0 text-white-50" style="font-size: 0.78rem; line-height: 1.45;">Bagian Kemahasiswaan memvalidasi identitas terlapor dan keaslian bukti yang diunggah.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded h-100" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15);">
                        <div class="d-flex align-items-center mb-1">
                            <span class="rounded-circle font-weight-bold d-inline-flex align-items-center justify-content-center mr-2 flex-shrink-0" style="width: 26px; height: 26px; background: #fff; color: #0d9488; font-size: 0.78rem;">3</span>
                            <span class="font-weight-bold small text-white">Laporan ke Kajur</span>
                        </div>
                        <p class="mb-0 text-white-50" style="font-size: 0.78rem; line-height: 1.45;">Laporan yang terverifikasi otomatis masuk ke akun Ketua Jurusan terkait untuk ditindaklanjuti.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Menunggu Verifikasi Segera (jika ada) -->
    @if($pendingCount > 0)
        <div class="alert border-0 shadow-sm mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between p-3" style="background: #fffbeb; border-left: 5px solid #f59e0b !important; border-radius: 14px;">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px; background: #fef3c7; color: #b45309;">
                    <i class="fas fa-bell fa-lg"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold mb-0 text-dark">Terdapat {{ $pendingCount }} Laporan Baru Belum Diverifikasi</h6>
                    <small class="text-muted">Periksa kesesuaian identitas terlapor dan bukti digital sebelum diteruskan ke pimpinan terkait.</small>
                </div>
            </div>
            <a href="{{ route('reports.inbox') }}" class="btn btn-sm font-weight-bold px-3 py-2 text-dark shadow-sm" style="background: #fde68a; border-radius: 8px;">
                Buka Inbox Verifikasi <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    @endif

    <!-- 3 KPI Stat Cards Row (Sederhana & Sesuai Permintaan) -->
    <div class="row mb-4">
        <!-- 1. Total Laporan -->
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="card feature-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Total Laporan</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-900">{{ $totalReports }}</div>
                        <small class="text-muted">
                            {{ $selectedAcademicYear === 'all' ? 'Seluruh Periode' : 'T.A. ' . $selectedAcademicYear }}
                        </small>
                    </div>
                    <div class="metric-icon-box" style="background: #eff6ff; color: #2563eb;">
                        <i class="fas fa-folder-open fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Menunggu Verifikasi -->
        <div class="col-md-4 mb-3 mb-md-0">
            <a href="{{ route('reports.inbox') }}" class="text-decoration-none">
                <div class="card feature-card h-100 p-3" style="transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Menunggu Verifikasi</div>
                            <div class="h3 mb-0 font-weight-bold text-amber-600" style="color: #d97706;">{{ $pendingCount }}</div>
                            <small class="text-muted">Buka Inbox Verifikasi <i class="fas fa-arrow-right ml-1"></i></small>
                        </div>
                        <div class="metric-icon-box" style="background: #fffbeb; color: #d97706;">
                            <i class="fas fa-hourglass-half fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- 3. Terverifikasi (Riwayat Laporan) -->
        <div class="col-md-4">
            <a href="{{ route('reports.history') }}" class="text-decoration-none">
                <div class="card feature-card h-100 p-3" style="transition: transform 0.2s, box-shadow 0.2s;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Riwayat Terverifikasi</div>
                            <div class="h3 mb-0 font-weight-bold text-emerald-600" style="color: #059669;">{{ $verifiedCount }}</div>
                            <small class="text-muted">Lihat Riwayat Laporan <i class="fas fa-arrow-right ml-1"></i></small>
                        </div>
                        <div class="metric-icon-box" style="background: #ecfdf5; color: #059669;">
                            <i class="fas fa-history fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Charts Row: 1. Grafik Pelanggaran Antar-Fakultas & 2. Donut Proporsi Kategori -->
    <div class="row mb-4">
        <!-- Grafik 1: Peringkat Pelanggaran Antar-Fakultas (Bar Chart) -->
        <div class="col-xl-8 col-lg-7 mb-4 mb-xl-0">
            <div class="card section-card h-100 overflow-hidden">
                <div class="card-header py-3 px-4 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                            <i class="fas fa-chart-bar text-teal mr-2" style="color: #0d9488;"></i> Peringkat Pelanggaran Antar-Fakultas
                        </h6>
                        <small class="text-muted">Frekuensi pelanggaran per fakultas (tertinggi ke terendah).</small>
                    </div>
                    <button type="button" class="btn btn-sm btn-light border font-weight-bold px-2 py-1"
                            id="expandFacBarBtn"
                            style="font-size: 0.75rem; border-radius: 8px; color: #0d9488;">
                        <i class="fas fa-expand-alt"></i> Perbesar
                    </button>
                </div>
                <div class="card-body p-4">
                    <div style="position: relative; height: 300px;">
                        <canvas id="facultyBarChart" onclick="document.getElementById('expandFacBarBtn').click()" style="cursor: pointer;" title="Klik untuk memperbesar grafik"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik 2: Proporsi Tingkat Keparahan (Donut Chart) -->
        <div class="col-xl-4 col-lg-5">
            <div class="card section-card h-100 overflow-hidden">
                <div class="card-header py-3 px-4 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                        <i class="fas fa-chart-pie mr-2 text-warning"></i> Kategori Pelanggaran
                    </h6>
                    <small class="text-muted">Proporsi tingkat keparahan kasus</small>
                </div>
                <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                    <div style="position: relative; height: 210px; width: 100%;">
                        <canvas id="severityDonutChart"></canvas>
                    </div>
                    <div class="mt-4 d-flex justify-content-center flex-wrap" style="gap: 16px;">
                        <div class="d-flex align-items-center text-xs font-weight-bold text-muted">
                            <span class="mr-2" style="width: 12px; height: 12px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                            Ringan ({{ $severityCounts['Ringan'] }})
                        </div>
                        <div class="d-flex align-items-center text-xs font-weight-bold text-muted">
                            <span class="mr-2" style="width: 12px; height: 12px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                            Sedang ({{ $severityCounts['Sedang'] }})
                        </div>
                        <div class="d-flex align-items-center text-xs font-weight-bold text-muted">
                            <span class="mr-2" style="width: 12px; height: 12px; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                            Berat ({{ $severityCounts['Berat'] }})
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================
         BAGIAN PILIHAN PER-FAKULTAS: GRAFIK & DAFTAR LAPORAN
         ======================================================== -->
    <div class="card section-card mb-5 overflow-hidden" id="sectionPerFakultas">
        <div class="card-header py-3 px-4 bg-white border-bottom d-flex flex-column flex-md-row align-items-md-center justify-content-between">
            <div class="mb-2 mb-md-0">
                <h5 class="m-0 font-weight-bold text-dark" style="letter-spacing: -0.2px;">
                    <i class="fas fa-sitemap mr-2" style="color: #0f766e;"></i> Analisis & Daftar Laporan Per-Fakultas
                </h5>
                <small class="text-muted">Pilih fakultas di bawah ini untuk melihat grafik distribusi prodi serta rincian seluruh laporannya.</small>
            </div>
            <span class="badge badge-subtle-teal font-weight-bold">
                <i class="fas fa-check-circle mr-1"></i> Data Real-Time
            </span>
        </div>

        <div class="card-body p-4">
            <!-- Nav Tabs Pilihan Fakultas -->
            <ul class="nav faculty-nav-tabs mb-4" id="facultyTabs" role="tablist">
                @php $isFirst = true; @endphp
                @foreach($facultyStats as $facName => $stats)
                    @php $slug = Str::slug($facName); @endphp
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $isFirst ? 'active' : '' }}" 
                                id="tab-btn-{{ $slug }}" 
                                data-toggle="tab" 
                                data-target="#tab-pane-{{ $slug }}" 
                                type="button" 
                                role="tab" 
                                aria-controls="tab-pane-{{ $slug }}" 
                                aria-selected="{{ $isFirst ? 'true' : 'false' }}">
                            <i class="fas fa-graduation-cap"></i>
                            <span>{{ $facName }}</span>
                            <span class="badge-count">{{ $stats['total'] }} Kasus</span>
                        </button>
                    </li>
                    @php $isFirst = false; @endphp
                @endforeach
            </ul>

            <!-- Tab Panes Content Per Fakultas -->
            <div class="tab-content" id="facultyTabsContent">
                @php $isFirstPane = true; @endphp
                @foreach($facultyStats as $facName => $stats)
                    @php 
                        $slug = Str::slug($facName); 
                        $reports = $stats['reports'];
                    @endphp
                    <div class="tab-pane fade {{ $isFirstPane ? 'show active' : '' }}" 
                         id="tab-pane-{{ $slug }}" 
                         role="tabpanel" 
                         aria-labelledby="tab-btn-{{ $slug }}">

                        <!-- Row 1: Grafik & Status Card Fakultas Ini -->
                        <div class="row mb-4">
                            <!-- Grafik Perbandingan Antar Prodi / Jurusan dalam Fakultas Ini -->
                            <div class="col-lg-7 mb-3 mb-lg-0">
                                <div class="p-3 rounded-lg border bg-white h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="font-weight-bold text-dark mb-0 small text-uppercase">
                                            <i class="fas fa-chart-bar mr-1" style="color: #0d9488;"></i> Distribusi Kasus per Jurusan
                                        </h6>
                                        <button type="button" class="btn btn-sm btn-light border font-weight-bold px-2 py-1"
                                                id="expandDeptBtn_{{ $slug }}"
                                                style="font-size: 0.75rem; border-radius: 8px; color: #0d9488;">
                                            <i class="fas fa-expand-alt mr-1"></i> Perbesar
                                        </button>
                                    </div>
                                    @php $chartItems = array_slice($stats['deptBreakdown'], 0, 5, true); $deptCount = count($chartItems); $chartH = max(180, $deptCount * 42); @endphp
                                    <div style="position: relative; height: {{ $chartH }}px;">
                                        <canvas id="deptChart_{{ $slug }}" style="cursor: pointer;" title="Klik untuk memperbesar grafik"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistik Status & Tingkat Keparahan di Fakultas Ini -->
                            <div class="col-lg-5">
                                <div class="p-3 rounded-lg border bg-white shadow-sm mb-3">
                                    <h6 class="font-weight-bold text-dark mb-3 small text-uppercase">
                                        <i class="fas fa-layer-group mr-1 text-teal" style="color: #0f766e;"></i> Ringkasan Status & Keparahan
                                    </h6>
                                    
                                    <!-- 3 Mini Severity Badges -->
                                    <div class="d-flex align-items-center justify-content-between mb-3" style="gap: 8px;">
                                        <div class="p-2 rounded text-center flex-fill" style="background: #ecfdf5; border: 1px solid #d1fae5;">
                                            <span class="d-block text-xs font-weight-bold text-success">Ringan</span>
                                            <span class="h5 font-weight-bold text-dark mb-0">{{ $stats['ringan'] }}</span>
                                        </div>
                                        <div class="p-2 rounded text-center flex-fill" style="background: #fffbeb; border: 1px solid #fef3c7;">
                                            <span class="d-block text-xs font-weight-bold" style="color: #b45309;">Sedang</span>
                                            <span class="h5 font-weight-bold text-dark mb-0">{{ $stats['sedang'] }}</span>
                                        </div>
                                        <div class="p-2 rounded text-center flex-fill" style="background: #fef2f2; border: 1px solid #fee2e2;">
                                            <span class="d-block text-xs font-weight-bold text-danger">Berat</span>
                                            <span class="h5 font-weight-bold text-dark mb-0">{{ $stats['berat'] }}</span>
                                        </div>
                                    </div>

                                    <!-- Status Verifikasi Sederhana (Hanya Menunggu & Terverifikasi) -->
                                    <div class="pt-2 border-top">
                                        <div class="d-flex align-items-center justify-content-between small mb-2">
                                            <span class="text-muted"><i class="fas fa-clock mr-1 text-warning"></i> Menunggu Verifikasi:</span>
                                            <span class="badge badge-warning px-2.5 py-1 text-dark font-weight-bold" style="background: #fef3c7; color: #b45309; border-radius: 6px;">{{ $stats['pending'] }} Kasus</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between small">
                                            <span class="text-muted"><i class="fas fa-check-circle mr-1" style="color: #0d9488;"></i> Terverifikasi:</span>
                                            <span class="badge font-weight-bold px-2.5 py-1" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; border-radius: 6px;">{{ $stats['verified'] }} Kasus</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 2: Tabel Daftar Laporan Pelanggaran Pada Fakultas Ini -->
                        <div class="border rounded-lg overflow-hidden bg-white">
                            <div class="p-3 bg-light border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
                                <div class="font-weight-bold text-dark small text-uppercase mb-2 mb-sm-0">
                                    <i class="fas fa-list-ul mr-1" style="color: #0f766e;"></i> Daftar Laporan: {{ $facName }}
                                </div>
                                <div class="d-flex align-items-center" style="gap: 8px;">
                                    <div class="position-relative">
                                        <input type="text" 
                                               class="form-control form-control-sm pl-4 table-search-input" 
                                               data-target="table_{{ $slug }}" 
                                               placeholder="Cari mahasiswa / NIM..." 
                                               style="border-radius: 8px; width: 220px; font-size: 0.82rem;">
                                        <i class="fas fa-search position-absolute text-muted" style="left: 10px; top: 9px; font-size: 0.75rem;"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive report-list-scroll">
                                <table class="table modern-table mb-0" id="table_{{ $slug }}">
                                    <thead>
                                        <tr>
                                            <th style="width: 170px;">No. Laporan</th>
                                            <th style="width: 130px;">Tgl Kejadian</th>
                                            <th>Mahasiswa Terlapor</th>
                                            <th>Program Studi</th>
                                            <th style="width: 110px;" class="text-center">Kategori</th>
                                            <th style="width: 180px;" class="text-center">Status</th>
                                            <th style="width: 110px;" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reports->take(10) as $rep)
                                            <tr>
                                                <td>
                                                    <span class="report-num-badge">
                                                        {{ $rep->report_number }}
                                                    </span>
                                                    @if($rep->evidence_path)
                                                        <span class="text-muted ml-1" title="Terdapat berkas bukti"><i class="fas fa-paperclip text-info" style="font-size: 0.8rem;"></i></span>
                                                    @endif
                                                </td>
                                                <td class="text-muted" style="white-space: nowrap;">
                                                    <i class="fas fa-calendar-alt text-muted mr-1"></i>
                                                    {{ $rep->incident_date ? $rep->incident_date->format('d M Y') : '-' }}
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold text-dark">{{ $rep->student_name }}</div>
                                                    <div class="text-xs text-muted font-weight-bold">NIM: {{ $rep->student_nim }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-size: 0.82rem;">
                                                        {{ $rep->department_name }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $sevStyle = match($rep->severity) {
                                                            'Berat'  => 'background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2;',
                                                            'Sedang' => 'background: #fffbeb; color: #b45309; border: 1px solid #fef3c7;',
                                                            default  => 'background: #ecfdf5; color: #059669; border: 1px solid #d1fae5;',
                                                        };
                                                    @endphp
                                                    <span class="badge px-2.5 py-1 font-weight-bold" style="{{ $sevStyle }} border-radius: 8px;">
                                                        {{ $rep->severity ?? 'Ringan' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if(in_array($rep->status, ['pending', 'dilaporkan']))
                                                        <span class="badge px-2.5 py-1.5 font-weight-bold" style="background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; border-radius: 8px; font-size: 0.78rem;">
                                                            <i class="fas fa-hourglass-half mr-1"></i> Menunggu Verifikasi
                                                        </span>
                                                    @else
                                                        <span class="badge px-2.5 py-1.5 font-weight-bold" style="background: #f0fdfa; color: #0f766e; border: 1px solid #ccfbf1; border-radius: 8px; font-size: 0.78rem;">
                                                            <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('reports.show', ['id' => $rep->id, 'return_to' => request()->getRequestUri()]) }}" class="btn btn-sm btn-outline-secondary font-weight-bold px-3 py-1.5" style="border-radius: 8px; font-size: 0.82rem;" title="Lihat Detail">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    <i class="fas fa-clipboard-check fa-3x mb-2 text-muted" style="opacity: 0.35;"></i>
                                                    <div class="font-weight-bold">Tidak ada laporan pelanggaran pada {{ $facName }}.</div>
                                                    <small class="text-muted">Data akan otomatis terisi ketika terdapat laporan baru pada fakultas ini.</small>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @if($reports->count() > 10)
                                <div class="px-3 py-2 border-top small text-muted">Menampilkan 10 dari {{ $reports->count() }} laporan. Gulir untuk melihat data lainnya.</div>
                            @endif
                        </div>

                    </div>
                    @php $isFirstPane = false; @endphp
                @endforeach
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Helper smooth switch to faculty tab
            window.switchToFacultyTab = function(slug) {
                const tabBtn = document.getElementById('tab-btn-' + slug);
                if (tabBtn) {
                    $(tabBtn).tab('show');
                    document.getElementById('sectionPerFakultas').scrollIntoView({ behavior: 'smooth' });
                }
            };

            // 1. Chart Utama: Peringkat Frekuensi Pelanggaran Antar-Fakultas (Bar Chart)
            const facCtx = document.getElementById('facultyBarChart').getContext('2d');
            const facLabels = {!! json_encode(array_keys($facultyCounts)) !!};
            const facData = {!! json_encode(array_values($facultyCounts)) !!};
            const facColors = ['#0d9488', '#0891b2', '#d97706', '#7c3aed', '#64748b'];

            const facChartInst = new Chart(facCtx, {
                type: 'bar',
                data: {
                    labels: facLabels,
                    datasets: [{
                        label: 'Total Kasus Pelanggaran',
                        data: facData,
                        backgroundColor: facColors,
                        borderRadius: 8,
                        barThickness: 36,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, font: { size: 12 } },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            ticks: {
                                autoSkip: false,
                                font: { size: 12, weight: '600' },
                                color: '#1e293b'
                            },
                            grid: { display: false }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 13 },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 8
                        }
                    }
                }
            });

            // Expand button & click for faculty bar chart
            const expandFacBtn = document.getElementById('expandFacBarBtn');
            if (expandFacBtn) {
                expandFacBtn.addEventListener('click', function() {
                    openChartModal(
                        'Peringkat Pelanggaran Antar-Fakultas',
                        'bar',
                        facLabels,
                        [{ label: 'Total Kasus Pelanggaran', data: facData, backgroundColor: facColors, borderRadius: 8 }],
                        { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { ticks: { autoSkip: false } } }, plugins: { legend: { display: false } } }
                    );
                });
            }

            // 2. Chart Donut: Kategori Pelanggaran (Ringan, Sedang, Berat)
            const sevCtx = document.getElementById('severityDonutChart').getContext('2d');
            const sevData = [
                {{ $severityCounts['Ringan'] }},
                {{ $severityCounts['Sedang'] }},
                {{ $severityCounts['Berat'] }}
            ];

            new Chart(sevCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Ringan', 'Sedang', 'Berat'],
                    datasets: [{
                        data: sevData,
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 900, easing: 'easeOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.parsed + ' kasus';
                                }
                            }
                        }
                    },
                    cutout: '72%'
                }
            });

            // Klik diagram donut untuk memperbesar grafik
            const sevCanvas = document.getElementById('severityDonutChart');
            const openSevModal = function() {
                openChartModal(
                    'Proporsi Kategori Tingkat Keparahan Kasus',
                    'doughnut',
                    ['Ringan', 'Sedang', 'Berat'],
                    [{
                        data: sevData,
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }],
                    { plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
                );
            };
            if (sevCanvas) {
                sevCanvas.style.cursor = 'pointer';
                sevCanvas.title = 'Klik untuk memperbesar grafik';
                sevCanvas.addEventListener('click', openSevModal);
            }

            // 3. Mini Charts untuk Masing-masing Fakultas di Tab Panes
            @foreach($facultyStats as $facName => $stats)
                @php
                    $slug = Str::slug($facName);
                    $chartItems = array_slice($stats['deptBreakdown'], 0, 5, true);
                @endphp
                (function() {
                    const canvasEl = document.getElementById('deptChart_{{ $slug }}');
                    if (canvasEl) {
                        const ctx = canvasEl.getContext('2d');
                        const labels = {!! json_encode(array_keys($chartItems)) !!};
                        const data   = {!! json_encode(array_values($chartItems)) !!};

                        // Ringkasan hanya menampilkan lima jurusan teratas.
                        canvasEl.parentElement.style.height = Math.max(180, labels.length * 42) + 'px';

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Kasus',
                                    data: data,
                                    backgroundColor: '#0d9488',
                                    borderRadius: 6,
                                    barThickness: 20,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                            indexAxis: 'y',
                            animation: { duration: 900, easing: 'easeOutQuart' },
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: { stepSize: 1 },
                                        grid: { color: '#f8fafc' }
                                    },
                                    y: {
                                        ticks: {
                                            autoSkip: false,
                                            font: { size: 10, weight: '600' },
                                            color: '#334155'
                                        },
                                        grid: { display: false }
                                    }
                                },
                                plugins: { legend: { display: false } }
                            }
                        });

                        const allLabels = {!! json_encode(array_keys($stats['deptBreakdown'])) !!};
                        const allData = {!! json_encode(array_values($stats['deptBreakdown'])) !!};
                        // Expand Modal Handler shows every department.
                        const openModalHandler = function() {
                            openChartModal(
                                'Distribusi Kasus per Jurusan — {{ addslashes($facName) }}',
                                'bar',
                                allLabels,
                                [{
                                    label: 'Jumlah Kasus',
                                    data: allData,
                                    backgroundColor: '#0d9488',
                                    borderRadius: 6
                                }],
                                { indexAxis: 'y', animation: { duration: 900, easing: 'easeOutQuart' }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } }, y: { ticks: { autoSkip: false, font: { size: 12, weight: '600' } } } }, plugins: { legend: { display: false } } }
                            );
                        };

                        const expandBtn = document.getElementById('expandDeptBtn_{{ $slug }}');
                        if (expandBtn) {
                            expandBtn.addEventListener('click', openModalHandler);
                        }
                        canvasEl.style.cursor = 'pointer';
                        canvasEl.addEventListener('click', openModalHandler);
                    }
                })();
            @endforeach

            // Trigger window resize when switching tabs so hidden canvases compute proper size
            if (window.jQuery) {
                jQuery('button[data-toggle="tab"]').on('shown.bs.tab', function () {
                    window.dispatchEvent(new Event('resize'));
                });
            }

            // 4. Live Search Filter untuk Tabel Laporan Fakultas
            document.querySelectorAll('.table-search-input').forEach(function(input) {
                input.addEventListener('keyup', function() {
                    const targetTableId = this.getAttribute('data-target');
                    const filter = this.value.toLowerCase();
                    const table = document.getElementById(targetTableId);
                    if (!table) return;

                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(function(row) {
                        const text = row.innerText.toLowerCase();
                        if (text.includes(filter)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</x-layout>

