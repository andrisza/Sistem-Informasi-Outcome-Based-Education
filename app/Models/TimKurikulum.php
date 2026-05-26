<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimKurikulum extends Model
{
    protected $table = 'tim_kurikulum';

    public $timestamps = false;

    protected $fillable = [
        'id_periode',
        'id_user',
        'jabatan_tim',
        'sk_nomor',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodeKurikulum::class, 'id_periode');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
