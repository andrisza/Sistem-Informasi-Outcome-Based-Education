<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SemesterAkademik extends Model
{
    public $timestamps = false;

    protected $table = 'semester_akademik';

    protected $fillable = [
        'tahun_akademik',
        'jenis',
        'is_aktif',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    /** Nama semester diturunkan dari jenis + tahun akademik (kolom `nama` sudah dihapus). */
    protected $appends = ['nama'];

    protected function casts(): array
    {
        return [
            'is_aktif'         => 'boolean',
            'tanggal_mulai'    => 'date',
            'tanggal_selesai'  => 'date',
            'created_at'       => 'datetime',
        ];
    }

    // ── Accessors ──────────────────────────────────────────────

    /** Nama tampilan, mis. "Gasal 2024/2025". */
    public function getNamaAttribute(): string
    {
        return trim(($this->jenis ?? '') . ' ' . ($this->tahun_akademik ?? ''));
    }

    // ── Relationships ──────────────────────────────────────────

    public function rpsHeaders(): HasMany
    {
        return $this->hasMany(RpsHeader::class, 'id_semester');
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', 1);
    }
}
