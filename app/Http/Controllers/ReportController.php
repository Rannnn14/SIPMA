<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ViolationReport;
use App\Models\Student;
use App\Models\ViolationType;
use App\Models\ReportEvidence;

class ReportController extends Controller
{
    // List of standard departments and faculty groupings
    public static array $departments = [
        'Teknik Informatika' => 'Fakultas Ilmu Komputer',
        'Sistem Informasi' => 'Fakultas Ilmu Komputer',
        'Teknik Elektro' => 'Fakultas Teknik',
        'Teknik Sipil' => 'Fakultas Teknik',
        'Manajemen' => 'Fakultas Ekonomi & Bisnis',
        'Akuntansi' => 'Fakultas Ekonomi & Bisnis',
    ];

    public static array $severities = ['Ringan', 'Sedang', 'Berat', 'Suspek'];

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

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = ViolationReport::query()->with(['student', 'reporter', 'verifier', 'handler']);

        // RBAC filtering
        if ($user->isMahasiswa()) {
            $query->where('reporter_id', $user->id);
        } elseif ($user->isKajur()) {
            $dept = $user->department;
            $query->where(function ($q) use ($dept) {
                $q->where('target_department', $dept)
                  ->orWhere('reported_department', $dept);
            })->whereIn('status', ['dikonfirmasi', 'diproses', 'selesai']);
        } elseif ($user->isDekan()) {
            // Filter departments under this faculty
            $allowedDepts = array_keys(array_filter(self::$departments, fn($f) => $f === ($user->faculty ?? 'Fakultas Ilmu Komputer')));
            $query->whereIn('target_department', $allowedDepts)
                  ->orWhereIn('reported_department', $allowedDepts);
        } elseif ($user->isWarek() || $user->isKemahasiswaan()) {
            // Kemahasiswaan & Warek see all
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
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('report_number', 'like', "%{$search}%")
                  ->orWhere('reported_name', 'like', "%{$search}%")
                  ->orWhere('reported_nim', 'like', "%{$search}%")
                  ->orWhere('incident_location', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        $departments = array_keys(self::$departments);

        return view('reports.index', compact('reports', 'departments'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isMahasiswa() && !$user->isKemahasiswaan()) {
            abort(403, 'Hanya Mahasiswa dan Kemahasiswaan yang dapat membuat laporan.');
        }

        $departments = array_keys(self::$departments);
        $severities = self::$severities;
        return view('reports.create', compact('departments', 'severities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reported_name' => 'required|string|max:150',
            'reported_nim' => 'required|string|max:50',
            'reported_department' => 'required|string|max:150',
            'incident_date' => 'required|date',
            'incident_time' => 'nullable',
            'incident_location' => 'required|string|max:255',
            'severity' => 'required|string|in:Ringan,Sedang,Berat,Suspek',
            'chronology' => 'required|string',
            'evidence' => 'nullable|file|mimes:pdf,jpg,jpeg,png,mp4|max:10240', // 10MB
        ]);

        // Auto point recommendation based on severity
        $defaultPoints = match ($request->severity) {
            'Ringan' => 20,
            'Sedang' => 50,
            'Berat' => 75,
            'Suspek' => 0,
            default => 25,
        };

        // Link student if found by NIM
        $matchedStudent = Student::where('nim', trim($request->reported_nim))->first();

        $evidencePath = null;
        $evidenceName = null;
        if ($request->hasFile('evidence')) {
            $file = $request->file('evidence');
            $evidenceName = $file->getClientOriginalName();
            $evidencePath = $file->store('evidences', 'public');
        }

        $report = ViolationReport::create([
            'report_number' => 'REP-' . date('Ymd') . '-' . rand(1000, 9999),
            'student_id' => $matchedStudent?->id,
            'reported_name' => $request->reported_name,
            'reported_nim' => $request->reported_nim,
            'reported_department' => $request->reported_department,
            'target_department' => $request->reported_department,
            'reporter_id' => Auth::id(),
            'points' => $defaultPoints,
            'severity' => $request->severity,
            'incident_date' => $request->incident_date,
            'incident_time' => $request->incident_time,
            'incident_location' => $request->incident_location,
            'chronology' => $request->chronology,
            'status' => 'pending', // Workflow Step 3: Data Terkirim, masuk ke Inbox Verifikasi Kemahasiswaan
            'academic_year' => '2026/2027',
            'semester' => 'Ganjil',
            'evidence_path' => $evidencePath,
            'evidence_name' => $evidenceName,
        ]);

        if ($evidencePath) {
            ReportEvidence::create([
                'violation_report_id' => $report->id,
                'original_name' => $evidenceName,
                'file_path' => $evidencePath,
                'mime_type' => $request->file('evidence')->getClientMimeType(),
            ]);
        }

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil diajukan dan sedang menunggu verifikasi Bagian Kemahasiswaan.');
    }

    public function show($id)
    {
        $report = ViolationReport::with(['student', 'reporter', 'verifier', 'handler', 'evidences'])->findOrFail($id);
        $user = Auth::user();

        // RBAC Access Check
        if ($user->isMahasiswa() && $report->reporter_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat laporan ini.');
        }
        if ($user->isKajur()) {
            if ($report->target_department !== $user->department && $report->reported_department !== $user->department) {
                abort(403, 'Laporan ini tidak ditujukan untuk jurusan Anda.');
            }
        }
        if ($user->isDekan()) {
            $allowedDepts = array_keys(array_filter(self::$departments, fn($f) => $f === ($user->faculty ?? 'Fakultas Ilmu Komputer')));
            if (!in_array($report->target_department, $allowedDepts) && !in_array($report->reported_department, $allowedDepts)) {
                abort(403, 'Laporan ini berada di luar fakultas Anda.');
            }
        }

        $departments = array_keys(self::$departments);
        $severities = self::$severities;
        $sanctionOptions = self::$sanctionOptions;

        return view('reports.show', compact('report', 'departments', 'severities', 'sanctionOptions'));
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
            'reported_department' => 'required|string|max:150',
            'severity' => 'required|string|in:Ringan,Sedang,Berat,Suspek',
            'points' => 'required|integer|min:0',
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
            'points' => $request->points,
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
            'target_department' => 'required|string|max:150',
            'points' => 'nullable|integer|min:0',
        ]);

        $report->update([
            'status' => 'dikonfirmasi', // Terkonfirmasi & diteruskan ke Kajur
            'target_department' => $request->target_department,
            'points' => $request->filled('points') ? $request->points : $report->points,
            'verified_by' => $user->id,
            'verified_at' => now(),
        ]);

        return back()->with('success', "Laporan berhasil DIKONFIRMASI dan DITERUSKAN ke Ketua Jurusan {$request->target_department}.");
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

    // Export Excel / CSV
    public function exportExcel()
    {
        $reports = ViolationReport::orderBy('created_at', 'desc')->get();
        $filename = "rekap_pelanggaran_sipma_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['No. Laporan', 'Tanggal Kejadian', 'Nama Terlapor', 'NIM', 'Jurusan Terlapor', 'Jurusan Dikonfirmasi', 'Tingkat/Kategori', 'Poin', 'Status', 'Sanksi Kajur', 'Pelapor'];

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
                    $r->reported_department,
                    $r->target_department,
                    $r->severity,
                    $r->points,
                    $r->status_label,
                    $r->sanction_type ?? '-',
                    $r->reporter_display_name,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Export PDF / Print View
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        $reports = ViolationReport::orderBy('created_at', 'desc')->get();

        return view('reports.print', compact('reports', 'user'));
    }
}
