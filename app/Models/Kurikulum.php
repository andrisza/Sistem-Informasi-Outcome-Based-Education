<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
