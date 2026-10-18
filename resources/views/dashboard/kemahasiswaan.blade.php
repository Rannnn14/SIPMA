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
            color: #475569;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
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

    <!-- Header Section with Clean Academic Year Filter -->
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4 pb-2">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold" style="letter-spacing: -0.3px;">
                <i class="fas fa-chart-line text-teal mr-2" style="color: #0f766e;"></i> Dashboard Kemahasiswaan
            </h1>
            <p class="text-muted small mb-0">Pusat monitoring, analisis komparatif antar-fakultas, dan verifikasi kasus pelanggaran kampus.</p>
        </div>

        <!-- Academic Year Dropdown & Add Button (No Semester Dropdown) -->
        <div class="d-flex align-items-center flex-wrap mt-3 mt-md-0" style="gap: 10px;">
            <form method="GET" action="{{ route('kemahasiswaan.dashboard') }}" class="m-0 d-flex align-items-center" id="academicYearForm">
                <div class="position-relative d-flex align-items-center">
                    <i class="fas fa-calendar-alt position-absolute text-muted" style="left: 14px; z-index: 2; pointer-events: none;"></i>
                    <select name="academic_year" class="academic-year-select pl-5 shadow-sm" onchange="document.getElementById('academicYearForm').submit()">
                        <option value="all" {{ $selectedAcademicYear === 'all' ? 'selected' : '' }}>Semua Tahun Akademik</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->year }}" {{ $selectedAcademicYear === $ay->year ? 'selected' : '' }}>
                                T.A. {{ $ay->year }} {{ $ay->is_active ? '• (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            <!-- Button Tambah Tahun Akademik Baru -->
            <button type="button" class="btn btn-teal-primary shadow-sm" data-toggle="modal" data-target="#modalAddAcademicYear" title="Tambah Tahun Akademik Selanjutnya">
                <i class="fas fa-plus-circle"></i>
                <span>Tahun Akademik Baru</span>
            </button>
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
                    <h6 class="font-weight-bold mb-0 text-dark">Terdapat {{ $pendingCount }} Laporan Baru Menunggu Verifikasi</h6>
                    <small class="text-muted">Periksa kesesuaian identitas terlapor dan bukti digital sebelum diteruskan ke Ketua Jurusan.</small>
                </div>
            </div>
            <a href="{{ route('reports.index', ['status' => 'pending']) }}" class="btn btn-sm font-weight-bold px-3 py-2 text-dark shadow-sm" style="background: #fde68a; border-radius: 8px;">
                Buka Inbox Verifikasi <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    @endif

    <!-- 4 KPI Stat Cards Row -->
    <div class="row mb-4">
        <!-- 1. Total Laporan -->
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
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
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="card feature-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Perlu Verifikasi</div>
                        <div class="h3 mb-0 font-weight-bold text-amber-600" style="color: #d97706;">{{ $pendingCount }}</div>
                        <small class="text-muted">Menunggu tinjauan staf</small>
                    </div>
                    <div class="metric-icon-box" style="background: #fffbeb; color: #d97706;">
                        <i class="fas fa-hourglass-half fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Dikonfirmasi ke Kajur -->
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="card feature-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Penanganan Kajur</div>
                        <div class="h3 mb-0 font-weight-bold text-blue-600" style="color: #0284c7;">{{ $confirmedCount + $inProgressCount }}</div>
                        <small class="text-muted">Proses klarifikasi & sidang</small>
                    </div>
                    <div class="metric-icon-box" style="background: #f0f9ff; color: #0284c7;">
                        <i class="fas fa-user-shield fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Selesai Tindak Lanjut -->
        <div class="col-xl-3 col-sm-6 mb-3 mb-xl-0">
            <div class="card feature-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">Selesai Ditindak</div>
                        <div class="h3 mb-0 font-weight-bold text-teal-600" style="color: #0d9488;">{{ $completedCount }}</div>
                        <small class="text-muted">Sanksi / pembinaan tuntas</small>
                    </div>
                    <div class="metric-icon-box" style="background: #f0fdf4; color: #059669;">
                        <i class="fas fa-check-double fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================
         BAGIAN UTAMA: FAKULTAS TERLEBIH DAHULU & GRAFIK PELANGGARAN
         ======================================================== -->

    <!-- Quick Summary Cards Antar-Fakultas -->
    <div class="row mb-4">
        @foreach($facultyCounts as $facName => $count)
            @php
                $stat = $facultyStats[$facName];
                $percent = $totalReports > 0 ? round(($count / $totalReports) * 100, 1) : 0;
            @endphp
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card feature-card h-100 p-3" style="cursor: pointer;" onclick="switchToFacultyTab('{{ Str::slug($facName) }}')">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mr-2" style="width: 36px; height: 36px; background: #f0fdfa; color: #0f766e;">
                                <i class="fas fa-university"></i>
                            </div>
                            <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $facName }}</span>
                        </div>
                        <span class="badge badge-subtle-teal">{{ $count }} Kasus</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between text-xs text-muted mt-2">
                        <span>Porsi: <strong>{{ $percent }}%</strong></span>
                        <span>Ringan: {{ $stat['ringan'] }} | Sedang: {{ $stat['sedang'] }} | Berat: {{ $stat['berat'] }}</span>
                    </div>
                    <div class="progress mt-2" style="height: 6px; border-radius: 6px; background-color: #f1f5f9;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background: #0f766e;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts Row: 1. Grafik Pelanggaran Antar-Fakultas & 2. Donut Proporsi Kategori -->
    <div class="row mb-4">
        <!-- Grafik 1: Peringkat Pelanggaran Antar-Fakultas (Bar Chart) -->
        <div class="col-xl-8 col-lg-7 mb-4 mb-xl-0">
            <div class="card section-card h-100 overflow-hidden">
                <div class="card-header py-3 px-4 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                            <i class="fas fa-chart-bar text-teal mr-2" style="color: #0f766e;"></i> Peringkat Pelanggaran Antar-Fakultas
                        </h6>
                        <small class="text-muted">Grafik frekuensi fakultas yang paling sering melakukan pelanggaran (urutan tertinggi).</small>
                    </div>
                    <span class="badge badge-subtle-gray">Kumulatif Fakultas</span>
                </div>
                <div class="card-body p-4">
                    <div style="position: relative; height: 300px;">
                        <canvas id="facultyBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik 2: Proporsi Tingkat Keparahan (Donut Chart) -->
        <div class="col-xl-4 col-lg-5">
            <div class="card section-card h-100 overflow-hidden">
                <div class="card-header py-3 px-4 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                            <i class="fas fa-chart-pie mr-2 text-warning"></i> Kategori Pelanggaran
                        </h6>
                        <small class="text-muted">Proporsi tingkat keparahan kasus.</small>
                    </div>
                    <span class="badge badge-subtle-gray">Ringan / Sedang / Berat</span>
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
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6 class="font-weight-bold text-dark mb-0 small text-uppercase">
                                            <i class="fas fa-chart-column mr-1 text-teal" style="color: #0f766e;"></i> Distribusi Kasus per Jurusan / Program Studi
                                        </h6>
                                        <span class="badge badge-subtle-gray">{{ count($stats['departments']) }} Program Studi</span>
                                    </div>
                                    <div style="position: relative; height: 190px;">
                                        <canvas id="deptChart_{{ $slug }}"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistik Status & Tingkat Keparahan di Fakultas Ini -->
                            <div class="col-lg-5">
                                <div class="p-3 rounded-lg border bg-white h-100 d-flex flex-column justify-content-between">
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

                                    <!-- Status Progress Breakdown -->
                                    <div class="pt-2 border-top">
                                        <div class="d-flex align-items-center justify-content-between small mb-1">
                                            <span class="text-muted"><i class="fas fa-clock mr-1 text-warning"></i> Menunggu Verifikasi:</span>
                                            <span class="font-weight-bold">{{ $stats['pending'] }} Kasus</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between small mb-1">
                                            <span class="text-muted"><i class="fas fa-spinner mr-1 text-info"></i> Dalam Penanganan Kajur:</span>
                                            <span class="font-weight-bold">{{ $stats['diproses'] }} Kasus</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between small">
                                            <span class="text-muted"><i class="fas fa-check-circle mr-1 text-success"></i> Selesai Ditindak:</span>
                                            <span class="font-weight-bold">{{ $stats['selesai'] }} Kasus</span>
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

                            <div class="table-responsive">
                                <table class="table modern-table mb-0" id="table_{{ $slug }}">
                                    <thead>
                                        <tr>
                                            <th>No. Laporan</th>
                                            <th>Mahasiswa Terlapor</th>
                                            <th>Program Studi</th>
                                            <th>Tgl Kejadian</th>
                                            <th>Kategori</th>
                                            <th>Status Kasus</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($reports as $rep)
                                            <tr>
                                                <td class="font-weight-bold" style="color: #0f766e;">
                                                    {{ $rep->report_number }}
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold text-dark">{{ $rep->student_name }}</div>
                                                    <small class="text-muted">NIM: {{ $rep->student_nim }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-subtle-gray">{{ $rep->department_name }}</span>
                                                </td>
                                                <td class="text-muted small">
                                                    {{ $rep->incident_date ? $rep->incident_date->format('d/m/Y') : '-' }}
                                                </td>
                                                <td>
                                                    @if($rep->severity === 'Berat')
                                                        <span class="badge badge-subtle-rose">Berat</span>
                                                    @elseif($rep->severity === 'Sedang')
                                                        <span class="badge badge-subtle-amber">Sedang</span>
                                                    @else
                                                        <span class="badge badge-subtle-teal">Ringan</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(in_array($rep->status, ['pending', 'dilaporkan']))
                                                        <span class="badge badge-subtle-amber">Menunggu Verifikasi</span>
                                                    @elseif(in_array($rep->status, ['dikonfirmasi', 'diproses', 'sedang_diproses', 'diverifikasi_kaprodi']))
                                                        <span class="badge badge-subtle-blue">Dalam Penanganan</span>
                                                    @elseif(in_array($rep->status, ['selesai', 'sanksi_ditetapkan']))
                                                        <span class="badge badge-subtle-teal">Selesai</span>
                                                    @elseif($rep->status === 'ditolak')
                                                        <span class="badge badge-subtle-rose">Ditolak</span>
                                                    @else
                                                        <span class="badge badge-subtle-gray">{{ $rep->status_label }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('reports.show', $rep->id) }}" class="btn btn-sm btn-outline-secondary font-weight-bold px-3 py-1" style="border-radius: 8px; font-size: 0.8rem;">
                                                        <i class="fas fa-eye mr-1"></i> Detail
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
                        </div>

                    </div>
                    @php $isFirstPane = false; @endphp
                @endforeach
            </div>
        </div>
    </div>

    <!-- ========================================================
         MODAL TAMBAH TAHUN AKADEMIK BARU
         ======================================================== -->
    <div class="modal fade" id="modalAddAcademicYear" tabindex="-1" role="dialog" aria-labelledby="modalAddAcademicYearTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
                <div class="modal-header px-4 py-3 bg-white border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px; background: #f0fdfa; color: #0f766e;">
                            <i class="fas fa-calendar-plus fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="modal-title font-weight-bold text-dark" id="modalAddAcademicYearTitle" style="font-size: 1.15rem;">Tambah Tahun Akademik Baru</h5>
                            <small class="text-muted">Buka periode akademik baru untuk pendataan laporan.</small>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kemahasiswaan.academic-years.store') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark small mb-2">Tahun Akademik Selanjutnya <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="year" 
                                   class="form-control form-control-lg" 
                                   placeholder="Contoh: {{ $suggestedNextYear }}" 
                                   value="{{ old('year', $suggestedNextYear) }}" 
                                   required 
                                   pattern="\d{4}/\d{4}" 
                                   title="Format harus YYYY/YYYY (contoh: {{ $suggestedNextYear }})"
                                   style="border-radius: 10px; font-size: 1.05rem; font-weight: 600;">
                            <small class="text-muted mt-1 d-block">Gunakan format 4 digit tahun / 4 digit tahun (contoh: <strong>{{ $suggestedNextYear }}</strong>).</small>
                        </div>

                        <div class="p-3 rounded-lg mb-2" style="background-color: #f0fdfa; border: 1px solid #ccfbf1;">
                            <div class="d-flex">
                                <i class="fas fa-info-circle fa-lg mr-2 mt-1" style="color: #0f766e;"></i>
                                <div class="small" style="color: #134e4a; line-height: 1.5;">
                                    <strong>Otomatisasi Laporan Baru:</strong> Ketika tahun akademik baru ini ditambahkan, sistem akan langsung menjadikannya sebagai <strong>Tahun Akademik Aktif</strong>. Semua laporan pelanggaran yang baru masuk akan otomatis dicatat ke tahun akademik ini.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-4 py-3 bg-light border-top">
                        <button type="button" class="btn btn-secondary font-weight-bold px-3 py-2" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                        <button type="submit" class="btn btn-teal-primary px-4 py-2">
                            <i class="fas fa-save mr-1"></i> Simpan & Aktifkan
                        </button>
                    </div>
                </form>
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

            new Chart(facCtx, {
                type: 'bar',
                data: {
                    labels: facLabels,
                    datasets: [{
                        label: 'Total Kasus Pelanggaran',
                        data: facData,
                        backgroundColor: ['#0f766e', '#0284c7', '#d97706', '#64748b'],
                        borderRadius: 8,
                        barThickness: 36,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: { size: 12, family: 'Inter, sans-serif' }
                            },
                            grid: { color: '#f1f5f9' }
                        },
                        x: {
                            ticks: {
                                font: { size: 12, weight: '600', family: 'Inter, sans-serif' },
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

            // 3. Mini Charts untuk Masing-masing Fakultas di Tab Panes
            @foreach($facultyStats as $facName => $stats)
                @php $slug = Str::slug($facName); @endphp
                (function() {
                    const canvasEl = document.getElementById('deptChart_{{ $slug }}');
                    if (canvasEl) {
                        const ctx = canvasEl.getContext('2d');
                        const labels = {!! json_encode(array_keys($stats['deptBreakdown'])) !!};
                        const data = {!! json_encode(array_values($stats['deptBreakdown'])) !!};

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Jumlah Kasus',
                                    data: data,
                                    backgroundColor: '#0d9488',
                                    borderRadius: 6,
                                    barThickness: 24,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                indexAxis: 'y', // Horizontal bar for clean department view
                                scales: {
                                    x: {
                                        beginAtZero: true,
                                        ticks: { stepSize: 1 },
                                        grid: { color: '#f8fafc' }
                                    },
                                    y: {
                                        ticks: {
                                            font: { size: 11, weight: '600' },
                                            color: '#334155'
                                        },
                                        grid: { display: false }
                                    }
                                },
                                plugins: {
                                    legend: { display: false }
                                }
                            }
                        });
                    }
                })();
            @endforeach

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
