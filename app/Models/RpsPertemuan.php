<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RpsPertemuan extends Model
{
    protected $table = 'rps_pertemuan';

    public $timestamps = false;

    protected $fillable = [
        'id_rps',
        'minggu_ke',
        'materi_pembelajaran',
        'metode_pembelajaran',
        'id_sub_cpmk',
        'indikator_penilaian',
        'estimasi_waktu',
        'media_pembelajaran',
        'referensi',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function rps(): BelongsTo
    {
        return $this->belongsTo(RpsHeader::class, 'id_rps');
    }

    public function jurnalMengajar(): HasMany
    {
        return $this->hasMany(JurnalMengajar::class, 'id_rps_pertemuan');
    }
}
