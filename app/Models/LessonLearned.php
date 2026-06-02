<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonLearned extends Model
{
    protected $table = 'lesson_learned';

    protected $fillable = [
        'id_kurikulum',
        'kategori',
        'temuan',
        'rekomendasi',
        'prioritas',
        'status',
        'id_user',
    ];

    public function kurikulum(): BelongsTo
    {
        return $this->belongsTo(Kurikulum::class, 'id_kurikulum');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
