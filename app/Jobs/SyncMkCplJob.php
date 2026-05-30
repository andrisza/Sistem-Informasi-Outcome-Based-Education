<?php

namespace App\Jobs;

use App\Models\Kurikulum;
use App\Services\MatrixConsistencyService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Job untuk rebuild pivot_mk_cpl secara asinkron di background.
 *
 * Dipanggil oleh PivotController saat user men-toggle matriks CPL↔BK atau MK↔BK,
 * maupun saat matriks 3D (CPL↔BK↔MK) diedit. Memindahkan operasi rebuild yang
 * bisa memblokir request ke queue worker, sehingga response ke user lebih cepat.
 *
 * Konfigurasi queue: QUEUE_CONNECTION=database (default), worker dijalankan dengan
 *   php artisan queue:work --queue=matrix-sync --tries=3
 */
class SyncMkCplJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Jumlah percobaan ulang jika job gagal. */
    public int $tries = 3;

    /** Timeout per percobaan dalam detik. */
    public int $timeout = 120;

    /** Backoff (detik) antar percobaan: 10s, 30s, 60s. */
    public array $backoff = [10, 30, 60];

    public function __construct(public readonly int $kurikulumId)
    {}

    public function handle(MatrixConsistencyService $service): void
    {
        $kurikulum = Kurikulum::find($this->kurikulumId);

        if (!$kurikulum) {
            // Kurikulum sudah dihapus — tidak ada yang perlu disinkronkan
            return;
        }

        $service->syncMkCpl($kurikulum);
    }

    /**
     * Tentukan queue yang digunakan — dipisahkan dari queue default agar
     * operasi sinkronisasi matriks tidak menghambat job lain.
     */
    public function queue(): string
    {
        return 'matrix-sync';
    }
}
