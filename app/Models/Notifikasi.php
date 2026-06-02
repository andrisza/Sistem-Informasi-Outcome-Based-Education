<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'judul',
        'pesan',
        'url',
        'tipe',
        'dibaca',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'dibaca'     => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
