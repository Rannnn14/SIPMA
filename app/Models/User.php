<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    public function isWarek(): bool
    {
        return $this->role === 'warek';
    }

    public function getCleanNameAttribute(): string
    {
        return trim(preg_replace('/\s*\([^)]*\)$/', '', $this->name ?? ''));
    }

    public function getRoleTitleAttribute(): string
    {
        return match ($this->role) {
            'mahasiswa' => 'Mahasiswa (Pelapor)',
            'kemahasiswaan', 'admin' => 'Kemahasiswaan (Verifikator)',
            'kajur', 'kaprodi' => 'Ketua Jurusan (' . ($this->department ?? 'Jurusan') . ')',
            'dekan' => 'Dekan (' . ($this->faculty ?? 'Fakultas') . ')',
            'warek' => 'Wakil Rektor (Universitas)',
            default => ucfirst($this->role),
        };
    }
}
