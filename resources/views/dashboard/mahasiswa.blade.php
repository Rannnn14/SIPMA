<x-layout>
    <x-slot:title>Portal Pelaporan Mahasiswa | SIPMA</x-slot:title>

    <style>
        /* Modern Card & Input Styling */
        .feature-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.25s ease-in-out;
        }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px -8px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        .form-section-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
        }

        .step-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .modern-input-group {
            position: relative;
        }
        .modern-input-group .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 4;
            pointer-events: none;
            transition: color 0.2s;
        }
        .modern-input-group .form-control,
        .modern-input-group .custom-select {
            padding-left: 42px;
            height: 48px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .modern-input-group .form-control:focus,
        .modern-input-group .custom-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        }
        .modern-input-group:focus-within .input-icon {
            color: #10b981;
        }

        /* Severity Radio Option Cards */
        .severity-option {
            cursor: pointer;
            margin-bottom: 0;
            width: 100%;
        }
        .severity-option input[type="radio"] {
            display: none;
        }
        .severity-box {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 16px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
        }
        .severity-box:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .severity-option input[type="radio"]:checked + .severity-box.sev-ringan {
            border-color: #10b981;
            background-color: #f0fdf4;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
        }
        .severity-option input[type="radio"]:checked + .severity-box.sev-sedang {
            border-color: #f59e0b;
            background-color: #fffbeb;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
        }
        .severity-option input[type="radio"]:checked + .severity-box.sev-berat {
            border-color: #ef4444;
            background-color: #fef2f2;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
        }

        /* Clock Picker Modal & Dial Styles */
        .clock-dial-container {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            position: relative;
            margin: 0 auto;
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.04);
            user-select: none;
        }
        .clock-center-dot {
            width: 12px;
            height: 12px;
            background: #0f766e;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
        }
        .clock-hand {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 3px;
            height: 85px;
            background: #0f766e;
            transform-origin: 50% 100%;
            transform: translate(-50%, -100%) rotate(0deg);
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 4;
            pointer-events: none;
        }
        .clock-hand-end {
            width: 32px;
            height: 32px;
            background: rgba(15, 118, 110, 0.25);
            border: 2px solid #0f766e;
            border-radius: 50%;
            position: absolute;
            top: -16px;
            left: 50%;
            transform: translateX(-50%);
        }
        .clock-num-btn {
            position: absolute;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 700;
            color: #334155;
            cursor: pointer;
            border-radius: 50%;
            transition: all 0.15s ease;
            z-index: 6;
        }
        .clock-num-btn:hover {
            background-color: #0f766e;
            color: #ffffff;
            transform: scale(1.15);
        }
        .clock-num-btn.active {
            background-color: #0f766e;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(15, 118, 110, 0.4);
        }

        /* Modern File Dropzone */
        .dropzone-box {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            background: #f8fafc;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .dropzone-box:hover {
            border-color: #0f766e;
            background: #f0fdfa;
        }
    </style>

    <!-- Alert Notifikasi Sukses / Error -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 border-0" role="alert" style="border-radius: 12px; background: #ecfdf5; border-left: 5px solid #10b981 !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x text-success mr-3"></i>
                <div>
                    <h6 class="font-weight-bold text-success mb-1">Laporan Berhasil Terkirim!</h6>
                    <p class="small text-muted mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4 border-0" role="alert" style="border-radius: 12px; border-left: 5px solid #ef4444 !important;">
            <i class="fas fa-exclamation-triangle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm mb-4 border-0" style="border-radius: 12px; border-left: 5px solid #ef4444 !important;">
            <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-circle mr-1"></i> Mohon lengkapi isian formulir:</h6>
            <ul class="mb-0 pl-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 1. Hero Welcome Banner (Bagian Selamat Datang) -->
    <div class="card border-0 text-white shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 50%, #14b8a6 100%); border-radius: 16px;">
        <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-md-center justify-content-between position-relative">
            <div style="z-index: 2;">
                <h2 class="font-weight-bold mb-2 text-white">Selamat Datang, {{ auth()->user()->clean_name }}!</h2>
                <p class="text-white-50 mb-4" style="max-width: 680px; font-size: 0.95rem; line-height: 1.6;">
                    Sampaikan laporan pelanggaran tata tertib mahasiswa secara objektif, jujur, dan bertanggung jawab demi terciptanya ketertiban dan integritas kampus.
                </p>
                <div>
                    <a href="#formPelaporan" class="btn btn-light font-weight-bold px-4 py-2 rounded-pill text-dark shadow-sm">
                        <i class="fas fa-paper-plane mr-2" style="color:#0d9488;"></i> Buat Laporan Pelanggaran
                    </a>
                </div>
            </div>
            <div class="d-none d-md-block text-center mt-3 mt-md-0" style="opacity: 0.35; z-index: 1;">
                <i class="fas fa-graduation-cap fa-7x text-white"></i>
            </div>
        </div>
    </div>

        <!-- 3 Modern Feature Cards -->
        <div class="row mb-5">
            <!-- Fitur 1 -->
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card feature-card h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb;">
                            <i class="fas fa-file-signature fa-lg"></i>
                        </div>
                        <span class="badge badge-primary-subtle text-primary font-weight-bold px-3 py-1" style="background-color: #eff6ff; border-radius: 8px; font-size: 0.75rem;">
                            Fitur Utama
                        </span>
                    </div>
                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 1.05rem;">1. Formulir Pelaporan Mandiri</h6>
                    <p class="small text-muted mb-3" style="line-height: 1.6;">
                        Pengisian data pelanggaran secara manual dan terstruktur: Nama Terlapor, NIM, Fakultas & Jurusan, Waktu & Jam Kejadian, Lokasi, Kategori Pelanggaran, dan Uraian Kronologi Lengkap.
                    </p>
                    <div class="mt-auto pt-2 border-top text-xs font-weight-bold text-primary d-flex align-items-center">
                        <i class="fas fa-check-circle mr-1 text-primary"></i> Input Mandiri & Fleksibel
                    </div>
                </div>
            </div>

            <!-- Fitur 2 -->
            <div class="col-md-4 mb-3 mb-md-0">
                <div class="card feature-card h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%); color: #0891b2;">
                            <i class="fas fa-cloud-upload-alt fa-lg"></i>
                        </div>
                        <span class="badge badge-info-subtle text-info font-weight-bold px-3 py-1" style="background-color: #ecfeff; border-radius: 8px; font-size: 0.75rem;">
                            Multi-Format
                        </span>
                    </div>
                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 1.05rem;">2. Unggah Bukti Digital</h6>
                    <p class="small text-muted mb-3" style="line-height: 1.6;">
                        Mendukung lampiran bukti digital kejadian berupa Foto (JPG, PNG), Berkas Dokumen Resmi (PDF), atau Video singkat (MP4) hingga batas 10 MB untuk memperkuat verifikasi kasus.
                    </p>
                    <div class="mt-auto pt-2 border-top text-xs font-weight-bold text-info d-flex align-items-center">
                        <i class="fas fa-check-circle mr-1 text-info"></i> Mendukung JPG, PNG, PDF & MP4
                    </div>
                </div>
            </div>

            <!-- Fitur 3 -->
            <div class="col-md-4">
                <div class="card feature-card h-100 p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #b45309;">
                            <i class="fas fa-clipboard-check fa-lg"></i>
                        </div>
                        <span class="badge badge-warning-subtle font-weight-bold px-3 py-1" style="background-color: #fffbeb; color: #b45309 !important; border-radius: 8px; font-size: 0.75rem;">
                            Alur Resmi
                        </span>
                    </div>
                    <h6 class="font-weight-bold text-dark mb-2" style="font-size: 1.05rem;">3. Penanganan Kasus Terpadu</h6>
                    <p class="small text-muted mb-3" style="line-height: 1.6;">
                        Laporan yang diajukan diverifikasi secara objektif untuk tindak lanjut pembinaan serta sanksi berkeadilan.
                    </p>
                    <div class="mt-auto pt-2 border-top text-xs font-weight-bold d-flex align-items-center" style="color: #b45309;">
                        <i class="fas fa-check-circle mr-1"></i> Verifikasi Laporan
                    </div>
                </div>
            </div>
        </div>

    <!-- 2. Formulir Langsung Pelaporan Pelanggaran Mahasiswa -->
    <div class="card form-section-card mt-4 mb-5 overflow-hidden" id="formPelaporan">
        <!-- Card Header Modern & Refined UI/UX -->
        <div class="card-header py-4 px-4 px-md-5 bg-white border-bottom">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center text-white mr-3 shadow-sm flex-shrink-0" style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%);">
                    <i class="fas fa-file-signature fa-lg"></i>
                </div>
                <div>
                    <h5 class="m-0 font-weight-bold text-dark" style="font-size: 1.25rem; letter-spacing: -0.2px;">
                        Formulir Pelaporan Pelanggaran Mahasiswa
                    </h5>
                    <small class="text-muted">Lengkapi data secara objektif, jujur, dan bertanggung jawab demi ketertiban kampus.</small>
                </div>
            </div>
        </div>

        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="card-body p-4 p-md-5">
            @csrf

            <!-- ==========================================
                 LANGKAH 1: IDENTITAS MAHASISWA TERLAPOR
                 ========================================== -->
            <div class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <span class="step-pill mr-2 text-white" style="background-color: #0f766e;">Langkah 1</span>
                    <h6 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">Identitas Mahasiswa Terlapor</h6>
                </div>
                <div class="p-3 mb-4 rounded-lg" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                    <small class="text-muted">
                        <i class="fas fa-info-circle text-info mr-1"></i> Masukkan data mahasiswa yang dilaporkan. Jika ada ketidakpastian data, Bagian Kemahasiswaan akan melakukan koreksi verifikasi basis data kampus.
                    </small>
                </div>

                <div class="row">
                    <!-- Nama Terlapor -->
                    <div class="col-md-6 mb-4">
                        <label class="font-weight-bold text-dark small mb-2">Nama Lengkap Terlapor <span class="text-danger">*</span></label>
                        <div class="modern-input-group">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="reported_name" value="{{ old('reported_name') }}" required 
                                class="form-control" placeholder="Contoh: Muhammad Budi Santoso">
                        </div>
                        <small class="text-muted mt-1 d-block">Ketik nama lengkap mahasiswa yang Anda laporkan.</small>
                    </div>

                    <!-- NIM Terlapor -->
                    <div class="col-md-6 mb-4">
                        <label class="font-weight-bold text-dark small mb-2">NIM Mahasiswa Terlapor <span class="text-danger">*</span></label>
                        <div class="modern-input-group">
                            <i class="fas fa-id-card input-icon"></i>
                            <input type="text" name="reported_nim" value="{{ old('reported_nim') }}" required 
                                class="form-control" placeholder="Contoh: 2024002">
                        </div>
                        <small class="text-muted mt-1 d-block">Nomor Induk Mahasiswa pihak terlapor jika diketahui.</small>
                    </div>

                    <!-- Fakultas Terlapor (Baru) -->
                    <div class="col-md-6 mb-4">
                        <label class="font-weight-bold text-dark small mb-2">Fakultas Terlapor <span class="text-danger">*</span></label>
                        <div class="modern-input-group">
                            <i class="fas fa-university input-icon"></i>
                            <select name="reported_faculty" id="facultySelect" required class="form-control custom-select">
                                <option value="">-- Pilih Fakultas --</option>
                                @foreach($faculties as $facName => $deptList)
                                    <option value="{{ $facName }}" {{ old('reported_faculty') === $facName ? 'selected' : '' }}>
                                        {{ $facName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-muted mt-1 d-block">Pilih fakultas asal mahasiswa terlapor.</small>
                    </div>

                    <!-- Jurusan / Program Studi Terlapor -->
                    <div class="col-md-6 mb-4">
                        <label class="font-weight-bold text-dark small mb-2">Jurusan / Program Studi Terlapor <span class="text-danger">*</span></label>
                        <div class="modern-input-group">
                            <i class="fas fa-graduation-cap input-icon"></i>
                            <select name="reported_department" id="departmentSelect" required class="form-control custom-select">
                                <option value="">-- Pilih Jurusan / Prodi --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" {{ old('reported_department') === $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="text-muted mt-1 d-block">Pilih program studi / jurusan mahasiswa terlapor.</small>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 LANGKAH 2: WAKTU, TEMPAT & KATEGORI
                 ========================================== -->
            <div class="mb-5 pt-3 border-top">
                <div class="d-flex align-items-center mb-3 mt-3">
                    <span class="step-pill mr-2 text-white" style="background-color: #0f766e;">Langkah 2</span>
                    <h6 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">Waktu, Lokasi, & Kategori Pelanggaran</h6>
                </div>

                <div class="row">
                    <!-- Tanggal Kejadian -->
                    <div class="col-md-4 mb-4">
                        <label class="font-weight-bold text-dark small mb-2">Tanggal Kejadian <span class="text-danger">*</span></label>
                        <div class="modern-input-group">
                            <i class="fas fa-calendar-alt input-icon"></i>
                            <input type="date" name="incident_date" value="{{ old('incident_date', date('Y-m-d')) }}" required class="form-control">
                        </div>
                        <small class="text-muted mt-1 d-block">Tanggal saat peristiwa terjadi.</small>
                    </div>

                    <!-- Jam Kejadian (Bisa Ketik Langsung & Jam Analog) -->
                    <div class="col-md-4 mb-4">
                        <label class="font-weight-bold text-dark small mb-2">
                            Jam Kejadian <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="text" name="incident_time" id="incidentTimeInput" value="{{ old('incident_time', date('H:i')) }}" 
                                class="form-control" placeholder="14:30" maxlength="5" autocomplete="off"
                                style="border: 1.5px solid #e2e8f0; height: 48px; border-top-left-radius: 10px; border-bottom-left-radius: 10px; font-weight: 700; font-size: 1.05rem; letter-spacing: 1px; color: #0f766e; background: #ffffff;">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary px-3" type="button" onclick="openClockPicker()" style="border: 1.5px solid #e2e8f0; border-left: 0; border-top-right-radius: 10px; border-bottom-right-radius: 10px; background-color: #f8fafc;" title="Pilih menggunakan jam analog">
                                    <i class="fas fa-clock" style="color: #0f766e;"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">Waktu Saat Peristiwa Terjadi.</small>
                    </div>

                    <!-- Lokasi Kejadian -->
                    <div class="col-md-4 mb-4">
                        <label class="font-weight-bold text-dark small mb-2">Lokasi Kejadian <span class="text-danger">*</span></label>
                        <div class="modern-input-group">
                            <i class="fas fa-map-marker-alt input-icon"></i>
                            <input type="text" name="incident_location" value="{{ old('incident_location') }}" required 
                                class="form-control" placeholder="Contoh: Gedung B Lantai 2 / Lab Komputer">
                        </div>
                        <small class="text-muted mt-1 d-block">Ruang, gedung, atau area spesifik.</small>
                    </div>
                </div>

                <!-- Kategori Pelanggaran (Hanya Ringan, Sedang, Berat) -->
                <div class="mb-3">
                    <label class="font-weight-bold text-dark small mb-2">
                        Perkiraan Kategori Pelanggaran <span class="text-danger">*</span>
                    </label>
                    <div class="row">
                        <!-- Ringan -->
                        <div class="col-md-4 mb-2 mb-md-0">
                            <label class="severity-option">
                                <input type="radio" name="severity" value="Ringan" {{ old('severity', 'Ringan') === 'Ringan' ? 'checked' : '' }} required>
                                <div class="severity-box sev-ringan">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; background: #ecfdf5; color: #10b981;">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">Pelanggaran Ringan</div>
                                            <div class="text-xs text-muted">Contoh: Pakaian, ketertiban umum</div>
                                        </div>
                                    </div>
                                    <span class="badge badge-success px-2 py-1 text-xs">Ringan</span>
                                </div>
                            </label>
                        </div>

                        <!-- Sedang -->
                        <div class="col-md-4 mb-2 mb-md-0">
                            <label class="severity-option">
                                <input type="radio" name="severity" value="Sedang" {{ old('severity') === 'Sedang' ? 'checked' : '' }} required>
                                <div class="severity-box sev-sedang">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; background: #fffbeb; color: #f59e0b;">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">Pelanggaran Sedang</div>
                                            <div class="text-xs text-muted">Contoh: Etika, keributan kelas</div>
                                        </div>
                                    </div>
                                    <span class="badge badge-warning text-dark px-2 py-1 text-xs">Sedang</span>
                                </div>
                            </label>
                        </div>

                        <!-- Berat -->
                        <div class="col-md-4">
                            <label class="severity-option">
                                <input type="radio" name="severity" value="Berat" {{ old('severity') === 'Berat' ? 'checked' : '' }} required>
                                <div class="severity-box sev-berat">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; background: #fef2f2; color: #ef4444;">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">Pelanggaran Berat</div>
                                            <div class="text-xs text-muted">Contoh: Kecurangan akademik, kekerasan</div>
                                        </div>
                                    </div>
                                    <span class="badge badge-danger px-2 py-1 text-xs">Berat</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 LANGKAH 3: KRONOLOGI & BERKAS BUKTI
                 ========================================== -->
            <div class="mb-4 pt-3 border-top">
                <div class="d-flex align-items-center mb-3 mt-3">
                    <span class="step-pill mr-2 text-white" style="background-color: #0f766e;">Langkah 3</span>
                    <h6 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">Kronologi Kejadian & Unggah Bukti</h6>
                </div>

                <!-- Deskripsi Kronologi -->
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark small mb-2">Deskripsi Lengkap Kronologi Kejadian <span class="text-danger">*</span></label>
                    <textarea name="chronology" rows="5" required class="form-control p-3" 
                        style="border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.95rem; line-height: 1.6;"
                        placeholder="Uraikan peristiwa dengan jelas: bagaimana awal mula kejadian, siapa saja yang berada di tempat kejadian, apa tindakan pelanggaran yang dilakukan, dsb...">{{ old('chronology') }}</textarea>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-pen-nib mr-1 text-muted"></i> Berikan rincian secara runtut dan objektif sesuai apa yang Anda saksikan.
                    </small>
                </div>

                <!-- Unggah Berkas Bukti (Modern Dropzone Box) -->
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark small mb-2">Unggah Berkas Bukti Pendukung (Opsional)</label>
                    
                    <div class="dropzone-box" onclick="document.getElementById('evidenceFile').click()">
                        <input type="file" name="evidence" id="evidenceFile" accept=".pdf,.jpg,.jpeg,.png,.mp4" style="display: none;">
                        <div class="mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto text-primary" style="width: 52px; height: 52px; background: #eff6ff;">
                                <i class="fas fa-cloud-upload-alt fa-2x"></i>
                            </div>
                        </div>
                        <h6 class="font-weight-bold text-dark mb-1" id="fileNameDisplay">
                            Klik di sini untuk memilih atau menarik berkas bukti
                        </h6>
                        <p class="text-muted small mb-2" id="fileSubDisplay">
                            Format yang didukung: <strong>PDF, JPG, PNG, atau MP4</strong> (Maksimal ukuran file: <strong>10 MB</strong>)
                        </p>
                        <div class="d-flex justify-content-center flex-wrap">
                            <span class="badge badge-light border text-muted mr-1 mb-1">📄 PDF Dokumen</span>
                            <span class="badge badge-light border text-muted mr-1 mb-1">🖼️ JPG / PNG Foto</span>
                            <span class="badge badge-light border text-muted mb-1">🎥 MP4 Rekaman Video</span>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- Footer & Tombol Aksi -->
            <div class="d-flex justify-content-end align-items-center pt-2">
                <button type="reset" class="btn btn-outline-secondary font-weight-bold px-4 py-2 mr-2" style="border-radius: 10px; height: 46px;">
                    Reset
                </button>
                <button type="submit" class="btn btn-primary font-weight-bold px-4 py-2 shadow-sm d-flex align-items-center" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); border: 0; border-radius: 10px; height: 46px; font-size: 0.95rem;">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan Pelanggaran
                </button>
            </div>
        </form>
    </div>

    <!-- =======================================================
         MODAL CLOCK PICKER (BENTUK JAM ANALOG INTERAKTIF)
         ======================================================= -->
    <div class="modal fade" id="clockPickerModal" tabindex="-1" role="dialog" aria-hidden="true" style="backdrop-filter: blur(4px);">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 360px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <!-- Header Jam Digital -->
                <div class="p-4 text-white text-center" style="background: linear-gradient(135deg, #1e1b4b 0%, #0f766e 100%);">
                    <div class="text-xs text-uppercase font-weight-bold mb-2" style="letter-spacing: 1px; opacity: 0.85;">
                        Pilih Jam Kejadian 
                    </div>
                    <div class="d-flex align-items-center justify-content-center my-2">
                        <!-- Hour Display Button -->
                        <div id="hourDisplayBtn" class="font-weight-bold px-3 py-1 rounded" onclick="setClockMode('hour')"
                            style="font-size: 2.8rem; line-height: 1; cursor: pointer; background: rgba(255,255,255,0.2);">
                            14
                        </div>
                        <span class="mx-2 font-weight-bold" style="font-size: 2.5rem; line-height: 1;">:</span>
                        <!-- Minute Display Button -->
                        <div id="minuteDisplayBtn" class="font-weight-bold px-3 py-1 rounded" onclick="setClockMode('minute')"
                            style="font-size: 2.8rem; line-height: 1; cursor: pointer; opacity: 0.65;">
                            30
                        </div>
                        <!-- AM / PM Toggle -->
                        <div class="d-flex flex-column ml-3">
                            <span id="ampmAmBtn" class="badge badge-light mb-1 px-2 py-1" style="cursor: pointer; opacity: 0.6;" onclick="setClockPeriod('AM')">AM</span>
                            <span id="ampmPmBtn" class="badge badge-warning px-2 py-1 font-weight-bold" style="cursor: pointer;" onclick="setClockPeriod('PM')">PM</span>
                        </div>
                    </div>
                </div>

                <!-- Clock Dial Body -->
                <div class="modal-body p-4 bg-white text-center">
                    <!-- Dial Container -->
                    <div class="clock-dial-container" id="clockDial">
                        <div class="clock-center-dot"></div>
                        <div class="clock-hand" id="clockHand">
                            <div class="clock-hand-end"></div>
                        </div>
                        <!-- Clock Numbers generated by JavaScript -->
                        <div id="clockNumbersWrapper"></div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="modal-footer bg-light px-3 py-2 border-top d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold px-3 py-2" data-dismiss="modal" style="border-radius: 8px; font-size: 0.85rem;">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-sm btn-light border font-weight-bold px-3 py-2 mr-2 text-dark" onclick="setNowTime()" style="border-radius: 8px; font-size: 0.85rem;">
                            <i class="fas fa-clock text-info mr-1"></i> Sekarang
                        </button>
                        <button type="button" class="btn btn-sm btn-primary font-weight-bold px-3 py-2 text-white" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); border: 0; border-radius: 8px; font-size: 0.85rem;" onclick="confirmTimeSelection()">
                            <i class="fas fa-check mr-1"></i> Terapkan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Interaktif: Clock Picker, Sinkronisasi Fakultas & Jurusan, dan File Preview -->
    <script>
        // Data mapping Fakultas <-> Jurusan
        const facultyDepartmentMap = @json($faculties);
        const departmentFacultyMap = @json(App\Http\Controllers\ReportController::$departments);

        document.addEventListener('DOMContentLoaded', function () {
            const facultySelect = document.getElementById('facultySelect');
            const departmentSelect = document.getElementById('departmentSelect');

            // 1. Saat Fakultas dipilih, filter daftar Jurusan yang sesuai
            if (facultySelect && departmentSelect) {
                facultySelect.addEventListener('change', function () {
                    const selectedFaculty = this.value;
                    const currentSelectedDept = departmentSelect.value;

                    if (!selectedFaculty) return;

                    const allowedDepts = facultyDepartmentMap[selectedFaculty] || [];
                    let hasCurrentDept = false;

                    // Rebuild department options
                    departmentSelect.innerHTML = '<option value="">-- Pilih Jurusan / Prodi --</option>';
                    allowedDepts.forEach(dept => {
                        const opt = document.createElement('option');
                        opt.value = dept;
                        opt.textContent = dept;
                        if (dept === currentSelectedDept) {
                            opt.selected = true;
                            hasCurrentDept = true;
                        }
                        departmentSelect.appendChild(opt);
                    });

                    if (!hasCurrentDept && allowedDepts.length > 0) {
                        departmentSelect.value = allowedDepts[0];
                    }
                });

                // 2. Saat Jurusan dipilih, otomatis cocokkan Fakultas jika belum dipilih
                departmentSelect.addEventListener('change', function () {
                    const selectedDept = this.value;
                    if (selectedDept && departmentFacultyMap[selectedDept]) {
                        facultySelect.value = departmentFacultyMap[selectedDept];
                    }
                });
            }

            // 3. File Input Display Feedback
            const fileInput = document.getElementById('evidenceFile');
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const fileSubDisplay = document.getElementById('fileSubDisplay');
            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        fileNameDisplay.innerHTML = '<span class="text-success"><i class="fas fa-check-circle mr-1"></i> ' + file.name + '</span>';
                        fileSubDisplay.innerHTML = 'Ukuran berkas: <strong>' + (file.size / (1024 * 1024)).toFixed(2) + ' MB</strong> | Tipe: ' + file.type;
                    }
                });
            }
        });

        // ==========================================
        // LOGIKA INTERAKTIF CLOCK-FACE (BENTUK JAM)
        // ==========================================
        let currentClockMode = 'hour'; // 'hour' or 'minute'
        let selectedHour12 = 2; // 1 to 12
        let selectedMinute = 30; // 0 to 59
        let selectedPeriod = 'PM'; // 'AM' or 'PM'

        function openClockPicker() {
            // Inisialisasi dari input field jika ada
            const rawVal = document.getElementById('incidentTimeInput').value;
            if (rawVal && rawVal.includes(':')) {
                const parts = rawVal.split(':');
                let h24 = parseInt(parts[0], 10);
                selectedMinute = parseInt(parts[1], 10) || 0;
                if (h24 >= 12) {
                    selectedPeriod = 'PM';
                    selectedHour12 = h24 === 12 ? 12 : (h24 - 12);
                } else {
                    selectedPeriod = 'AM';
                    selectedHour12 = h24 === 0 ? 12 : h24;
                }
            }

            currentClockMode = 'hour';
            updateClockDisplay();
            renderClockDial();
            $('#clockPickerModal').modal('show');
        }

        function setClockPeriod(period) {
            selectedPeriod = period;
            updateClockDisplay();
        }

        function setClockMode(mode) {
            currentClockMode = mode;
            updateClockDisplay();
            renderClockDial();
        }

        function updateClockDisplay() {
            // Format 24 Jam untuk output
            let h24 = selectedHour12;
            if (selectedPeriod === 'PM') {
                h24 = (selectedHour12 === 12) ? 12 : (selectedHour12 + 12);
            } else {
                h24 = (selectedHour12 === 12) ? 0 : selectedHour12;
            }

            const hStr = String(h24).padStart(2, '0');
            const mStr = String(selectedMinute).padStart(2, '0');

            document.getElementById('hourDisplayBtn').textContent = hStr;
            document.getElementById('minuteDisplayBtn').textContent = mStr;

            // Highlight tab yang aktif
            if (currentClockMode === 'hour') {
                document.getElementById('hourDisplayBtn').style.background = 'rgba(255,255,255,0.25)';
                document.getElementById('hourDisplayBtn').style.opacity = '1';
                document.getElementById('minuteDisplayBtn').style.background = 'transparent';
                document.getElementById('minuteDisplayBtn').style.opacity = '0.65';
            } else {
                document.getElementById('minuteDisplayBtn').style.background = 'rgba(255,255,255,0.25)';
                document.getElementById('minuteDisplayBtn').style.opacity = '1';
                document.getElementById('hourDisplayBtn').style.background = 'transparent';
                document.getElementById('hourDisplayBtn').style.opacity = '0.65';
            }

            // AM / PM buttons styling
            if (selectedPeriod === 'AM') {
                document.getElementById('ampmAmBtn').className = 'badge badge-warning px-2 py-1 font-weight-bold mb-1';
                document.getElementById('ampmAmBtn').style.opacity = '1';
                document.getElementById('ampmPmBtn').className = 'badge badge-light px-2 py-1';
                document.getElementById('ampmPmBtn').style.opacity = '0.6';
            } else {
                document.getElementById('ampmPmBtn').className = 'badge badge-warning px-2 py-1 font-weight-bold';
                document.getElementById('ampmPmBtn').style.opacity = '1';
                document.getElementById('ampmAmBtn').className = 'badge badge-light px-2 py-1 mb-1';
                document.getElementById('ampmAmBtn').style.opacity = '0.6';
            }
        }

        function renderClockDial() {
            const wrapper = document.getElementById('clockNumbersWrapper');
            const hand = document.getElementById('clockHand');
            wrapper.innerHTML = '';

            const radius = 95; // pixels dari pusat
            const centerX = 125;
            const centerY = 125;

            if (currentClockMode === 'hour') {
                // Jam 1 sampai 12
                const hourNumbers = [12, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
                hourNumbers.forEach((val, idx) => {
                    const angleDeg = idx * 30; // 0, 30, 60...
                    const angleRad = (angleDeg * Math.PI) / 180;
                    const x = centerX + radius * Math.sin(angleRad);
                    const y = centerY - radius * Math.cos(angleRad);

                    const el = document.createElement('div');
                    el.className = 'clock-num-btn' + (val === selectedHour12 ? ' active' : '');
                    el.style.left = (x - 16) + 'px';
                    el.style.top = (y - 16) + 'px';
                    el.textContent = val;

                    el.onclick = function () {
                        selectedHour12 = val;
                        const targetDeg = (val % 12) * 30;
                        hand.style.transform = `translate(-50%, -100%) rotate(${targetDeg}deg)`;
                        updateClockDisplay();
                        renderClockDial();

                        // Otomatis transisi ke pemilihan Menit setelah 300ms
                        setTimeout(() => {
                            setClockMode('minute');
                        }, 300);
                    };

                    wrapper.appendChild(el);
                });

                // Set posisi jarum jam
                const currentDeg = (selectedHour12 % 12) * 30;
                hand.style.transform = `translate(-50%, -100%) rotate(${currentDeg}deg)`;
            } else {
                // Menit: 00, 05, 10, ..., 55
                const minuteNumbers = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55];
                minuteNumbers.forEach((val, idx) => {
                    const angleDeg = idx * 30;
                    const angleRad = (angleDeg * Math.PI) / 180;
                    const x = centerX + radius * Math.sin(angleRad);
                    const y = centerY - radius * Math.cos(angleRad);

                    const el = document.createElement('div');
                    const isActive = Math.round(selectedMinute / 5) * 5 === val || selectedMinute === val;
                    el.className = 'clock-num-btn' + (isActive ? ' active' : '');
                    el.style.left = (x - 16) + 'px';
                    el.style.top = (y - 16) + 'px';
                    el.textContent = String(val).padStart(2, '0');

                    el.onclick = function () {
                        selectedMinute = val;
                        const targetDeg = val * 6; // 360 / 60 = 6 deg per menit
                        hand.style.transform = `translate(-50%, -100%) rotate(${targetDeg}deg)`;
                        updateClockDisplay();
                        renderClockDial();
                    };

                    wrapper.appendChild(el);
                });

                // Set posisi jarum menit
                const currentDeg = selectedMinute * 6;
                hand.style.transform = `translate(-50%, -100%) rotate(${currentDeg}deg)`;
            }
        }

        function setNowTime() {
            const now = new Date();
            let h = now.getHours();
            selectedMinute = now.getMinutes();
            if (h >= 12) {
                selectedPeriod = 'PM';
                selectedHour12 = h === 12 ? 12 : (h - 12);
            } else {
                selectedPeriod = 'AM';
                selectedHour12 = h === 0 ? 12 : h;
            }
            updateClockDisplay();
            renderClockDial();
        }

        function confirmTimeSelection() {
            let h24 = selectedHour12;
            if (selectedPeriod === 'PM') {
                h24 = (selectedHour12 === 12) ? 12 : (selectedHour12 + 12);
            } else {
                h24 = (selectedHour12 === 12) ? 0 : selectedHour12;
            }

            const formatted = String(h24).padStart(2, '0') + ':' + String(selectedMinute).padStart(2, '0');
            document.getElementById('incidentTimeInput').value = formatted;
            $('#clockPickerModal').modal('hide');
        }
    </script>
</x-layout>
