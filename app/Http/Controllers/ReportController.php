<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ViolationReport;
use App\Models\Student;
use App\Models\ViolationType;
use App\Models\ReportEvidence;
use App\Models\Faculty;
use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use ZipArchive;

class ReportController extends Controller
{
    protected static ?array $facultyDisplayMap = null;

    public static function facultyDisplayName(?string $name): string
    {
        if (empty($name)) {
            return '';
        }

        if (self::$facultyDisplayMap === null) {
            if (\Illuminate\Support\Facades\Schema::hasTable('faculties')) {
                self::$facultyDisplayMap = Faculty::pluck('short_name', 'name')->toArray();
            } else {
                self::$facultyDisplayMap = [
                    'FAKULTAS ILMU TARBIYAH DAN KEGURUAN' => 'FITK',
                    'FAKULTAS SYARIAH'                    => 'FS',
                    'FAKULTAS USHULUDDIN DAN ADAB'        => 'FUA',
                    'PASCASARJANA'                        => 'PASCA',
                    'FAKULTAS EKONOMI DAN BISNIS ISLAM'   => 'FEBI',
                    'FAKULTAS DAKWAH DAN KOMUNIKASI ISLAM'=> 'FDKI',
                    'ILMU TARBIYAH DAN KEGURUAN'          => 'FITK',
                    'SYARIAH'                             => 'FS',
                    'USHULUDDIN DAN ADAB'                 => 'FUA',
                    'EKONOMI DAN BISNIS ISLAM'            => 'FEBI',
                    'DAKWAH DAN KOMUNIKASI ISLAM'         => 'FDKI',
                    'Fakultas Syariah'                    => 'FS',
                ];
            }
        }

        $displayName = self::$facultyDisplayMap[$name] ?? $name;

        return match (strtoupper(trim($displayName))) {
            'FS' => 'FASYA',
            'PASCA' => 'PASCA SARJANA',
            default => $displayName,
        };
    }

    // List of standard faculties and their departments
    public static array $faculties = [
        'FITK' => [
            'Pendidikan Agama Islam',
            'Pendidikan Bahasa Arab',
            'Tadris Bahasa Inggris',
            'Tadris IPS',
            'Tadris Matematika',
            'Tadris Biologi',
            'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)',
            'Pendidikan Islam Anak Usia Dini (PIAUD)',
            'Manajemen Pendidikan Islam',
            'Tadris Bahasa Indonesia',
            'Tadris Kimia',
            'Informatika',
            'Matematika',
            'PJJ Pendidikan Agama Islam',
            'PJJ Pendidikan Bahasa Arab',
            'PJJ Pendidikan Guru Madrasah Ibtidaiyah',
        ],
        'FEBI' => [
            'Perbankan Syariah',
            'Ekonomi Syariah',
            'Akuntansi Syariah',
            'Pariwisata Syariah',
            'Bioteknologi',
        ],
        'Fakultas Syariah' => [
            'Hukum Keluarga Islam',
            'Hukum Ekonomi Syariah',
            'Hukum Tata Negara',
            'Ilmu Falak',
            'PJJ Hukum Keluarga Islam',
        ],
        'FUA' => [
            'Sejarah Peradaban Islam',
            'Aqidah dan Filsafat Islam',
            'Ilmu Al-Qur\'an dan Tafsir',
            'Ilmu Hadis',
            'Bahasa dan Sastra Arab',
            'Tasawuf dan Psikoterapi',
            'PJJ Sejarah Peradaban Islam',
        ],
        'FDKI' => [
            'Komunikasi dan Penyiaran Islam',
            'Pengembangan Masyarakat Islam',
            'Bimbingan dan Konseling Islam',
            'Sosiologi Agama',
        ],
    ];

    // List of standard departments and faculty groupings
    public static array $departments = [
        // FITK
        'Pendidikan Agama Islam' => 'FITK',
        'Pendidikan Bahasa Arab' => 'FITK',
        'Tadris Bahasa Inggris' => 'FITK',
        'Tadris IPS' => 'FITK',
        'Tadris Matematika' => 'FITK',
        'Tadris Biologi' => 'FITK',
        'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)' => 'FITK',
        'Pendidikan Islam Anak Usia Dini (PIAUD)' => 'FITK',
        'Manajemen Pendidikan Islam' => 'FITK',
        'Tadris Bahasa Indonesia' => 'FITK',
        'Tadris Kimia' => 'FITK',
        'Informatika' => 'FITK',
        'Matematika' => 'FITK',
        'PJJ Pendidikan Agama Islam' => 'FITK',
        'PJJ Pendidikan Bahasa Arab' => 'FITK',
        'PJJ Pendidikan Guru Madrasah Ibtidaiyah' => 'FITK',
        // FEBI
        'Perbankan Syariah' => 'FEBI',
        'Ekonomi Syariah' => 'FEBI',
        'Akuntansi Syariah' => 'FEBI',
        'Pariwisata Syariah' => 'FEBI',
        'Bioteknologi' => 'FEBI',
        // Fakultas Syariah
        'Hukum Keluarga Islam' => 'Fakultas Syariah',
        'Hukum Ekonomi Syariah' => 'Fakultas Syariah',
        'Hukum Tata Negara' => 'Fakultas Syariah',
        'Ilmu Falak' => 'Fakultas Syariah',
        'PJJ Hukum Keluarga Islam' => 'Fakultas Syariah',
        // FUA
        'Sejarah Peradaban Islam' => 'FUA',
        'Aqidah dan Filsafat Islam' => 'FUA',
        'Ilmu Al-Qur\'an dan Tafsir' => 'FUA',
        'Ilmu Hadis' => 'FUA',
        'Bahasa dan Sastra Arab' => 'FUA',
        'Tasawuf dan Psikoterapi' => 'FUA',
        'PJJ Sejarah Peradaban Islam' => 'FUA',
        // FDKI
        'Komunikasi dan Penyiaran Islam' => 'FDKI',
        'Pengembangan Masyarakat Islam' => 'FDKI',
        'Bimbingan dan Konseling Islam' => 'FDKI',
        'Sosiologi Agama' => 'FDKI',
    ];

    public static array $severities = ['Ringan', 'Sedang', 'Berat'];

    public static function facultyDepartmentMap(): array
    {
        static $map;
        if ($map !== null) {
            return $map;
        }
        if (!Schema::hasTable('faculties') || !Schema::hasTable('departments')) {
            return $map = self::$faculties;
        }

        return $map = Faculty::with('departments')->orderBy('name')->get()
            ->mapWithKeys(fn (Faculty $faculty) => [$faculty->name => $faculty->departments->pluck('name')->all()])
            ->all();
    }

    public static function departmentFacultyMap(): array
    {
        static $map;
        if ($map !== null) {
            return $map;
        }
        if (!Schema::hasTable('faculties') || !Schema::hasTable('departments')) {
            return $map = self::$departments;
        }

        return $map = Faculty::with('departments')->get()
            ->flatMap(fn (Faculty $faculty) => $faculty->departments->mapWithKeys(fn ($department) => [$department->name => $faculty->name]))
            ->all();
    }

    public static array $sanctionOptions = [
        'Teguran Lisan',
        'Surat Peringatan (SP 1)',
        'Surat Peringatan (SP 2)',
        'Surat Peringatan Keras (SP 3)',
        'Skorsing Perkuliahan 1 Semester',
        'Skorsing Perkuliahan 2 Semester',
        'Pembinaan Karakter & Kerja Sosial Kampus',
        'Klarifikasi / Mediasi Kasus',
    ];

    /**
     * Daftar pilihan semester terlapor.
     * Format: "Ganjil YYYY/YYYY" atau "Genap YYYY/YYYY"
     * Mencakup 4 semester terakhir + 1 semester mendatang untuk kemudahan.
     */
    public static function getSemesterOptions(): array
    {
        $options = [];
        $currentYear = (int) date('Y');

        // Generate semester Ganjil & Genap untuk 3 tahun akademik (mundur & maju 1)
        for ($y = $currentYear + 1; $y >= $currentYear - 2; $y--) {
            $options[] = "Ganjil {$y}/" . ($y + 1);
            $options[] = "Genap {$y}/" . ($y + 1);
        }

        return $options;
    }

    public function index(Request $request)
    {
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();

        // Mahasiswa tidak memiliki akses ke riwayat laporan (hanya pelaporan)
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        if ($user->isKemahasiswaan()) {
            return redirect()->route('reports.inbox');
        }

        $selectedAcademicYear = session('selected_academic_year_' . $user->id, AcademicYear::getActiveYear());
        $selectedAcademicYear = $this->normalizeAcademicPeriod($selectedAcademicYear);

        $query = ViolationReport::query()
            ->with(['student', 'reporter', 'verifier', 'handler'])
            ->whereNotIn('status', ['pending', 'dilaporkan']);

        // Kajur: mark reports as viewed (clears dashboard notification)
        if ($user->isKajur()) {
            $yearKey = str_replace('/', '-', $selectedAcademicYear);
            session(['kajur_last_viewed_reports_' . $user->id . '_' . $yearKey => now()->timestamp]);
        }

        // RBAC filtering
        if ($user->isKajur()) {
            $dept = $user->department;
            $query->where(function ($q) use ($dept) {
                $q->where('target_department', $dept)
                  ->orWhere('reported_department', $dept);
            })->whereNotIn('status', ['pending', 'dilaporkan', 'ditolak']);
        } elseif ($user->isDekan() || $user->isWadek3()) {
            // Filter departments under this faculty
            $allowedDepts = array_keys(array_filter(self::departmentFacultyMap(), fn($f) => $f === $user->faculty));
            $query->where(function ($q) use ($allowedDepts) {
                $q->whereIn('target_department', $allowedDepts)
                  ->orWhereIn('reported_department', $allowedDepts);
            });
        } elseif ($user->isWarek()) {
            // Warek read only
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department')) {
            $query->where(function ($q) use ($request) {
                $q->where('target_department', $request->department)
                  ->orWhere('reported_department', $request->department);
            });
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        // Semua halaman laporan mengikuti pilihan tahun akademik dari dashboard.
        if ($selectedAcademicYear !== 'all') {
            [$year, $term] = $this->splitAcademicPeriod($selectedAcademicYear);
            $query->where('academic_year', $year)->where('semester', $term);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('report_number', 'like', "%{$search}%")
                  ->orWhere('reported_name', 'like', "%{$search}%")
                  ->orWhere('reported_nim', 'like', "%{$search}%")
                  ->orWhere('incident_location', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->get();
        $departments = array_keys(self::departmentFacultyMap());

        return view('reports.index', compact('reports', 'departments', 'selectedAcademicYear'));
    }

    private function normalizeAcademicPeriod(string $period): string
    {
        return preg_match('/^(\d{4}\/\d{4})(?:\s+(Gasal|Genap))?$/u', $period, $m)
            ? $m[1] . ' ' . ($m[2] ?? 'Gasal') : $period;
    }

    private function splitAcademicPeriod(string $period): array
    {
        return preg_match('/^(\d{4}\/\d{4})\s+(Gasal|Genap)$/u', $period, $m)
            ? [$m[1], $m[2]] : [$period, 'Gasal'];
    }

    /**
     * Inbox Verifikasi: Hanya menampilkan laporan masuk yang belum diverifikasi
     */
    public function inbox(Request $request)
    {
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        $selectedAcademicYear = session('selected_academic_year_' . $user->id, AcademicYear::getActiveYear());

        $query = ViolationReport::query()
            ->with(['student', 'reporter', 'verifier', 'handler'])
            ->whereIn('status', ['pending', 'dilaporkan']);

        if ($selectedAcademicYear !== 'all') {
            $query->where('academic_year', $selectedAcademicYear);
        }

        if ($user->isKajur()) {
            $dept = $user->department;
            $query->where(function ($q) use ($dept) {
                $q->where('target_department', $dept)
                  ->orWhere('reported_department', $dept);
            });
        } elseif ($user->isWadek3() || $user->isDekan()) {
            $allowedDepts = array_keys(array_filter(self::departmentFacultyMap(), fn($f) => $f === $user->faculty));
            $query->where(function ($q) use ($allowedDepts) {
                $q->whereIn('target_department', $allowedDepts)
                  ->orWhereIn('reported_department', $allowedDepts);
            });
        }

        if ($request->filled('department')) {
            $query->where(function ($q) use ($request) {
                $q->where('target_department', $request->department)
                  ->orWhere('reported_department', $request->department);
            });
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('report_number', 'like', "%{$search}%")
                  ->orWhere('reported_name', 'like', "%{$search}%")
                  ->orWhere('reported_nim', 'like', "%{$search}%")
                  ->orWhere('incident_location', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->get();
        $departments = array_keys(self::departmentFacultyMap());
        $faculties = self::facultyDepartmentMap();

        return view('reports.inbox', compact('reports', 'departments', 'faculties', 'selectedAcademicYear'));
    }

    /**
     * Riwayat Laporan: Hanya menampilkan laporan yang sudah terverifikasi
     */
    public function history(Request $request)
    {
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        $selectedAcademicYear = session('selected_academic_year_' . $user->id, AcademicYear::getActiveYear());

        // Kajur sudah membuka halaman Data Laporan, tandai notifikasi sebagai dibaca.
        if ($user->isKajur()) {
            $yearKey = str_replace('/', '-', $selectedAcademicYear);
            session(['kajur_last_viewed_reports_' . $user->id . '_' . $yearKey => now()->timestamp]);
        }

        $query = ViolationReport::query()
            ->with(['student', 'reporter', 'verifier', 'handler'])
            ->whereNotIn('status', ['pending', 'dilaporkan']);

        if ($selectedAcademicYear !== 'all') {
            $query->where('academic_year', $selectedAcademicYear);
        }

        if ($user->isKajur()) {
            $dept = $user->department;
            $query->where(function ($q) use ($dept) {
                $q->where('target_department', $dept)
                  ->orWhere('reported_department', $dept);
            })->where('status', '!=', 'ditolak');
        } elseif ($user->isWadek3() || $user->isDekan()) {
            $allowedDepts = array_keys(array_filter(self::departmentFacultyMap(), fn($f) => $f === $user->faculty));
            $query->where(function ($q) use ($allowedDepts) {
                $q->whereIn('target_department', $allowedDepts)
                  ->orWhereIn('reported_department', $allowedDepts);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department')) {
            $query->where(function ($q) use ($request) {
                $q->where('target_department', $request->department)
                  ->orWhere('reported_department', $request->department);
            });
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('report_number', 'like', "%{$search}%")
                  ->orWhere('reported_name', 'like', "%{$search}%")
                  ->orWhere('reported_nim', 'like', "%{$search}%")
                  ->orWhere('incident_location', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->get();
        $departments = array_keys(self::departmentFacultyMap());
        $faculties = self::facultyDepartmentMap();

        return view('reports.history', compact('reports', 'departments', 'faculties', 'selectedAcademicYear'));
    }

    public function create()
    {
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();
        if (!$user->isMahasiswa() && !$user->isKemahasiswaan() && !$user->isKajur()) {
            abort(403, 'Hanya Mahasiswa, Kemahasiswaan, dan Kajur yang dapat membuat laporan.');
        }

        $faculties       = self::facultyDepartmentMap();
        $departments     = array_keys(self::departmentFacultyMap());
        $departmentFacultyMap = self::departmentFacultyMap();
        $severities      = self::$severities;
        $semesterOptions = self::getSemesterOptions();
        return view('reports.create', compact('faculties', 'departments', 'departmentFacultyMap', 'severities', 'semesterOptions'));
    }

    public function store(Request $request)
    {
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();
        $isMahasiswa = $user->isMahasiswa();

        // PHP menolak upload sebelum Laravel menjalankan validasi jika batas request terlalu kecil.
        // Berikan pesan yang jelas untuk kondisi tersebut dan error upload lain.
        $uploadedEvidence = $request->file('evidence', []);
        foreach ($uploadedEvidence as $index => $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile && !$file->isValid()) {
                $message = match ($file->getError()) {
                    UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Berkas bukti melebihi batas unggahan server. Coba foto/video yang lebih kecil atau naikkan batas upload_max_filesize dan post_max_size pada PHP yang menjalankan aplikasi.',
                    UPLOAD_ERR_PARTIAL => 'Unggahan berkas bukti terputus sebelum selesai. Periksa koneksi lalu coba kembali.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Server tidak memiliki folder sementara untuk menerima unggahan. Periksa pengaturan upload_tmp_dir PHP.',
                    UPLOAD_ERR_CANT_WRITE => 'Server gagal menyimpan unggahan sementara. Periksa ruang disk dan izin folder temporary.',
                    UPLOAD_ERR_EXTENSION => 'Ekstensi PHP menghentikan unggahan berkas bukti.',
                    default => 'Unggahan berkas bukti gagal diterima server. Silakan coba kembali.',
                };

                throw \Illuminate\Validation\ValidationException::withMessages([
                    "evidence.{$index}" => $message,
                ]);
            }
        }

        $departmentFacultyMap = self::departmentFacultyMap();
        $rules = [
            'reported_name'        => 'required|string|max:150',
            'reported_nim'         => 'required|string|max:50',
            'reported_faculty'     => ['required', 'string', Rule::in(array_keys(self::facultyDepartmentMap()))],
            'reported_department'  => ['required', 'string', Rule::in(array_keys($departmentFacultyMap))],
            'reported_semester'    => 'required|regex:/^[0-9]+$/|max:4',
            'incident_date'        => 'required|date',
            'incident_time'        => 'required',
            'incident_location'    => 'required|string|max:255',
            'severity'             => 'required|string|in:Ringan,Sedang,Berat',
            'chronology'           => 'required|string',
            'evidence'             => 'required|array|min:1|max:4',
            // Dokumen/foto dibatasi 10 MB; video dibatasi 50 MB.
            'evidence.*'           => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,mp4,mov,m4v,webm,3gp',
                function ($attribute, $file, $fail) {
                    if (!$file instanceof \Illuminate\Http\UploadedFile) {
                        return;
                    }

                    $extension = strtolower($file->getClientOriginalExtension());
                    $isVideo = in_array($extension, ['mp4', 'mov', 'm4v', 'webm', '3gp'], true);
                    $maxKilobytes = $isVideo ? 51200 : 10240;

                    if ($file->getSize() > $maxKilobytes * 1024) {
                        $fail($isVideo
                            ? 'Ukuran video maksimal 50 MB.'
                            : 'Ukuran dokumen atau foto maksimal 10 MB.');
                    }
                },
            ],
        ];

        $request->validate($rules, [
            'reported_name.required'       => 'Nama mahasiswa terlapor wajib diisi.',
            'reported_nim.required'        => 'NIM mahasiswa terlapor wajib diisi.',
            'reported_faculty.required'    => 'Fakultas terlapor wajib dipilih.',
            'reported_department.required' => 'Jurusan / Program Studi terlapor wajib dipilih.',
            'reported_semester.required'   => 'Semester terlapor wajib diisi.',
            'reported_semester.regex'      => 'Semester hanya boleh berupa angka (contoh: 2, 4, 6).',
            'incident_date.required'       => 'Tanggal kejadian wajib diisi.',
            'incident_time.required'       => 'Waktu / jam kejadian wajib diisi.',
            'incident_location.required'   => 'Lokasi kejadian wajib diisi.',
            'severity.required'            => 'Kategori tingkat pelanggaran wajib dipilih.',
            'chronology.required'          => 'Uraian kronologi kejadian wajib diisi.',
            'evidence.required'            => 'Berkas bukti pendukung wajib diunggah.',
            'evidence.max'                 => 'Maksimal 4 berkas bukti yang dapat diunggah.',
            'evidence.*.mimes'             => 'Berkas harus berformat PDF, JPG, PNG, MP4, MOV, M4V, WEBM, atau 3GP.',
        ]);

        $faculty = $departmentFacultyMap[$request->reported_department] ?? null;
        if ($faculty !== $request->reported_faculty) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'reported_department' => 'Jurusan yang dipilih tidak sesuai dengan fakultas.',
            ]);
        }

        // Link student if found by NIM
        $matchedStudent = Student::where('nim', trim($request->reported_nim))->first();

        $evidenceFiles = $request->file('evidence', []);
        $storedEvidence = [];
        foreach ($evidenceFiles as $index => $file) {
            $storedPath = $file->store('evidences', 'public');
            if (!$storedPath) {
                throw new \RuntimeException('Gagal menyimpan lampiran. Periksa izin tulis pada storage/app/public.');
            }
            $storedEvidence[$index] = $storedPath;
        }
        $firstEvidence = $evidenceFiles[0] ?? null;
        $evidenceName = $firstEvidence?->getClientOriginalName();
        $evidencePath = $storedEvidence[0] ?? null;

        $academicYear = \App\Models\AcademicYear::getActiveYear();
        preg_match('/^(\d{4})\/(\d{4})$/', $academicYear, $yearParts);
        $yearCode = isset($yearParts[1], $yearParts[2])
            ? substr($yearParts[1], -2) . substr($yearParts[2], -2)
            : preg_replace('/\D/', '', $academicYear);
        $nextNumber = ViolationReport::where('academic_year', $academicYear)->count() + 1;
        $reportNumber = 'REP-' . $yearCode . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $report = ViolationReport::create([
            'report_number'     => $reportNumber,
            'student_id'        => $matchedStudent?->id,
            'reported_name'     => $request->reported_name,
            'reported_nim'      => $request->reported_nim,
            'reported_faculty'  => $faculty,
            'reported_department' => $request->reported_department,
            'target_department' => $request->reported_department,
            'reporter_id'       => Auth::id(),
            'severity'          => $request->severity,
            'incident_date'     => $request->incident_date,
            'incident_time'     => $request->incident_time,
            'incident_location' => $request->incident_location,
            'chronology'        => $request->chronology,
            'status'            => 'pending',
            'academic_year'     => preg_replace('/\\s+(Gasal|Genap)$/u', '', $academicYear),
            'semester'          => preg_match('/\\b(Genap)$/u', $academicYear) ? 'Genap' : 'Gasal',
            'reported_semester' => $request->reported_semester, // Semester berupa angka
            'evidence_path'     => $evidencePath,
            'evidence_name'     => $evidenceName,
        ]);

        foreach ($evidenceFiles as $index => $file) {
            $filePath = $storedEvidence[$index];
            ReportEvidence::create([
                'violation_report_id' => $report->id,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'mime_type' => $file->getClientMimeType(),
            ]);
        }

        if ($user->isMahasiswa()) {
            return redirect()->route('reports.create')->with('success', 'Laporan Anda berhasil dikirim ke Bagian Kemahasiswaan. Terima kasih atas partisipasi Anda.');
        }

        if ($user->isKajur()) {
            return redirect()->route('reports.index')->with('success', 'Laporan berhasil diajukan untuk proses verifikasi.');
        }

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil diajukan untuk proses verifikasi.');
    }

    public function show(int $id)
    {
        $report = ViolationReport::with(['student', 'reporter', 'verifier', 'handler', 'evidences'])->findOrFail($id);
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();

        // RBAC Access Check
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Mahasiswa hanya memiliki hak akses untuk melakukan pelaporan pelanggaran.');
        }
        if ($user->isKajur()) {
            abort_if($report->status === 'ditolak', 404);
            if ($report->target_department !== $user->department && $report->reported_department !== $user->department) {
                abort(403, 'Laporan ini tidak ditujukan untuk jurusan Anda.');
            }
        }
        if ($user->isDekan() || $user->isWadek3()) {
            $allowedDepts = array_keys(array_filter(self::departmentFacultyMap(), fn($f) => $f === $user->faculty));
            if (!in_array($report->target_department, $allowedDepts) && !in_array($report->reported_department, $allowedDepts)) {
                abort(403, 'Laporan ini berada di luar fakultas Anda.');
            }
        }

        $departments = array_keys(self::departmentFacultyMap());
        $severities = self::$severities;
        $sanctionOptions = self::$sanctionOptions;

        return view('reports.show', compact('report', 'departments', 'severities', 'sanctionOptions'));
    }

    private function resolveEvidenceAndAuthorize(int $id, ?int $evidenceId = null): array
    {
        $report = ViolationReport::with('evidences')->findOrFail($id);
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();

        $evidence = $evidenceId
            ? $report->evidences->firstWhere('id', (int) $evidenceId)
            : $report->evidences->first();
        if ($evidenceId && !$evidence) {
            abort(404, 'Berkas bukti tidak ditemukan.');
        }
        $filePath = $evidence?->file_path ?? $report->evidence_path;
        $fileName = $evidence?->original_name ?? $report->evidence_name ?? 'Berkas Bukti';
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $fileMimeType = $evidence?->mime_type ?? ($filePath ? $disk->mimeType($filePath) : null);
        if (!$filePath) {
            abort(404, 'Berkas bukti tidak ditemukan.');
        }
        if ($user->isMahasiswa()) {
            abort(403, 'Mahasiswa hanya memiliki hak akses untuk melakukan pelaporan pelanggaran.');
        }
        if ($user->isKajur()) {
            abort_if($report->status === 'ditolak', 404);
            if ($report->target_department !== $user->department && $report->reported_department !== $user->department) {
                abort(403, 'Laporan ini tidak ditujukan untuk jurusan Anda.');
            }
        }
        if ($user->isDekan() || $user->isWadek3()) {
            $allowedDepts = array_keys(array_filter(self::departmentFacultyMap(), fn ($faculty) => $faculty === $user->faculty));
            if (!in_array($report->target_department, $allowedDepts) && !in_array($report->reported_department, $allowedDepts)) {
                abort(403, 'Laporan ini berada di luar fakultas Anda.');
            }
        }

        return [$report, $evidence, $filePath, $fileName, $fileMimeType];
    }

    public function previewEvidence(int $id, ?int $evidenceId = null)
    {
        [$report, $evidence, $filePath, $fileName, $fileMimeType] = $this->resolveEvidenceAndAuthorize($id, $evidenceId);

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $videoExtensions = ['mp4', 'mov', 'm4v', 'webm', '3gp', 'mkv', 'avi', 'ogg'];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];

        $isVideo = str_starts_with($fileMimeType ?? '', 'video/') || in_array($extension, $videoExtensions);
        $isImage = str_starts_with($fileMimeType ?? '', 'image/') || in_array($extension, $imageExtensions);

        $streamUrl = route('reports.evidence.stream', ['id' => $report->id, 'evidenceId' => $evidence?->id]);

        return view('reports.evidence-preview', compact('report', 'evidence', 'filePath', 'fileName', 'fileMimeType', 'isVideo', 'isImage', 'streamUrl'));
    }

    public function streamEvidence(Request $request, int $id, ?int $evidenceId = null)
    {
        [$report, $evidence, $filePath, $fileName, $fileMimeType] = $this->resolveEvidenceAndAuthorize($id, $evidenceId);

        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        if (!$disk->exists($filePath)) {
            abort(404, 'Berkas fisik tidak ditemukan di server.');
        }

        $fullPath = $disk->path($filePath);
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $videoMimes = [
            'mp4'  => 'video/mp4',
            'mov'  => 'video/quicktime',
            'm4v'  => 'video/mp4',
            'webm' => 'video/webm',
            '3gp'  => 'video/3gpp',
            'ogg'  => 'video/ogg',
            'mkv'  => 'video/x-matroska',
        ];
        $mimeType = $videoMimes[$extension] ?? $fileMimeType ?? $disk->mimeType($filePath) ?? 'application/octet-stream';

        return response()->file($fullPath, [
            'Content-Type'        => $mimeType,
            'Accept-Ranges'       => 'bytes',
            'Content-Disposition' => 'inline; filename="' . addslashes($fileName) . '"',
        ]);
    }

    // Kemahasiswaan Action 1: Koreksi Data Pelanggar
    public function updateData(Request $request, int $id)
    {
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();
        if (!$user->isKemahasiswaan()) {
            abort(403, 'Hanya Bagian Kemahasiswaan yang dapat mengoreksi data laporan.');
        }

        $report = ViolationReport::findOrFail($id);

        $data = $request->validate([
            'reported_name' => 'required|string|max:150',
            'reported_nim' => 'required|string|max:50',
            'reported_department' => ['required', 'string', Rule::in(array_keys(self::departmentFacultyMap()))],
            'severity' => ['required', 'string', Rule::in(self::$severities)],
            'incident_location' => 'required|string|max:255',
            'chronology' => 'nullable|string',
        ]);

        // Attempt matching student
        $matchedStudent = Student::where('nim', trim($data['reported_nim']))->first();

        $report->update([
            'reported_name' => trim($data['reported_name']),
            'reported_nim' => trim($data['reported_nim']),
            'reported_department' => $data['reported_department'],
            'target_department' => $data['reported_department'],
            'severity' => $data['severity'],
            'incident_location' => trim($data['incident_location']),
            'chronology' => isset($data['chronology']) ? trim($data['chronology']) : null,
            'student_id' => $matchedStudent?->id ?? $report->student_id,
        ]);

        return back()->with('success', 'Data pelanggar berhasil divalidasi dan diperbarui oleh Kemahasiswaan.');
    }

    // Kemahasiswaan Action 2: Setujui & Teruskan ke Kajur
    public function approveAndForward(Request $request, int $id)
    {
        /** @var User|null $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();
        if (!$user->isKemahasiswaan()) {
            abort(403, 'Hanya Bagian Kemahasiswaan yang dapat menyetujui laporan.');
        }

        $report = ViolationReport::findOrFail($id);

        $request->validate([
            'target_department' => 'nullable|string|max:150',
        ]);

        $targetDept = $request->input('target_department') ?: ($report->reported_department ?: $report->target_department);

        $report->update([
            'status' => 'dikonfirmasi', // Terkonfirmasi & diteruskan ke Kajur
            'target_department' => $targetDept,
            'rejection_reason' => null,
            'verified_by' => $user->id,
            'verified_at' => now(),
            'handled_by' => null,
            'handled_at' => null,
            'completed_at' => null,
            'sanction_type' => null,
            'kajur_notes' => null,
        ]);

        return back()->with('success', 'Laporan berhasil diverifikasi. Status: Terverifikasi.');
    }

    // Kemahasiswaan Action 3: Tolak Laporan
    public function reject(Request $request, int $id)
    {
        /** @var User|null $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();
        if (!$user->isKemahasiswaan()) {
            abort(403, 'Hanya Bagian Kemahasiswaan yang dapat menolak laporan.');
        }

        $report = ViolationReport::findOrFail($id);

        $request->validate([
            'rejection_reason' => 'required|string|min:5',
        ]);

        $report->update([
            'status' => 'ditolak',
            'rejection_reason' => $request->rejection_reason,
            'verified_by' => $user->id,
            'verified_at' => now(),
            'handled_by' => null,
            'handled_at' => null,
            'completed_at' => null,
            'sanction_type' => null,
            'kajur_notes' => null,
        ]);

        return back()->with('success', 'Laporan telah DITOLAK. Pelapor dapat membaca alasan penolakan pada status laporan.');
    }

    // Export Excel: one worksheet per academic year and semester
    public function exportExcel(Request $request)
    {
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();
        $query = ViolationReport::whereNotIn('status', ['pending', 'dilaporkan', 'ditolak']);

        $selectedAcademicYear = $this->normalizeAcademicPeriod((string) $request->query(
            'academic_year',
            session('selected_academic_year_' . ($user?->id ?? ''), AcademicYear::getActiveYear())
        ));
        if ($selectedAcademicYear && $selectedAcademicYear !== 'all') {
            [$year, $term] = $this->splitAcademicPeriod($selectedAcademicYear);
            $query->where('academic_year', $year)->where('semester', $term);
        }

        if ($user && $user->isKajur()) {
            $dept = $user->department;
            $query->where('status', '!=', 'ditolak');
            if ($dept) {
                $query->where(function ($q) use ($dept) {
                    $q->where('target_department', $dept)
                      ->orWhere('reported_department', $dept);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        } elseif ($user && ($user->isWadek3() || $user->isDekan())) {
            $allowedDepts = array_keys(array_filter(self::departmentFacultyMap(), fn($f) => $f === $user->faculty));
            $query->where(function ($q) use ($allowedDepts) {
                $q->whereIn('target_department', $allowedDepts)
                  ->orWhereIn('reported_department', $allowedDepts);
            });
        }

        $reports = $query->orderBy('academic_year')->orderBy('semester')->orderBy('created_at', 'desc')->get();
        $filenamePrefix = $user && $user->isKajur()
            ? 'data_laporan_' . \Illuminate\Support\Str::slug($user->department ?: 'jurusan')
            : 'rekap_laporan_terverifikasi';
        $filename = $filenamePrefix . '_' . date('Ymd_His') . '.xlsx';

        $columns = [
            'No. Laporan',
            'Periode TA',
            'Tanggal Kejadian',
            'Nama Terlapor',
            'NIM',
            'Jurusan Terlapor',
            'Semester Terlapor',
            'Kategori Pelanggaran',
            'Status'
        ];

        $groupedReports = $reports->groupBy(fn ($r) => ($r->academic_year ?: 'Tanpa TA') . ' ' . ($r->semester ?: 'Gasal'));
        $sheets = [];
        foreach ($groupedReports as $period => $periodReports) {
            $rows = [$columns];
            foreach ($periodReports as $r) {
                $rows[] = [
                    $r->report_number,
                    $r->academic_year . ' ' . ($r->semester ?: 'Gasal'),
                    $r->incident_date ? $r->incident_date->format('Y-m-d') : '-',
                    $r->student_name,
                    $r->student_nim,
                    $r->reported_department ?: $r->target_department,
                    $r->reported_semester ? 'Semester ' . $r->reported_semester : '-',
                    $r->severity ?? 'Ringan',
                    $r->status_label,
                ];
            }
            $sheets[] = ['name' => $period, 'rows' => $rows];
        }

        if (!$sheets) {
            $sheets[] = ['name' => $selectedAcademicYear === 'all' ? 'Semua Periode' : $selectedAcademicYear, 'rows' => [$columns]];
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'sipma-export-');
        if ($tempFile === false) {
            abort(500, 'Gagal menyiapkan file ekspor.');
        }
        try {
            $this->writeExcelWorkbook($tempFile, $sheets);
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Throwable $exception) {
            @unlink($tempFile);
            throw $exception;
        }
    }

    private function writeExcelWorkbook(string $path, array $sheets): void
    {
        $zip = new ZipArchive();
        if ($zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException('Gagal membuat workbook Excel.');
        }

        $escape = static fn ($value) => htmlspecialchars((string) $value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
        $sheetNames = [];
        foreach ($sheets as $index => $sheet) {
            $baseName = preg_replace('/[\\\\\/\?\*\[\]:]/u', ' ', $sheet['name']) ?: 'Periode';
            $baseName = mb_substr(trim($baseName), 0, 31) ?: 'Periode';
            $name = $baseName;
            $suffix = 2;
            while (in_array(mb_strtolower($name), $sheetNames, true)) {
                $tail = ' ' . $suffix++;
                $name = mb_substr($baseName, 0, 31 - mb_strlen($tail)) . $tail;
            }
            $sheetNames[] = mb_strtolower($name);

            $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
                . '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
            foreach ($sheet['rows'] as $rowIndex => $row) {
                $excelRow = $rowIndex + 1;
                $sheetXml .= '<row r="' . $excelRow . '">';
                foreach ($row as $columnIndex => $value) {
                    $column = '';
                    for ($n = $columnIndex + 1; $n > 0; $n = intdiv($n - 1, 26)) {
                        $column = chr(65 + (($n - 1) % 26)) . $column;
                    }
                    $cell = $column . $excelRow;
                    $sheetXml .= '<c r="' . $cell . '" t="inlineStr"><is><t xml:space="preserve">'
                        . $escape($value) . '</t></is></c>';
                }
                $sheetXml .= '</row>';
            }
            $sheetXml .= '</sheetData></worksheet>';
            $zip->addFromString('xl/worksheets/sheet' . ($index + 1) . '.xml', $sheetXml);
            $sheets[$index]['safe_name'] = $name;
        }

        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets>';
        $relations = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">';
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>';
        foreach ($sheets as $index => $sheet) {
            $id = $index + 1;
            $workbook .= '<sheet name="' . $escape($sheet['safe_name']) . '" sheetId="' . $id . '" r:id="rId' . $id . '"/>';
            $relations .= '<Relationship Id="rId' . $id . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . $id . '.xml"/>';
            $contentTypes .= '<Override PartName="/xl/worksheets/sheet' . $id . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>';
        }
        $workbook .= '</sheets></workbook>';
        $relations .= '</Relationships>';
        $contentTypes .= '</Types>';

        $zip->addFromString('[Content_Types].xml', $contentTypes);
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>');
        $zip->addFromString('xl/workbook.xml', $workbook);
        $zip->addFromString('xl/_rels/workbook.xml.rels', $relations);
        $zip->close();
    }

    // Export PDF / Print View: Khusus data riwayat yang sudah terverifikasi
    public function exportPdf(Request $request)
    {
        /** @var User $user Authenticated routes resolve to the application's User model. */
        $user = Auth::user();
        $query = ViolationReport::whereNotIn('status', ['pending', 'dilaporkan', 'ditolak']);

        $selectedAcademicYear = $this->normalizeAcademicPeriod(session('selected_academic_year_' . ($user?->id ?? ''), AcademicYear::getActiveYear()));
        if ($selectedAcademicYear !== 'all') {
            [$year, $term] = $this->splitAcademicPeriod($selectedAcademicYear);
            $query->where('academic_year', $year)->where('semester', $term);
        }

        if ($user && $user->isKajur()) {
            $dept = $user->department;
            $query->where('status', '!=', 'ditolak');
            $query->where(function ($q) use ($dept) {
                $q->where('target_department', $dept)
                  ->orWhere('reported_department', $dept);
            });
        } elseif ($user && ($user->isWadek3() || $user->isDekan())) {
            $allowedDepts = array_keys(array_filter(self::departmentFacultyMap(), fn($f) => $f === $user->faculty));
            $query->where(function ($q) use ($allowedDepts) {
                $q->whereIn('target_department', $allowedDepts)
                  ->orWhereIn('reported_department', $allowedDepts);
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->get();

        return view('reports.print', compact('reports', 'user'));
    }
}
