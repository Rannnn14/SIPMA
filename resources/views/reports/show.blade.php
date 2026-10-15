<x-layout>
    <x-slot:title>Detail Kasus {{ $report->report_number }} | SIPMA</x-slot:title>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <div class="small font-weight-bold text-muted mb-1">
                <a href="{{ route('reports.index') }}" class="text-primary"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Laporan</a>
            </div>
            <h1 class="h3 mb-0 text-gray-900 font-weight-bold">
                Kasus: {{ $report->report_number }}
            </h1>
        </div>
        
        <div class="mt-3 mt-sm-0">
            <span class="badge {{ $report->status_badge_class }} px-3 py-2 font-weight-bold" style="font-size:0.95rem;">
                <i class="fas fa-info-circle mr-1"></i> Status: {{ $report->status_label }}
            </span>
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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle mr-1"></i> <strong>Perhatian:</strong> {{ $errors->first() }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Kolom Kiri: Informasi Pelanggaran & Kronologi -->
        <div class="col-lg-8">
            <!-- Informasi Pokok Kejadian -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-light d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clipboard-check mr-1"></i> Informasi Kejadian & Pelapor
                    </h6>
                    <span class="badge badge-light border text-dark">
                        Tahun Akademik: {{ $report->academic_year ?? '2026/2027' }} ({{ $report->semester ?? 'Ganjil' }})
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="text-muted small font-weight-bold mb-0">Waktu Pelaporan</label>
                            <p class="font-weight-bold text-dark mb-0">{{ $report->created_at->format('d F Y - H:i') }} WIB</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small font-weight-bold mb-0">Identitas Pelapor</label>
                            <p class="font-weight-bold text-dark mb-0">
                                {{ $report->reporter_display_name }}
                                @if(!auth()->user()->isKemahasiswaan() && $report->reporter_id !== auth()->id())
                                    <span class="badge badge-secondary ml-1" title="Disamarkan untuk melindungi keamanan pelapor"><i class="fas fa-user-secret mr-1"></i> Dilindungi</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-3">
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small font-weight-bold mb-0"><i class="far fa-calendar mr-1"></i> Tanggal Kejadian</label>
                            <p class="font-weight-bold text-dark mb-0">{{ $report->incident_date ? $report->incident_date->format('d F Y') : '-' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small font-weight-bold mb-0"><i class="far fa-clock mr-1"></i> Waktu Kejadian</label>
                            <p class="font-weight-bold text-dark mb-0">{{ $report->incident_time ? date('H:i', strtotime($report->incident_time)) . ' WIB' : 'Tidak ditentukan' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small font-weight-bold mb-0"><i class="fas fa-map-marker-alt mr-1"></i> Lokasi</label>
                            <p class="font-weight-bold text-dark mb-0">{{ $report->incident_location }}</p>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small font-weight-bold mb-0">Kategori / Tingkat</label>
                            <div>
                                <span class="badge badge-primary px-3 py-1 font-weight-bold">
                                    Pelanggaran {{ $report->severity ?? 'Umum' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small font-weight-bold mb-0">Bobot Poin Pelanggaran</label>
                            <div class="h5 font-weight-bold text-danger mb-0">{{ $report->points }} Poin</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kronologi Kejadian -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-align-left mr-1"></i> Kronologi Kejadian</h6>
                </div>
                <div class="card-body">
                    <div class="p-3 bg-light rounded border text-dark" style="white-space: pre-wrap; line-height: 1.6;">
                        {{ $report->chronology }}
                    </div>
                </div>
            </div>

            <!-- Lampiran Berkas Bukti -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-paperclip mr-1"></i> Berkas Lampiran Bukti</h6>
                </div>
                <div class="card-body">
                    @if($report->evidence_path)
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-white shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt fa-2x text-primary mr-3"></i>
                                <div>
                                    <div class="font-weight-bold text-dark">{{ $report->evidence_name ?? 'Berkas_Bukti_Pelanggaran' }}</div>
                                    <small class="text-muted">Bukti yang diunggah pelapor saat submisi manual</small>
                                </div>
                            </div>
                            <div>
                                <a href="{{ asset('storage/' . $report->evidence_path) }}" target="_blank" class="btn btn-sm btn-outline-primary font-weight-bold">
                                    <i class="fas fa-external-link-alt mr-1"></i> Buka / Pratinjau
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-muted small font-italic py-2">
                            <i class="fas fa-info-circle mr-1"></i> Tidak ada file fisik lampiran bukti yang diunggah pada laporan ini.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Riwayat Alasan Penolakan jika ditolak -->
            @if($report->status === 'ditolak')
            <div class="card shadow mb-4 border-left-danger">
                <div class="card-body">
                    <h6 class="font-weight-bold text-danger mb-2"><i class="fas fa-ban mr-1"></i> Alasan Penolakan oleh Kemahasiswaan:</h6>
                    <div class="alert alert-danger mb-0">
                        {{ $report->rejection_reason }}
                    </div>
                    @if($report->verifier)
                        <div class="small text-muted mt-2">Ditolak oleh: <strong>{{ $report->verifier->name }}</strong> pada {{ $report->verified_at ? $report->verified_at->format('d M Y H:i') : '' }}</div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Riwayat Tindak Lanjut Kajur jika ada -->
            @if($report->kajur_notes)
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-gavel mr-1"></i> Hasil Tindak Lanjut Ketua Jurusan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="text-muted small font-weight-bold mb-0">Bentuk Sanksi / Tindakan:</label>
                        <h5 class="font-weight-bold text-dark">{{ $report->sanction_type ?? 'Pembinaan' }}</h5>
                    </div>
                    <div class="mb-2">
                        <label class="text-muted small font-weight-bold mb-0">Catatan Tindak Lanjut:</label>
                        <p class="text-dark bg-light p-3 rounded border mb-0">{{ $report->kajur_notes }}</p>
                    </div>
                    <div class="small text-muted mt-3">
                        Ditangani oleh: <strong>{{ $report->handler?->name ?? 'Ketua Jurusan' }}</strong> 
                        pada {{ $report->handled_at ? $report->handled_at->format('d M Y H:i') : '-' }} WIB
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Kolom Kanan: Data Terlapor & Panel Aksi Workflow Per Role -->
        <div class="col-lg-4">
            
            <!-- Kartu Data Terlapor -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-light d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-user-graduate mr-1"></i> Mahasiswa Terlapor</h6>
                    @if(auth()->user()->isKemahasiswaan())
                        <button type="button" class="btn btn-xs btn-outline-primary" data-toggle="modal" data-target="#modalKoreksi">
                            <i class="fas fa-edit mr-1"></i> Koreksi Data
                        </button>
                    @endif
                </div>
                <div class="card-body text-center">
                    <div class="bg-gray-200 rounded-circle mx-auto d-flex align-items-center justify-content-center text-gray-600 mb-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                        <i class="fas fa-user"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark mb-1">{{ $report->student_name }}</h5>
                    <p class="text-muted small mb-3">NIM: {{ $report->student_nim }}</p>

                    <div class="text-left bg-light p-3 rounded border">
                        <div class="mb-2">
                            <span class="text-muted small font-weight-bold text-uppercase d-block">Jurusan Input Pelapor</span>
                            <span class="font-weight-bold text-dark">{{ $report->reported_department ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold text-uppercase d-block">Jurusan Dikonfirmasi</span>
                            <span class="badge badge-info px-2 py-1 font-weight-bold">
                                {{ $report->target_department ?? $report->reported_department }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- PANEL AKSI KEMAHASISWAAN (VERIFIKATOR)    -->
            <!-- ========================================== -->
            @if(auth()->user()->isKemahasiswaan())
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-user-check mr-1"></i> Panel Verifikasi Kemahasiswaan</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Verifikasi bukti & kesesuaian data terlapor sebelum meneruskannya ke Ketua Jurusan yang bersangkutan.
                    </p>

                    <button type="button" class="btn btn-outline-secondary btn-block font-weight-bold mb-3 shadow-sm" data-toggle="modal" data-target="#modalKoreksi">
                        <i class="fas fa-edit mr-1 text-primary"></i> Koreksi Data Terlapor
                    </button>

                    @if($report->status === 'pending' || $report->status === 'dilaporkan')
                        <!-- Form Setujui & Teruskan -->
                        <form action="{{ route('reports.approve', $report->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Jurusan Tujuan Konfirmasi <span class="text-danger">*</span></label>
                                <select name="target_department" required class="form-control form-control-sm">
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ ($report->target_department ?? $report->reported_department) === $dept ? 'selected' : '' }}>
                                            {{ $dept }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="small font-weight-bold text-dark">Sesuaikan Poin Pelanggaran</label>
                                <input type="number" name="points" value="{{ $report->points }}" min="0" class="form-control form-control-sm">
                            </div>
                            <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm">
                                <i class="fas fa-check-circle mr-1"></i> Setujui & Teruskan ke Kajur
                            </button>
                        </form>

                        <!-- Form Tolak Laporan -->
                        <form action="{{ route('reports.reject', $report->id) }}" method="POST">
                            @csrf
                            <div class="form-group mb-2">
                                <label class="small font-weight-bold text-dark">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" rows="2" required class="form-control form-control-sm" placeholder="Contoh: Bukti tidak memadai / mahasiswa bukan bagian kampus ini..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-outline-danger btn-block font-weight-bold shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menolak laporan ini?')">
                                <i class="fas fa-times-circle mr-1"></i> Tolak Laporan
                            </button>
                        </form>
                    @else
                        <div class="alert alert-light border small text-muted mb-0">
                            Laporan ini telah diproses verifikasinya dengan status: <strong>{{ $report->status_label }}</strong>.
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- ========================================== -->
            <!-- PANEL AKSI KETUA JURUSAN (TINDAK LANJUT)   -->
            <!-- ========================================== -->
            @if(auth()->user()->isKajur())
            <div class="card shadow mb-4 border-left-info">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-gavel mr-1"></i> Tindak Lanjut Kasus (Kajur)</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">
                        Sebagai Ketua Jurusan <strong>{{ auth()->user()->department }}</strong>, Anda berwenang melakukan pemanggilan, memberikan sanksi pembinaan, dan mengubah status kasus.
                    </p>

                    <form action="{{ route('reports.tindakLanjut', $report->id) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark">Ubah Status Penanganan <span class="text-danger">*</span></label>
                            <select name="status" required class="form-control form-control-sm">
                                <option value="diproses" {{ $report->status === 'diproses' ? 'selected' : '' }}>
                                    Sedang Diproses (Pemanggilan / Klarifikasi)
                                </option>
                                <option value="selesai" {{ $report->status === 'selesai' ? 'selected' : '' }}>
                                    Selesai Tindak Lanjut (Sanksi Telah Ditetapkan)
                                </option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark">Bentuk Sanksi / Tindakan <span class="text-danger">*</span></label>
                            <select name="sanction_type" required class="form-control form-control-sm">
                                <option value="">-- Pilih Sanksi --</option>
                                @foreach($sanctionOptions as $opt)
                                    <option value="{{ $opt }}" {{ ($report->sanction_type ?? '') === $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark">Catatan Tindak Lanjut / Berita Acara <span class="text-danger">*</span></label>
                            <textarea name="kajur_notes" rows="4" required class="form-control form-control-sm" placeholder="Catat detail pemanggilan mahasiswa, hasil sidang jurusan, komitmen pembinaan, dll...">{{ old('kajur_notes', $report->kajur_notes) }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Tindak Lanjut
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- ========================================== -->
            <!-- PANEL READ-ONLY DEKAN & WAKIL REKTOR       -->
            <!-- ========================================== -->
            @if(auth()->user()->isDekan() || auth()->user()->isWarek())
            <div class="card shadow mb-4 border-left-secondary">
                <div class="card-header py-3 bg-light">
                    <h6 class="m-0 font-weight-bold text-secondary"><i class="fas fa-eye mr-1"></i> Hak Akses Monitoring (Read-Only)</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">
                        Akun <strong>{{ auth()->user()->role_title }}</strong> memiliki wewenang pemantauan eksekutif. Anda dapat mengevaluasi kronologi, bukti, serta vonis sanksi dari Ketua Jurusan untuk keperluan rapat dan perumusan kebijakan.
                    </p>
                    <a href="{{ route('reports.export.pdf') }}" target="_blank" class="btn btn-sm btn-outline-danger btn-block font-weight-bold mt-2">
                        <i class="fas fa-print mr-1"></i> Cetak Lembar Rekap Kasus
                    </a>
                </div>
            </div>
            @endif

        </div>
    </div>

    <!-- Modal Koreksi Data Pelanggar (Khusus Kemahasiswaan) -->
    @if(auth()->user()->isKemahasiswaan())
    <div class="modal fade" id="modalKoreksi" tabindex="-1" role="dialog" aria-labelledby="modalKoreksiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form action="{{ route('reports.koreksi', $report->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title font-weight-bold" id="modalKoreksiLabel">
                            <i class="fas fa-edit mr-1"></i> Koreksi & Sinkronisasi Data Mahasiswa Terlapor
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info small mb-4">
                            <i class="fas fa-info-circle mr-1"></i> Form ini digunakan oleh Kemahasiswaan jika terdapat salah ketik pada Nama atau NIM yang diinput pelapor secara manual, atau jika perlu menyesuaikan bobot poin sebelum diteruskan ke Kajur.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-dark">Nama Terlapor</label>
                                <input type="text" name="reported_name" value="{{ old('reported_name', $report->reported_name) }}" required class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-dark">NIM Terlapor</label>
                                <input type="text" name="reported_nim" value="{{ old('reported_nim', $report->reported_nim) }}" required class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-dark">Jurusan Terlapor</label>
                                <select name="reported_department" required class="form-control">
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept }}" {{ $report->reported_department === $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-dark">Kategori</label>
                                <select name="severity" required class="form-control">
                                    @foreach($severities as $sev)
                                        <option value="{{ $sev }}" {{ $report->severity === $sev ? 'selected' : '' }}>{{ $sev }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="small font-weight-bold text-dark">Poin Sanksi</label>
                                <input type="number" name="points" value="{{ old('points', $report->points) }}" min="0" class="form-control">
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-dark">Lokasi Kejadian</label>
                            <input type="text" name="incident_location" value="{{ old('incident_location', $report->incident_location) }}" required class="form-control">
                        </div>

                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-dark">Koreksi Kronologi (Jika Diperlukan)</label>
                            <textarea name="chronology" rows="3" required class="form-control">{{ old('chronology', $report->chronology) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-save mr-1"></i> Simpan Koreksi Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</x-layout>
