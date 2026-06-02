<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistLedik extends Model
{
    protected $table = 'checklist_ledik';

    public $timestamps = false;

    protected $fillable = [
        'id_kurikulum',
        'elemen',
        'kode_indikator',
        'deskripsi_indikator',
        'status',
        'catatan',
        'updated_by',
        'updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }
}
