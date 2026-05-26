<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilCpl extends Model
{
    protected $table = 'hasil_cpl';

    public $timestamps = false;

    protected $fillable = [
        'id_mahasiswa',
        'id_cpl',
        'id_kurikulum',
        'id_semester',
        'nilai_cpl',
        'skor_maks',
        'total',
        'status_tercapai',
        'recalculated_at',
    ];

    protected function casts(): array
    {
        return [
            'nilai_cpl'       => 'decimal:2',
            'skor_maks'       => 'float',
            'total'           => 'float',
            'status_tercapai' => 'boolean',
            'recalculated_at' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }

    public function cpl(): BelongsTo
    {
        return $this->belongsTo(CplProdi::class, 'id_cpl');
    }

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(SemesterAkademik::class, 'id_semester');
    }
}
