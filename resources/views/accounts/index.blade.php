<x-layout>
    <x-slot:title>Kelola Akun | SIPMA</x-slot:title>

    <style>
        .accounts-table-scroll {
            max-height: min(72vh, 760px);
            overflow-x: auto;
            overflow-y: auto;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            scrollbar-gutter: stable;
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
            background: #fff;
        }
        .accounts-table-scroll table {
            min-width: 980px;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        .accounts-table-scroll thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: #f8fafc;
            box-shadow: 0 1px 0 #e2e8f0;
            white-space: nowrap;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: #475569;
            padding: 14px 16px;
            border-top: none;
        }
        .accounts-table-scroll tbody td {
            vertical-align: middle;
            padding: 12px 16px;
            border-top: 1px solid #f1f5f9;
        }
        .accounts-table-scroll tbody tr:hover td {
            background-color: #f8fafc;
        }
        .account-actions { min-width: 90px; }
        .account-form-body { max-height: 68vh; overflow-y: auto; }
        .password-reset-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; }
        .password-reset-icon { width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center; border-radius: 12px; color: #0f766e; background: #ccfbf1; }
        .password-reset-card .form-control { min-height: 44px; border-color: #cbd5e1; }
        .password-reset-card .input-group-append .btn { border-color: #cbd5e1; background: #fff; }
        @media (max-width: 767.98px) {
            .accounts-table-scroll { max-height: min(68vh, 720px); }
            .account-search-row .form-group { margin-bottom: .75rem; }
        }
    </style>

    <div class="card mb-4 overflow-hidden border-0 shadow-sm" style="border-radius: 20px;">
        <div class="card-body px-4 px-md-5 py-4 text-white" style="min-height:132px; background:linear-gradient(110deg,#134e4a 0%,#0f766e 58%,#0d9488 100%);">
            <div class="d-flex align-items-center justify-content-between h-100 flex-wrap" style="gap:16px;">
                <div class="d-flex align-items-center" style="gap:16px;">
                <div class="d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width:64px;height:64px;border-radius:16px;background:rgba(255,255,255,.2);">
                    <i class="fas fa-users-cog fa-lg"></i>
                </div>
                <div>
                    <h1 class="m-0 font-weight-bold text-white" style="font-size:1.55rem;letter-spacing:-.3px;">Kelola Akun</h1>
                    <p class="mb-0 mt-1" style="color:rgba(255,255,255,.68);font-size:.98rem;">Kelola akun Ketua Jurusan, Wakil Dekan III, dan Wakil Rektor III.</p>
                </div>
                </div>
                <button type="button" class="btn font-weight-bold px-4 py-2" style="border-radius:999px;color:#fff;background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.32);" data-toggle="modal" data-target="#createAccountModal">
                    <i class="fas fa-plus mr-2"></i>Buat Akun Baru
                </button>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="row mb-4">
        @foreach ([['warek', 'Wakil Rektor III', 'fa-university'], ['wadek3', 'Wakil Dekan III', 'fa-user-tie'], ['kajur', 'Ketua Jurusan', 'fa-graduation-cap']] as [$role, $label, $icon])
            <div class="col-md-4 mb-3">
                <div class="card border-left-primary shadow-sm h-100 py-2">
                    <div class="card-body py-2 d-flex align-items-center justify-content-between">
                        <div><div class="small text-muted font-weight-bold text-uppercase">{{ $label }}</div><div class="h4 mb-0 font-weight-bold text-gray-800">{{ $counts[$role] ?? 0 }}</div></div>
                        <i class="fas {{ $icon }} fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3"><h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users-cog mr-2"></i>Daftar Akun</h6></div>
        <div class="card-body">
            <form method="GET" class="form-row align-items-end mb-3 account-search-row">
                <div class="form-group col-md-3"><label class="small font-weight-bold">Jabatan</label><select name="role" class="form-control"><option value="">Semua jabatan</option>@foreach (['warek' => 'Wakil Rektor III', 'wadek3' => 'Wakil Dekan III', 'kajur' => 'Ketua Jurusan'] as $key => $label)<option value="{{ $key }}" @selected(request('role') === $key)>{{ $label }}</option>@endforeach</select></div>
                <div class="form-group col-md-6"><label class="small font-weight-bold">Cari akun</label><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Nama, username, email, fakultas, atau jurusan"></div>
                <div class="form-group col-md-3 d-flex" style="gap:8px;"><button class="btn btn-outline-primary flex-fill"><i class="fas fa-search mr-1"></i>Cari</button><a href="{{ route('kemahasiswaan.accounts.index') }}" class="btn btn-light" title="Hapus filter"><i class="fas fa-undo"></i></a></div>
            </form>

            <div class="d-flex flex-wrap justify-content-between align-items-center small text-muted mb-2" style="gap:8px;">
                <span>{{ $accounts->count() }} akun ditemukan</span>
            </div>
            <div class="accounts-table-scroll">
                <table class="table table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 17%; min-width: 150px;">Nama</th>
                            <th style="width: 16%; min-width: 140px;">Jabatan</th>
                            <th style="width: 28%; min-width: 230px;">Fakultas / Jurusan</th>
                            <th style="width: 14%; min-width: 120px;">Username</th>
                            <th style="width: 17%; min-width: 160px;">Email</th>
                            <th class="text-right" style="width: 8%; min-width: 85px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accounts as $account)
                            <tr>
                                <td style="white-space: nowrap;">
                                    @if ($account->name)
                                        <span class="font-weight-bold text-dark">{{ $account->name }}</span>
                                    @else
                                        <span class="badge badge-light border text-muted px-2 py-1" style="font-weight: 500; font-size: 0.82rem;">
                                            <i class="fas fa-user-edit mr-1 text-black-50"></i>Belum diisi
                                        </span>
                                    @endif
                                </td>
                                <td style="white-space: nowrap;">
                                    @if ($account->role === 'kajur')
                                        <span class="badge px-2 py-1" style="background:#e0f2fe; color:#0369a1; font-weight:600; font-size:0.82rem;">
                                            <i class="fas fa-graduation-cap mr-1"></i>Ketua Jurusan
                                        </span>
                                    @elseif ($account->role === 'wadek3')
                                        <span class="badge px-2 py-1" style="background:#fef3c7; color:#b45309; font-weight:600; font-size:0.82rem;">
                                            <i class="fas fa-user-tie mr-1"></i>Wakil Dekan III
                                        </span>
                                    @elseif ($account->role === 'warek')
                                        <span class="badge px-2 py-1" style="background:#ede9fe; color:#6d28d9; font-weight:600; font-size:0.82rem;">
                                            <i class="fas fa-university mr-1"></i>Wakil Rektor III
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-2 py-1">{{ ucfirst($account->role) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($account->department)
                                        <div class="font-weight-bold text-dark mb-1" style="font-size: 0.9rem; line-height: 1.35;">{{ $account->department }}</div>
                                        <span class="badge badge-light border text-secondary px-2 py-0" style="font-size: 0.75rem; font-weight: 600;">
                                            {{ \App\Http\Controllers\ReportController::facultyDisplayName($account->faculty) }}
                                        </span>
                                    @else
                                        <div class="font-weight-bold text-dark mb-1" style="font-size: 0.9rem;">
                                            {{ $account->faculty ? \App\Http\Controllers\ReportController::facultyDisplayName($account->faculty) : 'UIN Siber Syekh Nurjati Cirebon' }}
                                        </div>
                                        <small class="text-muted">{{ $account->faculty ?: 'Universitas' }}</small>
                                    @endif
                                </td>
                                <td style="white-space: nowrap;">
                                    <code class="font-weight-bold px-2 py-1 rounded" style="background: #f1f5f9; color: #0d9488; font-size: 0.86rem; border: 1px solid #e2e8f0;">{{ $account->username }}</code>
                                </td>
                                <td style="white-space: nowrap;">
                                    <span class="text-muted small" style="font-weight: 500;"><i class="far fa-envelope mr-1 text-black-50"></i>{{ $account->email }}</span>
                                </td>
                                <td class="text-right text-nowrap account-actions" style="white-space: nowrap;">
                                    <button type="button" class="btn btn-sm btn-outline-primary edit-account" data-toggle="modal" data-target="#editAccountModal"
                                        data-id="{{ $account->id }}" data-name="{{ $account->name }}" data-email="{{ $account->email }}" data-username="{{ $account->username }}" data-role="{{ $account->role }}" data-faculty="{{ $account->faculty }}" data-department="{{ $account->department }}" title="Edit akun" aria-label="Edit akun"><i class="fas fa-pencil-alt"></i></button>
                                    <form method="POST" action="{{ route('kemahasiswaan.accounts.destroy', $account) }}" class="d-inline" onsubmit="return confirm('Hapus akun {{ addslashes($account->username) }}? Akun tidak bisa login lagi, tetapi riwayat laporan akan tetap tersimpan.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus akun" aria-label="Hapus akun"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-5">Belum ada akun untuk filter ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createAccountModal" tabindex="-1" role="dialog" aria-labelledby="createAccountTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form method="POST" action="{{ route('kemahasiswaan.accounts.store') }}" class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
                @csrf
                <input type="hidden" name="account_form" value="create">
                <div class="modal-header text-white border-0" style="background:linear-gradient(110deg,#134e4a,#0d9488);">
                    <div>
                        <h5 class="modal-title font-weight-bold mb-1" id="createAccountTitle"><i class="fas fa-user-plus mr-2"></i>Buat Akun Baru</h5>
                        <small class="text-white-50">Isi identitas akun dan tentukan jabatan serta penempatannya.</small>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body account-form-body p-4">
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-gray-800 mb-1">Identitas akun</h6>
                        <p class="small text-muted mb-3">Nama boleh dikosongkan dan diisi kemudian.</p>
                        <div class="form-row">
                            <div class="form-group col-md-6"><label>Nama pemilik</label><input name="name" value="{{ old('name') }}" class="form-control" maxlength="100" placeholder="Boleh dikosongkan"></div>
                            <div class="form-group col-md-6"><label>Jabatan <span class="text-danger">*</span></label><select name="role" class="form-control account-role" data-scope="create" required><option value="">Pilih jabatan</option><option value="kajur" @selected(old('role') === 'kajur')>Ketua Jurusan</option><option value="wadek3" @selected(old('role') === 'wadek3')>Wakil Dekan III</option><option value="warek" @selected(old('role') === 'warek')>Wakil Rektor III</option></select></div>
                            <div class="form-group col-md-6"><label>Username <span class="text-danger">*</span></label><input name="username" value="{{ old('username') }}" class="form-control" required maxlength="50" placeholder="Username untuk masuk"></div>
                            <div class="form-group col-md-6"><label>Email <span class="text-danger">*</span></label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required maxlength="150" placeholder="nama@kampus.ac.id"></div>
                        </div>
                    </div>

                    <div class="border-top pt-4 mt-1 mb-3">
                        <section class="password-reset-card" aria-labelledby="createPasswordHeading">
                            <div class="d-flex align-items-center mb-3" style="gap:12px;">
                                <span class="password-reset-icon"><i class="fas fa-key"></i></span>
                                <div>
                                    <h6 class="font-weight-bold text-gray-800 mb-1" id="createPasswordHeading">Atur Password</h6>
                                    <small class="text-muted">Tentukan password untuk akun yang akan dibuat.</small>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="createPassword" class="font-weight-bold small">Password <span class="text-danger">*</span></label>
                                    <div class="input-group"><input type="password" name="password" id="createPassword" class="form-control" required minlength="12" maxlength="100" autocomplete="new-password" placeholder="Buat password"><div class="input-group-append"><button class="btn btn-outline-secondary toggle-password" type="button" data-target="createPassword" aria-label="Tampilkan password"><i class="fas fa-eye"></i></button></div></div>
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="createPasswordConfirmation" class="font-weight-bold small">Konfirmasi password <span class="text-danger">*</span></label>
                                    <div class="input-group"><input type="password" name="password_confirmation" id="createPasswordConfirmation" class="form-control" required minlength="12" maxlength="100" autocomplete="new-password" placeholder="Masukkan ulang password"><div class="input-group-append"><button class="btn btn-outline-secondary toggle-password" type="button" data-target="createPasswordConfirmation" aria-label="Tampilkan konfirmasi password"><i class="fas fa-eye"></i></button></div></div>
                                </div>
                            </div>
                            <small class="text-muted"><i class="fas fa-shield-alt mr-1"></i>Minimal 12 karakter, gunakan kombinasi huruf dan angka.</small>
                        </section>
                    </div>

                    <div class="border-top pt-3">
                        <h6 class="font-weight-bold text-gray-800 mb-1">Penempatan</h6>
                        <p class="small text-muted mb-3">Fakultas dan jurusan baru akan langsung tersedia di formulir laporan dan rekap.</p>
                        <div class="form-row">
                            <div class="form-group col-md-6 account-faculty d-none" data-scope="create"><label>Fakultas <span class="text-danger">*</span></label><select name="faculty" class="form-control account-faculty-select" data-scope="create"><option value="">Pilih fakultas</option>@foreach ($faculties as $faculty)<option value="{{ $faculty->name }}" @selected(old('faculty') === $faculty->name)>{{ $faculty->name }}</option>@endforeach<option value="__new__" data-for-role="wadek3" @selected(old('faculty') === '__new__')>+ Tambah fakultas baru</option></select></div>
                            <div class="form-group col-md-6 account-new-faculty d-none" data-scope="create"><label>Nama fakultas baru</label><input name="new_faculty" value="{{ old('new_faculty') }}" class="form-control" maxlength="150" placeholder="Masukkan nama fakultas"></div>
                            <div class="form-group col-md-6 account-department d-none" data-scope="create"><label>Jurusan / Program Studi <span class="text-danger">*</span></label><select name="department" class="form-control account-department-select" data-scope="create"><option value="">Pilih jurusan</option>@foreach ($departments as $department)<option value="{{ $department->name }}" data-faculty="{{ $department->faculty->name }}" @selected(old('department') === $department->name)>{{ $department->name }} — {{ \App\Http\Controllers\ReportController::facultyDisplayName($department->faculty->name) }}</option>@endforeach<option value="__new__" @selected(old('department') === '__new__')>+ Tambah jurusan baru</option></select></div>
                            <div class="form-group col-md-6 account-new-department d-none" data-scope="create"><label>Nama jurusan baru</label><input name="new_department" value="{{ old('new_department') }}" class="form-control" maxlength="150" placeholder="Masukkan nama jurusan"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light font-weight-bold" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary font-weight-bold px-4"><i class="fas fa-check mr-1"></i>Simpan Akun</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="editAccountTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form method="POST" id="editAccountForm" class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
                @csrf @method('PUT')
                <div class="modal-header text-white border-0" style="background:linear-gradient(110deg,#134e4a,#0d9488);">
                    <div>
                        <h5 class="modal-title font-weight-bold mb-1" id="editAccountTitle"><i class="fas fa-user-edit mr-2"></i>Edit Akun</h5>
                        <small class="text-white-50" id="editAccountSubtitle">Perbarui identitas dan penempatan akun.</small>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body account-form-body p-4">
                    <div class="mb-3">
                        <h6 class="font-weight-bold text-gray-800 mb-1">Identitas akun</h6>
                        <p class="small text-muted mb-3"> </p>
                        <div class="form-row">
                            <div class="form-group col-md-6"><label>Nama pemilik</label><input name="name" id="editName" class="form-control" maxlength="100" placeholder="Nama pemilik akun"></div>
                            <div class="form-group col-md-6"><label>Jabatan <span class="text-danger">*</span></label><select name="role" id="editRole" class="form-control account-role" data-scope="edit" required><option value="kajur">Ketua Jurusan</option><option value="wadek3">Wakil Dekan III</option><option value="warek">Wakil Rektor III</option></select></div>
                            <div class="form-group col-md-6"><label>Username <span class="text-danger">*</span></label><input name="username" id="editUsername" class="form-control" required maxlength="50"></div>
                            <div class="form-group col-md-6"><label>Email <span class="text-danger">*</span></label><input type="email" name="email" id="editEmail" class="form-control" required maxlength="150"></div>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <h6 class="font-weight-bold text-gray-800 mb-1">Penempatan</h6>
                        <p class="small text-muted mb-3">Pilih fakultas dan jurusan yang sesuai dengan jabatan akun.</p>
                        <div class="form-row">
                            <div class="form-group col-md-6 account-faculty d-none" data-scope="edit"><label>Fakultas <span class="text-danger">*</span></label><select name="faculty" id="editFaculty" class="form-control account-faculty-select" data-scope="edit"><option value="">Pilih fakultas</option>@foreach ($faculties as $faculty)<option value="{{ $faculty->name }}">{{ $faculty->name }}</option>@endforeach<option value="__new__" data-for-role="wadek3">+ Tambah fakultas baru</option></select></div>
                            <div class="form-group col-md-6 account-new-faculty d-none" data-scope="edit"><label>Nama fakultas baru</label><input name="new_faculty" id="editNewFaculty" class="form-control" maxlength="150" placeholder="Masukkan nama fakultas"></div>
                            <div class="form-group col-md-6 account-department d-none" data-scope="edit"><label>Jurusan / Program Studi <span class="text-danger">*</span></label><select name="department" id="editDepartment" class="form-control account-department-select" data-scope="edit"><option value="">Pilih jurusan</option>@foreach ($departments as $department)<option value="{{ $department->name }}" data-faculty="{{ $department->faculty->name }}">{{ $department->name }} — {{ \App\Http\Controllers\ReportController::facultyDisplayName($department->faculty->name) }}</option>@endforeach<option value="__new__">+ Tambah jurusan baru</option></select></div>
                            <div class="form-group col-md-6 account-new-department d-none" data-scope="edit"><label>Nama jurusan baru</label><input name="new_department" id="editNewDepartment" class="form-control" maxlength="150" placeholder="Masukkan nama jurusan"></div>
                        </div>
                    </div>
                    <div class="border-top pt-4 mt-1">
                        <section class="password-reset-card" aria-labelledby="editPasswordHeading">
                            <div class="d-flex align-items-center mb-3" style="gap:12px;">
                                <span class="password-reset-icon"><i class="fas fa-key"></i></span>
                                <div>
                                    <h6 class="font-weight-bold text-gray-800 mb-1" id="editPasswordHeading">Ganti Password</h6>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="editPassword" class="font-weight-bold small">Password baru</label>
                                    <div class="input-group"><input type="password" name="password" id="editPassword" class="form-control" minlength="12" maxlength="100" autocomplete="new-password" placeholder="Masukkan password baru"><div class="input-group-append"><button class="btn btn-outline-secondary toggle-password" type="button" data-target="editPassword" aria-label="Tampilkan password baru"><i class="fas fa-eye"></i></button></div></div>
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="editPasswordConfirmation" class="font-weight-bold small">Konfirmasi password baru</label>
                                    <div class="input-group"><input type="password" name="password_confirmation" id="editPasswordConfirmation" class="form-control" minlength="12" maxlength="100" autocomplete="new-password" placeholder="Masukkan ulang password baru"><div class="input-group-append"><button class="btn btn-outline-secondary toggle-password" type="button" data-target="editPasswordConfirmation" aria-label="Tampilkan konfirmasi password"><i class="fas fa-eye"></i></button></div></div>
                                </div>
                            </div>
                            <small class="text-muted"><i class="fas fa-shield-alt mr-1"></i>Minimal 12 karakter, gunakan kombinasi huruf dan angka.</small>
                        </section>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-light font-weight-bold" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary font-weight-bold px-4"><i class="fas fa-save mr-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const editUrlTemplate = @json(route('kemahasiswaan.accounts.update', ['user' => '__USER__']));
            function updateRoleFields(scope, role) {
                const faculty = document.querySelector('.account-faculty[data-scope="' + scope + '"]');
                const department = document.querySelector('.account-department[data-scope="' + scope + '"]');
                const newFaculty = document.querySelector('.account-new-faculty[data-scope="' + scope + '"]');
                const newDepartment = document.querySelector('.account-new-department[data-scope="' + scope + '"]');
                const facultySelect = document.querySelector('.account-faculty-select[data-scope="' + scope + '"]');
                const departmentSelect = document.querySelector('.account-department-select[data-scope="' + scope + '"]');
                const newFacultyOption = facultySelect.querySelector('option[data-for-role="wadek3"]');
                faculty.classList.toggle('d-none', !['kajur', 'wadek3'].includes(role));
                department.classList.toggle('d-none', role !== 'kajur');
                if (!['kajur', 'wadek3'].includes(role)) newFaculty.classList.add('d-none');
                if (role !== 'kajur') newDepartment.classList.add('d-none');
                facultySelect.required = ['kajur', 'wadek3'].includes(role);
                departmentSelect.required = role === 'kajur';
                newFacultyOption.hidden = role !== 'wadek3';
                if (role === 'kajur' && facultySelect.value === '__new__') facultySelect.value = '';
                filterDepartments(scope);
            }
            function filterDepartments(scope) {
                const facultySelect = document.querySelector('.account-faculty-select[data-scope="' + scope + '"]');
                const departmentSelect = document.querySelector('.account-department-select[data-scope="' + scope + '"]');
                const selectedFaculty = facultySelect.value;
                Array.from(departmentSelect.options).forEach(function (option) {
                    if (!option.dataset.faculty) return;
                    option.hidden = selectedFaculty === '__new__' || (selectedFaculty && option.dataset.faculty !== selectedFaculty);
                });
                const role = document.querySelector('.account-role[data-scope="' + scope + '"]').value;
                if (selectedFaculty === '__new__' && role === 'kajur') departmentSelect.value = '__new__';
                document.querySelector('.account-new-department[data-scope="' + scope + '"]').classList.toggle('d-none', role !== 'kajur' || departmentSelect.value !== '__new__');
            }
            document.querySelectorAll('.account-role').forEach(function (select) {
                select.addEventListener('change', function () { updateRoleFields(this.dataset.scope, this.value); });
                updateRoleFields(select.dataset.scope, select.value);
            });
            document.querySelectorAll('.account-faculty-select').forEach(function (select) {
                select.addEventListener('change', function () {
                    const scope = this.dataset.scope;
                    document.querySelector('.account-new-faculty[data-scope="' + scope + '"]').classList.toggle('d-none', this.value !== '__new__');
                    const role = document.querySelector('.account-role[data-scope="' + scope + '"]').value;
                    const departmentSelect = document.querySelector('.account-department-select[data-scope="' + scope + '"]');
                    departmentSelect.value = this.value === '__new__' && role === 'kajur' ? '__new__' : '';
                    filterDepartments(scope);
                });
            });
            document.querySelectorAll('.account-department-select').forEach(function (select) {
                select.addEventListener('change', function () {
                    document.querySelector('.account-new-department[data-scope="' + this.dataset.scope + '"]').classList.toggle('d-none', this.value !== '__new__');
                });
            });
            document.querySelectorAll('.edit-account').forEach(function (button) {
                button.addEventListener('click', function () {
                    document.getElementById('editAccountForm').action = editUrlTemplate.replace('__USER__', this.dataset.id);
                    document.getElementById('editName').value = this.dataset.name;
                    document.getElementById('editEmail').value = this.dataset.email;
                    document.getElementById('editUsername').value = this.dataset.username;
                    document.getElementById('editAccountSubtitle').textContent = 'Mengedit akun ' + this.dataset.username;
                    document.getElementById('editRole').value = this.dataset.role;
                    document.getElementById('editFaculty').value = this.dataset.faculty || '';
                    document.getElementById('editDepartment').value = this.dataset.department || '';
                    updateRoleFields('edit', this.dataset.role);
                    document.getElementById('editNewFaculty').value = '';
                    document.getElementById('editNewDepartment').value = '';
                    document.getElementById('editPassword').value = '';
                    document.getElementById('editPasswordConfirmation').value = '';
                });
            });
            document.querySelectorAll('.toggle-password').forEach(function (button) {
                button.addEventListener('click', function () {
                    const input = document.getElementById(this.dataset.target);
                    const icon = this.querySelector('i');
                    input.type = input.type === 'password' ? 'text' : 'password';
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            });
            @if ($errors->any() && old('account_form') === 'create')
                $('#createAccountModal').modal('show');
            @endif
        })();
    </script>
</x-layout>
