<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipRapat extends Model
{
    protected $table = 'arsip_rapat';

    protected $fillable = [
        'id_kurikulum',
        'id_periode',
        'judul_rapat',
        'jenis_rapat',
        'tanggal_rapat',
        'tempat',
        'agenda',
        'notulen',
        'kesimpulan',
        'tindak_lanjut',
        'peserta',
        'file_notulen',
        'file_daftar_hadir',
        'file_lampiran',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_rapat' => 'date',
            'peserta'       => 'array',
            'file_lampiran' => 'array',
        ];
    }

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKurikulum::class, 'id_periode');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
