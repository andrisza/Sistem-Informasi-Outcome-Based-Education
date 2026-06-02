<?php

namespace App\Services;

use App\Models\Notifikasi;

class NotifikasiService
{
    /**
     * Kirim notifikasi ke satu atau beberapa user.
     *
     * @param int|array $userIds  ID user penerima (satu atau banyak)
     * @param string    $judul    Judul notifikasi
     * @param string    $pesan    Isi pesan notifikasi
     * @param string|null $url   URL target (opsional)
     * @param string    $tipe    Tipe: info | success | warning | review
     */
    public static function kirim(int|array $userIds, string $judul, string $pesan, ?string $url = null, string $tipe = 'info'): void
    {
        $ids = is_array($userIds) ? $userIds : [$userIds];

        $now = now();

        foreach ($ids as $id) {
            Notifikasi::create([
                'id_user'    => $id,
                'judul'      => $judul,
                'pesan'      => $pesan,
                'url'        => $url,
                'tipe'       => $tipe,
                'dibaca'     => 0,
                'created_at' => $now,
            ]);
        }
    }
}
