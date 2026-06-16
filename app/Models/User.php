<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'identifier',
        'program_studi',
        'fakultas',
        'perguruan_tinggi',
        'tahun_masuk',
        'kelas',
        'id_kurikulum',
        'foto',
        'status_aktif',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'role'              => Role::class,
            'tahun_masuk'       => 'integer',
        ];
    }

    // ── Helpers peran ─────────────────────────────────────────────────────────

    public function isKaprodi(): bool
    {
        return $this->role === Role::Kaprodi;
    }

    public function isTimKurikulum(): bool
    {
        return $this->role === Role::TimKurikulum;
    }

    public function isDosen(): bool
    {
        return $this->role === Role::Dosen;
    }

    public function isMahasiswa(): bool
    {
        return $this->role === Role::Mahasiswa;
    }

    /** Cek apakah user memiliki salah satu dari peran yang diberikan */
    public function hasRole(Role|string ...$roles): bool
    {
        $values = array_map(
            fn($r) => $r instanceof Role ? $r->value : $r,
            $roles
        );

        return in_array($this->role->value, $values);
    }

    /** Route nama dashboard sesuai peran */
    public function dashboardRoute(): string
    {
        return $this->role->dashboardRoute();
    }
}
