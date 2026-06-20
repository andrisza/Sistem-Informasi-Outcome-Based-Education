<?php

namespace App\Providers;

use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\User;
use App\Observers\MataKuliahObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        // ── Paksa HTTPS di produksi ───────────────────────────────────────────
        // Railway menerima HTTPS lalu meneruskan ke aplikasi sebagai HTTP, jadi
        // tanpa ini Laravel membuat URL aset (CSS/JS via @vite) dengan skema
        // http:// dan diblokir browser sebagai mixed content.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

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
                return true;
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
