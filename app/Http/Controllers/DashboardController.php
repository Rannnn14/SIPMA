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
        $faculties = ReportController::$faculties;
        $departments = array_keys(ReportController::$departments);
        $severities = ReportController::$severities;

        return view('dashboard.mahasiswa', compact('faculties', 'departments', 'severities'));
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

        // 3. Overall KPI Metrics
        $totalReports = $allReports->count();
        $pendingCount = $allReports->whereIn('status', ['pending', 'dilaporkan'])->count();
        $confirmedCount = $allReports->whereIn('status', ['dikonfirmasi', 'diverifikasi_kaprodi', 'diteruskan_kemahasiswaan'])->count();
        $rejectedCount = $allReports->where('status', 'ditolak')->count();
        $inProgressCount = $allReports->whereIn('status', ['diproses', 'sedang_diproses'])->count();
        $completedCount = $allReports->whereIn('status', ['selesai', 'sanksi_ditetapkan'])->count();

        // 4. Analisis Berbasis FAKULTAS (Peringkat Pelanggaran Antar-Fakultas)
        $facultiesList = ReportController::$faculties;
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

            $facultyStats[$facName] = [
                'name' => $facName,
                'departments' => $depts,
                'total' => $facReports->count(),
                'ringan' => $facReports->where('severity', 'Ringan')->count(),
                'sedang' => $facReports->where('severity', 'Sedang')->count(),
                'berat' => $facReports->where('severity', 'Berat')->count(),
                'pending' => $facReports->whereIn('status', ['pending', 'dilaporkan'])->count(),
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

        AcademicYear::setActive($year);

        return redirect()->route('kemahasiswaan.dashboard', ['academic_year' => $year])
            ->with('success', "Tahun Akademik {$year} berhasil ditambahkan dan ditetapkan sebagai tahun akademik aktif. Semua laporan baru otomatis masuk ke tahun akademik ini.");
    }

    /**
     * 3. Dashboard Ketua Jurusan (Tindak Lanjut & Sanksi Prodi)
     * Route: /kajur/dashboard
     */
    public function kajur()
    {
        $user = Auth::user();
        $myDept = $user->department ?? 'Teknik Informatika';

        $kajurReports = ViolationReport::where(function ($q) use ($myDept) {
            $q->where('target_department', $myDept)
              ->orWhere('reported_department', $myDept);
        })->whereIn('status', ['dikonfirmasi', 'diproses', 'selesai'])->get();

        $totalKajurReports = $kajurReports->count();
        $needsActionCount = $kajurReports->where('status', 'dikonfirmasi')->count();
        $inProcessCount = $kajurReports->where('status', 'diproses')->count();
        $completedCount = $kajurReports->where('status', 'selesai')->count();

        // Grafik Khusus Jurusan: Jenis/Kategori dominan di jurusan ini saja
        $severityCounts = [
            'Ringan' => $kajurReports->where('severity', 'Ringan')->count(),
            'Sedang' => $kajurReports->where('severity', 'Sedang')->count(),
            'Berat' => $kajurReports->where('severity', 'Berat')->count(),
            'Suspek' => $kajurReports->where('severity', 'Suspek')->count(),
        ];

        // Daftar Kasus Jurusan Saya
        $recentCases = ViolationReport::where(function ($q) use ($myDept) {
            $q->where('target_department', $myDept)
              ->orWhere('reported_department', $myDept);
        })->whereIn('status', ['dikonfirmasi', 'diproses', 'selesai'])
          ->orderBy('created_at', 'desc')
          ->take(5)
          ->get();

        return view('dashboard.kajur', compact(
            'myDept',
            'totalKajurReports',
            'needsActionCount',
            'inProcessCount',
            'completedCount',
            'severityCounts',
            'recentCases'
        ));
    }

    /**
     * 4. Dashboard Dekan (Monitoring Fakultas - Read Only)
     * Route: /dekan/dashboard
     */
    public function dekan()
    {
        $user = Auth::user();
        $myFaculty = $user->faculty ?? 'Fakultas Ilmu Komputer';
        $facultyDepts = array_keys(array_filter(ReportController::$departments, fn($f) => $f === $myFaculty));

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
     * 5. Dashboard Wakil Rektor (Monitoring Universitas - Read Only)
     * Route: /warek/dashboard
     */
    public function warek()
    {
        $allReports = ViolationReport::all();
        $totalReports = $allReports->count();
        $completedCount = $allReports->where('status', 'selesai')->count();
        $inProgressCount = $allReports->whereIn('status', ['dikonfirmasi', 'diproses'])->count();
        $resolutionRate = $totalReports > 0 ? round(($completedCount / $totalReports) * 100, 1) : 0;

        // Grafik Ranking Pelanggaran Seluruh Universitas
        $deptCounts = [];
        $departments = array_keys(ReportController::$departments);
        foreach ($departments as $dept) {
            $deptCounts[$dept] = $allReports->filter(function($r) use ($dept) {
                return ($r->target_department === $dept) || ($r->reported_department === $dept);
            })->count();
        }
        arsort($deptCounts);

        // Grafik Tren Pelanggaran per Bulan (6 bulan terakhir)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthKey = $month->format('M Y');
            $count = $allReports->filter(function ($r) use ($month) {
                return $r->created_at && $r->created_at->format('Y-m') === $month->format('Y-m');
            })->count();
            $monthlyTrend[$monthKey] = $count;
        }

        // Komposisi Kategori Pelanggaran
        $severityCounts = [
            'Ringan' => $allReports->where('severity', 'Ringan')->count(),
            'Sedang' => $allReports->where('severity', 'Sedang')->count(),
            'Berat' => $allReports->where('severity', 'Berat')->count(),
            'Suspek' => $allReports->where('severity', 'Suspek')->count(),
        ];

        return view('dashboard.warek', compact(
            'totalReports',
            'completedCount',
            'inProgressCount',
            'resolutionRate',
            'deptCounts',
            'monthlyTrend',
            'severityCounts'
        ));
    }
}
