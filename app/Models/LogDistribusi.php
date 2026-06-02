<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogDistribusi extends Model
{
    protected $table = 'log_distribusi';

    public $timestamps = false;

    protected $fillable = [
        'id_kurikulum',
        'id_pengirim',
        'id_penerima',
        'nama_dokumen',
        'versi',
        'catatan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengirim');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_penerima');
    }
}
