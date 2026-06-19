<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipRapat extends Model
{
    protected $table = 'arsip_rapat';

    protected $fillable = [
        'id_kurikulum',
        'judul_kegiatan',
        'tanggal',
        'tempat',
        'temuan',
        'tindak_lanjut',
        'file_lampiran',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'      => 'date',
            'file_lampiran' => 'array',
        ];
    }

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
