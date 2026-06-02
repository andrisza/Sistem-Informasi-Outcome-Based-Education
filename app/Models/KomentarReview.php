<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomentarReview extends Model
{
    protected $table = 'komentar_review';

    public $timestamps = false;

    protected $fillable = [
        'model_type',
        'model_id',
        'id_user',
        'konten',
        'elemen',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
