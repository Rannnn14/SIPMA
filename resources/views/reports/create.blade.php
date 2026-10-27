<x-layout>
    <x-slot:title>Form Pelaporan Pelanggaran Mahasiswa | SIPMA</x-slot:title>

    <style>
        /* Modern Card & Input Styling */
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
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.12);
        }
        .modern-input-group:focus-within .input-icon {
            color: #0d9488;
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
            background: #0d9488;
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
            background: #0d9488;
            transform-origin: 50% 100%;
            transform: translate(-50%, -100%) rotate(0deg);
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 4;
            pointer-events: none;
        }
        .clock-hand-end {
            width: 32px;
            height: 32px;
            background: rgba(13, 148, 136, 0.25);
            border: 2px solid #0d9488;
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
            background-color: #0d9488;
            color: #ffffff;
            transform: scale(1.15);
        }
        .clock-num-btn.active {
            background-color: #0d9488;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.4);
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
            border-color: #0d9488;
            background: #f0fdfa;
        }
    </style>

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

    <!-- Formulir Pelaporan Pelanggaran Mahasiswa (Langsung di Bagian Atas) -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    <div class="card form-section-card mb-5 overflow-hidden">
        <!-- Card Header Modern & Refined UI/UX -->
        <div class="card-header py-4 px-4 px-md-5 text-white border-0" style="background: linear-gradient(135deg, #134e4a 0%, #0f766e 60%, #0d9488 100%);">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center text-white mr-3 shadow-sm flex-shrink-0" style="width: 48px; height: 48px; border-radius: 12px; background: rgba(255, 255, 255, 0.2);">
                    <i class="fas fa-file-signature fa-lg"></i>
                </div>
                <div>
                    <h5 class="m-0 font-weight-bold text-white" style="font-size: 1.25rem; letter-spacing: -0.2px;">
                        Formulir Pelaporan Pelanggaran Mahasiswa
                    </h5>
                    <small class="text-white-50">Lengkapi data laporan secara objektif, jujur, dan bertanggung jawab demi ketertiban kampus.</small>
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
                    <span class="step-pill mr-2 text-white" style="background-color: #0d9488;">Langkah 1</span>
                    <h6 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">Identitas Mahasiswa Terlapor</h6>
                </div>
                <div class="p-3 mb-4 rounded-lg" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                    <small class="text-muted">
                        <i class="fas fa-info-circle text-primary mr-1"></i> Masukkan identitas mahasiswa yang dilaporkan. Data akan diverifikasi secara teliti sesuai basis data universitas.
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
                        <small class="text-muted mt-1 d-block">Ketik nama lengkap mahasiswa yang dilaporkan.</small>
                    </div>

                    <!-- NIM Terlapor -->
                    <div class="col-md-6 mb-4">
                        <label class="font-weight-bold text-dark small mb-2">NIM Mahasiswa Terlapor <span class="text-danger">*</span></label>
                        <div class="modern-input-group">
                            <i class="fas fa-id-card input-icon"></i>
                            <input type="text" name="reported_nim" value="{{ old('reported_nim') }}" required 
                                class="form-control" placeholder="Contoh: 2024002">
                        </div>
                        <small class="text-muted mt-1 d-block">Nomor Induk Mahasiswa pihak terlapor.</small>
                    </div>

                    <!-- Fakultas Terlapor -->
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

                    <!-- Semester Terlapor (Input Manual Angka Saja) -->
                    <div class="col-md-6 mb-4">
                        <label class="font-weight-bold text-dark small mb-2">
                            Semester Terlapor <span class="text-danger">*</span>
                        </label>
                        <div class="modern-input-group">
                            <i class="fas fa-hashtag input-icon"></i>
                            <input type="text" 
                                   name="reported_semester" 
                                   id="reportedSemesterInput" 
                                   value="{{ old('reported_semester') }}" 
                                   required 
                                   class="form-control" 
                                   placeholder="Contoh: 4"
                                   inputmode="numeric"
                                   pattern="[0-9]+"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                   title="Hanya boleh memasukkan angka">
                        </div>
                        <small class="text-muted mt-1 d-block">
                            <i class="fas fa-info-circle mr-1 text-primary"></i>
                            Ketik semester perkuliahan mahasiswa terlapor saat ini (hanya angka, contoh: 2, 4, 6).
                        </small>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 LANGKAH 2: WAKTU, TEMPAT & KATEGORI
                 ========================================== -->
            <div class="mb-5 pt-3 border-top">
                <div class="d-flex align-items-center mb-3 mt-3">
                    <span class="step-pill mr-2 text-white" style="background-color: #0d9488;">Langkah 2</span>
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
                                style="border: 1.5px solid #e2e8f0; height: 48px; border-top-left-radius: 10px; border-bottom-left-radius: 10px; font-weight: 700; font-size: 1.05rem; letter-spacing: 1px; color: #0d9488; background: #ffffff;">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary px-3" type="button" onclick="openClockPicker()" style="border: 1.5px solid #e2e8f0; border-left: 0; border-top-right-radius: 10px; border-bottom-right-radius: 10px; background-color: #f8fafc;" title="Pilih menggunakan jam analog">
                                    <i class="fas fa-clock" style="color: #0d9488;"></i>
                                </button>
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">Waktu saat peristiwa terjadi.</small>
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
                    <span class="step-pill mr-2 text-white" style="background-color: #0d9488;">Langkah 3</span>
                    <h6 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">Kronologi Kejadian & Unggah Bukti</h6>
                </div>

                <!-- Deskripsi Kronologi -->
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark small mb-2">Deskripsi Lengkap Kronologi Kejadian <span class="text-danger">*</span></label>
                    <textarea name="chronology" rows="5" required class="form-control p-3" 
                        style="border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 0.95rem; line-height: 1.6;"
                        placeholder="Uraikan peristiwa dengan jelas: bagaimana awal mula kejadian, siapa saja yang berada di tempat kejadian, apa tindakan pelanggaran yang dilakukan, dsb...">{{ old('chronology') }}</textarea>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-pen-nib mr-1 text-muted"></i> Berikan rincian secara runtut dan objektif.
                    </small>
                </div>

                <!-- Unggah Berkas Bukti (Wajib Diisi) -->
                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark small mb-2">Unggah Berkas Bukti Pendukung <span class="text-danger">*</span></label>
                    
                    <div class="dropzone-box" onclick="document.getElementById('evidenceFile').click()">
                        <input type="file" name="evidence[]" id="evidenceFile" accept=".pdf,.jpg,.jpeg,.png,.mp4" multiple required style="display: none;">
                        <div class="mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto text-primary" style="width: 52px; height: 52px; background: #eff6ff;">
                                <i class="fas fa-cloud-upload-alt fa-2x"></i>
                            </div>
                        </div>
                        <h6 class="font-weight-bold text-dark mb-1" id="fileNameDisplay">
                            Klik di sini untuk memilih 1–4 berkas bukti
                        </h6>
                        <p class="text-muted small mb-2" id="fileSubDisplay">
                            Format: <strong>PDF, JPG, PNG, MP4</strong> · Maksimal <strong>4 file</strong>, masing-masing 10 MB
                        </p>
                        <div id="selectedEvidenceFiles" class="d-flex flex-column align-items-center mt-3"></div>
                        <div class="d-flex justify-content-center flex-wrap">
                            <span class="badge badge-light border text-muted mr-1 mb-1">📄 PDF Dokumen</span>
                            <span class="badge badge-light border text-muted mr-1 mb-1">🖼️ JPG / PNG Foto</span>
                            <span class="badge badge-light border text-muted mb-1">🎥 MP4 Rekaman Video</span>
                        </div>
                        <div class="mt-3" onclick="event.stopPropagation();">
                            <button type="button" id="addEvidenceFile" class="btn btn-sm btn-outline-primary px-3" style="display: none; border-radius: 8px;" onclick="document.getElementById('evidenceFile').click();">
                                <i class="fas fa-plus mr-1"></i> Tambah Lampiran
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- Footer & Tombol Aksi -->
            <div class="d-flex justify-content-end align-items-center pt-2">
                <a href="{{ auth()->user()->isKajur() ? route('reports.index') : (auth()->user()->isKemahasiswaan() ? route('reports.inbox') : route('mahasiswa.dashboard')) }}" class="btn btn-outline-secondary font-weight-bold px-4 py-2 mr-2" style="border-radius: 10px; height: 46px; line-height: 28px;">
                    Batal
                </a>
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
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 380px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <!-- Header Jam Digital -->
                <div class="p-4 text-white text-center" style="background: linear-gradient(135deg, #134e4a 0%, #0d9488 100%);">
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

                <!-- Modal Actions (Berderet Rapi dalam Satu Baris) -->
                <div class="modal-footer bg-light px-3 py-2 border-top d-flex align-items-center justify-content-between flex-nowrap" style="gap: 8px;">
                    <button type="button" class="btn btn-sm btn-outline-secondary font-weight-bold px-2 py-2 flex-fill text-center" data-dismiss="modal" style="border-radius: 8px; font-size: 0.82rem; white-space: nowrap;">
                        <i class="fas fa-times mr-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-sm btn-light border font-weight-bold px-2 py-2 flex-fill text-center text-dark" onclick="setNowTime()" style="border-radius: 8px; font-size: 0.82rem; white-space: nowrap;">
                        <i class="fas fa-clock text-info mr-1"></i> Sekarang
                    </button>
                    <button type="button" class="btn btn-sm btn-primary font-weight-bold px-2 py-2 flex-fill text-center text-white" style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); border: 0; border-radius: 8px; font-size: 0.82rem; white-space: nowrap;" onclick="confirmTimeSelection()">
                        <i class="fas fa-check mr-1"></i> Terapkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Interaktif: Clock Picker, Sinkronisasi Fakultas & Jurusan, dan File Preview -->
    <script>
        // Data mapping Fakultas <-> Jurusan
        const facultyDepartmentMap = @json($faculties);
        const departmentFacultyMap = @json($departmentFacultyMap);

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
            const selectedFilesDisplay = document.getElementById('selectedEvidenceFiles');
            const addFileButton = document.getElementById('addEvidenceFile');
            if (fileInput) {
                let selectedFiles = [];
                fileInput.addEventListener('click', (event) => event.stopPropagation());
                const renderSelectedFiles = () => {
                    const transfer = new DataTransfer();
                    selectedFiles.forEach((file) => transfer.items.add(file));
                    fileInput.files = transfer.files;
                    const files = selectedFiles;
                    selectedFilesDisplay.replaceChildren();
                    fileNameDisplay.textContent = files.length ? files.length + ' berkas dipilih' : 'Klik di sini untuk memilih 1–4 berkas bukti';
                    addFileButton.style.display = files.length > 0 && files.length < 4 ? 'inline-block' : 'none';
                    fileSubDisplay.innerHTML = files.length
                        ? 'Total ukuran: <strong>' + (files.reduce((sum, file) => sum + file.size, 0) / (1024 * 1024)).toFixed(2) + ' MB</strong>'
                        : 'Format: <strong>PDF, JPG, PNG, MP4</strong> · Maksimal <strong>4 file</strong>, masing-masing 10 MB';
                    files.forEach((file, index) => {
                        const row = document.createElement('div');
                        row.className = 'd-flex align-items-center justify-content-between bg-white border rounded px-2 py-1 mb-1';
                        row.style.cssText = 'width: min(100%, 420px); gap: 12px;';
                        const name = document.createElement('span');
                        name.className = 'small text-dark text-truncate';
                        name.textContent = file.name;
                        const remove = document.createElement('button');
                        remove.type = 'button';
                        remove.className = 'btn btn-sm btn-outline-danger py-0 px-2';
                        remove.setAttribute('aria-label', 'Hapus ' + file.name);
                        remove.innerHTML = '<i class="fas fa-times" aria-hidden="true"></i>';
                        remove.addEventListener('click', (event) => {
                            event.stopPropagation();
                            selectedFiles.splice(index, 1);
                            renderSelectedFiles();
                        });
                        row.append(name, remove);
                        selectedFilesDisplay.appendChild(row);
                    });
                };
                fileInput.addEventListener('change', () => {
                    const newlySelected = Array.from(fileInput.files);
                    if (selectedFiles.length + newlySelected.length > 4) {
                        alert('Maksimal 4 berkas bukti yang dapat dipilih.');
                        renderSelectedFiles();
                        return;
                    }
                    selectedFiles = selectedFiles.concat(newlySelected);
                    renderSelectedFiles();
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
