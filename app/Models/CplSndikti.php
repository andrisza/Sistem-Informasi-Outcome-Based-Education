<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CplSndikti extends Model
{
    protected $table = 'cpl_sndikti';

    public $timestamps = false;

    protected $fillable = [
        'kode',
        'deskripsi',
        'kategori',
        'urutan',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function cplProdi(): BelongsToMany
    {
        return $this->belongsToMany(CplProdi::class, 'pivot_cplsn_cplp', 'id_cpl_sndikti', 'id_cpl_prodi');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
