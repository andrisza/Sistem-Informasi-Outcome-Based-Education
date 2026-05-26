<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilCpmk extends Model
{
    protected $table = 'hasil_cpmk';

    public $timestamps = false;

    protected $fillable = [
        'id_mahasiswa',
        'id_cpmk',
        'id_semester',
        'nilai',
        'recalculated_at',
    ];

    protected function casts(): array
    {
        return [
            'nilai'           => 'decimal:2',
            'recalculated_at' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }

    public function cpmk(): BelongsTo
    {
        return $this->belongsTo(Cpmk::class, 'id_cpmk');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(SemesterAkademik::class, 'id_semester');
    }
}
