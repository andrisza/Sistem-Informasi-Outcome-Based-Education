<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistribusiSemester extends Model
{
    protected $table = 'distribusi_semester';

    public $timestamps = false;

    protected $fillable = [
        'id_kurikulum',
        'semester',
        'total_sks',
        'jumlah_mk',
        'keterangan',
    ];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }
}
