<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pl extends Model
{
    use SoftDeletes;

    protected $table = 'pl';

    public $timestamps = false;

    protected $fillable = [
        'id_kurikulum',
        'kode_pl',
        'deskripsi',
        'kategori',
        'ref_area_fungsi_1',
        'ref_area_fungsi_2',
        'ref_area_fungsi_3',
        'urutan',
    ];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function cplProdi(): BelongsToMany
    {
        return $this->belongsToMany(CplProdi::class, 'pivot_pl_cpl', 'id_pl', 'id_cpl');
    }
}
