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
        'id_sub_cpmk',
        'indikator_penilaian',
        'kriteria_teknik',
        'bentuk_luring',
        'bentuk_daring',
        'materi_pembelajaran',
        'bobot_penilaian',
        'metode_pembelajaran',
        'estimasi_waktu',
        'media_pembelajaran',
        'referensi',
    ];

    public function subCpmk(): BelongsTo
    {
        return $this->belongsTo(SubCpmk::class, 'id_sub_cpmk');
    }

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
