<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'incident_date' => 'date',
        'verified_at' => 'datetime',
        'handled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function violationType()
    {
        return $this->belongsTo(ViolationType::class);
    }

    public function violationSubtype()
    {
        return $this->belongsTo(ViolationSubtype::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function evidences()
    {
        return $this->hasMany(ReportEvidence::class);
    }

    // Status helpers
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending', 'dilaporkan' => 'Menunggu Verifikasi',
            'ditolak' => 'Ditolak',
            'dikonfirmasi', 'diverifikasi_kaprodi', 'diteruskan_kemahasiswaan' => 'Dikonfirmasi (Ke Kajur)',
            'diproses', 'sedang_diproses' => 'Dalam Penanganan Kajur',
            'selesai', 'sanksi_ditetapkan' => 'Selesai Tindak Lanjut',
            default => ucfirst(str_replace('_', ' ', $this->status ?? 'pending')),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending', 'dilaporkan' => 'badge-warning text-dark',
            'ditolak' => 'badge-danger',
            'dikonfirmasi', 'diverifikasi_kaprodi', 'diteruskan_kemahasiswaan' => 'badge-info',
            'diproses', 'sedang_diproses' => 'badge-primary',
            'selesai', 'sanksi_ditetapkan' => 'badge-success',
            default => 'badge-secondary',
        };
    }

    // Display helpers
    public function getStudentNameAttribute(): string
    {
        return $this->reported_name ?: ($this->student?->name ?? 'Mahasiswa Belum Teridentifikasi');
    }

    public function getStudentNimAttribute(): string
    {
        return $this->reported_nim ?: ($this->student?->nim ?? '-');
    }

    public function getFacultyAttribute(): string
    {
        if (!empty($this->reported_faculty)) {
            return $this->reported_faculty;
        }
        $dept = $this->reported_department ?: $this->target_department;
        return \App\Http\Controllers\ReportController::departmentFacultyMap()[$dept] ?? 'Fakultas Lainnya';
    }

    public function getFacultyNameAttribute(): string
    {
        return $this->faculty;
    }

    public function getDepartmentNameAttribute(): string
    {
        return $this->target_department ?: ($this->reported_department ?: ($this->student?->study_program ?? '-'));
    }

    // Privacy / Anonymity helper
    public function getReporterDisplayNameAttribute(): string
    {
        $viewer = auth()->user();
        if ($viewer && in_array($viewer->role, ['kemahasiswaan', 'admin'])) {
            return $this->reporter ? $this->reporter->name : 'Anonim';
        }
        if ($viewer && $this->reporter_id === $viewer->id) {
            return $this->reporter->name . ' (Saya)';
        }
        return 'Pelapor Anonim (Dilindungi SIPMA)';
    }
}
