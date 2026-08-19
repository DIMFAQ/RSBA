<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('public_maintc_reports', function (Blueprint $table) {
            $table->string('tracking_code', 30)->unique()->nullable()->after('id');
            $table->string('pelapor_nama', 100)->nullable()->after('deskripsi');
            $table->string('pelapor_kontak', 100)->nullable()->after('pelapor_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('public_maintc_reports', function (Blueprint $table) {
            $table->dropColumn(['tracking_code', 'pelapor_nama', 'pelapor_kontak']);
        });
    }
};
