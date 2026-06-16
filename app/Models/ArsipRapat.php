<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipRapat extends Model
{
    protected $table = 'arsip_rapat';

    protected $fillable = [
        'id_kurikulum',
        'judul_rapat',
        'tanggal_rapat',
        'tempat',
        'notulen',
        'file_lampiran',
        'dibuat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_rapat' => 'date',
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
