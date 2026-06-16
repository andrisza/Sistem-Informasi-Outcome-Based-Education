<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop FK & column id_periode from arsip_rapat before dropping periode_kurikulum
        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->dropForeign(['id_periode']);
            $table->dropColumn('id_periode');
        });

        Schema::dropIfExists('tim_kurikulum');
        Schema::dropIfExists('lesson_learned');
        Schema::dropIfExists('checklist_ledik');
        Schema::dropIfExists('log_distribusi');
        Schema::dropIfExists('periode_kurikulum');
    }

    public function down(): void
    {
        Schema::table('arsip_rapat', function (Blueprint $table) {
            $table->foreignId('id_periode')->nullable()->after('id_kurikulum')
                  ->constrained('periode_kurikulum')->nullOnDelete();
        });
    }
};
