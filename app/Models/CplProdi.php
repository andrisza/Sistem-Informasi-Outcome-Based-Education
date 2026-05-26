<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CplProdi extends Model
{
    use SoftDeletes;

    protected $table = 'cpl_prodi';

    public $timestamps = false;

    protected $fillable = [
        'id_kurikulum',
        'kode_cpl',
        'deskripsi',
        'kategori',
        'urutan',
    ];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function pl(): BelongsToMany
    {
        return $this->belongsToMany(Pl::class, 'pivot_pl_cpl', 'id_cpl', 'id_pl');
    }

    public function cplSndikti(): BelongsToMany
    {
        return $this->belongsToMany(CplSndikti::class, 'pivot_cplsn_cplp', 'id_cpl_prodi', 'id_cpl_sndikti');
    }

    public function bahanKajian(): BelongsToMany
    {
        return $this->belongsToMany(BahanKajian::class, 'pivot_cpl_bk', 'id_cpl', 'id_bk');
    }

    public function cpmk(): HasMany
    {
        return $this->hasMany(Cpmk::class, 'id_cpl');
    }
}
