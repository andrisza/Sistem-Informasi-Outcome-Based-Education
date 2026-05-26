<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiMahasiswa extends Model
{
    protected $table = 'nilai_mahasiswa';

    protected $fillable = [
        'id_mahasiswa',
        'id_komponen',
        'id_semester',
        'nilai_mentah',
    ];

    protected function casts(): array
    {
        return [
            'nilai_mentah' => 'float',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }

    public function komponen(): BelongsTo
    {
        return $this->belongsTo(KomponenAsesmen::class, 'id_komponen');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(SemesterAkademik::class, 'id_semester');
    }
}
