<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilSubCpmk extends Model
{
    protected $table = 'hasil_sub_cpmk';

    public $timestamps = false;

    protected $fillable = [
        'id_mahasiswa',
        'id_sub_cpmk',
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

    public function subCpmk(): BelongsTo
    {
        return $this->belongsTo(SubCpmk::class, 'id_sub_cpmk');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(SemesterAkademik::class, 'id_semester');
    }
}
