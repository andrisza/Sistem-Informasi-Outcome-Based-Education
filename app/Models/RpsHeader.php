<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RpsHeader extends Model
{
    protected $table = 'rps_header';

    protected $fillable = [
        'id_mk',
        'id_semester',
        'id_dosen_pengembang',
        'id_koordinator_bk',
        'id_kaprodi_pengesah',
        'tanggal_penyusunan',
        'kode_dokumen',
        'sks_teori',
        'sks_praktikum',
        'nama_perguruan_tinggi',
        'nama_fakultas',
        'deskripsi_mk',
        'materi_pembelajaran',
        'pustaka_utama',
        'pustaka_pendukung',
        'status',
        'disahkan_pada',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_penyusunan' => 'date',
            'disahkan_pada'      => 'datetime',
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

    public function dosenPengembang(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_dosen_pengembang');
    }

    public function koordinatorBk(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_koordinator_bk');
    }

    public function kaprodiPengesah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_kaprodi_pengesah');
    }

    public function pertemuan(): HasMany
    {
        return $this->hasMany(RpsPertemuan::class, 'id_rps')->orderBy('minggu_ke');
    }

    public function detailMk(): HasMany
    {
        return $this->hasMany(RpsDetailMk::class, 'id_rps');
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeByDosen(Builder $query, int $dosenId): Builder
    {
        return $query->where('id_dosen_pengembang', $dosenId);
    }

    // ── Helpers ────────────────────────────────────────────────

    public function isReview(): bool
    {
        return $this->status === 'review';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isDisahkan(): bool
    {
        return $this->status === 'disahkan';
    }
}
