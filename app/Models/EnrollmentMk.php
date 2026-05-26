<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentMk extends Model
{
    protected $table = 'enrollment_mk';

    public $timestamps = false;

    protected $fillable = [
        'id_mahasiswa',
        'id_mk',
        'id_semester',
        'tanggal_daftar',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_daftar' => 'date',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(SemesterAkademik::class, 'id_semester');
    }
}
