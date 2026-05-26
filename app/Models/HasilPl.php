<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilPl extends Model
{
    protected $table = 'hasil_pl';

    public $timestamps = false;

    protected $fillable = [
        'id_mahasiswa',
        'id_pl',
        'id_kurikulum',
        'id_semester',
        'nilai_pl',
        'status_tercapai',
        'recalculated_at',
    ];

    protected function casts(): array
    {
        return [
            'nilai_pl'        => 'decimal:2',
            'status_tercapai' => 'boolean',
            'recalculated_at' => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_mahasiswa');
    }

    public function pl(): BelongsTo
    {
        return $this->belongsTo(Pl::class, 'id_pl');
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
