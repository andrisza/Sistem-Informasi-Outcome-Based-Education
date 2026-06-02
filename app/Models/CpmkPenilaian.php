<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CpmkPenilaian extends Model
{
    protected $table = 'cpmk_penilaian';

    public $timestamps = false;

    protected $fillable = [
        'id_cpmk', 'skor_maks', 'tahap_penilaian',
        'teknik_quiz', 'teknik_observasi', 'teknik_unjuk_kerja',
        'teknik_uts', 'teknik_uas', 'teknik_tes_lisan',
        'bobot_quiz', 'bobot_observasi', 'bobot_unjuk_kerja',
        'bobot_uts', 'bobot_uas', 'bobot_tes_lisan',
        'instrumen', 'kriteria',
    ];

    protected $casts = [
        'teknik_quiz'        => 'boolean',
        'teknik_observasi'   => 'boolean',
        'teknik_unjuk_kerja' => 'boolean',
        'teknik_uts'         => 'boolean',
        'teknik_uas'         => 'boolean',
        'teknik_tes_lisan'   => 'boolean',
    ];

    public function cpmk(): BelongsTo
    {
        return $this->belongsTo(Cpmk::class, 'id_cpmk');
    }

    public function getTotalBobotAttribute(): float
    {
        return (float) ($this->bobot_quiz ?? 0)
             + (float) ($this->bobot_observasi ?? 0)
             + (float) ($this->bobot_unjuk_kerja ?? 0)
             + (float) ($this->bobot_uts ?? 0)
             + (float) ($this->bobot_uas ?? 0)
             + (float) ($this->bobot_tes_lisan ?? 0);
    }

    public function getEffectiveSkorMaksAttribute(): float
    {
        // If skor_maks explicitly set, use it. Otherwise derive from total bobot.
        if (!is_null($this->skor_maks)) return (float) $this->skor_maks;
        return $this->total_bobot;
    }
}
