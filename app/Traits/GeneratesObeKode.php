<?php

namespace App\Traits;

use App\Models\Kurikulum;

/**
 * Trait bersama untuk semua controller OBE yang perlu:
 *  1. generateKodeKurikulum() — membuat kode kurikulum otomatis
 *  2. getKurikulumPrefix()    — mengekstrak prefix pendek dari kode kurikulum
 *                               untuk digunakan dalam kode entitas OBE
 *
 * Format kode kurikulum: K-{ABBR}-{JENJANG}-{YEAR}
 *   contoh: K-SI-S1-2025, K-TI-S1-2024
 *
 * Format prefix (diambil dari kode kurikulum):
 *   K-SI-S1-2025  → SI25
 *   K-TI-D4-2024  → TI24
 *   KUR-SI-2021   → SI21 (kode lama tetap didukung)
 */
trait GeneratesObeKode
{
    // ── Kurikulum ─────────────────────────────────────────────────────────────

    /**
     * Generate kode kurikulum otomatis. Menjamin unik — jika `K-SI-S1-2025`
     * sudah ada, coba `K-SI-S1-2025-2`, dst.
     */
    protected function generateKodeKurikulum(
        string $programStudi,
        string $jenjang,
        int    $tahunMulai
    ): string {
        $abbr = $this->abbreviateProdi($programStudi);
        $base = "K-{$abbr}-{$jenjang}-{$tahunMulai}";

        if (!\App\Models\Kurikulum::where('kode', $base)->exists()) {
            return $base;
        }

        for ($i = 2; $i <= 99; $i++) {
            $candidate = "{$base}-{$i}";
            if (!\App\Models\Kurikulum::where('kode', $candidate)->exists()) {
                return $candidate;
            }
        }

        return $base . '-' . now()->timestamp;
    }

    /**
     * Singkat nama program studi menjadi akronim huruf kapital (maks 4 huruf).
     * "Sistem Informasi" → "SI", "Teknik Informatika" → "TI"
     */
    protected function abbreviateProdi(string $prodiName): string
    {
        $words = preg_split('/[\s_]+/', trim($prodiName));
        $abbr  = strtoupper(implode('', array_map(
            fn($w) => mb_substr($w, 0, 1),
            array_filter($words)
        )));
        return substr($abbr, 0, 4) ?: 'KUR';
    }

    // ── Ekstrak prefix dari kode kurikulum ───────────────────────────────────

    /**
     * Mengekstrak prefix pendek dari kode kurikulum yang sudah ada.
     * Prefix = singkatan prodi (1–4 huruf) + 2 digit terakhir tahun.
     *
     * Mendukung berbagai format kode:
     *   K-SI-S1-2025  → SI25
     *   K-TI-D4-2024  → TI24
     *   KUR-SI-2021   → SI21
     *   K-IF-S1-2026  → IF26
     *
     * Digunakan oleh semua generator kode entitas OBE agar kode bersifat
     * traceable kembali ke kurikulumnya.
     */
    protected function getKurikulumPrefix(Kurikulum $kurikulum): string
    {
        $kode = strtoupper($kurikulum->kode);

        // Pola: awalan K[^A-Z]* lalu singkatan huruf, lalu opsional jenjang,
        // lalu 4-digit tahun
        // Contoh: K-SI-S1-2025, KUR-SI-2021, K-TI-D4-2024
        if (preg_match('/K[^A-Z]*([A-Z]{1,4})[^A-Z0-9]*(?:[A-Z0-9]{2,4}[^A-Z0-9]*)?(\d{4})/', $kode, $m)) {
            $abbr = $m[1];
            $year = substr($m[2], 2, 2); // 2025 → 25
            return $abbr . $year;
        }

        // Fallback: derive dari program_studi + tahun_mulai model
        $abbr = $this->abbreviateProdi($kurikulum->program_studi ?? '');
        $year = substr((string) ($kurikulum->tahun_mulai ?? date('Y')), 2, 2);
        return ($abbr ?: 'KUR') . $year;
    }
}
