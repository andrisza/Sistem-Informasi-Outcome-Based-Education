<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataKuliah extends Model
{
    use SoftDeletes;

    protected $table = 'mata_kuliah';

    public $timestamps = false;

    protected $fillable = [
        'id_kurikulum',
        'kode_mk',
        'nama_mk',
        'sks_teori',
        'sks_praktikum',
        // sks_total is a MySQL GENERATED column — excluded from fillable
        'semester',
        'kategori_mk',
        'kompetensi_mk',
        'kode_prasyarat',
        'konsentrasi',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function cpmk(): HasMany
    {
        return $this->hasMany(Cpmk::class, 'id_mk');
    }

    public function bahanKajian(): BelongsToMany
    {
        return $this->belongsToMany(BahanKajian::class, 'pivot_mk_bk', 'id_mk', 'id_bk');
    }

    public function cplProdi(): BelongsToMany
    {
        return $this->belongsToMany(CplProdi::class, 'pivot_mk_cpl', 'id_mk', 'id_cpl')
                    ->withPivot(['id_cpmk', 'bobot']);
    }

    public function rpsHeaders(): HasMany
    {
        return $this->hasMany(RpsHeader::class, 'id_mk');
    }

    public function cqiLogs(): HasMany
    {
        return $this->hasMany(LogEvaluasiCqi::class, 'id_mk');
    }
}
