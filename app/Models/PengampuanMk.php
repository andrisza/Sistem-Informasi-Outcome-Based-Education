<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengampuanMk extends Model
{
    protected $table = 'pengampuan_mk';

    public $timestamps = false;

    protected $fillable = [
        'id_mk',
        'id_dosen',
        'id_semester',
        'is_koordinator',
    ];

    protected function casts(): array
    {
        return [
            'is_koordinator' => 'boolean',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(SemesterAkademik::class, 'id_semester');
    }
}
