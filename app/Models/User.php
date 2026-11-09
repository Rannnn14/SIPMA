<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isKemahasiswaan(): bool
    {
        return in_array($this->role, ['kemahasiswaan', 'admin']);
    }

    public function isKajur(): bool
    {
        return in_array($this->role, ['kajur', 'kaprodi']);
    }

    public function isDekan(): bool
    {
        return $this->role === 'dekan';
    }

    public function isWadek3(): bool
    {
        return $this->role === 'wadek3';
    }

    public function isWarek(): bool
    {
        return $this->role === 'warek';
    }

    public function getCleanNameAttribute(): string
    {
        return trim(preg_replace('/\s*\([^)]*\)$/', '', $this->name ?? ''));
    }

    public function getDisplayNameAttribute(): string
    {
        $name = $this->clean_name;

        if ($this->isKemahasiswaan() && in_array(mb_strtolower($name), ['', 'bagian kemahasiswaan', 'admin kemahasiswaan'], true)) {
            return 'Kemahasiswaan';
        }

        if ($name === '') {
            if ($this->isKajur()) {
                return 'Kajur ' . ($this->department ?? 'Jurusan');
            }
            if ($this->isWadek3()) {
                return 'Wadek III ' . (\App\Http\Controllers\ReportController::facultyDisplayName($this->faculty));
            }
            if ($this->isDekan()) {
                return 'Dekan ' . (\App\Http\Controllers\ReportController::facultyDisplayName($this->faculty));
            }
            return 'Pengguna';
        }

        return $name;
    }

    public function getRoleTitleAttribute(): string
    {
        return match ($this->role) {
            'mahasiswa' => 'Mahasiswa (Pelapor)',
            'kemahasiswaan', 'admin' => 'Admin Kemahasiswaan',
            'kajur', 'kaprodi' => 'Ketua Jurusan (' . ($this->department ?? 'Jurusan') . ')',
            'dekan' => 'Dekan (' . ($this->faculty ?? 'Fakultas') . ')',
            'wadek3' => 'Wadek III (' . ($this->faculty ?? 'Fakultas') . ')',
            'warek' => 'Warek III (Universitas)',
            default => ucfirst($this->role),
        };
    }
}
