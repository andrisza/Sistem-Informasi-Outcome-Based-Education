<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cpmk extends Model
{
    use SoftDeletes;

    protected $table = 'cpmk';

    public $timestamps = false;

    protected $fillable = [
        'id_kurikulum',
        'id_mk',
        'id_cpl',
        'kode_cpmk',
        'deskripsi',
        'urutan',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk');
    }

    public function cplProdi(): BelongsTo
    {
        return $this->belongsTo(CplProdi::class, 'id_cpl');
    }

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function subCpmk(): HasMany
    {
        return $this->hasMany(SubCpmk::class, 'id_cpmk');
    }
}
