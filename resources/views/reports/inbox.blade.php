<x-layout>
    <x-slot:title>Inbox Verifikasi Laporan | SIPMA</x-slot:title>

    <style>
        .filter-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }
        .filter-card .form-control,
        .filter-card .custom-select {
            height: 42px;
            border-radius: 8px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.88rem;
        }
        .filter-card .form-control:focus,
        .filter-card .custom-select:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
        }
        .table-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }
        .inbox-table th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: none;
            border-bottom: 1.5px solid #e2e8f0;
            padding: 14px 16px;
        }
        .inbox-table td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }
        .inbox-table tbody tr:hover {
            background-color: #f8fafc;
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

    <!-- Top Notifications -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 border-0" role="alert" style="border-radius: 12px; background: #ecfdf5; border-left: 5px solid #10b981 !important;">
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

    <!-- Header Banner Teal Modern -->
    <div class="card mb-4 overflow-hidden border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header py-4 px-4 px-md-5 text-white border-0" style="background: linear-gradient(135deg, #134e4a 0%, #0f766e 60%, #0d9488 100%);">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center text-white mr-3 shadow-sm flex-shrink-0" style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255, 255, 255, 0.2);">
                    <i class="fas fa-inbox fa-lg"></i>
                </div>
                <div>
                    <h5 class="m-0 font-weight-bold text-white" style="font-size: 1.25rem; letter-spacing: -0.2px;">
                        Inbox Verifikasi Laporan Pelanggaran
                    </h5>
                    <small class="text-white-50">Tinjau dan validasi berkas laporan mahasiswa yang baru masuk sebelum diteruskan ke jurusan terkait.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. Sleek Filter Bar Langsung di Bawah Banner -->
    <div class="filter-card p-3 mb-4">
        <form method="GET" action="{{ route('reports.inbox') }}" class="row align-items-center">
            <!-- Search Input -->
            <div class="col-lg-5 col-md-6 mb-2 mb-lg-0">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute text-muted" style="left: 14px; top: 13px; font-size: 0.85rem;"></i>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="form-control pl-5" 
                           placeholder="Cari No. Laporan, Mahasiswa, atau NIM...">
                </div>
            </div>

            <!-- Jurusan Filter -->
            <div class="col-lg-3 col-md-6 mb-2 mb-lg-0">
                <select name="department" class="form-control custom-select">
                    <option value="">-- Semua Jurusan / Prodi --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kategori Filter -->
            <div class="col-lg-2 col-md-6 mb-2 mb-lg-0">
                <select name="severity" class="form-control custom-select">
                    <option value="">-- Kategori --</option>
                    <option value="Ringan" {{ request('severity') === 'Ringan' ? 'selected' : '' }}>Ringan</option>
                    <option value="Sedang" {{ request('severity') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                    <option value="Berat" {{ request('severity') === 'Berat' ? 'selected' : '' }}>Berat</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="col-lg-2 col-md-6 d-flex" style="gap: 6px;">
                <button type="submit" class="btn btn-block text-white font-weight-bold d-flex align-items-center justify-content-center" 
                        style="background: #0d9488; border-radius: 8px; height: 42px;" title="Terapkan Filter">
                    <i class="fas fa-filter mr-1.5"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'department', 'severity']))
                <a href="{{ route('reports.inbox') }}" class="btn btn-light border d-flex align-items-center justify-content-center px-3" 
                   style="border-radius: 8px; height: 42px;" title="Reset Filter">
                    <i class="fas fa-times text-muted"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 2. Data Table Card -->
    <div class="table-card mb-4">
        <div class="card-header py-3 px-4 bg-white border-bottom d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-2.5" style="width: 32px; height: 32px; background: #fffbeb; color: #d97706;">
                    <i class="fas fa-inbox"></i>
                </div>
                <h6 class="m-0 font-weight-bold text-dark" style="font-size: 0.95rem;">
                    Inbox Laporan Menunggu Verifikasi
                </h6>
            </div>
            <span class="badge badge-warning px-3 py-1.5 text-dark font-weight-bold" style="background:#fef3c7; color:#b45309; border-radius: 20px; font-size: 0.8rem;">
                <i class="fas fa-clock mr-1"></i> {{ $reports->total() }} Menunggu
            </span>
        </div>

        <div class="table-responsive">
            <table class="table inbox-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 170px;">No. Laporan</th>
                        <th style="width: 130px;">Tgl Kejadian</th>
                        <th>Mahasiswa Terlapor</th>
                        <th>Jurusan / Prodi</th>
                        <th style="width: 110px;" class="text-center">Kategori</th>
                        <th style="width: 170px;" class="text-center">Status</th>
                        <th style="width: 130px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <!-- No. Laporan -->
                        <td>
                            <span class="report-num-badge">
                                {{ $report->report_number }}
                            </span>
                            @if($report->evidence_path)
                                <span class="text-muted ml-1" title="Terdapat berkas bukti"><i class="fas fa-paperclip text-info" style="font-size: 0.8rem;"></i></span>
                            @endif
                        </td>

                        <!-- Tanggal Kejadian -->
                        <td class="text-muted" style="white-space: nowrap;">
                            <i class="fas fa-calendar-alt text-muted mr-1"></i>
                            {{ $report->incident_date ? $report->incident_date->format('d M Y') : '-' }}
                        </td>

                        <!-- Mahasiswa Terlapor -->
                        <td>
                            <div class="font-weight-bold text-dark">{{ $report->student_name }}</div>
                            <div class="text-xs text-muted font-weight-bold">NIM: {{ $report->student_nim }}</div>
                        </td>

                        <!-- Jurusan Terkait -->
                        <td>
                            <span class="badge badge-light border text-dark font-weight-bold px-2 py-1" style="font-size: 0.82rem;">
                                {{ $report->department_name }}
                            </span>
                        </td>

                        <!-- Kategori Pelanggaran -->
                        <td class="text-center">
                            @php
                                $sevStyle = match($report->severity) {
                                    'Berat'  => 'background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2;',
                                    'Sedang' => 'background: #fffbeb; color: #b45309; border: 1px solid #fef3c7;',
                                    default  => 'background: #ecfdf5; color: #059669; border: 1px solid #d1fae5;',
                                };
                            @endphp
                            <span class="badge px-2.5 py-1 font-weight-bold" style="{{ $sevStyle }} border-radius: 8px;">
                                {{ $report->severity ?? 'Ringan' }}
                            </span>
                        </td>

                        <!-- Status -->
                        <td class="text-center">
                            <span class="badge px-2.5 py-1.5 font-weight-bold" style="background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; border-radius: 8px; font-size: 0.78rem;">
                                <i class="fas fa-hourglass-half mr-1"></i> Menunggu Verifikasi
                            </span>
                        </td>

                        <!-- Aksi -->
                        <td class="text-center">
                            <a href="{{ route('reports.show', ['id' => $report->id, 'return_to' => request()->getRequestUri()]) }}" 
                               class="btn btn-sm font-weight-bold text-white px-3 py-1.5 d-inline-flex align-items-center" 
                               style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); border-radius: 8px; font-size: 0.82rem;">
                                <i class="fas fa-check-circle mr-1.5"></i> Verifikasi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <div class="py-3">
                                <i class="fas fa-inbox fa-3x text-muted mb-2" style="opacity: 0.4;"></i>
                                <div class="font-weight-bold text-dark">Tidak ada laporan yang menunggu verifikasi</div>
                                <small class="text-muted">Semua laporan yang masuk telah diverifikasi dan diproses.</small>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
        <div class="p-3 border-top bg-light d-flex justify-content-end">
            {{ $reports->links() }}
        </div>
        @endif
    </div>
</x-layout>

