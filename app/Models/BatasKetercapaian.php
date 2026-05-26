<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BatasKetercapaian extends Model
{
    protected $table = 'batas_ketercapaian';

    public $timestamps = false;

    protected $fillable = [
        'id_cpl',
        'id_kurikulum',
        'batas_nilai',
    ];

    protected function casts(): array
    {
        return [
            'batas_nilai' => 'float',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function cpl(): BelongsTo
    {
        return $this->belongsTo(CplProdi::class, 'id_cpl');
    }

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }
}
