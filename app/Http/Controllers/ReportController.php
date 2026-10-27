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
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
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
        $user = Auth::user();

        // Mahasiswa tidak memiliki akses ke riwayat laporan (hanya pelaporan)
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        if ($user->isKemahasiswaan()) {
            return redirect()->route('reports.inbox');
        }

        $query = ViolationReport::query()->with(['student', 'reporter', 'verifier', 'handler']);

        // Kajur: mark reports as viewed (clears dashboard notification)
        if ($user->isKajur()) {
            session(['kajur_last_viewed_reports_' . $user->id => now()->timestamp]);
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
        // Filter Tahun Akademik (untuk Kajur)
        if ($user->isKajur() && $request->filled('academic_year') && $request->academic_year !== 'all') {
            $query->where('academic_year', $request->academic_year);
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

        $reports = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $departments = array_keys(self::departmentFacultyMap());

        return view('reports.index', compact('reports', 'departments'));
    }

    /**
     * Inbox Verifikasi: Hanya menampilkan laporan masuk yang belum diverifikasi
     */
    public function inbox(Request $request)
    {
        $user = Auth::user();
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        $query = ViolationReport::query()
            ->with(['student', 'reporter', 'verifier', 'handler'])
            ->whereIn('status', ['pending', 'dilaporkan']);

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

        $reports = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $departments = array_keys(self::departmentFacultyMap());
        $faculties = self::facultyDepartmentMap();

        return view('reports.inbox', compact('reports', 'departments', 'faculties'));
    }

    /**
     * Riwayat Laporan: Hanya menampilkan laporan yang sudah terverifikasi
     */
    public function history(Request $request)
    {
        $user = Auth::user();
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        // Kajur sudah membuka halaman Data Laporan, tandai notifikasi sebagai dibaca.
        if ($user->isKajur()) {
            session(['kajur_last_viewed_reports_' . $user->id => now()->timestamp]);
        }

        $query = ViolationReport::query()
            ->with(['student', 'reporter', 'verifier', 'handler'])
            ->whereNotIn('status', ['pending', 'dilaporkan']);

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

        $reports = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        $departments = array_keys(self::departmentFacultyMap());
        $faculties = self::facultyDepartmentMap();

        return view('reports.history', compact('reports', 'departments', 'faculties'));
    }

    public function create()
    {
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
        $isMahasiswa = Auth::user()->isMahasiswa();

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
            'evidence.*'           => 'required|file|mimes:pdf,jpg,jpeg,png,mp4|max:10240',
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
            'evidence.*.mimes'             => 'Setiap berkas bukti harus berformat PDF, JPG, PNG, atau MP4.',
            'evidence.*.max'               => 'Ukuran setiap berkas bukti maksimal 10 MB.',
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
        $firstEvidence = $evidenceFiles[0] ?? null;
        $evidenceName = $firstEvidence?->getClientOriginalName();
        $evidencePath = $firstEvidence?->store('evidences', 'public');

        $report = ViolationReport::create([
            'report_number'     => 'REP-' . date('Ymd') . '-' . rand(1000, 9999),
            'student_id'        => $matchedStudent?->id,
            'reported_name'     => $request->reported_name,
            'reported_nim'      => $request->reported_nim,
            'reported_faculty'  => $faculty,
            'reported_department' => $request->reported_department,
            'target_department' => $request->reported_department,
            'reporter_id'       => Auth::id(),
            'points'            => 0, // Sistem poin telah ditiadakan sesuai ketentuan kampus
            'severity'          => $request->severity,
            'incident_date'     => $request->incident_date,
            'incident_time'     => $request->incident_time,
            'incident_location' => $request->incident_location,
            'chronology'        => $request->chronology,
            'status'            => 'pending',
            'academic_year'     => \App\Models\AcademicYear::getActiveYear(),
            'semester'          => 'Ganjil',
            'reported_semester' => $request->reported_semester, // Semester berupa angka
            'evidence_path'     => $evidencePath,
            'evidence_name'     => $evidenceName,
        ]);

        foreach ($evidenceFiles as $index => $file) {
            $filePath = $index === 0 ? $evidencePath : $file->store('evidences', 'public');
            ReportEvidence::create([
                'violation_report_id' => $report->id,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'mime_type' => $file->getClientMimeType(),
            ]);
        }

        if (Auth::user()->isMahasiswa()) {
            return redirect()->route('reports.create')->with('success', 'Laporan Anda berhasil dikirim ke Bagian Kemahasiswaan secara aman dan terlindungi (Anonim). Terima kasih atas partisipasi Anda.');
        }

        if (Auth::user()->isKajur()) {
            return redirect()->route('reports.index')->with('success', 'Laporan berhasil diajukan dari Ketua Jurusan dan sedang menunggu verifikasi Bagian Kemahasiswaan.');
        }

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil diajukan dan sedang menunggu verifikasi Bagian Kemahasiswaan.');
    }

    public function show($id)
    {
        $report = ViolationReport::with(['student', 'reporter', 'verifier', 'handler', 'evidences'])->findOrFail($id);
        $user = Auth::user();

        // RBAC Access Check
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Mahasiswa hanya memiliki hak akses untuk melakukan pelaporan pelanggaran.');
        }
        if ($user->isKajur()) {
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

    public function previewEvidence($id, $evidenceId = null)
    {
        $report = ViolationReport::with('evidences')->findOrFail($id);
        $user = Auth::user();

        $evidence = $evidenceId
            ? $report->evidences->firstWhere('id', (int) $evidenceId)
            : $report->evidences->first();
        if ($evidenceId && !$evidence) {
            abort(404, 'Berkas bukti tidak ditemukan.');
        }
        $filePath = $evidence?->file_path ?? $report->evidence_path;
        $fileName = $evidence?->original_name ?? $report->evidence_name ?? 'Berkas Bukti';
        $fileMimeType = $evidence?->mime_type ?? \Illuminate\Support\Facades\Storage::disk('public')->mimeType($filePath);
        if (!$filePath) {
            abort(404, 'Berkas bukti tidak ditemukan.');
        }
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Mahasiswa hanya memiliki hak akses untuk melakukan pelaporan pelanggaran.');
        }
        if ($user->isKajur() && $report->target_department !== $user->department && $report->reported_department !== $user->department) {
            abort(403, 'Laporan ini tidak ditujukan untuk jurusan Anda.');
        }
        if ($user->isDekan() || $user->isWadek3()) {
            $allowedDepts = array_keys(array_filter(self::departmentFacultyMap(), fn ($faculty) => $faculty === $user->faculty));
            if (!in_array($report->target_department, $allowedDepts) && !in_array($report->reported_department, $allowedDepts)) {
                abort(403, 'Laporan ini berada di luar fakultas Anda.');
            }
        }

        return view('reports.evidence-preview', compact('filePath', 'fileName', 'fileMimeType'));
    }

    // Kemahasiswaan Action 1: Koreksi Data Pelanggar
    public function updateData(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isKemahasiswaan()) {
            abort(403, 'Hanya Bagian Kemahasiswaan yang dapat mengoreksi data laporan.');
        }

        $report = ViolationReport::findOrFail($id);

        $request->validate([
            'reported_name' => 'required|string|max:150',
            'reported_nim' => 'required|string|max:50',
            'reported_department' => ['required', 'string', Rule::in(array_keys(self::departmentFacultyMap()))],
            'severity' => 'required|string|in:Ringan,Sedang,Berat,Suspek',
            'points' => 'nullable|integer|min:0',
            'incident_location' => 'required|string|max:255',
            'chronology' => 'required|string',
        ]);

        // Attempt matching student
        $matchedStudent = Student::where('nim', trim($request->reported_nim))->first();

        $report->update([
            'reported_name' => $request->reported_name,
            'reported_nim' => $request->reported_nim,
            'reported_department' => $request->reported_department,
            'severity' => $request->severity,
            'points' => 0, // Sistem poin telah ditiadakan
            'incident_location' => $request->incident_location,
            'chronology' => $request->chronology,
            'student_id' => $matchedStudent?->id ?? $report->student_id,
        ]);

        return back()->with('success', 'Data pelanggar berhasil divalidasi dan diperbarui oleh Kemahasiswaan.');
    }

    // Kemahasiswaan Action 2: Setujui & Teruskan ke Kajur
    public function approveAndForward(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isKemahasiswaan()) {
            abort(403, 'Hanya Bagian Kemahasiswaan yang dapat menyetujui laporan.');
        }

        $report = ViolationReport::findOrFail($id);

        $request->validate([
            'target_department' => 'nullable|string|max:150',
            'points' => 'nullable|integer|min:0',
        ]);

        $targetDept = $request->input('target_department') ?: ($report->reported_department ?: $report->target_department);

        $report->update([
            'status' => 'dikonfirmasi', // Terkonfirmasi & diteruskan ke Kajur
            'target_department' => $targetDept,
            'points' => 0,
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);

        return back()->with('success', "Laporan berhasil DISETUJUI dan DITERUSKAN ke Ketua Jurusan {$targetDept}.");
    }

    // Kemahasiswaan Action 3: Tolak Laporan
    public function reject(Request $request, $id)
    {
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
        ]);

        return back()->with('success', 'Laporan telah DITOLAK. Pelapor dapat membaca alasan penolakan pada status laporan.');
    }

    // Kajur Action: Tindak Lanjut (Diproses -> Selesai)
    public function tindakLanjut(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isKajur()) {
            abort(403, 'Hanya Ketua Jurusan yang berwenang melakukan tindak lanjut sanksi.');
        }

        $report = ViolationReport::findOrFail($id);

        // Department authorization
        if ($report->target_department !== $user->department && $report->reported_department !== $user->department) {
            abort(403, 'Laporan ini bukan wewenang jurusan Anda.');
        }

        $request->validate([
            'status' => 'required|in:diproses,selesai',
            'sanction_type' => 'required|string|max:150',
            'kajur_notes' => 'required|string|min:5',
        ]);

        $report->status = $request->status;
        $report->sanction_type = $request->sanction_type;
        $report->kajur_notes = $request->kajur_notes;
        $report->handled_by = $user->id;
        $report->handled_at = now();

        if ($request->status === 'selesai') {
            $report->completed_at = now();
        }

        $report->save();

        $statusMsg = $request->status === 'selesai' ? 'SELESAI TINDAK LANJUT' : 'SEDANG DIPROSES';
        return back()->with('success', "Status kasus berhasil diperbarui menjadi: {$statusMsg}. Catatan sanksi telah tersimpan.");
    }

    // Export Excel / CSV: Khusus data riwayat yang sudah terverifikasi
    public function exportExcel()
    {
        $user = Auth::user();
        $query = ViolationReport::whereNotIn('status', ['pending', 'dilaporkan']);

        if ($user && $user->isKajur()) {
            $dept = $user->department;
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

        $reports = $query->orderBy('created_at', 'desc')->get();
        $filenamePrefix = $user && $user->isKajur()
            ? 'data_laporan_' . \Illuminate\Support\Str::slug($user->department ?: 'jurusan')
            : 'rekap_laporan_terverifikasi';
        $filename = $filenamePrefix . '_' . date('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'No. Laporan',
            'Tanggal Kejadian',
            'Nama Terlapor',
            'NIM',
            'Jurusan Terlapor',
            'Semester Terlapor',
            'Kategori Pelanggaran',
            'Status'
        ];

        $callback = function() use ($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            fputcsv($file, $columns);

            foreach ($reports as $r) {
                fputcsv($file, [
                    $r->report_number,
                    $r->incident_date ? $r->incident_date->format('Y-m-d') : '-',
                    $r->student_name,
                    $r->student_nim,
                    $r->reported_department ?: $r->target_department,
                    $r->reported_semester ? 'Semester ' . $r->reported_semester : '-',
                    $r->severity ?? 'Ringan',
                    'Terverifikasi',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export PDF / Print View: Khusus data riwayat yang sudah terverifikasi
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $query = ViolationReport::whereNotIn('status', ['pending', 'dilaporkan']);

        if ($user && $user->isKajur()) {
            $dept = $user->department;
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
