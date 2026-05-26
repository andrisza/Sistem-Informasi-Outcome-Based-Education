<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepositoriMateri extends Model
{
    use SoftDeletes;

    protected $table = 'repositori_materi';

    public $timestamps = false;

    protected $fillable = [
        'id_mk',
        'id_semester',
        'id_dosen',
        'id_rps_pertemuan',
        'jenis_file',
        'nama_file',
        'file_path',
        'ukuran_kb',
        'mime_type',
        'deskripsi',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'deleted_at' => 'datetime',
            'ukuran_kb'  => 'integer',
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

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_dosen');
    }

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(RpsPertemuan::class, 'id_rps_pertemuan');
    }
}
