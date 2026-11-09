<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ViolationReport;
use App\Models\AcademicYear;

class DashboardController extends Controller
{
    private function selectedAcademicYear(Request $request, string $default): string
    {
        $key = 'selected_academic_year_' . Auth::id();
        $selected = (string) $request->input('academic_year', session($key, $default));
        if ($request->has('academic_year') || !session()->has($key)) {
            session([$key => $selected]);
        }

        if ($selected !== 'all') {
            $selected = $this->normalizeAcademicPeriod($selected);
            session([$key => $selected]);
        }

        return $selected;
    }

    private function normalizeAcademicPeriod(string $period): string
    {
        if (preg_match('/^(\d{4}\/\d{4})(?:\s+(Gasal|Genap))?$/u', $period, $matches)) {
            return $matches[1] . ' ' . ($matches[2] ?? 'Gasal');
        }
        return $period;
    }

    private const VERIFIED_REPORT_STATUSES = [
        'dikonfirmasi',
        'diverifikasi_kaprodi',
        'diteruskan_kemahasiswaan',
        'diproses',
        'sedang_diproses',
        'sanksi_ditetapkan',
        'selesai',
    ];

    /**
     * Redirect to the dedicated dashboard according to user's role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isKemahasiswaan()) {
            return redirect()->route('kemahasiswaan.dashboard');
        } elseif ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        } elseif ($user->isKajur()) {
            return redirect()->route('kajur.dashboard');
        } elseif ($user->isDekan()) {
            return redirect()->route('dekan.dashboard');
        } elseif ($user->isWadek3()) {
            return redirect()->route('wadek3.dashboard');
        } elseif ($user->isWarek()) {
            return redirect()->route('warek.dashboard');
        }

        abort(403, 'Role tidak dikenali.');
    }

    /**
     * 1. Dashboard Mahasiswa (Portal Pelapor)
     * Route: /mahasiswa/dashboard
     */
    public function mahasiswa()
    {
        $faculties       = ReportController::facultyDepartmentMap();
        $departments     = array_keys(ReportController::departmentFacultyMap());
        $departmentFacultyMap = ReportController::departmentFacultyMap();
        $severities      = ReportController::$severities;
        $semesterOptions = ReportController::getSemesterOptions();

        // Ambil data profil dari Bridge API session (jika tersedia)
        $bridgeProfile = session('bridge_profile');

        return view('dashboard.mahasiswa', compact(
            'faculties',
            'departments',
            'departmentFacultyMap',
            'severities',
            'semesterOptions',
            'bridgeProfile'
        ));
    }

    /**
     * 2. Dashboard Kemahasiswaan (Super Admin & Global Analyst)
     * Route: /kemahasiswaan/dashboard
     */
    public function kemahasiswaan(Request $request)
    {
        // 1. Academic Years setup
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        if ($academicYears->isEmpty()) {
            AcademicYear::create(['year' => '2026/2027 Gasal', 'is_active' => true]);
            $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        }

        $activeAcademicYear = AcademicYear::getActiveYear();

        // If academic_year is provided in URL, use it; otherwise default to active academic year (2026/2027)
        $selectedAcademicYear = $this->selectedAcademicYear($request, $activeAcademicYear);

        // Suggested next academic year for the add-modal
        $latestYear = $academicYears->first()?->year ?? '2026/2027';
        if (preg_match('/^(\d{4})\/(\d{4})/', $latestYear, $matches)) {
            $y1 = (int)$matches[1] + 1;
            $y2 = (int)$matches[2] + 1;
            $suggestedNextYear = "{$y1}/{$y2}";
        } else {
            $suggestedNextYear = '2027/2028';
        }

        // 2. Query reports filtered by selected academic year
        $query = ViolationReport::query()->with(['student', 'reporter']);

        if ($selectedAcademicYear && $selectedAcademicYear !== 'all') {
            [$year, $semester] = $this->splitAcademicPeriod($selectedAcademicYear);
            $query->where('academic_year', $year)->where('semester', $semester);
        }

        $allReports = $query->orderBy('created_at', 'desc')->get();

        // 3. Overall KPI Metrics: 3 Metrik Sederhana
        $totalReports = $allReports->count();
        $pendingCount = $allReports->whereIn('status', ['pending', 'dilaporkan'])->count();
        $verifiedCount = $allReports->whereNotIn('status', ['pending', 'dilaporkan', 'ditolak'])->count();
        $confirmedCount = $allReports->whereIn('status', ['dikonfirmasi', 'diverifikasi_kaprodi', 'diteruskan_kemahasiswaan'])->count();
        $rejectedCount = $allReports->where('status', 'ditolak')->count();
        $inProgressCount = $allReports->whereIn('status', ['diproses', 'sedang_diproses'])->count();
        $completedCount = $allReports->whereIn('status', ['selesai', 'sanksi_ditetapkan'])->count();

        // 4. Analisis Berbasis FAKULTAS
        $facultiesList = ReportController::facultyDepartmentMap();
        $facultyCounts = [];
        $facultyStats = [];

        foreach ($facultiesList as $facName => $depts) {
            $facReports = $allReports->filter(function ($r) use ($facName, $depts) {
                if ($r->reported_faculty === $facName) {
                    return true;
                }
                $rDept = $r->reported_department ?: $r->target_department;
                return in_array($rDept, $depts);
            });
            $verifiedFacReports = $facReports->whereIn('status', self::VERIFIED_REPORT_STATUSES);

            $facultyCounts[$facName] = $verifiedFacReports->count();

            // Department breakdown within this faculty
            $deptBreakdown = [];
            foreach ($depts as $deptName) {
                $deptBreakdown[$deptName] = $verifiedFacReports->filter(function ($r) use ($deptName) {
                    return ($r->reported_department === $deptName) || ($r->target_department === $deptName);
                })->count();
            }

            arsort($deptBreakdown);
            $facultyStats[$facName] = [
                'name' => $facName,
                'departments' => $depts,
                'total' => $facReports->count(),
                'ringan' => $verifiedFacReports->where('severity', 'Ringan')->count(),
                'sedang' => $verifiedFacReports->where('severity', 'Sedang')->count(),
                'berat' => $verifiedFacReports->where('severity', 'Berat')->count(),
                'pending' => $facReports->whereIn('status', ['pending', 'dilaporkan'])->count(),
                'verified' => $verifiedFacReports->count(),
                'rejected' => $facReports->where('status', 'ditolak')->count(),
                'diproses' => $facReports->whereIn('status', ['diproses', 'sedang_diproses', 'dikonfirmasi'])->count(),
                'selesai' => $facReports->whereIn('status', ['selesai', 'sanksi_ditetapkan'])->count(),
                'deptBreakdown' => $deptBreakdown,
                'reports' => $facReports->values(),
            ];
        }

        // Urutkan peringkat fakultas dari yang paling sering melakukan pelanggaran (Descending)
        arsort($facultyCounts);

        // 5. Severity distribution (Ringan, Sedang, Berat)
        $severityCounts = [
            'Ringan' => $allReports->whereIn('status', self::VERIFIED_REPORT_STATUSES)->where('severity', 'Ringan')->count(),
            'Sedang' => $allReports->whereIn('status', self::VERIFIED_REPORT_STATUSES)->where('severity', 'Sedang')->count(),
            'Berat' => $allReports->whereIn('status', self::VERIFIED_REPORT_STATUSES)->where('severity', 'Berat')->count(),
        ];

        // 6. Inbox Cepat Laporan Menunggu Verifikasi
        $pendingReports = ViolationReport::whereIn('status', ['pending', 'dilaporkan'])
            ->when($selectedAcademicYear !== 'all', function ($q) use ($selectedAcademicYear) { [$year, $semester] = $this->splitAcademicPeriod($selectedAcademicYear); $q->where('academic_year', $year)->where('semester', $semester); })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.kemahasiswaan', compact(
            'academicYears',
            'activeAcademicYear',
            'selectedAcademicYear',
            'suggestedNextYear',
            'totalReports',
            'pendingCount',
            'verifiedCount',
            'confirmedCount',
            'rejectedCount',
            'inProgressCount',
            'completedCount',
            'facultyCounts',
            'facultyStats',
            'severityCounts',
            'pendingReports'
        ));
    }

    /**
     * Simpan tahun akademik baru dan otomatis jadikan tahun akademik aktif untuk laporan selanjutnya.
     */
    public function storeAcademicYear(Request $request)
    {
        $request->validate([
            'year' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{4}\/\d{4}$/',
            ],
            'semester' => ['required', 'in:Gasal,Genap'],
        ], [
            'year.required' => 'Tahun akademik wajib diisi.',
            'year.regex' => 'Format tahun akademik harus YYYY/YYYY (contoh: 2027/2028).',
        ]);

        $yearOnly = trim($request->year);
        preg_match('/^(\d{4})\/(\d{4})$/', $yearOnly, $parts);
        if (!isset($parts[1], $parts[2]) || (int) $parts[2] !== (int) $parts[1] + 1) {
            return back()->withErrors(['year' => 'Tahun kedua harus satu tahun setelah tahun pertama.'])->withInput();
        }
        $year = $yearOnly . ' ' . $request->semester;

        AcademicYear::setActive($year);

        return redirect()->route('kemahasiswaan.dashboard', ['academic_year' => $year])
            ->with('success', "Tahun Akademik {$year} berhasil ditambahkan dan ditetapkan sebagai tahun akademik aktif. Semua laporan baru otomatis masuk ke tahun akademik ini.");
    }

    public function updateAcademicYear(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'year' => ['required', 'string', 'max:20', 'regex:/^(\\d{4})\\/(\\d{4})$/', 'unique:academic_years,year,' . $academicYear->id],
            'semester' => ['required', 'in:Gasal,Genap'],
        ], [
            'year.regex' => 'Format tahun akademik harus YYYY/YYYY (contoh: 2027/2028).',
            'year.unique' => 'Tahun akademik tersebut sudah terdaftar.',
        ]);

        preg_match('/^(\\d{4})\\/(\\d{4})$/', $validated['year'], $parts);
        if (!isset($parts[1], $parts[2]) || (int) $parts[2] !== (int) $parts[1] + 1) {
            return back()->withErrors(['year' => 'Tahun kedua harus satu tahun setelah tahun pertama.'])->withInput();
        }

        $academicYear->update(['year' => trim($validated['year']) . ' ' . $validated['semester']]);

        return redirect()->route('kemahasiswaan.dashboard', ['academic_year' => $academicYear->year])
            ->with('success', "Tahun Akademik {$academicYear->year} berhasil diperbarui.");
    }

    public function destroyAcademicYear(AcademicYear $academicYear)
    {
        [$yearOnly, $semester] = $this->splitAcademicPeriod($academicYear->year);
        if (ViolationReport::where('academic_year', $yearOnly)->where('semester', $semester)->exists()) {
            return back()->withErrors(['academic_year' => 'Tahun akademik tidak dapat dihapus karena masih digunakan oleh laporan.']);
        }

        $wasActive = $academicYear->is_active;
        $deletedYear = $academicYear->year;
        $academicYear->delete();

        if ($wasActive) {
            $replacement = AcademicYear::orderByDesc('year')->first();
            if ($replacement) {
                $replacement->update(['is_active' => true]);
            } else {
                AcademicYear::create(['year' => '2026/2027', 'is_active' => true]);
            }
        }

        return redirect()->route('kemahasiswaan.dashboard')
            ->with('success', "Tahun Akademik {$deletedYear} berhasil dihapus.");
    }

    private function splitAcademicPeriod(string $period): array
    {
        if (preg_match('/^(\d{4}\/\d{4})\s+(Gasal|Genap)$/u', $period, $matches)) {
            return [$matches[1], $matches[2]];
        }

        return [$period, 'Gasal'];
    }

    /**
     * 3. Dashboard Ketua Jurusan (Penerima Laporan Terverifikasi)
     * Route: /kajur/dashboard
     * Kajur HANYA menerima laporan yang sudah diverifikasi oleh Kemahasiswaan.
     * Kajur tidak bisa mengubah status atau memberi sanksi.
     */
    public function kajur(Request $request)
    {
        $user = Auth::user();
        $myDept = $user->department ?? 'Informatika';

        // ── Tahun Akademik Filter ──
        $academicYears = AcademicYear::orderByDesc('is_active')->orderByDesc('year')->get();
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $selectedAcademicYear = $this->selectedAcademicYear($request, $activeAcademicYear?->year ?? 'all');

        // ── Query laporan HANYA yang sudah diverifikasi (dikonfirmasi) untuk jurusan ini ──
        $query = ViolationReport::where(function ($q) use ($myDept) {
            $q->where('target_department', $myDept)
              ->orWhere('reported_department', $myDept);
        })->whereIn('status', self::VERIFIED_REPORT_STATUSES);

        if ($selectedAcademicYear !== 'all') {
            $query->where('academic_year', $selectedAcademicYear);
        }

        $kajurReports = $query->get();

        $totalKajurReports = $kajurReports->count();

        $severityCounts = [
            'Ringan' => $kajurReports->where('severity', 'Ringan')->count(),
            'Sedang' => $kajurReports->where('severity', 'Sedang')->count(),
            'Berat'  => $kajurReports->where('severity', 'Berat')->count(),
        ];

        // ── Cek notifikasi laporan baru (belum dilihat di Data Laporan) ──
        $yearKey = str_replace('/', '-', $selectedAcademicYear);
        $lastViewed = session('kajur_last_viewed_reports_' . $user->id . '_' . $yearKey, 0);
        $latestReport = ViolationReport::where(function ($q) use ($myDept) {
            $q->where('target_department', $myDept)
              ->orWhere('reported_department', $myDept);
        })->whereIn('status', self::VERIFIED_REPORT_STATUSES)
          ->when($selectedAcademicYear !== 'all', fn($q) => $q->where('academic_year', $selectedAcademicYear))
          ->orderByDesc('verified_at')
          ->first();

        $hasUnreadReports = $latestReport && $latestReport->verified_at
            && $latestReport->verified_at->timestamp > $lastViewed;

        $newReportsCount = $hasUnreadReports
            ? ViolationReport::where(function ($q) use ($myDept) {
                $q->where('target_department', $myDept)
                  ->orWhere('reported_department', $myDept);
            })->whereIn('status', self::VERIFIED_REPORT_STATUSES)
              ->when($selectedAcademicYear !== 'all', fn($q) => $q->where('academic_year', $selectedAcademicYear))
              ->whereRaw('UNIX_TIMESTAMP(verified_at) > ?', [$lastViewed])
              ->count()
            : 0;

        // ── 5 kasus terbaru ──
        $recentCases = ViolationReport::where(function ($q) use ($myDept) {
            $q->where('target_department', $myDept)
              ->orWhere('reported_department', $myDept);
        })->whereIn('status', self::VERIFIED_REPORT_STATUSES)
          ->when($selectedAcademicYear !== 'all', fn($q) => $q->where('academic_year', $selectedAcademicYear))
          ->orderBy('verified_at', 'desc')
          ->take(5)
          ->get();

        return view('dashboard.kajur', compact(
            'myDept',
            'academicYears',
            'selectedAcademicYear',
            'totalKajurReports',
            'severityCounts',
            'recentCases',
            'hasUnreadReports',
            'newReportsCount'
        ));
    }

    /**
     * 4. Dashboard Dekan (Monitoring Fakultas - Read Only)
     * Route: /dekan/dashboard
     */
    public function dekan(Request $request)
    {
        $user = Auth::user();
        $academicYears = AcademicYear::orderByDesc('year')->get();
        $selectedAcademicYear = $this->selectedAcademicYear($request, AcademicYear::getActiveYear());
        $myFaculty = $user->faculty ?? (array_key_first(ReportController::facultyDepartmentMap()) ?? '');
        $facultyDepts = array_keys(array_filter(ReportController::departmentFacultyMap(), fn($f) => $f === $myFaculty));

        $facultyReports = ViolationReport::where(function ($q) use ($facultyDepts) {
            $q->whereIn('target_department', $facultyDepts)
              ->orWhereIn('reported_department', $facultyDepts);
        })->when($selectedAcademicYear !== 'all', fn($q) => $q->where('academic_year', $selectedAcademicYear))->get();
        $verifiedFacultyReports = $facultyReports->whereIn('status', self::VERIFIED_REPORT_STATUSES);

        $totalFacultyReports = $facultyReports->count();
        $completedCount = $facultyReports->where('status', 'selesai')->count();
        $inProgressCount = $facultyReports->whereIn('status', ['dikonfirmasi', 'diproses'])->count();
        $pendingCount = $facultyReports->where('status', 'pending')->count();

        // Grafik Pelanggaran Fakultas (Perbandingan antar Jurusan di fakultasnya)
        $deptStats = [];
        foreach ($facultyDepts as $dept) {
            $deptStats[$dept] = $verifiedFacultyReports->filter(function($r) use ($dept) {
                return ($r->target_department === $dept) || ($r->reported_department === $dept);
            })->count();
        }

        // Grafik Jenis Pelanggaran Fakultas
        $severityCounts = [
            'Ringan' => $verifiedFacultyReports->where('severity', 'Ringan')->count(),
            'Sedang' => $verifiedFacultyReports->where('severity', 'Sedang')->count(),
            'Berat' => $verifiedFacultyReports->where('severity', 'Berat')->count(),
            'Suspek' => $verifiedFacultyReports->where('severity', 'Suspek')->count(),
        ];

        arsort($deptStats);

        return view('dashboard.dekan', compact(
            'myFaculty',
            'facultyDepts',
            'totalFacultyReports',
            'completedCount',
            'inProgressCount',
            'pendingCount',
            'deptStats',
            'severityCounts',
            'academicYears',
            'selectedAcademicYear'
        ));
    }

    /**
     * 5b. Dashboard Wakil Dekan III (Monitoring Fakultas - Read Only)
     * Route: /wadek3/dashboard
     */
    public function wadek3(Request $request)
    {
        $user = Auth::user();
        $myFaculty = $user->faculty ?? (array_key_first(ReportController::facultyDepartmentMap()) ?? '');
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $activeAcademicYear = AcademicYear::getActiveYear();
        $selectedAcademicYear = $this->selectedAcademicYear($request, $activeAcademicYear);
        $facultyDepts = array_keys(array_filter(ReportController::departmentFacultyMap(), fn($f) => $f === $myFaculty));

        $reportsQuery = ViolationReport::where(function ($q) use ($facultyDepts) {
            $q->whereIn('target_department', $facultyDepts)
              ->orWhereIn('reported_department', $facultyDepts);
        });
        if ($selectedAcademicYear && $selectedAcademicYear !== 'all') {
            $reportsQuery->where('academic_year', $selectedAcademicYear);
        }
        $facultyReports = $reportsQuery->get();
        $verifiedFacultyReports = $facultyReports->whereIn('status', self::VERIFIED_REPORT_STATUSES);

        $totalFacultyReports = $facultyReports->count();
        $completedCount = $facultyReports->where('status', 'selesai')->count();
        $inProgressCount = $facultyReports->whereIn('status', ['dikonfirmasi', 'diproses'])->count();
        $pendingCount = $facultyReports->whereIn('status', ['pending', 'dilaporkan'])->count();
        $verifiedCount = $verifiedFacultyReports->count();

        $deptStats = [];
        foreach ($facultyDepts as $dept) {
            $deptStats[$dept] = $verifiedFacultyReports->filter(function($r) use ($dept) {
                return ($r->target_department === $dept) || ($r->reported_department === $dept);
            })->count();
        }
        arsort($deptStats);

        // Breakdown severity per jurusan untuk modal detail
        $deptBreakdown = [];
        foreach ($facultyDepts as $dept) {
            $deptReports = $verifiedFacultyReports->filter(function($r) use ($dept) {
                return ($r->target_department === $dept) || ($r->reported_department === $dept);
            });
            $deptBreakdown[$dept] = [
                'total'  => $deptReports->count(),
                'ringan' => $deptReports->where('severity', 'Ringan')->count(),
                'sedang' => $deptReports->where('severity', 'Sedang')->count(),
                'berat'  => $deptReports->where('severity', 'Berat')->count(),
            ];
        }

        $severityCounts = [
            'Ringan' => $verifiedFacultyReports->where('severity', 'Ringan')->count(),
            'Sedang' => $verifiedFacultyReports->where('severity', 'Sedang')->count(),
            'Berat'  => $verifiedFacultyReports->where('severity', 'Berat')->count(),
        ];

        return view('dashboard.wadek3', compact(
            'myFaculty',
            'academicYears',
            'selectedAcademicYear',
            'facultyDepts',
            'totalFacultyReports',
            'completedCount',
            'inProgressCount',
            'pendingCount',
            'verifiedCount',
            'deptStats',
            'severityCounts',
            'deptBreakdown'
        ));
    }

    /**
     * 6. Dashboard Wakil Rektor III (Monitoring Universitas - Read Only)
     * Route: /warek/dashboard
     */
    public function warek(Request $request)
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $activeAcademicYear = AcademicYear::getActiveYear();
        $selectedAcademicYear = $this->selectedAcademicYear($request, $activeAcademicYear);

        $reportsQuery = ViolationReport::with(['student', 'reporter'])->orderBy('created_at', 'desc');
        if ($selectedAcademicYear && $selectedAcademicYear !== 'all') {
            $reportsQuery->where('academic_year', $selectedAcademicYear);
        }
        $allReports = $reportsQuery->get();
        $totalReports = $allReports->count();
        $pendingCount = $allReports->whereIn('status', ['pending', 'dilaporkan'])->count();
        $verifiedCount = $allReports->whereNotIn('status', ['pending', 'dilaporkan', 'ditolak'])->count();
        $verifiedReports = $allReports->whereIn('status', self::VERIFIED_REPORT_STATUSES);

        // Rekap fakultas mengikuti cara Kemahasiswaan mengelompokkan data.
        $facultyCounts = [];
        $facultyStats = [];
        foreach (ReportController::facultyDepartmentMap() as $facultyName => $facultyDepartments) {
            $facultyReports = $allReports->filter(function ($report) use ($facultyName, $facultyDepartments) {
                if ($report->reported_faculty === $facultyName) return true;
                $department = $report->reported_department ?: $report->target_department;
                return in_array($department, $facultyDepartments, true);
            })->values();
            $verifiedFacultyReports = $facultyReports->whereIn('status', self::VERIFIED_REPORT_STATUSES);

            $deptBreakdown = [];
            $deptDetails = [];
            foreach ($facultyDepartments as $department) {
                $departmentReports = $verifiedFacultyReports->filter(fn($report) =>
                    $report->reported_department === $department || $report->target_department === $department
                );
                $deptBreakdown[$department] = $departmentReports->count();
                $deptDetails[$department] = [
                    'total' => $departmentReports->count(),
                    'ringan' => $departmentReports->where('severity', 'Ringan')->count(),
                    'sedang' => $departmentReports->where('severity', 'Sedang')->count(),
                    'berat' => $departmentReports->where('severity', 'Berat')->count(),
                ];
            }

            $facultyCounts[$facultyName] = $verifiedFacultyReports->count();
            $facultyStats[$facultyName] = [
                'departments' => $facultyDepartments,
                'total' => $facultyReports->count(),
                'ringan' => $verifiedFacultyReports->where('severity', 'Ringan')->count(),
                'sedang' => $verifiedFacultyReports->where('severity', 'Sedang')->count(),
                'berat' => $verifiedFacultyReports->where('severity', 'Berat')->count(),
                'pending' => $facultyReports->whereIn('status', ['pending', 'dilaporkan'])->count(),
                'verified' => $verifiedFacultyReports->count(),
                'rejected' => $facultyReports->where('status', 'ditolak')->count(),
                'deptBreakdown' => $deptBreakdown,
                'deptDetails' => collect($deptDetails)->sortByDesc('total')->all(),
                'reports' => $facultyReports,
            ];
        }
        arsort($facultyCounts);

        foreach ($facultyStats as &$stats) {
            arsort($stats['deptBreakdown']);
            $stats['deptDetails'] = collect($stats['deptDetails'])->sortByDesc('total')->all();
        }
        unset($stats);

        // Komposisi Kategori Pelanggaran
        $severityCounts = [
            'Ringan' => $verifiedReports->where('severity', 'Ringan')->count(),
            'Sedang' => $verifiedReports->where('severity', 'Sedang')->count(),
            'Berat' => $verifiedReports->where('severity', 'Berat')->count(),
        ];

        return view('dashboard.warek', compact(
            'academicYears',
            'selectedAcademicYear',
            'totalReports',
            'pendingCount',
            'verifiedCount',
            'facultyCounts',
            'facultyStats',
            'severityCounts'
        ));
    }
}
