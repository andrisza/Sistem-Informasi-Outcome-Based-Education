<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KomponenAsesmen extends Model
{
    protected $table = 'komponen_asesmen';

    public $timestamps = false;

    protected $fillable = [
        'id_mk',
        'id_semester',
        'id_sub_cpmk',
        'nama_komponen',
        'jenis_komponen',
        'bobot_persen',
        'skor_maks',
    ];

    protected function casts(): array
    {
        return [
            'bobot_persen' => 'float',
            'skor_maks'    => 'float',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk');
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(SemesterAkademik::class, 'id_semester');
    }

    public function nilaiMahasiswa(): HasMany
    {
        return $this->hasMany(NilaiMahasiswa::class, 'id_komponen');
    }

    public function subCpmk(): BelongsTo
    {
        return $this->belongsTo(SubCpmk::class, 'id_sub_cpmk');
    }
}
