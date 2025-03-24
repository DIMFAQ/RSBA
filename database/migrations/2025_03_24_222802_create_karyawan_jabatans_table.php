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
        Schema::create('sdm_kary_jabatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jabatan_id');
            $table->unsignedBigInteger('karyawan_id');
            $table->date('tgl_mulai');
            $table->date('tgl_berakhir')->nullable();
            $table->timestamps();

            $table->foreign('jabatan_id')->references('id')->on('sdm_jabatan')->onDelete('cascade');
            $table->foreign('karyawan_id')->references('id')->on('sdm_karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_kary_jabatan');
    }
};
