<x-layout>
    <x-slot:title>{{ $report->report_number }} | Detail Laporan SIPMA</x-slot:title>

    <style>
        .btn-kembali-sipma {
            background: #ffffff;
            border: 1.5px solid #0d9488;
            color: #0d9488;
            padding: 9px 22px;
            font-size: 0.95rem;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-kembali-sipma:hover {
            background: #0d9488;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
            transform: translateY(-1px);
        }
    </style>

    @php
        $backRoute = in_array($report->status, ['pending', 'dilaporkan']) ? route('reports.inbox') : route('reports.history');
        if (auth()->user()->isKajur()) {
            $backRoute = route('reports.index');
        }
        $returnTo = request()->query('return_to');
        if (is_string($returnTo) && str_starts_with($returnTo, '/') && !str_starts_with($returnTo, '//')) {
            $backRoute = url($returnTo);
        }
    @endphp

    <!-- Tombol Kembali yang Bagus & Rada Besar -->
    <div class="mb-3">
        <a href="{{ $backRoute }}" class="btn-kembali-sipma shadow-sm">
            <i class="fas fa-arrow-left mr-2" style="font-size: 1rem;"></i> Kembali
        </a>
    </div>

    <!-- Header Banner Teal Modern (Langsung REP-..., Tanpa Tulisan 'KASUS') -->
    <div class="card mb-4 overflow-hidden border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-header py-4 px-4 px-md-5 text-white border-0" style="background: linear-gradient(135deg, #134e4a 0%, #0f766e 60%, #0d9488 100%);">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div class="d-flex align-items-center mb-3 mb-md-0">
                    <div class="d-flex align-items-center justify-content-center text-white mr-3 shadow-sm flex-shrink-0" style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255, 255, 255, 0.2);">
                        <i class="fas fa-file-signature fa-lg"></i>
                    </div>
                    <div>
                        <!-- Langsung nomor laporan tanpa tulisan "Kasus:" -->
                        <h4 class="m-0 font-weight-bold text-white" style="letter-spacing: 0.5px; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 1.35rem;">
                            {{ $report->report_number }}
                        </h4>
                        <small class="text-white-50">Rincian data berkas dan informasi pelaporan pelanggaran tata tertib mahasiswa.</small>
                    </div>
                </div>

                <!-- Status Badge di Samping Banner -->
                <div>
                    @if(in_array($report->status, ['pending', 'dilaporkan']))
                        <span class="badge px-3 py-2 font-weight-bold shadow-sm" style="background: #fef3c7; color: #b45309; border-radius: 20px; font-size: 0.88rem;">
                            <i class="fas fa-hourglass-half mr-1"></i> Menunggu Verifikasi
                        </span>
                    @elseif($report->status === 'ditolak')
                        <span class="badge px-3 py-2 font-weight-bold shadow-sm" style="background: #fee2e2; color: #dc2626; border-radius: 20px; font-size: 0.88rem;">
                            <i class="fas fa-times-circle mr-1"></i> Ditolak
                        </span>
                    @else
                        <span class="badge px-3 py-2 font-weight-bold shadow-sm" style="background: #ffffff; color: #0f766e; border-radius: 20px; font-size: 0.88rem;">
                            <i class="fas fa-check-circle mr-1 text-success"></i> Terverifikasi
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 border-0" role="alert" style="border-radius: 12px; border-left: 5px solid #ef4444 !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-lg text-danger mr-3"></i>
                <div class="text-dark">
                    <strong>Perhatian:</strong> {{ $errors->first() }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Kolom Kiri: Informasi Pelanggaran & Kronologi -->
        <div class="col-lg-8">
            <!-- Informasi Pokok Kejadian (Tampilan Rapi, Dua Kotak di Atas & Detail di Bawah) -->
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                <div class="card-header py-3 px-4 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold" style="color: #0d9488;">
                        <i class="fas fa-clipboard-check mr-2"></i> Informasi Kejadian & Pelapor
                    </h6>
                </div>
                <div class="card-body p-4">
                    <!-- Dua Kotak di Bagian Atas: Waktu Pelaporan & Identitas Pelapor -->
                    <div class="row mb-3">
                        <!-- Kotak 1: Waktu Pelaporan -->
                        <div class="{{ auth()->user()->isKajur() ? 'col-12' : 'col-md-6 mb-3 mb-md-0' }}">
                            <div class="p-3 rounded h-100" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="far fa-clock mr-2 text-teal" style="color: #0d9488; font-size: 0.95rem;"></i>
                                    <span class="text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">Waktu Pelaporan</span>
                                </div>
                                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                    {{ $report->created_at->format('d F Y – H:i') }} WIB
                                </div>
                            </div>
                        </div>

                        <!-- Kotak 2: Identitas Pelapor -->
                        @unless(auth()->user()->isKajur())
                        <div class="col-md-6">
                            <div class="p-3 rounded h-100" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="far fa-user mr-2 text-teal" style="color: #0d9488; font-size: 0.95rem;"></i>
                                    <span class="text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">Identitas Pelapor</span>
                                </div>
                                <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                                    {{ $report->reporter_display_name }}
                                    @if($report->reporter && $report->reporter->nim)
                                        <span class="text-muted font-weight-normal ml-1">({{ $report->reporter->nim }})</span>
                                    @elseif($report->student && $report->reporter_id === $report->student->user_id && $report->student->nim)
                                        <span class="text-muted font-weight-normal ml-1">({{ $report->student->nim }})</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endunless
                    </div>

                    <!-- Informasi Kejadian di Bawah Dua Kotak (Tanpa Pemisah Peluru / Bulet) -->
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0" style="font-size: 0.95rem;">
                            <tbody>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="text-muted font-weight-bold py-2.5 pl-0" style="width: 32%;">
                                        <i class="far fa-calendar-alt mr-2 text-teal" style="color: #0d9488;"></i> Waktu & Tanggal Kejadian
                                    </td>
                                    <td class="font-weight-bold text-dark py-2.5">
                                        : {{ $report->incident_date ? $report->incident_date->format('d F Y') : '-' }}@if($report->incident_time) - {{ date('H:i', strtotime($report->incident_time)) }} WIB @endif
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="text-muted font-weight-bold py-2.5 pl-0">
                                        <i class="fas fa-map-marker-alt mr-2 text-teal" style="color: #0d9488;"></i> Lokasi Kejadian
                                    </td>
                                    <td class="font-weight-bold text-dark py-2.5">
                                        : {{ $report->incident_location }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold py-2.5 pl-0">
                                        <i class="fas fa-shield-alt mr-2 text-teal" style="color: #0d9488;"></i> Kategori Pelanggaran
                                    </td>
                                    <td class="py-2.5">
                                        : @php
                                            $sevStyle = match($report->severity) {
                                                'Berat'  => 'background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2;',
                                                'Sedang' => 'background: #fffbeb; color: #b45309; border: 1px solid #fef3c7;',
                                                default  => 'background: #ecfdf5; color: #059669; border: 1px solid #d1fae5;',
                                            };
                                        @endphp
                                        <span class="badge px-3 py-1 font-weight-bold" style="{{ $sevStyle }} border-radius: 6px; font-size: 0.88rem;">
                                            {{ $report->severity ?? 'Ringan' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kronologi Kejadian (Tanpa Spasi / Indentasi di Awal) -->
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                <div class="card-header py-3 px-4 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold" style="color: #0d9488;">
                        <i class="fas fa-align-left mr-2"></i> Kronologi Kejadian
                    </h6>
                </div>
                <div class="card-body p-4">
                    <div class="p-3 rounded text-dark" style="white-space: pre-wrap; line-height: 1.7; background: #f8fafc; border: 1px solid #e2e8f0; font-size: 0.95rem; border-radius: 12px;">{{ trim($report->chronology) }}</div>
                </div>
            </div>

            <!-- Berkas Lampiran Bukti -->
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                <div class="card-header py-3 px-4 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold" style="color: #0d9488;">
                        <i class="fas fa-paperclip mr-2"></i> Berkas Lampiran Bukti
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($report->evidence_path)
                        <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; background: #e6fffa; color: #0d9488; font-size: 1.15rem;">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $report->evidence_name ?? 'Berkas_Bukti_Pelanggaran' }}</div>
                                    <small class="text-muted">Bukti yang diunggah pelapor saat submisi data</small>
                                </div>
                            </div>
                            <div>
                                <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank" class="btn btn-sm font-weight-bold text-white px-3 py-2 shadow-sm" style="background: #0d9488; border-radius: 8px;">
                                    <i class="fas fa-external-link-alt mr-1"></i> Buka / Pratinjau
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-muted small font-italic py-2">
                            <i class="fas fa-info-circle mr-1"></i> Tidak ada file lampiran bukti yang diunggah pada laporan ini.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Riwayat Alasan Penolakan jika ditolak -->
            @if($report->status === 'ditolak')
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 16px; border-left: 5px solid #ef4444 !important; background: #fff5f5;">
                <div class="card-body p-4">
                    <h6 class="font-weight-bold text-danger mb-2"><i class="fas fa-ban mr-1"></i> Alasan Penolakan oleh Kemahasiswaan:</h6>
                    <div class="p-3 bg-white rounded border border-danger text-dark mb-0">
                        {{ $report->rejection_reason }}
                    </div>
                    @if($report->verifier)
                        <div class="small text-muted mt-2">Ditolak oleh: <strong>{{ $report->verifier->name }}</strong> pada {{ $report->verified_at ? $report->verified_at->format('d M Y H:i') : '' }} WIB</div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Riwayat Tindak Lanjut Kajur jika ada -->
            @if($report->kajur_notes)
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 16px; border-left: 5px solid #10b981 !important; background: #f0fdf4;">
                <div class="card-header py-3 px-4 bg-transparent border-bottom">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-gavel mr-1"></i> Hasil Tindak Lanjut Ketua Jurusan</h6>
                </div>
                <div class="card-body p-4">
                    <div class="mb-2">
                        <label class="text-muted small font-weight-bold mb-0">Bentuk Sanksi / Tindakan:</label>
                        <h5 class="font-weight-bold text-dark">{{ $report->sanction_type ?? 'Pembinaan' }}</h5>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small font-weight-bold mb-0">Catatan Tindak Lanjut:</label>
                        <p class="text-dark bg-white p-3 rounded border mb-0">{{ $report->kajur_notes }}</p>
                    </div>
                    <div class="small text-muted mt-3">
                        Ditangani oleh: <strong>{{ $report->handler?->name ?? 'Ketua Jurusan' }}</strong> 
                        pada {{ $report->handled_at ? $report->handled_at->format('d M Y H:i') : '-' }} WIB
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Data Terlapor & Panel Aksi -->
        <div class="col-lg-4">
            
            <!-- Kartu Data Mahasiswa Terlapor (Disertai Semester Terlapor) -->
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                <div class="card-header py-3 px-4 bg-white border-bottom d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-dark d-flex align-items-center" style="white-space: nowrap;">
                        <i class="fas fa-user-graduate mr-2" style="color: #0d9488;"></i> Mahasiswa Terlapor
                    </h6>
                    @if(auth()->user()->isKemahasiswaan())
                        <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold px-2.5 py-1" style="border-radius: 6px; font-size: 0.78rem;" data-toggle="modal" data-target="#modalKoreksi">
                            <i class="fas fa-edit mr-1"></i> Koreksi
                        </button>
                    @endif
                </div>
                <div class="card-body text-center p-4">
                    <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" style="width: 68px; height: 68px; font-size: 1.8rem; background: #e6fffa; color: #0d9488; border: 2px solid #ccfbf1;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1" style="font-size: 1.15rem;">{{ $report->student_name }}</h5>
                    <p class="text-muted small mb-3">NIM: <strong class="text-dark">{{ $report->student_nim }}</strong></p>

                    <!-- Rincian Akademik Mahasiswa Terlapor (Tata Letak Rapi & Sejajar) -->
                    <div class="text-left p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                        <table class="table table-borderless table-sm mb-0" style="font-size: 0.92rem;">
                            <tbody>
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td class="text-muted font-weight-bold py-2.5 pl-0" style="width: 44%;">
                                        <i class="fas fa-book-reader mr-1.5 text-teal" style="color: #0d9488;"></i> Jurusan / Prodi
                                    </td>
                                    <td class="font-weight-bold text-dark py-2.5">
                                        : {{ $report->reported_department ?? $report->target_department ?? '-' }}
                                    </td>
                                </tr>
                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                    <td class="text-muted font-weight-bold py-2.5 pl-0">
                                        <i class="fas fa-university mr-1.5 text-teal" style="color: #0d9488;"></i> Fakultas
                                    </td>
                                    <td class="font-weight-bold text-dark py-2.5">
                                        : {{ $report->faculty_name ?: 'FEBI' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted font-weight-bold py-2.5 pl-0">
                                        <i class="fas fa-graduation-cap mr-1.5 text-teal" style="color: #0d9488;"></i> Semester
                                    </td>
                                    <td class="font-weight-bold text-dark py-2.5">
                                        : @if($report->reported_semester) Semester {{ $report->reported_semester }} @else - @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PANEL AKSI KEMAHASISWAAN (VERIFIKASI) -->
            @if(auth()->user()->isKemahasiswaan())
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                <div class="card-header py-3 px-4 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-user-check mr-1" style="color: #0d9488;"></i> Panel Verifikasi</h6>
                </div>
                <div class="card-body p-4">
                    @if(in_array($report->status, ['pending', 'dilaporkan']))
                        <p class="small text-muted mb-3" style="line-height: 1.5;">
                            Tinjau laporan ini. Ketika Anda menekan <strong>Setujui</strong>, laporan otomatis terverifikasi dan diteruskan ke Ketua Jurusan <strong>{{ $report->reported_department }}</strong>.
                        </p>

                        <!-- Tombol Setujui & Tolak Berderet Rapi -->
                        <div class="d-flex align-items-center" style="gap: 10px;">
                            <!-- Form Setujui (Otomatis Terkirim ke Kajur Sesuai Jurusan Terlapor) -->
                            <form action="{{ route('reports.approve', $report->id) }}" method="POST" class="flex-fill m-0">
                                @csrf
                                <input type="hidden" name="target_department" value="{{ $report->reported_department }}">
                                <button type="submit" class="btn btn-block font-weight-bold text-white shadow-sm py-2" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); border-radius: 10px; font-size: 0.92rem;" onclick="return confirm('Apakah Anda yakin ingin MENYETUJUI laporan ini dan meneruskannya ke Ketua Jurusan {{ $report->reported_department }}?')">
                                    <i class="fas fa-check-circle mr-1"></i> Setujui
                                </button>
                            </form>

                            <!-- Tombol Tolak Laporan (Membuka Modal Alasan Penolakan) -->
                            <button type="button" class="btn btn-outline-danger flex-fill font-weight-bold shadow-sm py-2" style="border-radius: 10px; font-size: 0.92rem;" data-toggle="modal" data-target="#modalTolak">
                                <i class="fas fa-times-circle mr-1"></i> Tolak
                            </button>
                        </div>
                    @else
                        <div class="p-3 rounded text-center" style="background: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 10px;">
                            <i class="fas fa-check-circle fa-2x mb-2" style="color: #0d9488;"></i>
                            <div class="font-weight-bold text-dark" style="font-size: 0.92rem;">Laporan Terverifikasi</div>
                            <small class="text-muted d-block mt-1">
                                Laporan telah diteruskan ke Ketua Jurusan <strong>{{ $report->target_department ?? $report->reported_department }}</strong>.
                            </small>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- PANEL READ-ONLY KAJUR: Laporan sudah diterima, tidak ada aksi -->
            @if(auth()->user()->isKajur())
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 16px; border: 1px solid #ccfbf1 !important;">
                <div class="card-header py-3 px-4 border-bottom" style="background: #f0fdfa;">
                    <h6 class="m-0 font-weight-bold" style="color: #0f766e;"><i class="fas fa-check-circle mr-1"></i> Laporan Diterima oleh Jurusan</h6>
                </div>
                <div class="card-body p-4">
                    <p class="small text-muted mb-0">
                        Laporan ini telah diverifikasi oleh Kemahasiswaan dan diteruskan ke Jurusan <strong>{{ auth()->user()->department }}</strong>. Lakukan tindak lanjut sesuai prosedur internal jurusan.
                    </p>
                </div>
            </div>
            @endif

            <!-- PANEL READ-ONLY DEKAN & WAKIL REKTOR -->
            @if(auth()->user()->isDekan() || auth()->user()->isWarek() || auth()->user()->isWadek3())
            <div class="card shadow-sm mb-4 border-0" style="border-radius: 16px; border: 1px solid #e2e8f0 !important;">
                <div class="card-header py-3 px-4 bg-white border-bottom">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-eye mr-1" style="color: #0d9488;"></i> Hak Akses Monitoring (Read-Only)</h6>
                </div>
                <div class="card-body p-4">
                    <p class="small text-muted mb-2">
                        Akun <strong>{{ auth()->user()->role_title }}</strong> memiliki wewenang pemantauan eksekutif untuk mengevaluasi kronologi, bukti, serta tindak lanjut dari pimpinan jurusan.
                    </p>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Modal Koreksi Data Pelanggar (Khusus Kemahasiswaan) -->
    @if(auth()->user()->isKemahasiswaan())
    <div class="modal fade" id="modalKoreksi" tabindex="-1" role="dialog" aria-labelledby="modalKoreksiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <form action="{{ route('reports.koreksi', $report->id) }}" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #134e4a 0%, #0d9488 100%);">
                        <h5 class="modal-title font-weight-bold" id="modalKoreksiLabel">
                            <i class="fas fa-edit mr-1"></i> Koreksi Data Mahasiswa Terlapor
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info small mb-4 border-0" style="background: #f0fdfa; color: #0f766e; border-left: 4px solid #0d9488 !important; border-radius: 8px;">
                            <i class="fas fa-info-circle mr-1"></i> Form ini digunakan jika terdapat salah ketik pada data yang dilaporkan sebelum diteruskan ke Ketua Jurusan.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-dark">Nama Terlapor</label>
                                <input type="text" name="reported_name" value="{{ old('reported_name', $report->reported_name) }}" required class="form-control" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-dark">NIM Terlapor</label>
                                <input type="text" name="reported_nim" value="{{ old('reported_nim', $report->reported_nim) }}" required class="form-control" style="border-radius: 8px;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-dark">Jurusan Terlapor</label>
                                <select name="reported_department" required class="form-control" style="border-radius: 8px;">
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ $report->reported_department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-dark">Kategori Pelanggaran</label>
                                <select name="severity" required class="form-control" style="border-radius: 8px;">
                                    @foreach($severities as $sev)
                                        <option value="{{ $sev }}" {{ $report->severity === $sev ? 'selected' : '' }}>{{ $sev }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" name="points" value="0">
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark">Lokasi Kejadian</label>
                            <input type="text" name="incident_location" value="{{ old('incident_location', $report->incident_location) }}" required class="form-control" style="border-radius: 8px;">
                        </div>

                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-dark">Koreksi Kronologi (Jika Diperlukan)</label>
                            <textarea name="chronology" rows="3" required class="form-control" style="border-radius: 8px;">{{ old('chronology', $report->chronology) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary font-weight-bold px-3 py-2" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                        <button type="submit" class="btn font-weight-bold text-white px-3 py-2" style="background: #0d9488; border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan Koreksi Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Laporan (Khusus Kemahasiswaan) -->
    <div class="modal fade" id="modalTolak" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{ route('reports.reject', $report->id) }}" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h6 class="modal-title font-weight-bold">
                        <i class="fas fa-times-circle mr-1"></i> Tolak Laporan Ini
                    </h6>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">
                        Tuliskan alasan penolakan laporan ini secara objektif dan jelas:
                    </p>
                    <div class="form-group mb-0">
                        <textarea name="rejection_reason" rows="3" required class="form-control" style="border-radius: 8px;" placeholder="Contoh: Bukti tidak memadai atau data terlapor tidak sesuai..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary font-weight-bold px-3 py-2" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger font-weight-bold px-3 py-2" style="border-radius: 8px;">
                        <i class="fas fa-ban mr-1"></i> Konfirmasi Tolak Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</x-layout>
