<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SemesterAkademik extends Model
{
    public $timestamps = false;

    protected $table = 'semester_akademik';

    protected $fillable = [
        'nama',
        'tahun_akademik',
        'jenis',
        'is_aktif',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected function casts(): array
    {
        return [
            'is_aktif'         => 'boolean',
            'tanggal_mulai'    => 'date',
            'tanggal_selesai'  => 'date',
            'created_at'       => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function rpsHeaders(): HasMany
    {
        return $this->hasMany(RpsHeader::class, 'id_semester');
    }

    public function cqiLogs(): HasMany
    {
        return $this->hasMany(LogEvaluasiCqi::class, 'id_semester');
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', 1);
    }
}
