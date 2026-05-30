<?php

namespace App\Providers;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\User;
use App\Observers\MataKuliahObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ── Model Observers ───────────────────────────────────────────────────
        MataKuliah::observe(MataKuliahObserver::class);

        // ── Authorization Gates ───────────────────────────────────────────────

        /**
         * Gate: modify-kurikulum
         *
         * Aturan:
         *  - Kaprodi → selalu boleh.
         *  - Tim Kurikulum → hanya boleh jika terdaftar di tim_kurikulum
         *    untuk salah satu periode dari kurikulum yang dimaksud.
         *
         * Penggunaan di controller:
         *   $this->authorize('modify-kurikulum', $kurikulum);
         */
        Gate::define('modify-kurikulum', function (User $user, Kurikulum $kurikulum): bool {
            if ($user->isKaprodi()) {
                return true;
            }

            if ($user->isTimKurikulum()) {
                return DB::table('tim_kurikulum')
                    ->join('periode_kurikulum', 'tim_kurikulum.id_periode', '=', 'periode_kurikulum.id')
                    ->where('periode_kurikulum.id_kurikulum', $kurikulum->id)
                    ->where('tim_kurikulum.id_user', $user->id)
                    ->exists();
            }

            return false;
        });

        /**
         * Gate: arsip-kurikulum
         *
         * Hanya Kaprodi yang boleh mengarsipkan atau mengaktifkan kurikulum.
         */
        Gate::define('arsip-kurikulum', function (User $user): bool {
            return $user->isKaprodi();
        });
    }
}
