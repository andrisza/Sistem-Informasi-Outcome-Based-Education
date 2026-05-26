<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JurnalMengajar extends Model
{
    protected $table = 'jurnal_mengajar';

    public $timestamps = false;

    protected $fillable = [
        'id_rps_pertemuan',
        'id_dosen',
        'tanggal_pelaksanaan',
        'realisasi_materi',
        'jumlah_hadir',
        'file_bukti_path',
        'catatan',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pelaksanaan' => 'date',
            'created_at'          => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(RpsPertemuan::class, 'id_rps_pertemuan');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }
}
