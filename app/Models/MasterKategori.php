<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MasterKategori extends Model
{
    protected $table = 'master_kategori';

    protected $fillable = [
        'jenis',
        'nama',
        'deskripsi',
        'urutan',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan'   => 'integer',
    ];

    /**
     * Scope: only active categories.
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Scope: filter by jenis (pl, cpl, bk, mk).
     */
    public function scopeJenis(Builder $query, string $jenis): Builder
    {
        return $query->where('jenis', $jenis);
    }
}
