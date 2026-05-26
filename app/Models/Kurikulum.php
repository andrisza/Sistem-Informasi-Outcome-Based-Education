<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kurikulum extends Model
{
    protected $table = 'kurikulum';

    protected $fillable = [
        'kode',
        'nama_kurikulum',
        'program_studi',
        'jenjang',
        'tahun_mulai',
        'tahun_selesai',
        'visi',
        'misi',
        'tujuan',
        'sasaran',
        'status',
        'locked_at',
        'locked_by',
        'dibuat_oleh',
        'disahkan_oleh',
        'disahkan_pada',
    ];

    protected function casts(): array
    {
        return [
            'locked_at'    => 'datetime',
            'disahkan_pada' => 'date',
        ];
    }

    public function isArsip(): bool
    {
        return $this->status === 'arsip';
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function pl(): HasMany
    {
        return $this->hasMany(Pl::class, 'id_kurikulum');
    }

    public function cplProdi(): HasMany
    {
        return $this->hasMany(CplProdi::class, 'id_kurikulum');
    }

    public function bahanKajian(): HasMany
    {
        return $this->hasMany(BahanKajian::class, 'id_kurikulum');
    }

    public function mataKuliah(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'id_kurikulum');
    }

    public function periode(): HasMany
    {
        return $this->hasMany(PeriodeKurikulum::class, 'id_kurikulum');
    }

    public function arsipRapat(): HasMany
    {
        return $this->hasMany(ArsipRapat::class, 'id_kurikulum');
    }

    public function distribusiSemester(): HasMany
    {
        return $this->hasMany(DistribusiSemester::class, 'id_kurikulum');
    }

    public function cpmk(): HasMany
    {
        return $this->hasMany(Cpmk::class, 'id_kurikulum');
    }

    public function pembuatUser()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function lockedByUser()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function disahkanOlehUser()
    {
        return $this->belongsTo(User::class, 'disahkan_oleh');
    }

    public function logEvaluasiCqi(): HasMany
    {
        return $this->hasMany(LogEvaluasiCqi::class, 'id_kurikulum');
    }

    // ── Helpers ────────────────────────────────────────────────

    public function statusColor(): string
    {
        return match($this->status) {
            'aktif' => 'bg-green-100 text-green-700',
            'arsip' => 'bg-gray-100 text-gray-500',
            'draft' => 'bg-amber-100 text-amber-700',
            default => 'bg-gray-100 text-gray-500',
        };
    }
}
