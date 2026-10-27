<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reports:purge-rejected', function () {
    $cutoff = now()->subDays(7);
    $deleted = DB::table('violation_reports')
        ->where('status', 'ditolak')
        ->where(function ($query) use ($cutoff) {
            $query->where('verified_at', '<=', $cutoff)
                ->orWhere(function ($legacyQuery) use ($cutoff) {
                    $legacyQuery->whereNull('verified_at')
                        ->where('created_at', '<=', $cutoff);
                });
        })
        ->delete();

    $this->info("{$deleted} laporan yang ditolak dihapus permanen.");
})->purpose('Hapus permanen laporan yang ditolak setelah tujuh hari');

Schedule::useCache('file');
Schedule::command('reports:purge-rejected')->hourly();
