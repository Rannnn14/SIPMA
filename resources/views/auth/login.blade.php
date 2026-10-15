<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk ke Akun | SIPMA - Sistem Pendataan Pelanggaran Mahasiswa</title>
    
    <!-- Bootstrap 4.6 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-navy: #1e1b4b;
            --primary-indigo: #4338ca;
            --accent-teal: #0f766e;
        }
        body {
            background-color: #0f172a;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 10px;
        }
        .login-wrapper {
            max-width: 1050px;
            width: 100%;
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }
        /* Left Info Side */
        .info-side {
            background: linear-gradient(145deg, #1e1b4b 0%, #2e2870 50%, #4338ca 100%);
            color: #ffffff;
            padding: 45px 35px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .info-card-item {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 14px;
            transition: all 0.2s ease;
        }
        .info-card-item:hover {
            background: rgba(255, 255, 255, 0.14);
            transform: translateY(-2px);
        }
        /* Right Form Side */
        .form-side {
            padding: 45px 40px;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-control-custom {
            height: 48px;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            padding-left: 14px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }
        .form-control-custom:focus {
            border-color: #4338ca;
            box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.15);
        }
        .btn-submit {
            height: 48px;
            background: #4338ca;
            color: #ffffff;
            font-weight: 700;
            border-radius: 8px;
            font-size: 1rem;
            border: none;
            transition: all 0.2s;
        }
        .btn-submit:hover {
            background: #3730a3;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(67, 56, 202, 0.3);
        }
        .demo-chip {
            cursor: pointer;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 0.8rem;
            background: #f8fafc;
            color: #334155;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .demo-chip:hover {
            background: #eef2ff;
            border-color: #6366f1;
            color: #4338ca;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="row no-gutters">
            
            <!-- SISI KIRI: INFORMASI & PANDUAN SISTEM -->
            <div class="col-lg-6 info-side">
                <div>
                    <!-- Logo / Brand Header -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-white rounded-circle p-2 text-primary mr-3 d-flex align-items-center justify-content-center shadow" style="width: 48px; height: 48px;">
                            <i class="fas fa-shield-alt fa-2x text-indigo" style="color: #4338ca;"></i>
                        </div>
                        <div>
                            <h3 class="font-weight-bold mb-0 text-white" style="letter-spacing: 1px;">SIPMA</h3>
                            <small class="text-indigo-200 text-uppercase" style="letter-spacing: 0.5px; opacity: 0.85;">Sistem Pelanggaran Mahasiswa</small>
                        </div>
                    </div>

                    <h4 class="font-weight-bold text-white mb-2">
                        Portal Penegakan Disiplin & Etika Akademik
                    </h4>
                    <p class="text-light small mb-4 font-italic" style="opacity: 0.9;">
                        Mewujudkan lingkungan perguruan tinggi yang aman, tertib, dan berkeadilan melalui sistem pelaporan pelanggaran terintegrasi.
                    </p>

                    <!-- Poin-Poin Informasi Web -->
                    <div class="info-card-item">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-user-lock text-warning fa-lg mr-3 mt-1"></i>
                            <div>
                                <h6 class="font-weight-bold text-white mb-1 small text-uppercase">1. Jaminan Anonimitas Pelapor</h6>
                                <p class="small text-light mb-0" style="font-size: 0.8rem; opacity: 0.85;">
                                    Identitas mahasiswa pelapor dilindungi penuh dan disamarkan (Anonymous) di hadapan Kajur, Dekan, maupun Warek untuk menghindari konflik atau intimidasi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="info-card-item">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-network-wired text-info fa-lg mr-3 mt-1"></i>
                            <div>
                                <h6 class="font-weight-bold text-white mb-1 small text-uppercase">2. Alur Kerja Berjenjang (RBAC)</h6>
                                <p class="small text-light mb-0" style="font-size: 0.8rem; opacity: 0.85;">
                                    Laporan mahasiswa diverifikasi terlebih dahulu oleh Bagian Kemahasiswaan, lalu diteruskan ke Ketua Jurusan terkait untuk pemanggilan dan vonis sanksi.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="info-card-item">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-id-card text-success fa-lg mr-3 mt-1"></i>
                            <div>
                                <h6 class="font-weight-bold text-white mb-1 small text-uppercase">3. Akses Login Mahasiswa & Pimpinan</h6>
                                <p class="small text-light mb-0" style="font-size: 0.8rem; opacity: 0.85;">
                                    Mahasiswa dapat login menggunakan <strong>NIM</strong> langsung, sedangkan pengelola/dosen masuk menggunakan username atau email resmi kampus.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top border-white-50 d-flex justify-content-between align-items-center small text-light" style="opacity: 0.75;">
                    <div><i class="fas fa-headset mr-1"></i> Helpdesk Kemahasiswaan</div>
                    <div>SIPMA v2.0 &bull; {{ date('Y') }}</div>
                </div>
            </div>

            <!-- SISI KANAN: FORM LOGIN RESMI -->
            <div class="col-lg-6 form-side">
                <div class="mb-4">
                    <span class="badge badge-light text-primary font-weight-bold px-3 py-1 mb-2 border text-uppercase" style="letter-spacing: 0.5px;">
                        Autentikasi Pengguna
                    </span>
                    <h3 class="font-weight-bold text-dark mb-1">Masuk ke Akun</h3>
                    <p class="text-muted small">Silakan masukkan NIM atau Email dan Kata Sandi Anda untuk melanjutkan.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3 small rounded mb-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i> {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success py-2 px-3 small rounded mb-3">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-warning py-2 px-3 small rounded mb-3">
                        <i class="fas fa-info-circle mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- Form Login Utama -->
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-dark mb-1">
                            NIM / Email / Username <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-user text-muted"></i></span>
                            </div>
                            <input type="text" name="login" id="loginField" value="{{ old('login') }}" required autofocus
                                class="form-control form-control-custom border-left-0"
                                placeholder="Masukkan NIM (Mahasiswa) atau Email">
                        </div>
                        <small class="form-text text-muted" style="font-size: 0.75rem;">
                            Contoh Mahasiswa: <code>2024001</code> | Contoh Kemahasiswaan: <code>kemahasiswaan</code>
                        </small>
                    </div>

                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="small font-weight-bold text-dark mb-0">
                                Kata Sandi <span class="text-danger">*</span>
                            </label>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-lock text-muted"></i></span>
                            </div>
                            <input type="password" name="password" id="passwordField" required
                                class="form-control form-control-custom border-left-0 border-right-0"
                                placeholder="••••••••">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary border-left-0 bg-white" type="button" id="togglePasswordBtn">
                                    <i class="fas fa-eye text-muted" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember" class="custom-control-input" id="rememberMe">
                            <label class="custom-control-label small text-muted font-weight-normal" for="rememberMe">
                                Ingat saya di perangkat ini
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit btn-block shadow-sm">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk ke Sistem
                    </button>
                </form>

                <!-- Panel Bantuan Akun Demo / Autofill -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small font-weight-bold text-muted text-uppercase">
                            <i class="fas fa-key text-warning mr-1"></i> Data Akun Demo (Klik untuk Isi Form)
                        </span>
                        <small class="text-muted">Password: <code>password</code></small>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="demo-chip" onclick="fillForm('2024001', 'password')">
                                <div>
                                    <strong class="d-block text-dark">Mahasiswa</strong>
                                    <small class="text-muted">NIM: 2024001</small>
                                </div>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="demo-chip" onclick="fillForm('kemahasiswaan', 'password')">
                                <div>
                                    <strong class="d-block text-dark">Kemahasiswaan</strong>
                                    <small class="text-muted">User: kemahasiswaan</small>
                                </div>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="demo-chip" onclick="fillForm('kajur_ti', 'password')">
                                <div>
                                    <strong class="d-block text-dark">Kajur TI</strong>
                                    <small class="text-muted">User: kajur_ti</small>
                                </div>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="demo-chip" onclick="fillForm('dekan', 'password')">
                                <div>
                                    <strong class="d-block text-dark">Dekan Fasilkom</strong>
                                    <small class="text-muted">User: dekan</small>
                                </div>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="demo-chip" onclick="fillForm('warek', 'password')">
                                <div>
                                    <strong class="d-block text-dark">Wakil Rektor (Universitas)</strong>
                                    <small class="text-muted">User: warek</small>
                                </div>
                                <i class="fas fa-arrow-right text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Autofill & Toggle Password -->
    <script>
        function fillForm(login, password) {
            document.getElementById('loginField').value = login;
            document.getElementById('passwordField').value = password;
            document.getElementById('passwordField').focus();
        }

        document.getElementById('togglePasswordBtn').addEventListener('click', function () {
            const pwdField = document.getElementById('passwordField');
            const icon = document.getElementById('togglePasswordIcon');
            if (pwdField.type === 'password') {
                pwdField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwdField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>
