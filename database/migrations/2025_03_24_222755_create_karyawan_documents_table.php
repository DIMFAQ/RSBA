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
        Schema::create('sdm_kary_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->string('nama', 50);
            $table->enum('jenis', ['ijazah', 'sertifikat', 'sip', 'pribadi', 'lain']);
            $table->string('filename', 100);
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id')->on('sdm_karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sdm_kary_document');
    }
};
