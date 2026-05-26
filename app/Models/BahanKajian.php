<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BahanKajian extends Model
{
    use SoftDeletes;

    protected $table = 'bahan_kajian';

    public $timestamps = false;

    protected $fillable = [
        'id_kurikulum',
        'kode_bk',
        'nama_bk',
        'deskripsi',
        'urutan',
    ];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function cplProdi(): BelongsToMany
    {
        return $this->belongsToMany(CplProdi::class, 'pivot_cpl_bk', 'id_bk', 'id_cpl');
    }

    public function mataKuliah(): BelongsToMany
    {
        return $this->belongsToMany(MataKuliah::class, 'pivot_mk_bk', 'id_bk', 'id_mk');
    }
}
