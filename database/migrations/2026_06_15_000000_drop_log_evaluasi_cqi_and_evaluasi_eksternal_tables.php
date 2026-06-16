<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // log_evaluasi_cqi dulu — kolomnya memiliki FK ke evaluasi_eksternal
        Schema::dropIfExists('log_evaluasi_cqi');
        Schema::dropIfExists('evaluasi_eksternal');
    }

    public function down(): void
    {
        // Fitur Evaluasi CQI, Evaluasi Eksternal, dan Laporan OBE telah dihapus dari aplikasi — tidak direkonstruksi.
    }
};
