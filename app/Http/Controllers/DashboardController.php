<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ViolationReport;
use App\Models\AcademicYear;

class DashboardController extends Controller
{
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
            AcademicYear::create(['year' => '2026/2027', 'is_active' => true]);
            $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        }

        $activeAcademicYear = AcademicYear::getActiveYear();

        // If academic_year is provided in URL, use it; otherwise default to active academic year (2026/2027)
        $selectedAcademicYear = $request->has('academic_year') ? $request->get('academic_year') : $activeAcademicYear;

        // Suggested next academic year for the add-modal
        $latestYear = $academicYears->first()?->year ?? '2026/2027';
        if (preg_match('/^(\d{4})\/(\d{4})$/', $latestYear, $matches)) {
            $y1 = (int)$matches[1] + 1;
            $y2 = (int)$matches[2] + 1;
            $suggestedNextYear = "{$y1}/{$y2}";
        } else {
            $suggestedNextYear = '2027/2028';
        }

        // 2. Query reports filtered by selected academic year
        $query = ViolationReport::query()->with(['student', 'reporter']);

        if ($selectedAcademicYear && $selectedAcademicYear !== 'all') {
            $query->where('academic_year', $selectedAcademicYear);
        }

        $allReports = $query->orderBy('created_at', 'desc')->get();

        // 3. Overall KPI Metrics: 3 Metrik Sederhana
        $totalReports = $allReports->count();
        $pendingCount = $allReports->whereIn('status', ['pending', 'dilaporkan'])->count();
        $verifiedCount = $allReports->whereNotIn('status', ['pending', 'dilaporkan'])->count();
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

            $facultyCounts[$facName] = $facReports->count();

            // Department breakdown within this faculty
            $deptBreakdown = [];
            foreach ($depts as $deptName) {
                $deptBreakdown[$deptName] = $facReports->filter(function ($r) use ($deptName) {
                    return ($r->reported_department === $deptName) || ($r->target_department === $deptName);
                })->count();
            }

            arsort($deptBreakdown);
            $facultyStats[$facName] = [
                'name' => $facName,
                'departments' => $depts,
                'total' => $facReports->count(),
                'ringan' => $facReports->where('severity', 'Ringan')->count(),
                'sedang' => $facReports->where('severity', 'Sedang')->count(),
                'berat' => $facReports->where('severity', 'Berat')->count(),
                'pending' => $facReports->whereIn('status', ['pending', 'dilaporkan'])->count(),
                'verified' => $facReports->whereNotIn('status', ['pending', 'dilaporkan'])->count(),
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
            'Ringan' => $allReports->where('severity', 'Ringan')->count(),
            'Sedang' => $allReports->where('severity', 'Sedang')->count(),
            'Berat' => $allReports->where('severity', 'Berat')->count(),
        ];

        // 6. Inbox Cepat Laporan Menunggu Verifikasi
        $pendingReports = ViolationReport::whereIn('status', ['pending', 'dilaporkan'])
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
        ], [
            'year.required' => 'Tahun akademik wajib diisi.',
            'year.regex' => 'Format tahun akademik harus YYYY/YYYY (contoh: 2027/2028).',
        ]);

        $year = trim($request->year);

        preg_match('/^(\d{4})\/(\d{4})$/', $year, $parts);
        if ((int) $parts[2] !== (int) $parts[1] + 1) {
            return back()->withErrors(['year' => 'Tahun kedua harus satu tahun setelah tahun pertama.'])->withInput();
        }

        AcademicYear::setActive($year);

        return redirect()->route('kemahasiswaan.dashboard', ['academic_year' => $year])
            ->with('success', "Tahun Akademik {$year} berhasil ditambahkan dan ditetapkan sebagai tahun akademik aktif. Semua laporan baru otomatis masuk ke tahun akademik ini.");
    }

    public function updateAcademicYear(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'year' => ['required', 'string', 'max:20', 'regex:/^(\\d{4})\\/(\\d{4})$/', 'unique:academic_years,year,' . $academicYear->id],
        ], [
            'year.regex' => 'Format tahun akademik harus YYYY/YYYY (contoh: 2027/2028).',
            'year.unique' => 'Tahun akademik tersebut sudah terdaftar.',
        ]);

        preg_match('/^(\\d{4})\\/(\\d{4})$/', $validated['year'], $parts);
        if ((int) $parts[2] !== (int) $parts[1] + 1) {
            return back()->withErrors(['year' => 'Tahun kedua harus satu tahun setelah tahun pertama.'])->withInput();
        }

        $academicYear->update(['year' => trim($validated['year'])]);

        return redirect()->route('kemahasiswaan.dashboard', ['academic_year' => $academicYear->year])
            ->with('success', "Tahun Akademik {$academicYear->year} berhasil diperbarui.");
    }

    public function destroyAcademicYear(AcademicYear $academicYear)
    {
        if (ViolationReport::where('academic_year', $academicYear->year)->exists()) {
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
        $selectedAcademicYear = $request->input('academic_year', $activeAcademicYear?->year ?? 'all');

        // ── Query laporan HANYA yang sudah diverifikasi (dikonfirmasi) untuk jurusan ini ──
        $query = ViolationReport::where(function ($q) use ($myDept) {
            $q->where('target_department', $myDept)
              ->orWhere('reported_department', $myDept);
        })->whereNotIn('status', ['pending', 'dilaporkan', 'ditolak']);

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
        $lastViewed = session('kajur_last_viewed_reports_' . $user->id, 0);
        $latestReport = ViolationReport::where(function ($q) use ($myDept) {
            $q->where('target_department', $myDept)
              ->orWhere('reported_department', $myDept);
        })->whereNotIn('status', ['pending', 'dilaporkan', 'ditolak'])
          ->orderByDesc('verified_at')
          ->first();

        $hasUnreadReports = $latestReport && $latestReport->verified_at
            && $latestReport->verified_at->timestamp > $lastViewed;

        $newReportsCount = $hasUnreadReports
            ? ViolationReport::where(function ($q) use ($myDept) {
                $q->where('target_department', $myDept)
                  ->orWhere('reported_department', $myDept);
            })->whereNotIn('status', ['pending', 'dilaporkan', 'ditolak'])
              ->whereRaw('UNIX_TIMESTAMP(verified_at) > ?', [$lastViewed])
              ->count()
            : 0;

        // ── 5 kasus terbaru ──
        $recentCases = ViolationReport::where(function ($q) use ($myDept) {
            $q->where('target_department', $myDept)
              ->orWhere('reported_department', $myDept);
        })->whereNotIn('status', ['pending', 'dilaporkan', 'ditolak'])
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
    public function dekan()
    {
        $user = Auth::user();
        $myFaculty = $user->faculty ?? (array_key_first(ReportController::facultyDepartmentMap()) ?? '');
        $facultyDepts = array_keys(array_filter(ReportController::departmentFacultyMap(), fn($f) => $f === $myFaculty));

        $facultyReports = ViolationReport::where(function ($q) use ($facultyDepts) {
            $q->whereIn('target_department', $facultyDepts)
              ->orWhereIn('reported_department', $facultyDepts);
        })->get();

        $totalFacultyReports = $facultyReports->count();
        $completedCount = $facultyReports->where('status', 'selesai')->count();
        $inProgressCount = $facultyReports->whereIn('status', ['dikonfirmasi', 'diproses'])->count();
        $pendingCount = $facultyReports->where('status', 'pending')->count();

        // Grafik Pelanggaran Fakultas (Perbandingan antar Jurusan di fakultasnya)
        $deptStats = [];
        foreach ($facultyDepts as $dept) {
            $deptStats[$dept] = $facultyReports->filter(function($r) use ($dept) {
                return ($r->target_department === $dept) || ($r->reported_department === $dept);
            })->count();
        }

        // Grafik Jenis Pelanggaran Fakultas
        $severityCounts = [
            'Ringan' => $facultyReports->where('severity', 'Ringan')->count(),
            'Sedang' => $facultyReports->where('severity', 'Sedang')->count(),
            'Berat' => $facultyReports->where('severity', 'Berat')->count(),
            'Suspek' => $facultyReports->where('severity', 'Suspek')->count(),
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
            'severityCounts'
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
        $selectedAcademicYear = $request->has('academic_year')
            ? $request->get('academic_year')
            : $activeAcademicYear;
        $facultyDepts = array_keys(array_filter(ReportController::departmentFacultyMap(), fn($f) => $f === $myFaculty));

        $reportsQuery = ViolationReport::where(function ($q) use ($facultyDepts) {
            $q->whereIn('target_department', $facultyDepts)
              ->orWhereIn('reported_department', $facultyDepts);
        });
        if ($selectedAcademicYear && $selectedAcademicYear !== 'all') {
            $reportsQuery->where('academic_year', $selectedAcademicYear);
        }
        $facultyReports = $reportsQuery->get();

        $totalFacultyReports = $facultyReports->count();
        $completedCount = $facultyReports->where('status', 'selesai')->count();
        $inProgressCount = $facultyReports->whereIn('status', ['dikonfirmasi', 'diproses'])->count();
        $pendingCount = $facultyReports->whereIn('status', ['pending', 'dilaporkan'])->count();
        $verifiedCount = $facultyReports->whereNotIn('status', ['pending', 'dilaporkan'])->count();

        $deptStats = [];
        foreach ($facultyDepts as $dept) {
            $deptStats[$dept] = $facultyReports->filter(function($r) use ($dept) {
                return ($r->target_department === $dept) || ($r->reported_department === $dept);
            })->count();
        }
        arsort($deptStats);

        // Breakdown severity per jurusan untuk modal detail
        $deptBreakdown = [];
        foreach ($facultyDepts as $dept) {
            $deptReports = $facultyReports->filter(function($r) use ($dept) {
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
            'Ringan' => $facultyReports->where('severity', 'Ringan')->count(),
            'Sedang' => $facultyReports->where('severity', 'Sedang')->count(),
            'Berat'  => $facultyReports->where('severity', 'Berat')->count(),
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
        $selectedAcademicYear = $request->has('academic_year')
            ? $request->get('academic_year')
            : $activeAcademicYear;

        $reportsQuery = ViolationReport::with(['student', 'reporter'])->orderBy('created_at', 'desc');
        if ($selectedAcademicYear && $selectedAcademicYear !== 'all') {
            $reportsQuery->where('academic_year', $selectedAcademicYear);
        }
        $allReports = $reportsQuery->get();
        $totalReports = $allReports->count();
        $pendingCount = $allReports->whereIn('status', ['pending', 'dilaporkan'])->count();
        $verifiedCount = $allReports->whereNotIn('status', ['pending', 'dilaporkan'])->count();

        // Rekap fakultas mengikuti cara Kemahasiswaan mengelompokkan data.
        $facultyCounts = [];
        $facultyStats = [];
        foreach (ReportController::facultyDepartmentMap() as $facultyName => $facultyDepartments) {
            $facultyReports = $allReports->filter(function ($report) use ($facultyName, $facultyDepartments) {
                if ($report->reported_faculty === $facultyName) return true;
                $department = $report->reported_department ?: $report->target_department;
                return in_array($department, $facultyDepartments, true);
            })->values();

            $deptBreakdown = [];
            $deptDetails = [];
            foreach ($facultyDepartments as $department) {
                $departmentReports = $facultyReports->filter(fn($report) =>
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

            $facultyCounts[$facultyName] = $facultyReports->count();
            $facultyStats[$facultyName] = [
                'departments' => $facultyDepartments,
                'total' => $facultyReports->count(),
                'ringan' => $facultyReports->where('severity', 'Ringan')->count(),
                'sedang' => $facultyReports->where('severity', 'Sedang')->count(),
                'berat' => $facultyReports->where('severity', 'Berat')->count(),
                'pending' => $facultyReports->whereIn('status', ['pending', 'dilaporkan'])->count(),
                'verified' => $facultyReports->whereNotIn('status', ['pending', 'dilaporkan'])->count(),
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
            'Ringan' => $allReports->where('severity', 'Ringan')->count(),
            'Sedang' => $allReports->where('severity', 'Sedang')->count(),
            'Berat' => $allReports->where('severity', 'Berat')->count(),
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
