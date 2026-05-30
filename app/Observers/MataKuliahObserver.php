<?php

namespace App\Observers;

use App\Models\DistribusiSemester;
use App\Models\MataKuliah;

/**
 * Otomatis menyinkronkan tabel distribusi_semester setiap kali MataKuliah
 * dibuat, diperbarui, dihapus (soft), atau dipulihkan.
 *
 * Didaftarkan di AppServiceProvider::boot().
 */
class MataKuliahObserver
{
    /**
     * Dipanggil setelah create() atau update() berhasil.
     * Mencakup perubahan SKS atau perpindahan semester.
     */
    public function saved(MataKuliah $mk): void
    {
        $this->syncDistribusi($mk->id_kurikulum);
    }

    /**
     * Dipanggil setelah soft-delete. MK sudah tidak muncul di query normal,
     * sehingga rekalkulasi akan mengecualikannya.
     */
    public function deleted(MataKuliah $mk): void
    {
        $this->syncDistribusi($mk->id_kurikulum);
    }

    /**
     * Dipanggil setelah restore dari soft-delete.
     */
    public function restored(MataKuliah $mk): void
    {
        $this->syncDistribusi($mk->id_kurikulum);
    }

    // ── Private helper ─────────────────────────────────────────────────────────

    /**
     * Hitung ulang distribusi SKS & jumlah MK per semester untuk satu kurikulum.
     * Hanya MK yang tidak soft-deleted yang dihitung (query default Eloquent).
     */
    private function syncDistribusi(int $kurikulumId): void
    {
        // Hitung agregat per semester dari MK aktif (non-deleted)
        $rows = MataKuliah::where('id_kurikulum', $kurikulumId)
            ->selectRaw('semester, SUM(sks_teori + sks_praktikum) AS total_sks, COUNT(*) AS jumlah_mk')
            ->groupBy('semester')
            ->get();

        $semesterAktif = $rows->pluck('semester');

        // Hapus baris distribusi untuk semester yang tidak memiliki MK lagi
        if ($semesterAktif->isNotEmpty()) {
            DistribusiSemester::where('id_kurikulum', $kurikulumId)
                ->whereNotIn('semester', $semesterAktif)
                ->delete();
        } else {
            // Tidak ada MK sama sekali — hapus semua distribusi
            DistribusiSemester::where('id_kurikulum', $kurikulumId)->delete();
        }

        // Upsert distribusi per semester
        foreach ($rows as $row) {
            DistribusiSemester::updateOrCreate(
                ['id_kurikulum' => $kurikulumId, 'semester' => $row->semester],
                ['total_sks' => $row->total_sks, 'jumlah_mk' => $row->jumlah_mk]
            );
        }
    }
}
