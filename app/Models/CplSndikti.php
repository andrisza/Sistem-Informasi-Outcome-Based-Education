<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
    ];

    public function cplProdi(): BelongsToMany
    {
        return $this->belongsToMany(CplProdi::class, 'pivot_cplsn_cplp', 'id_cpl_sndikti', 'id_cpl_prodi');
    }
}
