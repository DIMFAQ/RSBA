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
        Schema::create('sdm_kary_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->string('nama', 50);
            $table->date('tahun_lulus');
            $table->string('instansi', 100);
            $table->string('gelar', 50)->nullable();
            $table->enum('set_gelar', ['preffix', 'suffix'])->nullable(); //preffix = depan, suffix = belakang
            $table->enum('tingkat', ['sd', 'smp', 'sma', 'd3', 'd4', 's1', 's2', 's3', 'dokter', 'spesialis', 'profesi', 'lain']);
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id')->on('sdm_karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_kary_pendidikan');
    }
};
