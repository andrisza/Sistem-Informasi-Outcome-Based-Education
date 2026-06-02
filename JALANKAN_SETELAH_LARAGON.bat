@echo off
echo ============================================================
echo  SI-OBE — Jalankan setelah Laragon Start All
echo ============================================================
echo.

cd /d "C:\laragon\www\si-obe"

echo [1/4] Menjalankan migrasi (kolom konsentrasi)...
php artisan migrate --force
echo.

echo [2/4] Memperbarui pemetaan kurikulum dari spreadsheet...
php artisan db:seed --class=CurriculumMappingSeeder --force
echo.

echo [3/4] Membersihkan cache...
php artisan view:clear
php artisan config:cache
php artisan route:cache
echo.

echo [4/4] Verifikasi data...
php artisan tinker --execute="$kur=App\Models\Kurikulum::where('kode','K-SI-S1-2021')->first(); echo 'MK: '.App\Models\MataKuliah::where('id_kurikulum',$kur->id)->count().PHP_EOL; echo 'pivot_pl_cpl: '.DB::table('pivot_pl_cpl')->whereIn('id_pl',$kur->pl()->pluck('id'))->count().PHP_EOL; echo 'pivot_cpl_bk: '.DB::table('pivot_cpl_bk')->whereIn('id_cpl',$kur->cplProdi()->pluck('id'))->count().PHP_EOL; echo 'pivot_mk_bk: '.DB::table('pivot_mk_bk')->whereIn('id_mk',$kur->mataKuliah()->pluck('id'))->count().PHP_EOL;"
echo.

echo ============================================================
echo  Selesai! Buka http://si-obe.test:8080 di browser.
echo ============================================================
pause
