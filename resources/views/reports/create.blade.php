<x-layout>
    <x-slot:title>Form Pelaporan Pelanggaran Mahasiswa | SIPMA</x-slot:title>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-900 font-weight-bold">Form Input Pelaporan Manual</h1>
            <p class="text-muted small mb-0">Laporkan dugaan pelanggaran tata tertib mahasiswa secara objektif dan bertanggung jawab.</p>
        </div>
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Riwayat
        </a>
    </div>

    <!-- Informasi / Panduan & Disclaimer Perlindungan Identitas -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card border-left-info shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-shield-alt text-info fa-2x mr-3"></i>
                        <div>
                            <h6 class="font-weight-bold text-info mb-1">Panduan Pengajuan Laporan & Jaminan Perlindungan Identitas</h6>
                            <p class="small text-muted mb-0">Identitas pelapor dilindungi penuh oleh sistem SIPMA dan disamarkan (Anonymous) di hadapan Ketua Jurusan, Dekan, maupun Wakil Rektor.</p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row small text-dark mt-2">
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-check-circle text-success mr-1"></i> <strong>Syarat Bukti Valid:</strong> Foto, tangkapan layar dokumen, video singkat, atau PDF dokumen pendukung (maks. 10MB).
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-check-circle text-success mr-1"></i> <strong>Verifikasi Berjenjang:</strong> Laporan akan diperiksa dan diverifikasi terlebih dahulu oleh Bagian Kemahasiswaan sebelum diteruskan ke Kajur.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card bg-gradient-light shadow-sm border-left-warning">
                <div class="card-body py-3">
                    <h6 class="font-weight-bold text-dark mb-1 small text-uppercase"><i class="fas fa-exclamation-triangle text-warning mr-1"></i> Disclaimer Pelapor</h6>
                    <p class="small text-muted mb-0" style="font-size: 0.8rem;">
                        Pastikan data dan kronologi yang dilaporkan adalah fakta sebenarnya. Laporan palsu atau fitnah dapat dikenai sanksi pelanggaran etika kampus.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Terdapat kesalahan pengisian form:</h6>
            <ul class="mb-0 pl-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-light d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-pen-alt mr-1"></i> Form Data Terlapor & Detail Kejadian
            </h6>
            <span class="badge badge-primary px-3 py-1">Manual Input</span>
        </div>
        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="card-body p-4">
            @csrf

            <!-- Section 1: Data Terlapor Manual -->
            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                <i class="fas fa-user-edit mr-1 text-primary"></i> 1. Identitas Terlapor (Input Manual)
            </h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Nama Terlapor <span class="text-danger">*</span></label>
                        <input type="text" name="reported_name" value="{{ old('reported_name') }}" required class="form-control" placeholder="Contoh: Budi Santoso">
                        <small class="text-muted">Ketik nama lengkap mahasiswa yang dilaporkan.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">NIM Terlapor <span class="text-danger">*</span></label>
                        <input type="text" name="reported_nim" value="{{ old('reported_nim') }}" required class="form-control" placeholder="Contoh: 2024002">
                        <small class="text-muted">Ketik Nomor Induk Mahasiswa jika diketahui.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Jurusan / Program Studi Terlapor <span class="text-danger">*</span></label>
                        <select name="reported_department" required class="form-control">
                            <option value="">-- Pilih Jurusan / Prodi --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ old('reported_department') === $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilihan seragam agar mudah diteruskan ke Kajur terkait.</small>
                    </div>
                </div>
            </div>

            <!-- Section 2: Detail Kejadian & Kategori -->
            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3 mt-4">
                <i class="fas fa-calendar-alt mr-1 text-primary"></i> 2. Waktu, Tempat, dan Kategori Pelanggaran
            </h6>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Tanggal Kejadian <span class="text-danger">*</span></label>
                        <input type="date" name="incident_date" value="{{ old('incident_date', date('Y-m-d')) }}" required class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Waktu Kejadian (Opsional)</label>
                        <input type="time" name="incident_time" value="{{ old('incident_time') }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold text-dark small">Kategori Pelanggaran <span class="text-danger">*</span></label>
                        <select name="severity" required class="form-control">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($severities as $sev)
                                <option value="{{ $sev }}" {{ old('severity') === $sev ? 'selected' : '' }}>
                                    Pelanggaran {{ $sev }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih perkiraan tingkat pelanggaran (Ringan / Sedang / Berat / Suspek).</small>
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small">Lokasi Kejadian <span class="text-danger">*</span></label>
                <input type="text" name="incident_location" value="{{ old('incident_location') }}" placeholder="Contoh: Gedung B Lantai 2, Ruang Lab Komputer 3" required class="form-control">
            </div>

            <!-- Section 3: Kronologi & Bukti -->
            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3 mt-4">
                <i class="fas fa-align-left mr-1 text-primary"></i> 3. Deskripsi Kejadian & Lampiran Bukti
            </h6>
            <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small">Deskripsi Kronologi Kejadian <span class="text-danger">*</span></label>
                <textarea name="chronology" rows="4" required class="form-control" placeholder="Ceritakan urutan kejadian secara rinci, apa yang terjadi, siapa saja saksi yang ada...">{{ old('chronology') }}</textarea>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold text-dark small">Unggah Berkas Bukti (File Pendukung)</label>
                <div class="custom-file">
                    <input type="file" name="evidence" class="custom-file-input" id="evidenceFile" accept=".pdf,.jpg,.jpeg,.png,.mp4">
                    <label class="custom-file-label" for="evidenceFile">Pilih berkas (PDF, JPG, PNG, atau MP4 - Maksimal 10 MB)</label>
                </div>
                <small class="form-text text-muted">Format file yang didukung: Dokumen PDF, Gambar JPG/PNG, atau Video singkat MP4 (maksimal 10MB).</small>
            </div>

            <hr>

            <div class="d-flex justify-content-end align-items-center mt-4">
                <a href="{{ route('reports.index') }}" class="btn btn-secondary mr-2 font-weight-bold shadow-sm">Batal</a>
                <button type="submit" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm">
                    <i class="fas fa-paper-plane mr-1"></i> Kirim Laporan Sekarang
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const fileInput = document.getElementById('evidenceFile');
            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    const fileName = e.target.files[0]?.name || 'Pilih berkas (PDF, JPG, PNG, atau MP4 - Maksimal 10 MB)';
                    const label = this.nextElementSibling;
                    if (label) label.textContent = fileName;
                });
            }
        });
    </script>
</x-layout>
