<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodeKurikulum extends Model
{
    protected $table = 'periode_kurikulum';

    protected $fillable = [
        'id_kurikulum',
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'ketua_tim',
        'deskripsi',
        'dokumen_sk',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_mulai'   => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function ketuaTim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ketua_tim');
    }

    public function timKurikulum(): HasMany
    {
        return $this->hasMany(TimKurikulum::class, 'id_periode');
    }

    public function arsipRapat(): HasMany
    {
        return $this->hasMany(ArsipRapat::class, 'id_periode');
    }
}
