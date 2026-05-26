<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogEvaluasiCqi extends Model
{
    protected $table = 'log_evaluasi_cqi';

    protected $fillable = [
        'id_mk',
        'id_kurikulum',
        'id_semester',
        'id_cpl_terdampak',
        'temuan',
        'akar_masalah',
        'rekomendasi',
        'target_implementasi',
        'status',
        'dilaporkan_oleh',
        'disetujui_oleh',
    ];

    // ── Relationships ──────────────────────────────────────────

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk');
    }

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(SemesterAkademik::class, 'id_semester');
    }

    public function cplTerdampak(): BelongsTo
    {
        return $this->belongsTo(CplProdi::class, 'id_cpl_terdampak');
    }

    public function dilaporkanOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
