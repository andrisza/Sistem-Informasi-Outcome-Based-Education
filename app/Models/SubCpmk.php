<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCpmk extends Model
{
    protected $table = 'sub_cpmk';

    public $timestamps = false;

    protected $fillable = [
        'id_cpmk',
        'kode_sub_cpmk',
        'deskripsi',
        'bobot',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'bobot' => 'decimal:2',
        ];
    }

    public function cpmk(): BelongsTo
    {
        return $this->belongsTo(Cpmk::class, 'id_cpmk');
    }
}
